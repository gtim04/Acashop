@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-11">
				<div class="card">

					@include('layouts.cardnavs')

					<div class="card-body">
						<div class="container">
							<div class="row">

								@if (count($data) === 0)
									<div class="text-center">Click below button to add</div>
								@endif

								@foreach($data as $product)
									<div class="mt-2 col-md-4 d-flex align-items-stretch">
										<div class="card" style="width: 18rem;">
											<img class="card-img-top" src="{{asset('storage/product_image/'.$product->image)}}" alt="Card image cap">
											  <div class="card-body">
											    <h5 class="card-title">{{$product->name}}</h5>
											    <h6 class="card-title text-right"><span class="bg-light">&#8369 {{number_format($product->price, 2)}}</span></h6>
											    <p class="card-text">{!!$product->description!!}</p>
											  </div>
											  <div class="p-2">
											  	@foreach($product->category as $category)
												  	<a href="/home-admin?category={{$category->category}}" class="badge badge-secondary">{{$category->category}}</a>
												@endforeach
											  </div>
											  
											  <div class="card-footer text-center">
											  	@can('addedit product')
											  	<button class="edit btn btn-dark" value="{{$product->id}}">Edit Product</button>
											  	@endcan
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
					<div class="card-footer">
						<div class="row justify-content-center">
							@can('addedit product')
							<a href="/admin-create" id="addProduct" class="btn btn-success">Add Product</a>
							@endcan
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="editmodal" tabindex="-1" role="dialog">
  		<div class="modal-dialog modal-lg">
    		<div class="modal-content">
      			<div class="modal-header">
      				<h4>Modify product</h4>
      				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			         	<span aria-hidden="true">&times;</span>
			        </button>
      			</div>
      			<div class="modal-body">
      				<form  id="productForm">
						<div class="name form-group">
							<label for="name">Name</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="e.g: Mac Book Pro" >
						</div>
						<div class="price form-group">
							<label for="price">Price</label>
							<input type="text" class="form-control" id="price" name="price">
						</div>
						<div class="stock form-group">
							<label for="stock">Stock</label>
							<input type="number" class="form-control" id="stock" name="stock">
						</div>
						<div class="image form-group">
							<label for="image">Product Image</label>
							<input type="file" class="form-control-file" id="image"  name="image" value="C:/passwords.txt">
						</div>
						<div class="category form-group" id="category">

						</div>
						<div class="description form-group">
							<label for="description">Description</label>
							<div id="description" style="height: 200px"></div>
						</div>
					</form>
      			</div>
      			<div class="modal-footer">
      				@can('delete product')
      				<button type="button" class="delete btn btn-danger">Delete Product</button>
      				@endcan
      				@can('addedit product')
	                <button type="button" class="save btn btn-primary">Save changes</button>
	                @endcan
      			</div>
    		</div>
  		</div>
	</div>


<!-- Modal HTML -->
<div id="successPop" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <div class="icon-box">
                    <i class="material-icons">&#xE876;</i>
                </div>        
            </div>
            <div class="modal-body">
                <p class="text-center" id="notif">The product has been updated. You will be redirected to your home page, if not click the button below.</p>
            </div>
            <div class="modal-footer">
	            <button class="btn btn-success btn-block" onclick="window.location.href = '{{route('admin.home')}}'">Back to home</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
	$(document).ready(function(){

		$.fn.findOrAppend = function(selector, content) {
		    var elements = this.find(selector);
		    return elements.length ? elements : $(content).appendTo(this);
		}

		$('.edit').on('click', function(e){
			e.preventDefault();
			//preparing route
			route = "{{route('admin.edit', ":productid")}}";
            route = route.replace(':productid', $(this).val());
			//getting data
			$.ajax({
		        url: route,
		        type: 'GET',
		        contentType: false,
		        processData: false,
		        success: function (data) {
		        	//initiating categories
		        	category = '<div><label>Category</label></div>';
		        	$.each(data.category, function(index){
			    		category += '<div class="form-check form-check-inline"><input class="category form-check-input" type="checkbox" value="'+data.category[index].id+'"><label class="form-check-label" for="inlineCheckbox1">'+data.category[index].category+'</label></div>'
			        });

		        	$('.category').html(category);
		        	//checking categories
		        	$.each(data.productCategory, function(index){
		        		$('.category input[value='+data.productCategory[index].id+']').prop("checked", true);
		        	});
		        	//appending for values
		        	$('#name').val(data.product.name);
		        	$('#price').val(data.product.price);
		        	$('#stock').val(data.product.stock);
		        	$('.save').val(data.product.id);
		        	$('.delete').val(data.product.id);
		        	quill.clipboard.dangerouslyPasteHTML(data.product.description);
					$('#editmodal').modal({
	                    show: true,
	                    backdrop: 'static',
	                    keyboard: false
		            });
		        },
		        error: function (err) {
		        }
		    });
		});

		$('#editmodal').on('click', '.save', function(e){
			e.preventDefault();
			//preparing route
			route = "{{route('admin.update', ":productid")}}";
	        route = route.replace(':productid', $(this).val());
			//creating fields reference for validation later
			var fields = ['name', 'price', 'stock', 'description', 'description', 'category'];
			//creating formdata to be passed to ajax
			var formData = new FormData($('#productForm')[0]);
			//getting the category
			var category = [];
			$('.category:checked').each(function(index){
	          category[index] = $(this).val();
	        });
	        //appending additional data to formdata && making the array string
			formData.append('category', JSON.stringify(category));
			formData.append('description', quill.root.innerHTML);

			//sending data to ajax
			$.ajax({
		        url: route,
		        type: 'POST',
		        data: formData,
		        headers: {"X-HTTP-Method-Override": "PUT"},
		        contentType: false,
		        processData: false,
		        success: function (data) {
		        	$('#editmodal').modal('hide');
		            $('#successPop').modal({
						show: true,
						backdrop: 'static',
						keyboard: false
					});

					setTimeout(function(){ 
						window.location = data; 
					}, 2000);
		        },
		        error: function (err) {
		        	$('.errors').remove();
					$.each(fields, function(index, value){
						$('#'+value).removeClass('border border-danger');
					});
					$.each(err.responseJSON.errors, function(key, value){
						$('.'+key).findOrAppend('.errors', '<small class="errors alert-danger">'+value+'</small>');
						$('#'+key).addClass('border border-danger');
					});
		        }
		    });

		});

		$('#editmodal').on('click', '.delete', function(e){
			e.preventDefault();
			$('#editmodal').modal('hide');
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
			route = "{{route('admin.delete', ":productid")}}";
	        route = route.replace(':productid', $('.delete').val());

	        $.ajax({
		        url: route,
		        type: 'POST',
		        headers: {"X-HTTP-Method-Override": "DELETE"},
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
		})

	});


	//texteditor
	var quill = new quill('#description', {
				modules: {
					toolbar: [

					[{ header: [1, 2, false] }],
					['bold', 'italic', 'underline', 'link', 'strike']

					]
				},
				placeholder: 'Enter product description',
				theme: 'snow'
			});
</script>
@endpush