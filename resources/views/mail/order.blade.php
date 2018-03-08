<table border="0" width="100%">
    <tbody>
        <tr>
            <td align="center" colspan="2"><h1>Order</h1></td>
        </tr>
        <tr>
            <td width="50%">name: {!! $name !!}</td>
            <td width="50%">email: {!! $email !!}</td>
        </tr>
        <tr>
            <td width="50%">address: {!! $address !!}</td>
            <td width="50%">total price: {!! number_format($price) !!}</td>
        </tr>
        <tr>
            <td colspan="2">
                <table border="0" width="100%">
                    <thead>
                        <tr align="left">
                            <th>name</th>
                            <th>price</th>
                            <th>quantity</th>
                            <th>value</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($order_items as $key => $value)
                        <tr>
                            <td>{!! $value['name'] !!}</td>
                            <td>{!! number_format($value['price']) !!}</td>
                            <td>{!! $value['quantity'] !!}</td>
                            <td>{!! number_format($value['price'] * $value['quantity']) !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
