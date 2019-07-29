<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Hash;
use Redirect;
use Input;

class ConfigureAppController extends Controller
{
    public function configureApp(Request $request){ 
        if($this->validateUser()){
            $config_dir = base_path()."/config";
            $file_name = $config_dir."/".env("MO_OAUTH_DEFAULTAPPS_FILE_NAME", "mo_default_apps.json");
            if(!file_exists($file_name)){
                return Redirect::route('dashboard')->with('error','Something went wrong!!! Contact to your administrator support.');
            }
            if(sizeof($this->getAppList()) >= 1){
                return Redirect::route('app-list')->with('error','You can only add 1 application with free version. Upgrade to <a href="'.route('licensing').'">premium</a> to add more.');
            }
            if($request->has('appId')){
                $currentAppId = $request->get('appId');
                $currentApp = $this->getApp($currentAppId);

                if($currentApp == false){
                    return Redirect::back()->with('error','Something went wrong!!!');
                }
                return view('configure-app-form')->with('currentApp',$currentApp);

            }else{
                $defaultapps = file_get_contents($file_name);
                $defaultappsjson = json_decode($defaultapps);
                return view('configure-app-select')->with('defaultappsjson',$defaultappsjson);
            }
        }else{
            Session::flush();
            return Redirect::route('get-login');
        }
    }

    private function getAppList(){
        $config_dir = base_path()."/config";
        $file_name = $config_dir."/".env("MO_OAUTH_CONFIGAPPS_FILE_NAME", "mo_config_apps.php");

        if(!file_exists($file_name)){
            $content = "<?php return array();";    
            $fp = fopen($file_name,"wb");    
            fwrite($fp,$content);   
            fclose($fp);
        }

        $applist = include $file_name;

        return $applist;
    }

    private function getClientApp($appname){
        $applist = $this->getAppList();

        if(!array_key_exists($appname,$applist)){
            return Redirect::route('app-list');
        }

        return $applist[$appname];
    }

    public function appList(Request $request){
        if($this->validateUser()){
  
            $config_dir = base_path()."/config";
            $file_name = $config_dir."/".env("MO_OAUTH_CONFIGAPPS_FILE_NAME", "mo_config_apps.php");

            if(!file_exists($file_name)){
                return Redirect::route('configure-app')->with('error',"It's seem like you have not configured app!!! configure your first OAuth app.");
            }

            $applist = include $file_name;

            if(sizeof($applist) == 0){
                return Redirect::route('configure-app');
            }

            return view('app-list')->with('applist',$applist);
        }else{
            Session::flush();
            return Redirect::route('get-login');
        }
    }

    public function editApp(Request $request){
        if($this->validateUser()){

            if(!$request->has('app')){
                return Redirect::route('app-list');
            }

            $appname = $request->get('app');

            $config_dir = base_path()."/config";
            $file_name = $config_dir."/".env("MO_OAUTH_CONFIGAPPS_FILE_NAME", "mo_config_apps.php");

            if(!file_exists($file_name)){
                return Redirect::route('configure-app')->with('error',"It's seem like you have not configured app!!! configure your first OAuth app.");
            }

            $applist = include $file_name;

            if(!array_key_exists($appname,$applist)){
                return Redirect::route('app-list');
            }

            $app = $applist[$appname];

            $data = [
                'appname' => $appname,
                'app' => $app
            ];

            return view('edit-app')->with($data);
        }else{
            Session::flush();
            return Redirect::route('get-login');
        }
    }

    public function getSetup(Request $request){
        if($this->validateUser()){
            $appname = '<appname>';
            if($request->has('app')){
                $appname = $request->get('app');
            }
            return view('how-to-setup')->with('appname',$appname);
        }else{
            Session::flush();
            return Redirect::route('get-login');
        }
    }

    public function getLicensing(){
        if($this->validateUser()){
            return view('licensing');
        }else{
            Session::flush();
            return Redirect::route('get-login');
        }
    }

