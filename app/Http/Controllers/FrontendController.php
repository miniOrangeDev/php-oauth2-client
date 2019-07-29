<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Hash;
use Input;

class FrontendController extends Controller
{
   
    public function index(){
        
        $config_dir = base_path()."/config";
        if(!file_exists($config_dir)){
            mkdir($config_dir);
        }

        $file_name = $config_dir."/".env("MO_OAUTH_CREDENTIALS_FILE_NAME", "mo_oauth_credentials.php");
        if(!file_exists($file_name)){
            $content = "<?php return array();";
            $fp = fopen($file_name,"wb");
            fwrite($fp,$content);
            fclose($fp);
        }

        if(Session::has('mo_oauth_logintoken') && Session::has('mo_oauth_authorized')){

            $credentials_array = include $file_name;

            if(sizeof($credentials_array) == 0){
                Session::flush();
                return Redirect::route('get-login');
            }

            return Redirect::route('configure-app');
        }else{
            return Redirect::route('get-login');
        }
    }

    
    public function getLogin(){
        if(Session::has('mo_oauth_logintoken') && Session::has('mo_oauth_authorized')){
            return Redirect::route('dashboard');
        }

        return view('login');
    }

    
    public function postLogin(Request $request){

        $this->validate($request , [
            'email' => 'required',
            'password' => 'required'
        ]);

        $email = $request->get('email');
        $password = $request->get('password');

        if(empty($password)){
            Redirect::back()->with('error','You must fill password');
        }

        $config_dir = base_path()."/config";
        $file_name = $config_dir."/".env("MO_OAUTH_CREDENTIALS_FILE_NAME", "mo_oauth_credentials.php");
        if(!file_exists($file_name)){
            return Redirect::route('dashboard');
        }

        $credentials_array = include $file_name;

        if(is_array($credentials_array)){

            if($this->checkInternetConnection()){

                $response = $this->checkUserExist($email);

                if($response->status == "CUSTOMER_NOT_FOUND"){
                    if(isset($credentials_array[$email]['appKey']) && $credentials_array[$email]['appKey'] == ''){
                        if(Hash::check($password, $credentials_array[$email]['password'])){
                            $this->createMoasCustomer($email,$password);
                            goto continue_login_process;
                        }else{
                            return Redirect::route('get-register')->with('error','Invalid Password of admin portal');
                        }
                    }else{
                        return Redirect::route('get-register')->with('error',$response->message);
                    }
                }

                continue_login_process:

                $response = $this->getCustomerKey($email,$password);

                if($response == NULL){
                    return Redirect::back()->with('error','Invalid username or password. Please try again.');
                }

                if($response->status == "SUCCESS"){

                    if(sizeof($credentials_array) == 0){
                        
                        $user_details_array = array();

                        $user_details_array['password'] = Hash::make($password);
                        $user_details_array['appKey'] = $response->apiKey;
                        $user_details_array['appSecret'] = $response->appSecret;
                        $user_details_array['token'] = $response->token;
                        $user_details_array['id'] = $response->id;

                        $credentials_array[$email] = $user_details_array;

                        file_put_contents($file_name,'<?php return ' . var_export($credentials_array, true) . ';' );
                    }

                    if(isset($credentials_array[$email]['appKey']) && $credentials_array[$email]['appKey'] == ''){
                        $user_details_array = array();

                        $user_details_array['password'] = Hash::make($password);
                        $user_details_array['appKey'] = $response->apiKey;
                        $user_details_array['appSecret'] = $response->appSecret;
                        $user_details_array['token'] = $response->token;
                        $user_details_array['id'] = $response->id;

                        $credentials_array[$email] = $user_details_array;

                        file_put_contents($file_name,'<?php return ' . var_export($credentials_array, true) . ';' );
                    }

                    if(array_key_exists($email, $credentials_array)){
                        $details = $credentials_array[$email];

                        if(Hash::check($password, $details['password'])){

                            Session::put('mo_oauth_logintoken',Hash::make($email));
                            Session::put('mo_oauth_loginemail',$email);
                            Session::put('mo_oauth_authorized',true);

                            return Redirect::route('dashboard');
                        }else{
                            return Redirect::back()->with('error','Invalid Admin portal password please try again later');
                        }
                    }else{
                        return Redirect::back()->with('error','You have already set up the admin portal <br> try to login with right credentials');
                    }

                }else{
                    return Redirect::back()->with('error','Something went wrong please try again later');
                }
            }else{

                if(sizeof($credentials_array) == 0){
                    return Redirect::route('get-register')->with('error','No such user registerd please register an user to admin portal');
                }

                if(array_key_exists($email, $credentials_array)){
                    $details = $credentials_array[$email];

                    Session::put('mo_oauth_logintoken',Hash::make($email));
                    Session::put('mo_oauth_loginemail',$email);
                    Session::put('mo_oauth_authorized',true);

                    return Redirect::route('dashboard');
                }else{
                    return Redirect::back()->with('error','Invalid Email Address  of admin portal');
                }

            }
        }else{
            unlink($file_name);
            return Redirect::route('dashboard');
        }  
    }

    
    public function getRegister(){
        if(Session::has('mo_oauth_logintoken') && Session::has('mo_oauth_authorized')){
            return Redirect::route('dashboard');
        }

        return view('register');
    }

