@extends('layout.default')

@section('title')
    Configure OAuth
@endsection


@section('content')
<main class="app-content">   
    <div class="app-title"> 
        <div>
            <h1><i class="fa fa-plus-circle"></i> Configure OAuth</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item">Configure OAuth</li>
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
                    <h4>Add Application</h4>

                    {{-- form for application --}}
                    <form action="{{route('post-configure-app')}}" method="POST">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-10">
                                
                            {{-- Application name  --}}
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label class="control-label"><font color="#FF0000">*</font><b>Application:</b></label>
                                </div>
                                <div class="col-md-6">    
                                    <input type="hidden" name="mo_oauth_app_name" value="{{$currentApp->appId}}">
                                    <input type="hidden" name="mo_oauth_app_type" value="{{$currentApp->type}}">
                                    <label class="control-label"><b>{{$currentApp->label}}</b><a href="{{route('configure-app')}}" class="btn btn-primary" style="margin-left:20px">Change Application</a></label>
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

                            {{-- App name  --}}
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label class="control-label"><font color="#FF0000">*</font><b>App Name: </b></label>
                                </div>
                                <div class="col-md-6">    
                                    <input id="form-control"  class="form-control" name="mo_oauth_custom_app_name"  type="text" value='{{old('mo_oauth_custom_app_name')}}' placeholder="Enter App name" required>
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
                                            <input id="form-control"  class="form-control" name="mo_oauth_sso_protocol" readonly="true"  type="text" value='{{$currentApp->type}}' placeholder="Enter SSO Protocol">
                                        </div>
                                    </div>

                            {{-- Client ID  --}}
                            <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><font color="#FF0000">*</font><b>Client ID:</b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_client_id"  type="text" value='{{old('mo_oauth_client_id')}}' placeholder="Enter Client ID" required>
                                        </div>
                                    </div>

                             {{-- Client Secret  --}}
                             <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><font color="#FF0000">*</font><b>Client Secret:</b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_client_secret"  type="text" value='{{old('mo_oauth_client_secret')}}' placeholder="Enter Client Secret" required>
                                        </div>
                                    </div>


                            {{-- Scope  --}}
                            <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><b>Scope:</b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_scope"  type="text" value='{{$currentApp->scope}}' placeholder="Enter Scope">
                                        </div>
                                    </div>

                            {{-- Authorized Endpoints  --}}
                            <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><font color="#FF0000">*</font><b>Authorize Endpoint:</b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_authorize_endpoint"  type="text" value='{{$currentApp->authorize}}' placeholder="Enter Authorize Endpoint" required>
                                        </div>
                                    </div>

                            {{-- Access Token Endpoints  --}}
                            <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><font color="#FF0000">*</font><b>Access Token Endpoint:</b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_access_token_endpoint"  type="text" value='{{$currentApp->token}}' placeholder="Enter Access Token Endpoint" required>
                                        </div>
                                    </div>

                            
                            @if($currentApp->type == "oauth")
                            {{-- Get User Info Endpoint  --}}
                            <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="control-label"><font color="#FF0000">*</font><b>Get User Info Endpoint:</b></label>
                                        </div>
                                        <div class="col-md-6">    
                                            <input id="form-control"  class="form-control" name="mo_oauth_user_info_endpoint"  type="text" value='{{$currentApp->userinfo}}' placeholder="Enter Get User Info Endpoint" required>
                                        </div>
                                    </div>
                            @endif

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
