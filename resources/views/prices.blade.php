@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <a href="/prices/update" class="btn btn-info" role="button">Refresh Price</a>
                </div>
            </div>
            <table class="table table-hover">
                <tr>
                    <td>#</td>
                    <td>Item Name</td>
                    <td>Price</td>
                </tr>
                @forelse($items as $item)
                    <tr>
                        <td>{{$item['itemId']}}</td>
                        <td>{{$item['itemName']}}</td>
                        <td>{{$item['price']}}</td>
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
@endsection
