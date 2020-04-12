@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-11">
			<div class="card">

				@include('layouts.cardnavs')

				<div class="card-body">
					
					<hr>
					<table class="table text-center" id="userTable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Role/s</th>
								<th>Email</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
					
					</hr>
				</div>

				<div class="card-footer">

				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="show_user" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enter changes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <div class="name form-group">
						<label for="name">Name</label>
						<input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
					</div>
					<div class="email form-group">
						<label for="email">Email</label>
						<input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}">
					</div>
					<div>
						<label for="role[]">Role/s</label>
					</div>
					<div class="roles form-check form-check-inline">
					</div>
                </form>
            </div>
            <div class="modal-footer">
            	<button class="delete btn btn-danger">Remove User</button>
                <button class="save btn btn-success">Save Changes</button>
            </div>
        </div>
    </div>
</div><!-- modal end -->
@endsection

@push('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/b-1.6.1/b-print-1.6.1/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/b-1.6.1/b-print-1.6.1/datatables.min.js"></script>
<script>
	var id = '';

	var table = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            "url": "{!! route('admin.manadmin') !!}",
            "type": "GET"
        },
        columns: [{ 
            data: 'id', name: 'id' 
        },{ 
            data: 'name', name: 'name' 
        },{ 
            data: 'roles[].name', name: 'roles[].name' 
        }, { 
            data: 'email', name: 'email' 
        }, { 
            data: 'viewBtn', name: 'viewBtn' 
        }],

    });

	$('#userTable').on('click', '.view', function(){

		id = $(this).closest('tr').find('.sorting_1').html();

		$.post('{{route('admin.showadmin')}}', {id}, function(data){
			console.log(data)
			//getting roles
			roles = '';
			$.each(data.roles, function(key, value){
	    		roles += '<div class="form-check form-check-inline"><input class="roles form-check-input" type="checkbox" name="role[]" value="'+key+'"><label class="form-check-label" for="inlineCheckbox1">'+value+'</label></div>'
	        });
			//attaching roles
			$('.roles').html(roles);
			//checking roles specific
			$.each(data.user.roles, function(index){
        		$('.roles input[value='+data.user.roles[index].id+']').prop("checked", true);
        	});
			//appending add data
        	$('#name').val(data.user.name);
        	$('#email').val(data.user.email);
        	//showing modal
			$('#show_user').modal({
	            show: true,
	            backdrop: 'static',
	            keyboard: false
	        });
		});
	});

	$('.save').on('click', function(){
		//preparing route
		route = "{{route('admin.updateadmin', ":userid")}}";
        route = route.replace(':userid', id);
        //getting form
        formData = new FormData($('#editUserForm')[0]);
        //updating user
		$.ajax({
	        url: route,
	        type: 'POST',
	        data: formData,
	        headers: {"X-HTTP-Method-Override": "PUT"},
	        contentType: false,
	        processData: false,
	        success: function (data) {
	        	table.ajax.reload( null, false);
	        	$('#editUserForm').findOrAppend('.alert', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'+data+'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
	        },
	        error: function (err) {
	        	$.each(err.responseJSON.errors, function(key, value){
					$('.'+key).findOrAppend('.errors', '<small class="errors alert-danger">'+value+'</small>');
					$('#'+key).addClass('border border-danger');
				});
	        }
	    });
	});

	$('#show_user').on('hide.bs.modal', function(){
		$('.alert').remove();
		$('.errors').remove();
		$('input').removeClass('border border-danger');
	});

	$('.delete').on('click', function(){
		$('#show_user').modal('hide');
		$('#failmodal').modal({
			show: true,
			backdrop: 'static',
			keyboard: false
		});
	});

	//confirm delete
	$('.confirm-delete').on('click', function(e){
		//preparing route
		route = "{{route('admin.deleteadmin', ":userid")}}";
        route = route.replace(':userid', id);

		$.ajax({
	        url: route,
	        type: 'POST',
	        headers: {"X-HTTP-Method-Override": "DELETE"},
	        contentType: false,
	        processData: false,
	        success: function (data) {
	        	console.log(data)
	        	$('#failmodal').modal('hide')
	        	table.ajax.reload( null, false);
	        },
	        error: function (err) {
	        	console.log(err)
	        }
	    });
	});

	//cancel delete
	$('.cancel-delete').on('click', function(e){
		e.preventDefault();
		$('#show_user').modal('toggle')
		$('#failmodal').modal('hide')
	});

</script>
@endpush