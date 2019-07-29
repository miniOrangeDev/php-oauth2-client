@extends('layout.default')

@section('title')
    How to setup
@endsection


@section('content')
<main class="app-content">   
    <div class="app-title"> 
        <div>
            <h1><i class="fa fa-info-circle"></i> How to setup ?</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item">How to setup ?</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Notifications -->
            <div id="notific">    
                @include('inc.message')    
            </div>
            <div class="tile">
              <div class="tile-body">
                  <h3>Follow this steps to setup the PHP OAuth Connector</h3>
                  <hr>
                  <h5>Step 1 : </h5>
                  <ul>
                    <li>In <b>PHP OAuth Connector</b>, go to Configure OAuth tab and select the application from the given list.</li>
                  </ul>
                  <img src="{{asset('images/how-to-setup/step-1.png')}}" style="width:800px;height:400px;margin-left:50px; border:1px solid;">
                  <br><br><br>
                  
                  <h5>Step 2 : </h5>
                  <ul>
                      <li>Configure the app using OAuth provider details.</li>
                      <li>You need to provide these <b>App Name, Client ID, Client Secret, Authorize Endpoint, Access Token Endpoint, Get user info Endpoint (if application works on OAuth protocol).</b></li>
                      <li>Click on the <b>Save settings</b> button to save your configuration.</li>
                  </ul>
                  <img src="{{asset('images/how-to-setup/step-2.png')}}" style="width:800px;height:400px;margin-left:50px; border:1px solid;">
                  <br><br><br>

                  
                  <h5>Step 3 : </h5>
                  <ul>
                      <li>Your Configured application list.</li>
                      <li>Your can click on <b>Edit application, Attribute Mapping, Delete, How to configure?</b> to do relevant action.</li>
                  </ul>
                  <img src="{{asset('images/how-to-setup/step-3.png')}}" style="width:800px;height:400px;margin-left:50px; border:1px solid;">
                  <br><br><br>
                  
                  <h5>Step 4 : </h5>
                  <ul>
                      <li>You can test if the app configured properly or not by clicking  on the <b>Test Configuration</b> button.</li>
                      <li>Go to <b>Application list -> Edit Application -> [Test Configuration Button]</b></li>
                  </ul>
                  <img src="{{asset('images/how-to-setup/step-4.png')}}" style="width:800px;height:400px;margin-left:50px; border:1px solid;">
                  <br><br><br>

                  <h5>Step 5 : </h5>
                  <ul>
                      <li>If the configuration is correct, you can see a Test Configuration screen with the user's attribute values.</li>
                  </ul>
                  <img src="{{asset('images/how-to-setup/step-5.png')}}" style="width:400px;height:400px;margin-left:50px; border:1px solid;">
                  <br><br><br>

                  
                  <h5>Step 6 : </h5>
                  <ul>
                      <li>After testing your configuration do attribute mapping.</li>
                      <li>Go to <b>Attribute Mapping</b> block and enter <b>"Attribute Name"</b> (which came from test configuration)</li>
                      <li>Click on the <b>Save settings</b> button to save your configuration.</li>
                  </ul>
                  <img src="{{asset('images/how-to-setup/step-6.png')}}" style="width:800px;height:400px;margin-left:50px; border:1px solid;">
                  <br><br><br>

                  <h5>Step 7 : </h5>
                  <ul>
                    <li>
                        Use the following URL as a link in your application from where you want to perform SSO:<br>
                    </li>
                    <figure style="background-color:#979b9f; border-radius:5px;padding:10px;">
                        <code style="color:#212529;">https://&lt;your-domain&gt;/sso?app={{$appname}}&redirect_to=&lt;after-login-redirect-url&gt;</code>
                    </figure>
                    <br>
                    <li>
                        <b>&lt;after-login-redirect-url&gt;</b> - when the SSO completes and values are store on Session then the it will redirect to this <b>&lt;after-login-redirect-url&gt;</b> URL.
                    </li><br>
                    <li>
                        For example, you can use it as:<br>
                        <figure style="background-color:#979b9f; border-radius:5px;padding:10px;">
                            <code style="color:#212529;">&lt;a href="https://example.com/sso?app=myfirstapp&redirect_to=https://redirect-domain.com"&gt;Login SSO&lt;/a&gt;</code>
                        </figure>
                        <p><br>In this example: <br>- <b>&lt;appname&gt;</b>:  <i style="color:#e83e8c"><b>myfirstapp</b></i> which you have configured.<br>- <b>&lt;after-login-redirect-url&gt;</b>:  <i style="color:#e83e8c"><b>https://redirect-domain.com</b></i>  at where after login it will redirect. </p>
                    </li>
                  </ul>
                  <br><br>

                  <h5>Step 8 : </h5>
                  <ul>
                    <li>Use the following code in your application to access the user attributes:
                    </li>
                    <figure style="background-color:#979b9f; border-radius:5px;">
                        <pre>
                            <code>
                                if (!isset($_SESSION)){
                                    session_start();
                                }
                                $email = $_SESSION['sso_email'];
                                // These variables contain the mapped attribute values.
                                // After retrieving these values using the above code, you can use the $email variable in your code.
                            </code>
                        </pre>
                    </figure>
                    <li><code><b>$_SESSION['sso_email']</b></code> : contains the Email.<br/></li>
                  </ul>
                <br><br><br>
              </div>
            </div>
        </div>
    </div>
</main>
@endsection