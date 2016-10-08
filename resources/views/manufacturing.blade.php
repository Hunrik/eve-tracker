@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Add job</div>
                    <div class="panel-body">
                        <form class="form-inline" role="form" method="POST">
                            {!! csrf_field() !!}
                                    <!-- Form Name -->
                            <legend>Calculate item</legend>
                            <input type="hidden" name="itemID" id="itemID" value="">
                            <!-- Text input-->
                            <div class="form-group">
                                <label for="name">Blueprint</label>
                                <input id="name" name="name" type="text" placeholder=""
                                       class="form-control input-md" required="">
                            </div>
                            <div class="form-group">
                                <label for="quantity">ME</label>
                                <input id="quantity" name="me" type="number" placeholder=""
                                       class="form-control input-md form-number" value="0">
                            </div>
                            <div class="form-group">
                                <label for="quantity">PE</label>
                                <input id="quantity" name="pe" type="number" placeholder=""
                                       class="form-control input-md form-number" value="0">
                            </div>
                            <!-- Text input-->
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input id="quantity" name="quantity" type="number" placeholder=""
                                       class="form-control input-md form-number" required="">
                            </div>
                            <!-- Appended Input-->
                            <input type="submit" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="{{ url('/js/jquery-ui.min.js') }}"></script>
<script>
    $("#name").autocomplete({
        source: function (request, response) {
            $.ajax({
                method: "GET",
                url: "/api/blueprint",
                dataType: "json",
                data: {term: request.term},
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.typeName,
                            id: item.typeID,
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
@endsection
@section('header')
    <link href="{{ url('/css/jquery-ui.min.css') }}" rel='stylesheet' type='text/css'>
@endsection
