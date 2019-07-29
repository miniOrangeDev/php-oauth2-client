<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Hash;
use Redirect;
use Input;
use Exception;


class SsoController extends Controller
{
    private $user_info = array();


    public function index(Request $request){
        if($request->has('app')){
            $appname = $request->get('app');

            $app = $this->getApp($appname);

            if($app == false){
                exit("No such app configured");
            }

            $state = $appname;

            if($request->has('redirect_to')){
                $state .= "|".$request->get('redirect_to');
            }

            $url = $this->ssoRequest($app,$appname,$state);
 
            return Redirect::to($url);

        }else{
            exit("Invalid request");
        } 
    }

    
    public function testConfigure(Request $request){
        if($this->validateUser()){

            if(!$request->has('app')){
                return Redirect::route('app-list');
            }

            $appname = $request->get('app');

            $app = $this->getApp($appname);

            $state = $appname."|testconfig"; 

            $url = $this->ssoRequest($app,$appname,$state);

            Session::put('mo_oauth_test_configuration',true);

            return Redirect::to($url);
        }else{
            Session::flush();
            return Redirect::route('get-login');
        }
    }


    
    public function getResponse(Request $request){
        
        if(!$request->has('code')){
            if($request->has('error_description'))
                exit($request->get('error_description'));    
            else if($request->has('error'))        
                exit($request->get('error'));
			exit('Invalid response');
        }

        $state = '';
        if($request->has('state')){
            $state = $request->get('state');
            $state = base64_decode($state);
            $state = explode("|",$state);
        }else{
            exit("Invalid Request");
        }

        try{

            $currentappname = "";
            

            if(Session::has('mo_oauth_appname') && !empty(Session::get('mo_oauth_appname'))){
                $currentappname = Session::get('mo_oauth_appname');
            }else if($request->has('state') && !empty($request->get('state'))){
                $currentappname = $state[0];
            }

            if(empty($currentappname)){
                exit('No request found for this application');
            }

            $currentappname = $this->getApp($currentappname);

            if($currentappname == false){
                exit('Application is not configured');
            }

            

            $token_endpoint = $currentappname['token'];
            $app_type = $currentappname['app_type'];
            $grant_type = "authorization_code";
            $client_id = $currentappname['client_id'];
            $client_secret = $currentappname['client_secret'];
            $callback_url = route('response');
            $code = $request->get('code');

            if(empty($client_id)){
                exit('Invalid Client ID');
            }
            
            if(empty($client_secret)){
                exit('Invalid Client Secret');
            }
            

            if($app_type == "openidconnect"){
            
                $id_token = $this->getIdToken($token_endpoint,$grant_type,$client_id,$client_secret,$code,$callback_url);

                if(!$id_token){
                    exit("Invalid ID Token");
                }


                $user_data = $this->getResourceOwnerFromIdToken($id_token);

            }else{
            
                $access_token = $this->getAccessToken($token_endpoint,$grant_type,$client_id,$client_secret,$code,$callback_url);

                if(!$access_token){
                    exit("Invalid Access Token");
                }

                $user_info_url = $currentappname['user_info'];

                $user_data = $this->getResourceOwner($user_info_url,$access_token);
            }
               
            
                    
            if(!$user_data){
                exit("Invalid Response");
            }

            $this->responseCheck("",$user_data);

            if(Session::has('mo_oauth_test_configuration') && isset($state[1])){
                if(Session::get('mo_oauth_test_configuration') == true && $state[1] == 'testconfig'){
                    Session::forget('mo_oauth_test_configuration');
                    return view('test-config')->with('user_info',$this->user_info);
                }
            }else{
                if (!isset($_SESSION)) {
                    session_start();
                }

                if(array_key_exists('attributes',$currentappname)){
                    $attr_array = $currentappname['attributes'];
                    if(array_key_exists('email_attr',$attr_array)){
                        $_SESSION['sso_email'] = $this->user_info[$attr_array['email_attr']];
                    }else{
                        exit("Email address not received. Check your <b>Attribute Mapping</b> configuration.");                        
                    }
                }else{
                    exit("Email address not received. Check your <b>Attribute Mapping</b> configuration.");
                }
                

                if(isset($state[1])){
                    $redirect_to = $state[1];
                    if (filter_var($redirect_to, FILTER_VALIDATE_URL) === FALSE) {
                        exit('Not a valid redirect URL');
                    }
                    return Redirect::to($redirect_to);
                }
                exit("Invalid Redirect To");
            }
                     
        }catch(Exception $e) {
            return $e->getMessage();
        }
    }

    private function responseCheck($nestedprefix,$details){
        foreach ($details as $key => $resource) {   
            if(!is_array($resource) && !is_object($resource)){
                if(empty($nestedprefix))
                    $this->user_info[$key] = $resource;
                else{
                    $index = $nestedprefix.$key;
                    $this->user_info[$index] = $resource;
                }
            }else{
                if(empty($nestedprefix))
                    $nestedprefix = $key.".";
                else
                    $nestedprefix .= $key.".";
                $this->responseCheck($nestedprefix,$resource);
            }
        }
    }

