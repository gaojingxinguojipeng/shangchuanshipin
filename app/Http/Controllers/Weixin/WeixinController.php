<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Storage;
class WeixinController extends Controller
{
    public function curl(){
        $obj=new \curl();
        $info=$obj->sendGet("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx6e2f91dce5d727a9&secret=b76edfa18fa9e7b06026f3f527318621");
        $arrInfo=json_decode($info,true);
        $access=$arrInfo['access_token'];
//  $url="https://api.weixin.qq.com/cgi-bin/clear_quota?access_token=$access";
        $time=$arrInfo['expires_in']/60;

        $key="access";
//         return $access;
        cache([$key=>$access],$time);
    }
    public function open(){
        $obj=new \curl();
        $key="access";
        $accessToken=cache($key);
        $url="https://api.weixin.qq.com/cgi-bin/user/get?access_token=$accessToken";
        $strUserInfo=$obj->sendGet($url);
//         print_r($strUserInfo);die;
        $arrUser=json_decode($strUserInfo,true);
//         print_r($arrUser);exit;
        $arrOpenId=$arrUser['data']['openid'];
//        print_r($arrOpenId);die;
        $arrInfo=$this->qunfa($arrOpenId);
    }

    private function qunfa($arrOpenId){
        $obj=new \curl();
        $key="access";
        $accessToken=cache($key);
        $url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=$accessToken";
        $rand=mt_rand(1,1000);
        $arr=array(
            "touser"=>$arrOpenId,
            "msgtype"=>"text",
            "text"=>array(
                "content"=>"521".$rand,
            ),
        );
        $res=json_encode($arr,true);
        $info=$obj->sendPost($url,$res);
        return $info;
    }
}
