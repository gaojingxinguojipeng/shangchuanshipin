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
            <td>change</td>
            <td>name</td>
            <td>price</td>
            <td>num</td>
            <td>time</td>
        </tr>
        @foreach($data as $k=>$v)
            <tr>
                <td><input type="checkbox"></td>
                <td>{{$v->goods_name}}</td>
                <td>{{$v->goods_price}}</td>
                <td>{{$v->buy_number}}</td>
                <td>{{date("Y-m-d H:i:s",$v->ctime)}}</td>
            </tr>
        @endforeach
    </table>
   <button>去结算</button>
</from>
</body>
</html>