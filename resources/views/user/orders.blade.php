@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-11">
			<div class="card border-dark">

				<div class="card-header bg-dark">
				</div>
				<div class="card-body">
					<h5>Hi {{auth()->user()->name}} your order/s are listed below.</h5>
					<hr>
					<table class="table text-center" id="orderTable">
						<thead>
							<tr>
								<th>id</th>
								<th>Code</th>
								<th>Created at</th>
								<th>Total</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
					
					</hr>
				</div>

				<div class="card-footer bg-dark">
					<a href="{{route('user.home')}}" class="btn btn-primary float-right">Browse more products!</a>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/b-1.6.1/b-print-1.6.1/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/b-1.6.1/b-print-1.6.1/datatables.min.js"></script>
<script>
$(document).ready(function(){
	$('#orderTable').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            "url": "{!! route('user.orders') !!}",
            "type": "GET"
        },
        columns: [{ 
            data: 'id', name: 'id' 
        },{ 
            data: 'code', name: 'code' 
        }, { 
            data: 'created_at', name: 'created_at' 
        }, { 
            data: 'total', name: 'total' 
        }, { 
            data: 'viewBtn', name: 'viewBtn' 
        }],

    });

    $('#orderTable').on('click', '.view', function(){ 
        id = $(this).closest('tr').find('.sorting_1').text();
        //preparing route
		route = "{{route('user.summary', ":productid")}}";
        route = route.replace(':productid', id);
		//getting data
        $.get(route, function( data ) {
		  	document.write(data);
		});
    });
});
</script>
@endpush