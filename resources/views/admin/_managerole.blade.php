@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-11">
			<div class="card">

				@include('layouts.cardnavs')

				<div class="card-body">
					
					<hr>
					<table class="table text-center" id="roleTable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Permission/s</th>
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

<div class="modal fade" id="show_role" tabindex="-1" role="dialog">
    <div class="modal-lg modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enter changes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm">
                    <div class="name form-group">
						<label for="name">Role Name</label>
						<input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
					</div>
					<div>
						<label for="permissions">Permission/s</label>
					</div>
					<div class="permissions form-check form-check-inline">
					</div>
                </form>
            </div>
            <div class="modal-footer">
            	<button class="delete btn btn-danger">Remove Role</button>
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
	var id =''

	table = $('#roleTable').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            "url": "{!! route('admin.manroles') !!}",
            "type": "GET"
        },
        columns: [{ 
            data: 'id', name: 'id' 
        },{ 
            data: 'name', name: 'name' 
        },{ 
            data: 'permissions[].name', name: 'permissions[].name' 
        },{ 
            data: 'viewBtn', name: 'viewBtn' 
        }],

    });

    $('#roleTable').on('click', '.view', function(){
		id = $(this).closest('tr').find('.sorting_1').html();
		//preparing route
		route = "{{route('admin.showrole', ":roleid")}}";
        route = route.replace(':roleid', id);

		$.get(route, function( data ) {
			$('#name').val(data.role.name);

			permissions = ''

			$.each(data.permissions, function(key, value){
	    		permissions += '<div class="form-check form-check-inline"><input class="permissions form-check-input" type="checkbox" name="permissions[]" value="'+key+'"><label class="form-check-label" for="inlineCheckbox1">'+value+'</label></div>'
	        });

			$('.permissions').html(permissions);

			$.each(data.role.permissions, function(index){
        		$('.permissions input[value='+data.role.permissions[index].id+']').prop("checked", true);
        	});

			$('#show_role').modal('show');

		});
	});

	$('.save').on('click', function(){
		//preparing route
		route = "{{route('admin.updaterole', ":roleid")}}";
        route = route.replace(':roleid', id);
        //getting form
        formData = new FormData($('#editRoleForm')[0]);
        //updating role
		$.ajax({
	        url: route,
	        type: 'POST',
	        data: formData,
	        headers: {"X-HTTP-Method-Override": "PUT"},
	        contentType: false,
	        processData: false,
	        success: function (data) {
	        	console.log(data)
	        	table.ajax.reload( null, false);
	        	$('#editRoleForm').findOrAppend('.alert', '<div class="mt-2 alert alert-success alert-dismissible fade show" role="alert"><strong>'+data+'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
	        },
	        error: function (err) {
	        	console.log(err)
	        	$.each(err.responseJSON.errors, function(key, value){
					$('.'+key).findOrAppend('.errors', '<small class="errors alert-danger">'+value+'</small>');
					$('#'+key).addClass('border border-danger');
				});
	        }
	    });
	});

	$('#show_role').on('hide.bs.modal', function(){
		$('.alert').remove();
		$('.errors').remove();
		$('input').removeClass('border border-danger');
	});

	$('.delete').on('click', function(){
		$('#show_role').modal('hide');
		$('#failmodal').modal({
			show: true,
			backdrop: 'static',
			keyboard: false
		});
	});

	//confirm delete
	$('.confirm-delete').on('click', function(e){
		//preparing route
		route = "{{route('admin.deleterole', ":roleid")}}";
        route = route.replace(':roleid', id);

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
		$('#show_role').modal('toggle')
		$('#failmodal').modal('hide')
	});

</script>
@endpush