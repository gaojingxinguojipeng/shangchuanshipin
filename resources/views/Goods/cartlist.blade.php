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
                <td><input type="checkbox" value="{{$v->goods_id}}"></td>
                <td>{{$v->goods_name}}</td>
                <td>{{$v->goods_price}}</td>
                <td class="num"goods_id="{{$v->goods_id}}">
                    <p class="old_num">{{$v->buy_number}}</p>
                    <input type="text" class="new_num" style="display:none" goods_id="{{$v->goods_id}}">
                </td>
                <td>{{date("Y-m-d H:i:s",$v->ctime)}}</td>
            </tr>
        @endforeach
    </table>
    <button class="CK" >加入订单</button>
</from>
</body>
</html>
<script src="/js/jquery-3.2.1.min.js"></script>
<script>
    $('.num').on('click',function(){
        var goods_id=$(this).attr('goods_id');
        $(this).children(".new_num").show();
        $(this).children(".old_num").hide();
    })
    $('.new_num').on('blur',function(){
        var new_num=$(this).val();
        var goods_id=$(this).attr('goods_id');
        $.ajax({
            type:"post",
            data:{new_num:new_num,goods_id:goods_id},
            datatype:"json",
            url:"/update_num",
            success:function(msg){
                if(msg.code==1){
                    alert(msg.res);
                }else{
                    alert(msg.res);
                }



            }
        })
    })

    $('.CK').click(function () {
        var kaka =[];//定义一个数组
        $('input[type="checkbox"]:checked').each(function(){
            kaka.push($(this).val());
        });
        $.ajax({
            type:"post",
            data:{goods_id:kaka},
            datatype:"json",
            url:"/order",
            success:function(msg){
                if(msg.code==0){
                    alert(msg.msg);
                    location.href="/orderlist";
                }else{
                    alert(msg.msg);
                }
            }
        })

    })
</script>