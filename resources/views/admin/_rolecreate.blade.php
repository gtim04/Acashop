@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-11">
			<div class="card">

				@include('layouts.cardnavs')

				<div class="card-body">
					<div class="container col-md-5">
						<form  method="POST" action="{{route('admin.rolecreate')}}">
							@csrf
							@if(session()->has('success'))
								<div class="alert alert-success alert-dismissible fade show" role="alert">
									<strong>{{session('success')}}</strong>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
								 	</button>
								</div>
							@endif
							<div class="name form-group">
								<label for="name">Role Name</label>
								<input type="text" class="form-control" id="name" name="name" placeholder="e.g: Manager" >
								@error('name')
									<small class="text-danger"><strong>{{$message}}</strong></small>
								@enderror
							</div>
							<div>
								<label for="permission[]">Please select the permission/s of this role:</label>
							</div>
							
							@foreach($permissions as $permission)
							<div class="form-check">
								<input 
								class="permission p-2 form-check-input" 
								type="checkbox"
								name="permission[]" 
								value="{{$permission}}">
								<label 
								class="mr-3 form-check-label" 
								for="inlineCheckbox">
									{{ucwords($permission)}}
								</label>
							</div>
							@endforeach
							
							<div class="form-group">
								@error('permission')
									<small class="text-danger"><strong>{{$message}}</strong></small>
								@enderror
								<button type="submit" class="btn btn-success float-right">Create Role</button>
							</div>
						</form>
					</div>
				</div>

				<div class="card-footer">

				</div>
			</div>
		</div>
	</div>
</div>
@endsection