    private function getAppList(){
        $config_dir = base_path()."/config";
        $file_name = $config_dir."/".env("MO_OAUTH_CONFIGAPPS_FILE_NAME", "mo_config_apps.php");

        if(!file_exists($file_name)){
            return Redirect::route('configure-app')->with('error',"It's seem like you have not configured app!!! configure your first OAuth app.");
        }

        $applist = include $file_name;

        return $applist;
    }

    
    private function getApp($appname){
        $applist = $this->getAppList();

        if(!array_key_exists($appname,$applist)){
            return false;
        }

        return $applist[$appname];
    }

    private function getResourceOwnerFromIdToken($id_token){
        $id_array = explode(".", $id_token);
		if(isset($id_array[1])) {
            $id_body = base64_decode($id_array[1]);
			if(is_array(json_decode($id_body, true))){
				return json_decode($id_body,true);
			}
		}
		exit('Invalid response received.<br><b>Id_token : </b>'.$id_token);
    }

    
    private function getIdToken($token_endpoint,$grant_type,$client_id,$client_secret,$code,$redirect_url){

        $content = $this->getToken($token_endpoint,$grant_type,$client_id,$client_secret,$code,$redirect_url);
        
        $response = json_decode($content,true); 

        if(isset($response['status'])){
            if($response['status'] == 'FAILED')
            exit($response['message']);
        }

        if(isset($response['id_token'])){
            return $response['id_token'];
        }else {
			exit('Invalid response received from OAuth Provider. Contact your administrator for more details.<br><br><b>Response : </b><br>'. $response);
        }
    }

    
    private function getToken($token_endpoint,$grant_type,$client_id,$client_secret,$code,$redirect_url){

        $ch = curl_init($token_endpoint);

		$headers = array (
            'Accept'  => 'application/json', 
            'charset'       => 'UTF - 8', 
            'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secret ),
            'Content-Type' => 'application/x-www-form-urlencoded'
        );
        
		$body = array (
            'grant_type'    => $grant_type,
            'code'          => $code,
            'client_id'     => $client_id,      
            'client_secret' => $client_secret,     
            'redirect_uri'  => $redirect_url
        );


		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt ( $ch, CURLOPT_ENCODING, "" );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required for https urls
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // required for https urls
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));

		$content = curl_exec($ch);

        if (curl_error($ch)) {
            $error_msg = curl_error($ch);
            var_dump($error_msg);
            exit();
        }
        
        curl_close($ch);

        return $content;
    }


    private function getAccessToken($token_endpoint,$grant_type,$client_id,$client_secret,$code,$redirect_url){
        
        $content = $this->getToken($token_endpoint,$grant_type,$client_id,$client_secret,$code,$redirect_url);
        
        $response = json_decode($content,true); 
        
        if(isset($response['error'])){
            exit($response['error']['message']);
        }
 
        if(isset($response['access_token'])){
            return $response['access_token'];
        }else {
            exit('Invalid response received from OAuth Provider. Contact your administrator for more details.<br><br><b>Response : </b><br>'. $response);
        }
    }


    private function getResourceOwner($resource_owner_url,$access_token){
        
        $headers = array(
            'Content-Type:application/json',
            'Authorization: Bearer '.$access_token
        );

        $ch = curl_init($resource_owner_url);


        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // required for https urls
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $content = json_decode($response,true);
        curl_close($ch);

        return $content;
    }

    private function validateUser(){
        if(Session::has('mo_oauth_logintoken') && Session::has('mo_oauth_authorized')){

            $config_dir = base_path()."/config";
            $file_name = $config_dir."/".env("MO_OAUTH_CREDENTIALS_FILE_NAME", "mo_oauth_credentials.php");
            
            $credentials_array = include $file_name;

            $email_list = array_keys($credentials_array);

            $logintoken = Session::get('mo_oauth_logintoken');

            if(Hash::check($email_list[0], $logintoken)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    
    private function ssoRequest($app,$appname,$state){
        $callback_url = route('response');

        $state = base64_encode($state);

        $grant_type="authorization_code";
        $response_type = "code";

        $client_id = $app['client_id'];
        $auth_endpoint = $app['authorize'];
        $scope = $app['scope'];

        $authorizationUrl = '';

        if(strpos($auth_endpoint, '?' ) !== false){
            $authorizationUrl = $auth_endpoint."?client_id=". $client_id ."&scope=". $scope ."&redirect_uri=". $callback_url ."&response_type=code&state=". $state;;   
        }   
        else {
            $authorizationUrl = $auth_endpoint."?client_id=". $client_id ."&scope=". $scope ."&redirect_uri=". $callback_url ."&response_type=code&state=". $state;;   
        }

        Session::put('mo_oauth_state',$state);
        Session::put('mo_oauth_appname',$appname);

        return $authorizationUrl;
    }

}
