@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-11">
				<div class="card border-dark">

					@if (count($data) === 0)
						<div class="card-header bg-dark text-light">Sorry we don't have any products right now. Come back later.</div>
					@else
					<div class="card-header bg-dark text-light"><h4>Thank you for shopping at Acashop! Follow your heart, add to cart!</h4>
						@if(request('category'))
						<a href="{{route('user.home')}}" class="badge-pill badge-secondary float-right">Clear Filter</a>
						@endif
			         </div>
					@endif

					

					<div class="card-body">
						<div class="container">
							<div class="row">

								@if (count($data) === 0)
									<div class="text-center">Ooops sorry we dont have any product right now.</div>
								@endif

								@foreach($data as $product)
									<div class="mt-2 col-md-4 d-flex align-items-stretch">
										<div class="card border-dark" style="width: 18rem;">
											<img class="card-img-top" src="{{asset('storage/product_image/'.$product->image)}}" alt="Card image cap">
											  <div class="card-body">
											    <h5 class="card-title">{{$product->name}}</h5>
											    <h6 class="card-title text-right"><span class="bg-light">&#8369 {{number_format($product->price, 2)}}</span></h6>
											    <p class="card-text">{!!$product->description!!}</p>
											  </div>
											  <div class="p-2">
											  	@foreach($product->category as $category)
												  	<a href="/home-user?category={{$category->category}}" class="badge badge-secondary">{{$category->category}}</a>
												@endforeach
											  </div>
											  <div class="card-footer text-center">
											  	<button class="view btn btn-dark" value="{{$product->id}}">View Product</button>
											  </div>
										</div>
									</div>
								@endforeach	
							</div>
						</div>
						@if(!request('category'))
						<div class="row justify-content-center mt-3"><p>{{ $data->links() }}</p></div>
						@endif
					</div>
					<div class="card-footer bg-dark text-light">
						<a href="{{route('user.orders')}}" class="btn btn-primary">Go to your orders!</a>
						<button class="viewCart btn btn-primary float-right"><span class="badge badge-light">{{$orderCount}}</span> View cart</button>
					</div>
				</div>
			</div>
		</div>
	</div>

{{-- view product --}}
	<div class="modal fade" id="viewmodal" tabindex="-1" role="dialog">
  		<div class="modal-dialog">
    		<div class="modal-content">
      			<div class="modal-header">
      				<h4 id="prodName"></h4>
      				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			         	<span aria-hidden="true">&times;</span>
			        </button>
      			</div>
      			<div class="modal-body">
      				<form  id="productForm">
						<div class="name form-group">
							<label for="name">Name</label>
							<input type="text" class="form-control" id="name" name="name" readonly>
						</div>
						<div class="price form-group">
							<label for="price">Price</label>
							<input type="text" class="form-control" id="price" name="price" readonly>
						</div>
						<div class="stock form-group">
							<label for="stock">Stock</label>
							<input type="text" class="form-control" id="stock" name="stock" readonly>
						</div>
						<div class="description form-group">
							<label for="description">Description</label>
							<div id="description" style="height: 150px"></div>
						</div>
						<div class="quantity form-group">
							<label for="quantity">How many you would like to add?</label>
							<input type="number" class="form-control" id="quantity" name="quantity">
						</div>
					</form>
      			</div>
      			<div class="modal-footer">
	                <button type="button" class="add btn btn-primary">Add to cart</button>
      			</div>
    		</div>
  		</div>
	</div>

{{-- sucess --}}
<div id="successPop" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <div class="icon-box">
                    <i class="material-icons">&#xE876;</i>
                </div>        
            </div>
            <div class="modal-body">
                <p class="text-center" id="notif">The product has been added. You will be redirected to your home page, if not click the button below.</p>
            </div>
            <div class="modal-footer">
	            <button class="btn btn-success btn-block" onclick="window.location.href = '{{route('user.home')}}'">Back to home</button>
            </div>
        </div>
    </div>
</div>

