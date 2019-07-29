@extends('layout.default')

@section('title')
    Licensing
@endsection

@section('custom_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/licensing.css') }}">
@endsection


@section('content')
<main class="app-content">   
    <div class="app-title"> 
        <div>
            <h1><i class="fa fa-money"></i> Licensing</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item">Licensing</li>
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
                  <h4 class="text-center">miniOrange SSO using PHP OAuth Connector</h4><br>
                  <h5 class="text-center" style="color:rgb(233, 125, 104)">You are currently on the Free version of the PHP OAuth Connector</h5>
                  <h6 class="text-center" style="color:rgb(233, 125, 104)">Free version is recommended for setting up Proof of Concept (PoC)</h6>
                  <h5 class="text-center" style="color:rgb(233, 125, 104)">Try it to test the SSO connection with your OAuth2/OpenID Connect compliant Providers</h5>

                  <div class="license-line"></div>

                  <h2 class="text-center">Choose your plan</h2>
                  <div class="row">
                      <ul class="pricing-row">
                          <li class="pricing-block">
                              <div class="pricing-card">
                                <div class="pricing-header">
                                    <h4 class="text-center">FREE</h4>
                                    <h6 class="text-center">(Single User)</h6><br><br>
                                    <div class="pricing-amount">
                                        $0*
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                </div>
                                <div class="pricing-body">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr><td>1 OAuth / OpenID Connect provider Support</td></tr>
                                            <tr style="height:60px"><td>Basic Attribute Mapping (Email)</td></tr>
                                            <tr style="height:60px"><td>Authorization Code Grant</td></tr>
                                            <tr><td>Login using link / shortcode</td></tr>
                                            <tr><td>Custom Redirect URL after login</td></tr>
                                            <tr><td><br/></td></tr>
                                            <tr><td><br/></td></tr>
                                            <tr><td><br/></td></tr>
                                            <tr><td><b>Support</b><br>Basic Email Support On Demand<br><a target="_blank" href="https://www.miniorange.com/contact">Contact us</a></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                              </div>
                          </li>
                          <li class="pricing-block">
                                <div class="pricing-card">
                                  <div class="pricing-header">
                                      <h4 class="text-center">PREMIUM</h4>
                                      <h6 class="text-center">(Unlimited OAuth Providers & Advance Attribute/Role mapping)</h6><br><br>
                                      <div class="pricing-amount">
                                          $249*
                                      </div>
                                      <p class="text-center" style="color:#2f6062">(One Time)</p><br>
                                      <button onclick="upgradeform('php_oauth_2_connector_plan')" class="upgrade-button">Upgrade Now</button>
                                  </div>
                                  <div class="pricing-body">
                                      <table class="table table-striped">
                                          <tbody>
                                            <tr><td>Unlimited OAuth / OpenID Connect provider Support</td></tr>
                                            <tr><td>Advanced Attribute Mapping (Username, FirstName, LastName, Email, Group Name)</td></tr>
                                            <tr><td>Authorization Code Grant, Password Grant, Client Credentials Grant, Implicit Grant, Refresh token Grant</td></tr>
                                            <tr><td>Login using link / shortcode</td></tr>
                                            <tr><td>Custom Redirect URL after login</td></tr>
                                            <tr><td>Advanced Role + Group Mapping</td></tr>
                                            <tr><td>Custom Logout URL</td></tr>
                                            <tr><td>JWT Support</td></tr>
                                            <tr><td><b>Support</b><br>Basic Email & GoToMeeting Support On Demand<br><a target="_blank" href="https://www.miniorange.com/contact">Contact us</a></td></tr>
                                          </tbody>
                                      </table>
                                  </div>
                                </div>
                            </li>
                      </ul>
                  </div>
              </div>
            </div>
        </div>
    </div>
</main>
<form style="display:none;" id="loginform" action="https://login.xecurify.com/moas/login" 
target="_blank" method="post">
<input type="text" name="redirectUrl" value="https://login.xecurify.com/moas/login/initializepayment" />
<input type="text" name="requestOrigin" id="requestOrigin"  />
</form>
@endsection


@section('custom_js')
<script>
    function upgradeform(planType){
        //alert(planType);
        $('#requestOrigin').val(planType);
        $('#loginform').submit();
    }
</script>
@endsection