    public function deleteApp(Request $request){
        if($this->validateUser()){

            if(!$request->has('app')){
                return Redirect::route('app-list');
            }

            $appname = $request->get('app');

            $config_dir = base_path()."/config";
            $file_name = $config_dir."/".env("MO_OAUTH_CONFIGAPPS_FILE_NAME", "mo_config_apps.php");

            if(!file_exists($file_name)){
                return Redirect::route('configure-app')->with('error',"It's seem like you have not configured app!!! configure your first OAuth app.");
            }

            $applist = include $file_name;

            if(!array_key_exists($appname,$applist)){
                return Redirect::route('app-list');
            }

            unset($applist[$appname]);

            file_put_contents($file_name,'<?php return ' . var_export($applist, true) . ';' );

            return Redirect::route('app-list');
        }else{
            Session::flush();
            return Redirect::route('get-login');
        }
    }

    public function updateApp(Request $request){
        if($this->validateUser()){

            $this->validate($request , [
                'mo_oauth_app_name' => 'required',
                'mo_oauth_client_id' => 'required',
                'mo_oauth_client_secret' => 'required',
                'mo_oauth_authorize_endpoint' => 'required',
                'mo_oauth_access_token_endpoint' => 'required'
            ]);


            if($request->get('mo_oauth_app_type') === "oauth"){
                if(empty($request->get('mo_oauth_user_info_endpoint'))){
                    return Redirect::back()->withInput(Input::all())->withError('User info endpoint is required');
                }
            }

            $config_dir = base_path()."/config";
            $file_name = $config_dir."/".env("MO_OAUTH_CONFIGAPPS_FILE_NAME", "mo_config_apps.php");

            if(!file_exists($file_name)){
                return Redirect::route('configure-app')->with('error',"It's seem like you have not configured app!!! configure your first OAuth app.");
            }

            $configapp = include $file_name;

            if(!array_key_exists($request->get('mo_oauth_app_name'),$configapp)){
                return Redirect::back()->withInput(Input::all())->withError($request->get('mo_oauth_app_name').' app does not exist');                
            }


            $app_name = $request->get('mo_oauth_app_name');
            $sso_protocol = $request->get('mo_oauth_sso_protocol');
            $client_id = $request->get('mo_oauth_client_id');
            $client_secret = $request->get('mo_oauth_client_secret');
            $scope = $request->get('mo_oauth_scope');
            $authorize_endpoint = $request->get('mo_oauth_authorize_endpoint');
            $token_endpoint = $request->get('mo_oauth_access_token_endpoint');
            $user_info_endpoint = '';

            if($request->has('mo_oauth_user_info_endpoint'))
                $user_info_endpoint = $request->get('mo_oauth_user_info_endpoint');


            if(is_array($configapp)){

                $newapp = $configapp[$app_name];

                $newapp['sso_protocol'] = $sso_protocol;
                $newapp['client_id'] = $client_id;
                $newapp['client_secret'] = $client_secret;
                $newapp['scope'] = $scope;
                $newapp['authorize'] = $authorize_endpoint;
                $newapp['token'] = $token_endpoint;
                $newapp['user_info'] = $user_info_endpoint;
                
                $configapp[$app_name] = $newapp;

                file_put_contents($file_name,'<?php return ' . var_export($configapp, true) . ';' );

                return Redirect::back()->with('success',$app_name." successfully changed");
                
            }else{
                unlink($file_name);  
                return Redirect::back()->withInput(Input::all())->withError('Something went wrong try again later');
            }

        }else{
            Session::flush();
            return Redirect::route('get-login');
        }
    }

    public function attributeMappingPost(Request $request){
        if($this->validateUser()){

            $this->validate($request , [
                'mo_oauth_attribute_email' => 'required',
                'mo_oauth_appname' => 'required'
            ]);

            $currentApp = $this->getClientApp($request->get('mo_oauth_appname'));

            if($currentApp == false){
                return Redirect::route('configure-app')->with('error','Looks like this app does not exist!! configure your app');
            }
            
            $attribute_array = [
                'email_attr' => $request->get('mo_oauth_attribute_email')
            ];

            $currentApp['attributes'] = $attribute_array;

            $config_dir = base_path()."/config";
            $file_name = $config_dir."/".env("MO_OAUTH_CONFIGAPPS_FILE_NAME", "mo_config_apps.php");

            if(!file_exists($file_name)){
                return Redirect::route('configure-app')->with('error',"It's seem like you have not configured app!!! configure your first OAuth app.");
            }

            $configapp = include $file_name;

            $configapp[$request->get('mo_oauth_appname')] = $currentApp;

            file_put_contents($file_name,'<?php return ' . var_export($configapp, true) . ';' );

            return Redirect::back()->with('success','Attributes successfully mapped');
        }else{
            Session::flush();
            return Redirect::route('get-login');
        }
    } 


