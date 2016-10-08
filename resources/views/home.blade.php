@extends('layouts.app')

@section('content')
<div class="container spark-screen">
  <div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">This months profit</div>

          <div class="panel-body text-center">
              <h2>{{ number_format( $totalProfit ) }} ISK
                <small class="text-success">+ {{ number_format( $profitToday) }} ISK today
                </small>
              </h2>
              <h4>{{ number_format( $totalProfit / date('j') )}} ISK/Day</h4>
          </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">Wallett</div>

          <div class="panel-body text-center">
              <h2>{{ number_format( $wallet ) }} ISK<br>
                <small>{{ number_format( $totalProfit / date('j') )}} ISK/Day
                </small>
              </h2>
          </div>
        </div>
    </div>
  </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Jobs</div>

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
                              <td><a data-toggle="modal" href="/job/{{$job['id']}}" data-target="#jobView">{{$job['typeName']}}</a></td>
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
@endsection