{{-- cart --}}
<div class="modal fade" id="cartmodal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
  			<div class="modal-header">
  				<h4 id="prodName">Hey {{auth()->user()->name}} ready to check out?</h4>
  				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		         	<span aria-hidden="true">&times;</span>
		        </button>
  			</div>
  			<div class="modal-body">
  				<table class="table table-dark table-striped text-center">
  					<thead>
  						<tr>
  							<th colspan="4">Your cart summary!</th>
  						</tr>
  						<tr>
  							<th>Product</th>
  							<th>Price</th>
  							<th>Quantity</th>
  							<th>Total</th>
  						</tr>
  					</thead>
  					<tbody id="tableContent">
						  						
  					</tbody>
  				</table>
  			</div>
  			<div class="modal-footer">
                <button type="button" class="btn btn-success" id="checkout">Check out!</button>
  			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function(){

	//viewing product
	$('.view').on('click', function(e){
		e.preventDefault();
		$('.errors').remove();
		$('#quantity').removeClass('border border-danger');
		//preparing route
		route = "{{route('user.show', ":productid")}}";
        route = route.replace(':productid', $(this).val());
		//getting data
		$.ajax({
	        url: route,
	        type: 'GET',
	        contentType: false,
	        processData: false,
	        success: function (data) {
	        	$('#name').val(data.name);
	        	$('#prodName').text(data.name);
	        	$('#price').val('₱ '+numberFormat(data.price));
	        	$('#stock').val(data.stock);
	        	$('.add').val(data.id);
	        	$('#description').html(data.description);
	        	$('#quantity').val(0);
				$('#viewmodal').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false
	            });
	        },
	        error: function (err) {
	        }
	    });
	});

	//adding items to cart
	$('.add').on('click', function(e){
		e.preventDefault();
		//preparing route
		route = "{{route('user.add', ":productid")}}";
        route = route.replace(':productid', $(this).val());
		//running cart
		$.ajax({
	        url: route,
	        type: 'POST',
	        data: { quantity: $('#quantity').val() },
	        success: function (data) {
	        	$('#viewmodal').modal('hide');
	        	$('#successPop').modal({
					show: true,
					backdrop: 'static',
					keyboard: false
				});

				setTimeout(function(){ 
					window.location = data; 
				}, 1000);
	        },
	        error: function (err) {
	        	$('.errors').remove()
	        	if(err.responseJSON.error){
	        		$('.quantity').findOrAppend('.errors', '<small class="errors alert-danger">'+err.responseJSON.error+'</small>');
	        		$('#quantity').addClass('border border-danger');
	        	}
	        	$.each(err.responseJSON.errors, function(key, value){
					$('.'+key).findOrAppend('.errors', '<small class="errors alert-danger">'+value+'</small>');
					$('#'+key).addClass();
				});
	        }
	    });
	});

	//viewing cart
	$('.viewCart').on('click', function(e){
		e.preventDefault();
		//viewing cart
		$.ajax({
	        url: '{{route('user.cart')}}',
	        type: 'GET',
	        contentType: false,
	        processData: true,
	        success: function (data) {
	        	var table = '';
	        	if('orders' in data && data.orders.count > 0){
	        		$.each(data.orders.products, function(index, value){
						table += '<tr><td>'+data.orders.products[index].product.name+'</td><td>&#8369;'+numberFormat(data.orders.products[index].product.price)+'</td><td>'+data.orders.products[index].quantity+'</td><td>&#8369;'+numberFormat(data.orders.products[index].price)+'<button class="remove close" value="'+data.orders.products[index].product.id+'"><span aria-hidden="true">&times;</span></button></td></tr>';
					});
					table += '<tr><td colspan="3" class="text-right">Total amount to pay: </td><td>&#8369;'+numberFormat(data.orders.total)+'</td></tr>'
	        	} else {
	        		table += '<tr><td colspan="4">You have no items in your cart.</td></tr>'
	        	}
	        	$("#tableContent").html(table);
	        	$('#cartmodal').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false
	            });
	        },
	        error: function (err) {
	        }
	    });
	});

	//show confirmation message
    $('#cartmodal').on('click', '.remove', function(e){
		e.preventDefault();
		$('#cartmodal').modal('hide');
    	$('#failmodal').modal({
			show: true,
			backdrop: 'static',
			keyboard: false
		});
	});

    //confirm delete
	$('.confirm-delete').on('click', function(e){
		e.preventDefault();
		//preparing route
		route = "{{route('user.remove', ":productid")}}";
	    route = route.replace(':productid', $('.remove').val());
		//running cart
		$.ajax({
	        url: route,
	        type: 'GET',
	        contentType: false,
	        processData: false,
	        success: function (data) {
	        	$('#notif').html('Product removed. You will be redirected to the home page.');
	        	$('#failmodal').modal('hide')
	        	$('#successPop').modal({
					show: true,
					backdrop: 'static',
					keyboard: false
				});
				setTimeout(function(){ 
					window.location = data; 
				}, 1000);
	        },
	        error: function (err) {
	        }
	    });
	})

	//cancel delete
	$('.cancel-delete').on('click', function(e){
		e.preventDefault();
		$('#failmodal').modal('toggle')
		$('#cartmodal').modal('toggle');
	})

	$('#checkout').on('click', function(e){
		e.preventDefault();
		
		$('.close').hide();
		$(this).prop('disabled', true);
		$(this).html('Sending email. Please wait..');

		$.ajax({
	        url: '{{route('user.checkout')}}',
	        type: 'POST',
	        contentType: false,
	        processData: false,
	        success: function (data) {
	        	$('#notif').html('Order completed. You will be redirected to the summary page.');
	        	$('#cartmodal').modal('hide');
	        	$('#successPop').modal({
					show: true,
					backdrop: 'static',
					keyboard: false
				});
				setTimeout(function(){ 
					window.location = data; 
				}, 4000);
	        },
	        error: function (err) {
	        	$('#notif').html(err.responseJSON.error);
	        	$('#cartmodal').modal('hide');
	        	$('#successPop').modal({
					show: true,
					backdrop: 'static',
					keyboard: false
				});
	        }
	    });
	});
});
</script>
@endpush