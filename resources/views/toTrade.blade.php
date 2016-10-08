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
                    <td>Item Name</td>
                    <td>Avg pricet</td>
                    <td>Sell</td>
                    <td>Buy</td>
                    <td>Profit</td>
                    <td>Probable profit</td>
                    <td>%</td>
                </tr>
                @forelse($items as $item)
                    <tr>
                        <td class="{{$item['typeID']}}">{{$item['typeName']}}</td>
                        <td>{{ number_format( $item['avgPrice'], 2) }}</td>
                        <td>{{ number_format( $item['sell'], 2) }}</td>
                        <td>{{ number_format( $item['buy'], 2) }}</td>
                        <td>{{ number_format( $item['profit'], 2 )}}</td>
                        <td>{{ number_format( $item['percent'],2)}}</td>
                        <td>{{ number_format( $item['totalProfit']) }}</td>
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
