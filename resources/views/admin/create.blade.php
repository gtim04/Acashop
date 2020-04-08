@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-10">
			<div class="card">
				<div class="card-header">Create new product</div>

				<div class="card-body">
					@if (session('status'))
					<div class="alert alert-success" role="alert">
						{{ session('status') }}
					</div>
					@endif

					<div>
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
								<input type="file" class="form-control-file" id="image"  name="image">
							</div>
							<div class="category form-group" id="category">
								<div>
									<label>Category</label>
								</div>
								@foreach($data as $category)
								<div class="form-check form-check-inline">
									<input class="category form-check-input" type="checkbox" value="{{$category->id}}">
									<label class="form-check-label" for="inlineCheckbox1">{{$category->category}}</label>
								</div>
								@endforeach
							</div>
							<div class="description form-group">
								<label for="description">Description</label>
								<div id="description" style="height: 200px"></div>
							</div>
							<div class="form-group">
								<a href="{{route('admin.home')}}" class="btn btn-danger float-left">Discard Product</a>
								<button id="submit" class="btn btn-success float-right">Submit Product</button>
							</div>
						</form>
					</div>

				</div>
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
                <p class="text-center">The product has been added. You will be redirected to your home page, if not click the button below.</p>
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

	$('#submit').on('click', function(e){
		e.preventDefault();
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
	        url: '{!! route('admin.store') !!}',
	        type: 'POST',
	        data: formData,
	        contentType: false,
	        processData: false,
	        success: function (data) {
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