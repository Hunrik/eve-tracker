@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Add job</div>
                    <div class="panel-body">
                        <form class="form-inline" role="form" method="POST" action="{{ url('/api/addJob') }}">
                            {!! csrf_field() !!}
                                    <!-- Form Name -->
                            <legend>Add Item</legend>
                            <input type="hidden" name="itemID" id="itemID" value="">
                            <!-- Text input-->
                            <div class="form-group">
                                <label for="name">Item name</label>
                                <input id="name" name="name" type="text" placeholder=""
                                       class="form-control input-md" required="">
                            </div>
                            <!-- Text input-->
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input id="quantity" name="quantity" type="number" placeholder=""
                                       class="form-control input-md form-number" required="">
                            </div>
                            <!-- Appended Input-->
                            <div class="form-group">
                                <label for="price">Price per item</label>

                                <div class="input-group">
                                    <input id="price" name="price" class="form-control form-number" placeholder=""
                                           type="number"
                                           required="">
                                    <span class="input-group-addon">ISK</span>
                                </div>
                            </div>
                            <input type="submit" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Profit</div>
                    <div class="panel-body">
                        <canvas id="profitChart" width="100%" height="400"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Orders</div>
                    <div class="panel-body">
                        <table class="table table-hover">
                            <tr>
                                <th>Name</th>
                                <th class="progressColumn text-center">Status</th>
                                <th class="text-right">Production Price</th>
                                <th class="text-right">Avg Profit</th>
                                <th class="text-right">Profit %</th>
                                <th class="text-right">Total profit</th>
                            </tr>
                            @forelse($jobs as $job)
                                <tr class="{{ $job['left']>0 ? 'info' : 'success' }}">
                                    <td><a href="#" class="view-order" data-job-id="{{$job['id']}}">{{$job['typeName']}}</a></td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="{{($job['quantity']-$job['left'])/$job['quantity']*100}}" aria-valuemin="0" aria-valuemax="100" style="width: {{($job['quantity']-$job['left'])/$job['quantity']*100}}%;">
                                                {{$job['left']}}/{{$job['quantity']}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right">{{number_format($job['price'])}}</td>
                                    <td class="text-right">{{number_format($job['avgProfit'])}}</td>
                                    <td class="text-right">{{number_format($job['avgProfit'] / $job['price']*100,2)}}
                                        %
                                    </td>
                                    <td class="text-right">{{number_format($job['totalProfit'])}} ISK</td>
                                </tr>
                            @empty
                                <h3>No entries</h3>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="jobView">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Dynamic Content</h4>
            </div>

            <div class="modal-body">

                Content is loading...

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{ url('/js/jquery-ui.min.js') }}"></script>
    <script src="{{ url('/js/moment.js') }}"></script>
    <script src="{{ url('/js/chart.min.js') }}"></script>
    <script>
        var dataset = {!! $stats !!};
        var profit = _.pluck(dataset, 'profit');
        var labels = _.pluck(dataset, 'day');
        var data = {
            labels: labels,
            datasets: [{
                label: 'Daily profit',
                fillColor: "#dff0d8",
                strokeColor: "#62ae43",
                data: _.map(profit, function (x) {
                    var y = parseInt(x) / 1000000;
                    return y.toFixed(2);
                })
            }]
        };
        $(document).ready(function () {
            var ctx = $('#profitChart').get(0).getContext("2d");
            ctx.canvas.style.width = '100%';
            ctx.canvas.style.height = '200px';
            ctx.canvas.width = ctx.canvas.offsetWidth;
            ctx.canvas.height = ctx.canvas.offsetHeight;
            var myLineChart = new Chart(ctx).Line(data, {});
        })
    </script>
    <script>
        $("#name").autocomplete({
            source: function (request, response) {
                $.ajax({
                    method: "GET",
                    url: "/items",
                    dataType: "json",
                    data: {term: request.term},
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {
                                label: item.name,
                                id: item.id,
                            };
                        }));
                    }
                });
            },
            minLength: 2,
            select: function (event, ui) {
                $('#itemID').val(ui.item.id);
            }
        });
    </script>
    <script>
        $(".view-order").on('click', function () {

          $('#jobView').removeData('bs.modal');
          $('#jobView').modal({remote: '/job/' + $(this).attr('data-job-id') });
          $('#jobView').modal('show');
          return false;
    });
    </script>
@endsection
@section('header')
    <link href="{{ url('/css/jquery-ui.min.css') }}" rel='stylesheet' type='text/css'>
@endsection
