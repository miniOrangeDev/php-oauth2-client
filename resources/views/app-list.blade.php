@extends('layout.default')

@section('title')
    Application list
@endsection


@section('content')
<main class="app-content">   
    <div class="app-title"> 
        <div>
            <h1><i class="fa fa-list"></i>  Application list</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-home fa-lg"></i></a></li>
                <li class="breadcrumb-item">Application list</li>
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
                  <div class="row">
                      <div class="col-md-8"></div>
                      <div class="col-md-4 text-center">
                          <a href="{{route('configure-app')}}" class="btn btn-primary">Add Application</a>
                      </div>
                  </div>
                    @if(sizeof($applist) == 0)
                        <p class="text-center">No Applications</p>
                    @else
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applist as $list => $application)
                                <tr>
                                    <td>{{$list}}</td>
                                    <td><a href="{{route('edit-app',['app'=>$list])}}">Edit Application</a> | <a href="{{route('edit-app',['app'=>$list])}}#attribute-mapping">Attribute Mapping</a> | <a onclick="return confirm('Are you sure you want to delete this item?')" href="{{route('delete-app',['app'=>$list])}}">Delete</a> | <a href="{{route('how-to-setup',['app'=>$list])}}">How to Configure?</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
              </div>
            </div>
        </div>
    </div>
</main>
@endsection