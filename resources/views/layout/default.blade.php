<!DOCTYPE html>
<html lang="en">
  <head>

    <title>@yield('title') - miniOrange OAuth Admin</title>

    {{-- meta tag --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- custom CSS for particular page  --}}
    @yield('custom_css')
  </head>

  <body class="app sidebar-mini rtl">
    <!-- Navbar-->
    <header class="app-header"><a class="app-header__logo" href="{{route('dashboard')}}"><img src="{{asset('images/logo-home.png')}}"></a>
      <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
        <!-- User Menu-->
        <li><a class="app-nav__item" href="#">{{Session::get('mo_oauth_loginemail')}}</a>
        </li>
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
            <li><a class="dropdown-item" href="{{route('logout')}}"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="{{asset('images/xecurify-logo.png')}}" alt="User Image">
        <div>
          <p class="app-sidebar__user-name">PHP OAuth</p>
          <p class="app-sidebar__user-designation">Connector</p>
        </div>
      </div>
      <ul class="app-menu">
        <li><a class="app-menu__item {{ Request::is('configure-app') ? 'active' : '' }}" href="{{route('configure-app')}}"><i class="app-menu__icon fa fa-plus-circle"></i><span class="app-menu__label">Configure OAuth</span></a></li>
        <li><a class="app-menu__item {{ Request::is('how-to-setup') ? 'active' : '' }}" href="{{route('how-to-setup')}}"><i class="app-menu__icon fa fa-info-circle"></i><span class="app-menu__label">How to setup ?</span></a></li>
        <li><a class="app-menu__item {{ Request::is('licensing') ? 'active' : '' }}" href="{{route('licensing')}}"><i class="app-menu__icon fa fa-money"></i><span class="app-menu__label">Licensing</span></a></li>
      </ul>
    </aside>
    
    {{-- all the content will be come here  --}}
    @yield('content')

    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="{{ asset('js/plugins/pace.min.js') }}"></script>
    
    {{-- Custom javascript for the pages  --}}
    @yield('custom_js')
  </body>
</html>