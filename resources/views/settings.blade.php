@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Api Keys</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table">
                            <tr>
                                <th>Api Key</th>
                                <th>Verificaion code</th>
                                <th>AccessMask</th>
                            </tr>
                            @forelse($apikeys as $apikey)
                                <tr>
                                    <td>{{$apikey['key']}}</td>
                                    <td>{{$apikey['vCode']}}</td>
                                    <td>
                                        <a href="/user/setAccess/{{$apikey['key']}}" class="btn btn-default btn-sm">Set Access</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>No api keys found</td>
                                </tr>
                            @endforelse
                        </table>
                        <form class="form-horizontal" role="form" method="POST" id="addKey"
                              action="{{ url('/user/addApiKey') }}">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('key') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Api Key</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="key" id="key" value="{{ old('key') }}"
                                           required>

                                    @if ($errors->has('key'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('key') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('vCode') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Verification Code</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="vCode" id="vCode"
                                           alue="{{ old('vCode') }}"
                                           required>

                                    @if ($errors->has('vCode'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('vCode') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-plus"></i>Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="characterPicker" tabindex="-1" role="dialog" aria-labelledby="characterPickerLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="characterPickerLabel">Modal title</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" method="POST" id="chars" name="chars"
                          action="{{ url('/user/addApiKey') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" value="" id="modal-key" name="key">
                        <input type="hidden" value="" id="modal-vCode" name="vCode">

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="document.chars.submit()">Add characters</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('scripts')
    <script src="/js/listgroup.min.js"></script>
    <script>
        $('#addKey').submit(function (event) {
            event.preventDefault();
            $('#chars .checkbox').remove();
            $.ajax({
                url: '/user/getChars',
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    key: $('#key').val(),
                    vCode: $('#vCode').val()
                }

            }).done(function (data) {
                var chars = $.parseJSON(data);
                console.log(chars);
                $('#modal-key').val($('#key').val());
                $('#modal-vCode').val($('#vCode').val());
                $.each(chars,function (index, char) {
                    $('#chars').append('<div class="checkbox" id="chars"><label><input type="checkbox" name="chars[]" value="'+char.characterID+'">'+char.name+'</label></div>');
                })
            });
            $('#characterPicker').modal('show');
        });
    </script>
@endsection
