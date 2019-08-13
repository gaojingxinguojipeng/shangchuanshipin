<?php

namespace App\Http\Controllers\Oss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Video;
use OSS\OssClient;
use OSS\Core\OssException;
class OssController extends Controller
{

    protected $accessKeyId='LTAIoRj7Bhbw9eqI';
    protected $accessKeySecret='Z6yALsws5xtLJoN2t0UUlIrR2oJQ1j';
    public function vedio()
    {
        $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, 'oss-cn-beijing.aliyuncs.com');
        $bucket='gaojingxin';
        $object=Str::random(15).time().mt_rand(1,99999);

        //获取所有的文件
//        $path=storage_path('app/public/files');
        $file= scandir("storage/files");
        foreach ($file as $k=>$v)
        {
            if($v=='.'||$v == '..'){
                continue;
            }
            $file_name = 'files/'.$v;
            $content = "storage/files/".$v;
            try{
                $cc=$ossClient->uploadFile($bucket,$file_name,$content);
            } catch(OssException $e) {
                printf(__FUNCTION__ . ": FAILED\n");
                printf($e->getMessage() . "\n");
                return;
            }
            unlink($content);
        }
    }

    public function vedioList(){
        $id=$_GET['id'];
        $vedio=Video::where(['id'=>$id])->first()->toArray();
        $a=[
            'a'=>$vedio['path'],
        ];
        return view("Vedio.vediolist",$a);
    }
    public function ceshi(){
        return view("Vedio.ceshi");
    }

    public function oss(){
        $json=file_get_contents("php://input");
        $obj_str='>>>'."$json".'\n';
        file_put_contents("logs/a.log","$obj_str");
        dd(json_decode("$json"));
    }
    public function zhuan(){
        $a=$_GET['json'];
        dd(json_decode("$a"));
    }

}
