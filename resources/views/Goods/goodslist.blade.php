<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<from>
    <table border="1">
            <tr>
                <td>name</td>
                <td>price</td>
                <td>num</td>
                <td>desc</td>
                <td>change</td>
            </tr>
            @foreach($data as $k=>$v)
            <tr>
                <td>{{$v->goods_name}}</td>
                <td>{{$v->goods_price}}</td>
                <td>{{$v->goods_number}}</td>
                <td>{{$v->desc}}</td>
                <td><a href="cart?id={{$v->goods_id}}">加入购物车</a></td>
            </tr>
            @endforeach
    </table>
</from>
</body>
</html>