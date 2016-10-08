@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <a href="/journal/update" class="btn btn-info" role="button">Refresh Price</a>
                </div>
            </div>
            <table class="table table-hover">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Quantity</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Type</th>
                </tr>
                @forelse($entries as $entry)
                    <tr class="{{ $entry['transactionType']=='sell' ? 'success' : 'danger' }}">
                        <td>{{$entry['transactionID']}}</td>
                        <td>{{$entry['transactionDateTime']}}</td>
                        <td>{{$entry['quantity']}}</td>
                        <td>{{$entry['typeName']}}</td>
                        <td>{{$entry['price']}}</td>
                        <td>{{$entry['transactionType']}}</td>
                    </tr>
                @empty
                    <h3>No entries</h3>
                @endforelse
            </table>
        </div>
    </div>
</div>
@endsection
