@component('mail::message')
# Order

@component('mail::table')
|       |                           |                |                                              |
|:------|:------------------------- |:-------------- |:-------------------------------------------- |
| Name  | {!! $content['name'] !!}  |                |                                              |
| Email | {!! $content['email'] !!} | Address        | {!! $content['address'] !!}                  |
| Phone | {!! $content['phone'] !!} | Total price    | {!! number_format($content['price']) !!}     |
@endcomponent

@component('mail::table')
| Name                  | Price                                 | Quantity                  | Value        |
|:--------------------- | -------------------------------------:| -------------------------:| ------------:|
@foreach($content['order_items'] as $item)
| {!! $item['name'] !!} | {!! number_format($item['price']) !!} | {!! $item['quantity'] !!} | {!! number_format($item['price'] * $item['quantity']) !!} |
@endforeach
@endcomponent

Thanks,<br>
{!! $content['name'] !!}
@endcomponent
