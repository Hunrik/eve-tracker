@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Registered Users</div>

                    <div class="panel-body">

                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Registered Users</div>

                    <div class="panel-body">
                        <table class="table table-hover">
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Email</th>
                            </tr>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{$user['id']}}</td>
                                    <td>{{$user['name']}}</td>
                                    <td>{{$user['email']}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td>No users found</td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
