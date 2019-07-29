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
    <title>Register - miniOrange OAuth Admin</title>
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
      <div class="login-box" style="min-height:490px">
        <form method="post" class="login-form" action="{{route('post-register')}}">
          {{ csrf_field() }}
          <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN UP</h3>
          <div class="form-group">
            <label class="control-label">EMAIL</label>
            <input class="form-control" type="email" pattern="[a-zA-Z0-9!#$%&amp;'*+\/=?^_`{|}~.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*" placeholder="Email" autofocus name="email" value="{{ old('email') }}" required>
          </div>
          <div class="form-group">
            <label class="control-label">PASSWORD</label>
            <input class="form-control" type="password" placeholder="Password"  minlength="6" name="password" id="password" required>
          </div>
          <div class="form-group">
            <label class="control-label">CONFIRM PASSWORD</label>
            <input class="form-control" type="password" placeholder="Confirm Password"  minlength="6" name="confirm_password" id="confirm_password" required>
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block" id="register"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN UP</button>
          </div>
          <div class="form-group">
            <p class="semibold-text mb-2 text-center"><span class="label-text">Already have an account?</span> <a href="{{ route('get-login') }}">login</a></p>
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
    <script>
        var password = document.getElementById("password")
        , confirm_password = document.getElementById("confirm_password");

        function validatePassword(){
          if(password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match");
          } else {
            confirm_password.setCustomValidity('');
          }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    </script>
  </body>
</html>