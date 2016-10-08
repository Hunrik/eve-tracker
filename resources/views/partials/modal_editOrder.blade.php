<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">{{$order['typeName']}}</h4>
</div>

<div class="modal-body">
	<form class="form-horizontal row" id="modalForm" role="form" action="/job/{{$order['id']}}" method="post">
		<input type="hidden" name="_method" value="PUT" />
		{{ csrf_field() }}
		<div class="form-group col-sm-12">
		    <label for="typeName" class="col-sm-4 control-label">Item name</label>
		    <div class="col-sm-6">
		    	<p class="form-control-static">{{$order['typeName']}} (#{{$order['typeID']}}) Sold: {{$quantity}}</p>
	    	</div>
		</div>
		<div class="form-group col-sm-6 col-md-4 colfix">
		    <label for="typeName" class="col-sm-12 text-center">Quantity</label>
		    <div class="col-sm-12">
		    	<input type="text" name="quantity" class="form-control" value="{{$order['quantity']}}">
	    	</div>
		</div>
		<div class="form-group col-sm-6 col-md-4 colfix">
		    <label for="typeName" class="col-sm-12 text-center">Left</label>
		    <div class="col-sm-12">
		    	<input type="text" name="left" class="form-control" value="{{$order['left']}}">
	    	</div>
		</div>
		<div class="form-group col-sm-6 col-md-4 colfix">
		    <label for="typeName" class="col-sm-12 text-center">Price per unit</label>
		    <div class="col-sm-12">
		    	<input type="text" name="price" class="form-control" value="{{$order['price']}}">
	    	</div>
		</div>
		<input type="submit">
	</form>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
	<button type="button" id="submitModal" class="btn btn-info">Save changes</button>
</div>
<script>
$("#submitModal").click( function() {
    $('#modalForm').submit();
});
</script>
