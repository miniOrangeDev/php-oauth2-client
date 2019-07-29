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
                    <div class="form-group" style="margin-top:30px">                      
                        <input type="text" id="mo_oauth_client_default_apps_input" class="form-control" onkeyup="mo_oauth_client_default_apps_input_filter()" placeholder="Select application" title="Type in a Application Name">    
                    </div>
                    <hr>
                    <div class="row">    
                        <ul id="mo_oauth_client_default_apps">
                            @foreach ($defaultappsjson as $appId => $application)    
                                <li data-appid="{{$appId}}">
                                    <a href="{{route('configure-app',['appId' => $appId])}}"><img class="mo_oauth_client_default_app_icon" src="{{asset('images/apps/'.$application->image)}}"><br>{{$application->label}}</a>
                                </li>
                            @endforeach   
                        </ul>
                    </div>
                    <div id="mo_oauth_client_search_res"></div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

{{-- Custom CSS --}}
@section('custom_js')
    <script>
        function mo_oauth_client_default_apps_input_filter() {
            var input, filter, ul, li, a, i;
            var counter = 0;
            input = document.getElementById("mo_oauth_client_default_apps_input");
            filter = input.value.toUpperCase();
            ul = document.getElementById("mo_oauth_client_default_apps");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                if (a.innerHTML.split('<br>')[1].toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                    counter++;
                }
                if(counter>=li.length) {
                    document.getElementById("mo_oauth_client_search_res").innerHTML = "<p class='lead muted'>No Applications Found In This Category.</p>";
                } else {
                    document.getElementById("mo_oauth_client_search_res").innerHTML = "";
                }
            }
        }
    </script>
@endsection