    public function postRegister(Request $request){ 

        $this->validate($request , [
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);
        $email = $request->get('email');
        $password = $request->get('password');
        $confirm_password = $request->get('confirm_password');

        if($password !== $confirm_password){
            return Redirect::back()->with('error',"Password and confirm password doesn't match");
        }

        $password = Hash::make($password);

        $config_dir = base_path()."/config";
        $file_name = $config_dir."/".env("MO_OAUTH_CREDENTIALS_FILE_NAME", "mo_oauth_credentials.php");
        if(!file_exists($file_name)){
            return Redirect::route('dashboard');
        }

        $credentials_array = include $file_name;

        if(is_array($credentials_array)){
            if(sizeof($credentials_array)){
                return Redirect::route('get-login')->with('error','You have already set up admin user for portal');
            }

            if($this->checkInternetConnection()){
                $response = $this->checkUserExist($email);

                if($response->status == 'SUCCESS'){
                    return Redirect::route('get-login')->with('error',$email.' is already exist with miniorange!! try to login here');
                }

                $response = $this->createMoasCustomer($email,$request->get('password'));

                if($response->status == 'SUCCESS'){

                    $user_details_array = array();

                    $user_details_array['password'] = $password;
                    $user_details_array['appKey'] = $response->apiKey;
                    $user_details_array['appSecret'] = $response->appSecret;
                    $user_details_array['token'] = $response->token;
                    $user_details_array['id'] = $response->id;

                    file_put_contents($file_name,'<?php return ' . var_export(array($email=>$user_details_array), true) . ';' );

                    Session::put('mo_oauth_logintoken',Hash::make($email));
                    Session::put('mo_oauth_loginemail',$email);
                    Session::put('mo_oauth_authorized',true);

                    return Redirect::route('dashboard')->with('success',$response->message);
                }else{
                    return Redirect::route('get-login')->with('error',$response->message);  
                }

            }else{
                $user_details_array = array();

                $user_details_array['password'] = $password;
                $user_details_array['appKey'] = '';
                $user_details_array['appSecret'] = '';
                $user_details_array['token'] = '';
                $user_details_array['id'] = '';

                file_put_contents($file_name,'<?php return ' . var_export(array($email=>$user_details_array), true) . ';' );

                Session::put('mo_oauth_logintoken',Hash::make($email));
                Session::put('mo_oauth_loginemail',$email);
                Session::put('mo_oauth_authorized',true);

                return Redirect::route('dashboard')->with('success',"Customer is created");
            }

        }else{
            unlink($file_name);
            return Redirect::back('dashboard');
        }
    }

    
    private function createMoasCustomer($email,$password){

        $url = "https://login.xecurify.com/moas/rest/customer/add";
		$ch = curl_init($url);


        $fields = array (
            'areaOfInterest' => 'PHP OAuth Connector',
            'email' => $email,
            'password' => $password
        );

        $field_string = json_encode( $fields );

        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch, CURLOPT_ENCODING, "" );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required for https urls
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
            'Content-Type: application/json',
            'charset: UTF - 8',
            'Authorization: Basic' 
        ));

        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $field_string );
        $content = curl_exec ( $ch );

        if (curl_errno ( $ch )) {
            echo 'Request Error:' . curl_error ( $ch );
            exit ();
        }
    
        curl_close ( $ch );	
        return json_decode($content);
    }

    
    private function checkUserExist($email){
        
        $url = "https://login.xecurify.com/moas/rest/customer/check-if-exists";


        $ch = curl_init($url);
        $fields = array (
            'email' => $email        
        );
                
        $field_string = json_encode ( $fields );

        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch, CURLOPT_ENCODING, "" );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required for https urls
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
            'Content-Type: application/json',
            'charset: UTF - 8',
            'Authorization: Basic' 
            ));
             
        curl_setopt ( $ch, CURLOPT_POST, true );    
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $field_string );
                
                
        $content = curl_exec ( $ch );
    
        if (curl_errno ( $ch )) {
            echo 'Error in sending curl Request';        
            exit ();       
        }

        curl_close ( $ch );	
               
        return json_decode($content);
    }

    private function getCustomerKey($email,$password){
        
        $url = "https://login.xecurify.com/moas/rest/customer/key";
		$ch = curl_init ( $url );        
		
        $fields = array (
            'email' => $email,
            'password' => $password 
        );
           
        $field_string = json_encode ( $fields );
        
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch, CURLOPT_ENCODING, "" );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required for https urls        
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );        
        curl_setopt ( $ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
            'Content-Type: application/json',
            'charset: UTF - 8',
            'Authorization: Basic' 
        ));
         
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $field_string );
            
        $content = curl_exec ( $ch );
        if (curl_errno ( $ch )) {
            echo 'Error in sending curl Request';
            exit ();
        }
        
        curl_close ( $ch );
           
        return json_decode($content);
    }

   
    private function checkInternetConnection(){
        $connected = @fsockopen("login.xecurify.com", 80); 
        if ($connected){
            fclose($connected);
            return true;
        }else{
            return false;
        }
    }

  
    public function logoutUser(){
        Session::flush();
        return Redirect::route('dashboard');
    }

}
