<?php

namespace App\Http\Controllers\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
//    展示商品
public function goodslist(){
    $res=DB::table("goods")->get();
    return view("Goods.goodslist",["data"=>$res]);
}
public function cart(){
    $id=$_GET['id'];
    $arr=DB::table("goods")->where(["goods_id"=>$id])->first();
    //dd($arr);
    $time=time();
    $data=[
        "goods_id"=>$id,
        "goods_name"=>$arr->goods_name,
        "goods_img"=>$arr->goods_img,
        'goods_price'=>$arr->goods_price,
        'ctime'=>$time,
        "buy_number"=>1,
        'id'=>1,
        'status'=>1
    ];
    $res=DB::table("cart")->where(["goods_id"=>$id])->first();
    if($res!=null){
        DB::table("cart")->where(["goods_id"=>$id])->update(["buy_number"=>$res->buy_number+1]);
    }else{
        $arr=DB::table("cart")->insert($data);
    }
    $res=DB::table('cart')->where(["id"=>1,'status'=>1])->get();
    return view("Goods.cartlist",["data"=>$res]);
}
public function update_num(Request $request){
    $goods_id=$request->input("goods_id");
    $new_num=$request->input("new_num");
    $res=DB::table("cart")->where(['goods_id'=>$goods_id])->update(["buy_number"=>$new_num]);
    if($res){
        $arr=[
            "code"=>1,
            "res"=>"修改成功"
        ];
    }else{
        $arr=[
            "code"=>0,
            "res"=>"修改失败"
        ];
    }
    return $arr;
}
//订单生成，数据入库
    public function order(Request $request){
        $goods_id=$request->input('goods_id');
        $total=0;

        $cate = [];
        foreach($goods_id as $k=>$v){
            $data1=DB::table('goods')->where('goods_id',$v)->first();
            $price=$data1->goods_price;

            $where=[
                'status'=>1,
                'id'=>1,
                'goods_id'=>$v
            ];
            $res=DB::table('cart')->where($where)->first();
            $buy_number=$res->buy_number;
            $money=$price*$buy_number;

            $cate[$data1->cate_id][$k] = $money;         //将加入订单的相同分类下商品的全部价格放入该分类下的数组

            $total+=$money;
        }



        $order_amount=$total;

        if(empty($goods_id)){
            $arr=['code'=>1,'msg'=>'选择商品不能为空'];
            return $arr;
        }

        $order_no = date("YmdHis",time()).rand(1000,9999);
        $add_time=time();
        $out_time=time()+15*60;
        $datainfo=[
            'id'=>1,
            'order_no'=>$order_no,
            'order_amount'=>$order_amount,
            'add_time'=>$add_time,
            'out_time'=>$out_time,
        ];
        DB::table('order')->insert($datainfo);

        $res=DB::table('order')->where(['order_no'=>$order_no])->get(['order_id']);

        $order_id=$res[0]->order_id;
//        print_r($order_id);
        $data=DB::table('cart')
            ->join('goods','cart.goods_id','=','goods.goods_id')
            ->where(['goods.status'=>1,'id'=>1])
            ->where(['cart.status'=>1])
            ->whereIn('goods.goods_id',$goods_id)
            ->get();
//       var_dump($data);die;
        foreach($data as $v){
            $arr=[
                'id'=>1,
                'order_id'=>$order_id,
                'goods_id'=>$v->goods_id,
                'goods_name'=>$v->goods_name,
                'goods_price'=>$v->goods_price,
                'goods_img'=>$v->goods_img,
                'buy_number'=>$v->buy_number,
                'order_no'=>$order_no,
                'status'=>1,
                'ctime'=>time()
            ];
            DB::table('order_detail')->insert($arr);
        }
        $res1=DB::table('cart')->whereIn('goods_id',$goods_id)->where('id',1)->update(['status'=>0]);
        if($res1){
            $arr=[
                'code'=>0,
                'msg'=>'加入订单成功',
                'order_id'=>$order_id
            ];
            return $arr;
        }
    }
    //个人订单展示
    public function orderlist(Request $request){
        $data=DB::table('order_detail')->where(['id'=>1,'status'=>1])->get();
            return view("Goods.orderlist",["data"=>$data]);
    }

}
