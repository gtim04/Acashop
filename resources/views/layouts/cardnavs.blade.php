@if (session('status'))
<div class="alert alert-success" role="alert">
	{{ session('status') }}
</div>
@endif



<div class="card-header">
	@if(request('category'))
	<a href="{{route('admin.home')}}" class="badge-pill badge-secondary float-right">Clear Filter</a>
	@endif
	<ul class="nav nav-tabs card-header-tabs">
		<li class="nav-item">
			<a class="{{ Request::path() === 'home-admin' ? 'nav-link active' : 'nav-link'}}" href="{{ route('admin.home') }}">Products</a>
		</li>
		@can('add user')
			<li class="nav-item">
				<a class="{{ Request::path() === 'create-userform' ? 'nav-link active' : 'nav-link'}}" href="{{ route('admin.userform') }}">Create User</a>
			</li>
		@endcan

		@can('add role')
			<li class="nav-item">
				<a class="{{ Request::path() === 'create-roleform' ? 'nav-link active' : 'nav-link'}}" href="{{ route('admin.roleform') }}">Create Role</a>
			</li>
		@endcan

		@can('manage admin')
		<li class="nav-item">
			<a class="{{ Request::path() === 'manageadmin' ? 'nav-link active' : 'nav-link'}}" href="{{ route('admin.manadmin') }}">Manage Administrators</a>
		</li>
		@endcan

		@can('manage user')
		<li class="nav-item">
			<a class="{{ Request::path() === 'manageuser' ? 'nav-link active' : 'nav-link'}}" href="{{ route('admin.manuser') }}">Manage Users</a>
		</li>
		@endcan

		@can('manage role')
		<li class="nav-item">
			<a class="{{ Request::path() === 'manageroles' ? 'nav-link active' : 'nav-link'}}" href="{{ route('admin.manroles') }}">Manage Roles</a>
		</li>
		@endcan
	</ul>
</div>