    public function configureAppPost(Request $request){
        if($this->validateUser()){

            $this->validate($request , [
                'mo_oauth_app_name' => 'required',
                'mo_oauth_app_type' => 'required',
                'mo_oauth_custom_app_name' => 'required',
                'mo_oauth_client_id' => 'required',
                'mo_oauth_client_secret' => 'required',
                'mo_oauth_authorize_endpoint' => 'required',
                'mo_oauth_access_token_endpoint' => 'required'
            ]);


            if($request->get('mo_oauth_app_type') === "oauth"){
                if(empty($request->get('mo_oauth_user_info_endpoint'))){
                    return Redirect::back()->withInput(Input::all())->withError('User info endpoint is required');
                }
            }

            if(sizeof($this->getAppList()) >= 1){
                return Redirect::route('app-list')->with('error','You can only add 1 application with free version. Upgrade to premium to add more.');
            }

            $config_dir = base_path()."/config";
            $file_name = $config_dir."/".env("MO_OAUTH_CONFIGAPPS_FILE_NAME", "mo_config_apps.php");

            if(!file_exists($file_name)){
                $content = "<?php return array();";
                $fp = fopen($file_name,"wb");
                fwrite($fp,$content);
                fclose($fp);
            }

            $configapp = include $file_name;

            if(array_key_exists($request->get('mo_oauth_custom_app_name'),$configapp)){
                return Redirect::back()->withInput(Input::all())->withError($request->get('mo_oauth_custom_app_name').' app is already exist change the application name');                
            }


            $app_name = $request->get('mo_oauth_app_name');
            $app_type = $request->get('mo_oauth_app_type');
            $custom_app_name = $request->get('mo_oauth_custom_app_name');
            $sso_protocol = $request->get('mo_oauth_sso_protocol');
            $client_id = $request->get('mo_oauth_client_id');
            $client_secret = $request->get('mo_oauth_client_secret');
            $scope = $request->get('mo_oauth_scope');
            $authorize_endpoint = $request->get('mo_oauth_authorize_endpoint');
            $token_endpoint = $request->get('mo_oauth_access_token_endpoint');
            $user_info_endpoint = '';

            if($request->has('mo_oauth_user_info_endpoint'))
                $user_info_endpoint = $request->get('mo_oauth_user_info_endpoint');


            if(is_array($configapp)){

                $newapp = array();

                $newapp['app_name'] = $app_name;
                $newapp['app_type'] = $app_type;
                $newapp['sso_protocol'] = $sso_protocol;
                $newapp['client_id'] = $client_id;
                $newapp['client_secret'] = $client_secret;
                $newapp['scope'] = $scope;
                $newapp['authorize'] = $authorize_endpoint;
                $newapp['token'] = $token_endpoint;
                $newapp['user_info'] = $user_info_endpoint;
                
                $configapp[$custom_app_name] = $newapp;

                file_put_contents($file_name,'<?php return ' . var_export($configapp, true) . ';' );

                return Redirect::route('app-list')->with('success',$custom_app_name." successfully configured");
                
            }else{
                unlink($file_name);  
                return Redirect::back()->withInput(Input::all())->withError('Something went wrong try again later');
            }

        }else{
            Session::flush();
            return Redirect::route('get-login');
        }
    }

    
    private function validateUser(){
        if(Session::has('mo_oauth_logintoken') && Session::has('mo_oauth_authorized')){

            $config_dir = base_path()."/config";
            $file_name = $config_dir."/".env("MO_OAUTH_CREDENTIALS_FILE_NAME", "mo_oauth_credentials.php");

            if(!file_exists($file_name)){
                return false;
            }
            
            $credentials_array = include $file_name;

            if(sizeof($credentials_array) == 0){ 
                Session::flush();
                return false;
            }

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

    private function getApp($inputAppId){

        $config_dir = base_path()."/config";
        $file_name = $config_dir."/".env("MO_OAUTH_DEFAULTAPPS_FILE_NAME", "mo_default_apps.json");

        $defaultapps = file_get_contents($file_name);
        $defaultappsjson = json_decode($defaultapps);
        foreach($defaultappsjson as $appId => $application) {
            if($appId == $inputAppId) {
                $application->appId = $appId;
                return $application;
            }
        }
        return false;
    }

}
