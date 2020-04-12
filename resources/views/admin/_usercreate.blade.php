@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-11">
			<div class="card">

				@include('layouts.cardnavs')

				<div class="card-body">
					<div class="container col-md-7">
						<form  method="POST" action="{{route('admin.usercreate')}}">
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
								<label for="name">Name</label>
								<input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
								@error('name')
									<small class="text-danger"><strong>{{$message}}</strong></small>
								@enderror
							</div>
							<div class="name form-group">
								<label for="email">Email</label>
								<input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}">
								@error('email')
									<small class="text-danger"><strong>{{$message}}</strong></small>
								@enderror
							</div>
							<div class="name form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control" id="password" name="password">
								@error('password')
									<small class="text-danger"><strong>{{$message}}</strong></small>
								@enderror
							</div>
							<div class="name form-group">
								<label for="password_confirmation">Confirm Password</label>
								<input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
								@error('password_confirmation')
									<small class="text-danger"><strong>{{$message}}</strong></small>
								@enderror
							</div>
							<div>
								<label for="role[]">Please select the role/s of this user:</label>
							</div>
							
							@foreach($roles as $role)
							<div class="form-check form-check-inline">
								<input 
								class="p-2 category form-check-input" 
								type="checkbox"
								name="role[]" 
								value="{{$role}}">
								<label 
								class="mr-3 form-check-label" 
								for="inlineCheckbox1">
									{{ucwords($role)}}
								</label>
							</div>
							@endforeach
							
							<div class="form-group">
								@error('role')
									<small class="text-danger"><strong>{{$message}}</strong></small>
								@enderror
								<button type="submit" class="btn btn-success float-right">Create User</button>
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