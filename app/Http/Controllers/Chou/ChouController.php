<?php

namespace App\Http\Controllers\Chou;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Jobs\JobList;
use Illuminate\Support\Facades\Redis;
class ChouController extends Controller
{
    public function choua()
    {
        $uid=$_GET['uid'];
        $res=DB::table("choujiang")->where(["uid"=>$uid])->first();
        if($res)
        {
            echo "该用户以抽奖";
        }else{
            $jiangci=mt_rand(1,100);
            $data=[
                'uid'=>$uid,
                'jiangci'=>$jiangci,
            ];
            DB::table("choujiang")->insert($data);
            $arr=DB::table("choujiang")->where(["uid"=>$uid])->first();
            if($arr->jiangci<=5){
                echo "一等奖";
                DB::table("choujiang")->where(['uid'=>$uid])->update(['status'=>"一等奖"]);
                $are=DB::table("choujiang")->where(['status'=>"一等奖"])->first();
                if($are){
                    echo "请您在抽取一次1";
                    DB::table("choujiang")->where(['uid'=>$uid])->delete();
                }
            }else if($arr->jiangci>5 && $arr->jiangci<=15){
                echo "二等奖";
                DB::table("choujiang")->where(['uid'=>$uid])->update(['status'=>"二等奖"]);
                $r=DB::table("choujiang")->where(['status'=>'二等奖'])->get();
                if(count($r)>2){
                    echo "请您在抽取一次2";
                    DB::table("choujiang")->where(['uid'=>$uid])->delete();
                }
            }else if($arr->jiangci>15 && $arr->jiangci<50){
                echo "三等奖";
                DB::table("choujiang")->where(['uid'=>$uid])->update(['status'=>"三等奖"]);
                $r=DB::table("choujiang")->where(['status'=>'三等奖'])->get();
                if(count($r)>10){
                    echo "请您在抽取一次3";
                    DB::table("choujiang")->where(['uid'=>$uid])->delete();
                }
            }else{
                echo "参与奖";
                DB::table("choujiang")->where(['uid'=>$uid])->update(['status'=>"参与奖"]);

            }
        }


    }
  private  function get_rand($proArr) {
        $result = '';

        //概率数组的总概率精度
        $proSum = array_sum($proArr); //计算数组中元素的和

        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) { //如果这个随机数小于等于数组中的一个元素，则返回数组的下标
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }

        unset ($proArr);

        return $result;
    }
    public function jiang(){
        $prize_arr = array(
    '0' => array('id'=>1,'prize'=>'平板电脑','v'=>1),
    '1' => array('id'=>2,'prize'=>'数码相机','v'=>5),
    '2' => array('id'=>3,'prize'=>'音箱设备','v'=>10),
    '3' => array('id'=>4,'prize'=>'4G优盘','v'=>12),
    '4' => array('id'=>5,'prize'=>'10Q币','v'=>22),
    '5' => array('id'=>6,'prize'=>'下次没准就能中哦','v'=>50),
    );

    foreach ($prize_arr as $key => $val) {
    $arr[$val['id']] = $val['v']; //将$prize_arr放入数组下标为$prize_arr的id元素，值为v元素的数组中
    }
    $rid = get_rand($arr); //根据概率获取奖项id

    $res['yes'] = $prize_arr[$rid-1]['prize']; //获取中奖项

    unset($prize_arr[$rid-1]); //将中奖项从数组中剔除，剩下未中奖项
    shuffle($prize_arr); //打乱数组顺序
    for($i=0;$i<count($prize_arr);$i++){
    $pr[] = $prize_arr[$i]['prize'];
    }
    $res['no'] = $pr;
    echo json_encode($res);

    }


 public function zhuzhu(){
	    $a=DB::table("a")->get();
	    dd($a);
      }
  public function insert(){
        echo __METHOD__;
      JobList::dispatch()->onQueue('send_email');
  }

}
