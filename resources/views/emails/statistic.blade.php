<table>
    <thead>
        <tr>
            <th>name</th>
            <th>count</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sellingProducts as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
