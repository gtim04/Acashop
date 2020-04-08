@if (session('status'))
<div class="alert alert-success" role="alert">
	{{ session('status') }}
</div>
@endif



<div class="card-header">
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

		@can('manage user')
		<li class="nav-item">
			<a class="{{ Request::path() === 'manageusers' ? 'nav-link active' : 'nav-link'}}" href="{{ route('admin.manusers') }}">Manage Users</a>
		</li>
		@endcan

		@can('manage role')
		<li class="nav-item">
			<a class="{{ Request::path() === 'manageroles' ? 'nav-link active' : 'nav-link'}}" href="{{ route('admin.manroles') }}">Manage Roles</a>
		</li>
		@endcan
	</ul>
</div>
