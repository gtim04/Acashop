@component('mail::message')
# Thank you for shopping!

Order code: {{$order->code}}

@component('mail::table')
| Product       | Price         | Quantity |  Total       |
| :-------------:|:-------------:|:-------------:|:-------------:|
@foreach($order->product as $product)
|{{$product->name}}|₱ {{number_format($product->price, 2)}} | {{$product->pivot->quantity}}|₱{{number_format($product->pivot->totalProduct,2)}} |
@endforeach
|				|				|Your total:	| ₱ {{number_format($order->total, 2)}} |


@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent