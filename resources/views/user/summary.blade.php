@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-11">
			<div class="card border-dark">

				<div class="card-header">
					<h4>Order Code: {{$data->code}}</h4>
				</div>
				<div class="card-body">
					<h5>This is your order summary, you can also check your email for confirmation.</h5>
					<hr>
					<table class="table table-dark text-center">
						<thead>
							<tr>
								<th>Product</th>
								<th>Price</th>
								<th>Quantity</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							@foreach($data->product as $product)
							<tr>
								<td>{{$product->name}}</td>
								<td>&#8369 {{number_format($product->price, 2)}}</td>
								<td>{{$product->pivot->quantity}}</td>
								<td>&#8369 {{number_format($product->pivot->totalProduct, 2)}}</td>
							</tr>
							@endforeach
							<tr>
								<td class="text-right" colspan="3">Total</td>
								<td>&#8369 {{number_format($data->total, 2)}}</td>
							</tr>
						</tbody>
					</table>
					<small>Order created at: {{$data->created_at}}</small>
					</hr>
				</div>

				<div class="card-footer">
					<a href="{{route('user.orders')}}" class="btn btn-success float-left">Go to orders</a>
					<a href="{{route('user.home')}}" class="btn btn-success float-right">Browse more products!</a>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection