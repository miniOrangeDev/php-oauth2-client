<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- title  -->
    <title>Login - miniOrange OAuth Admin</title>
  </head>
  <body>

    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        <img src="{{asset('images/logo-large.png')}}">
      </div>
      <!-- Notifications -->
      <div id="notific">
          @include('inc.message')
      </div>
      <div class="login-box" style="height:450px">
        <form method="post" class="login-form" action="{{route('post-login')}}">
          {{ csrf_field() }}
          <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
          <div class="form-group">
            <label class="control-label">EMAIL</label>
            <input class="form-control" value="{{old('email')}}" type="email" pattern="[a-zA-Z0-9!#$%&amp;'*+\/=?^_`{|}~.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*" placeholder="Email" autofocus name="email" required>
          </div>
          <div class="form-group">
            <label class="control-label">PASSWORD</label>
            <input class="form-control"  minlength="6" type="password" placeholder="Password" name="password" required>
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
          </div>
          <div class="form-group">
            <p class="semibold-text mb-2 text-center"><span class="label-text">Not registerd?</span> <a href="{{ route('get-register') }}">Create an account</a></p>
          </div>
          <div class="form-group">
            <p class="semibold-text mb-2 text-center"><a target="_blank" href="https://login.xecurify.com/moas/idp/resetpassword">Forgot Password?</a></p>
          </div>
        </form>
      </div>
    </section>

    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="{{ asset('js/plugins/pace.min.js') }}"></script>
  </body>
</html>