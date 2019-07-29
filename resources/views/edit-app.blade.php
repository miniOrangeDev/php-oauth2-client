@extends('layout.default')

@section('title')
    Update Application
@endsection


@section('content')
<main class="app-content">   
    <div class="app-title"> 
        <div>
            <h1><i class="fa fa-plus-circle"></i> Update Application</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item">Update Application</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Notifications -->
            <div id="notific">        
                @include('inc.message')        
            </div>

            {{-- application details --}}
            <div class="tile">
                <div class="tile-body">
                    <h4>Update Application</h4>

                    {{-- form for application --}}
                    <form action="{{route('post-edit-app')}}" method="POST">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-10">
                                
                            {{-- Application name  --}}
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label class="control-label"><font color="#FF0000">*</font><b>Application:</b></label>
                                </div>
                                <div class="col-md-6">    
                                    <input type="hidden" id="mo_oauth_app_name" name="mo_oauth_app_name" value="{{$appname}}" required>
                                    <label class="control-label"><b>{{$appname}}</b><a target="_blank" href="{{route('how-to-setup',['app'=>$appname])}}" style="margin-left:20px">How to setup ?</a></label>
                                </div>
                            </div>

                            {{-- Redirect URL  --}}
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label class="control-label"><b>Redirect / Callback URL: </b></label>
                                </div>
                                <div class="col-md-6">    
                                    <input id="form-control"  class="form-control" readonly="true" name="mo_oauth_redirect_url"  type="text" value='{{route('response')}}'>
                                </div>
                            </div>

                            {{-- Display App name  --}}
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label class="control-label"><b>Display App Name: </b><br><p style="color:red">[PREMIUM]</p></label>
                                    </div>
                                    <div class="col-md-6">    
                                        <input id="form-control"  class="form-control" name="mo_oauth_display_app_name" readonly="true"  type="text" value='{{old('mo_oauth_display_app_name')}}' placeholder="Enter Display App name">
                                    </div>
                                </div>

                            {{-- SSO protocol  --}}
                            <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><b>SSO Protocol: </b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_sso_protocol" readonly="true"  type="text" value='{{$app['sso_protocol']}}' placeholder="Enter SSO Protocol">
                                        </div>
                                    </div>

                            {{-- Client ID  --}}
                            <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><font color="#FF0000">*</font><b>Client ID:</b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_client_id"  type="text" value='{{$app['client_id']}}' placeholder="Enter Client ID" required>
                                        </div>
                                    </div>

                             {{-- Client Secret  --}}
                             <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><font color="#FF0000">*</font><b>Client Secret:</b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_client_secret"  type="text" value='{{$app['client_secret']}}' placeholder="Enter Client Secret" required>
                                        </div>
                                    </div>


                            {{-- Scope  --}}
                            <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><b>Scope:</b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_scope"  type="text" value='{{$app['scope']}}' placeholder="Enter Scope">
                                        </div>
                                    </div>

                            {{-- Authorized Endpoints  --}}
                            <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><font color="#FF0000">*</font><b>Authorize Endpoint:</b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_authorize_endpoint"  type="text" value='{{$app['authorize']}}' placeholder="Enter Authorize Endpoint" required>
                                        </div>
                                    </div>

                            {{-- Access Token Endpoints  --}}
                            <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><font color="#FF0000">*</font><b>Access Token Endpoint:</b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_access_token_endpoint"  type="text" value='{{$app['token']}}' placeholder="Enter Access Token Endpoint" required>
                                        </div>
                                    </div>

                            
                            @if($app['app_type'] == "oauth")
                            {{-- Get User Info Endpoint  --}}
                            <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><font color="#FF0000">*</font><b>Get User Info Endpoint:</b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_user_info_endpoint"  type="text" value='{{$app['user_info']}}' placeholder="Enter Get User Info Endpoint" required>
                                        </div>
                                    </div>
                            @endif

                            {{-- Submit button --}}
                            <div class="row mt-3">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">Save settings</button>
                                    <button type="button" id="test-configuration" onclick="testConfiguration()" class="btn btn-primary">Test Configuration</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            {{-- Attribute mapping  --}}
            <div class="tile" id="attribute-mapping">
                    <div class="tile-body">
                        <h4>Attribute Mapping [required for ACCOUNT LINKING ]</h4>

                        <p style="color:red">Do Test Configuration above to get configuration for attribute mapping.</p>

                        {{-- form for application --}}
                        <form action="{{route('attribute-mapping')}}" method="POST">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-10">
    
                                {{-- Email  --}}
                                <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="control-label"><font color="#FF0000">*</font><b>Email: </b></label>
                                            </div>
                                            <div class="col-md-6">    
                                                <input name="mo_oauth_appname" value="{{$appname}}" type="hidden">
                                                <input id="form-control"  class="form-control" name="mo_oauth_attribute_email"  type="text" value='@if(isset($app['attributes'])){{$app['attributes']['email_attr']}}@endif' placeholder="Enter attribute name for Email" required>
                                                
                                                {{-- premium version line  --}}
                                                <br>
                                                <p>Advanced attribute mapping is available in premium version.</p>
                                            </div>
                                        </div>

                                {{-- First name  --}}
                                <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="control-label"><b>First Name: </b></label>
                                            </div>
                                            <div class="col-md-6">    
                                                <input id="form-control"  class="form-control" name="mo_oauth_attribute_first_name" disabled  type="text" value='' placeholder="Enter attribute name for First name">
                                            </div>
                                        </div>

                                
                                {{-- Last Name  --}}
                                <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="control-label"><b>Last Name: </b></label>
                                            </div>
                                            <div class="col-md-6">    
                                                <input id="form-control"  class="form-control" name="mo_oauth_attribute_last_name" disabled  type="text" value='' placeholder="Enter attribute name for Last Name">
                                            </div>
                                        </div>

                                
                                {{-- username  --}}
                                <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="control-label"><b>Username: </b></label>
                                            </div>
                                            <div class="col-md-6">    
                                                <input id="form-control"  class="form-control" name="mo_oauth_attribute_username" disabled  type="text" value='' placeholder="Enter attribute name for Username">
                                            </div>
                                        </div>

                                    
                                {{-- Group / Role  --}}
                                <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="control-label"><b>Group / Role: </b></label>
                                            </div>
                                            <div class="col-md-6">    
                                                <input id="form-control"  class="form-control" name="mo_oauth_attribute_group" disabled  type="text" value='' placeholder="Enter attribute name for Group/Role">
                                            </div>
                                        </div>

                                {{-- Display Name  --}}
                                <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="control-label"><b>Display Name: </b></label>
                                            </div>
                                            <div class="col-md-6">    
                                                <select name="mo_oauth_attribute_display_name" class="form-control" disabled>
                                                    <option value="FirstName" selected>FirstName</option>
                                                </select>
                                            </div>
                                        </div>

    
                               
                                {{-- Submit button --}}
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary">Save settings</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</main>
@endsection


@section('custom_js')
    <script>
        // function to give user test configuration on button click
        function testConfiguration(){
            var mo_oauth_app_name = jQuery("#mo_oauth_app_name").val();  
            var test_config_url = "{{route('test-configuration',['app'=>$appname])}}";
            var myWindow = window.open(test_config_url, "Test Attribute Configuration", "width=600, height=600");

            while(1){    
                if(myWindow.closed()) { 
                    $(document).trigger("config_tested");  
                    break;    
                } else {continue;}      
            }
        }
    </script>
@endsection