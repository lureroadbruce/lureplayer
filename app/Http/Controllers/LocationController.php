<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
class LocationController extends Controller
{

    public function getLocationList()
    {
        $list = User::select('nick_name as name', 'lat','lng','id','avatar')->get();
        return $list;
    }
    public function wechatLogin(Request $request)
    {

        $code = $request->input('code');
        $app_id = $request->input('app_id');
        $app_secret = $request->input('app_secret');
        $open_id = $this->get_openid($code,$app_id,$app_secret);
        if($open_id)
        {
            $user = User::where('wechat_openid',$open_id)->select('nick_name', 'wechat_openid as open_id','id','avatar')->first();
            if($user)
            {
                return ['status'=>0,'user'=>$user];
            }
            else
            {
                return array('status'=>2,'user'=>array('open_id'=>$open_id));
            }

        }
        return array('status'=>1,'msg'=>'miss open_id');
    }

    public function get_openid($code,$app_id,$secret)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/sns/jscode2session?appid=".$app_id."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        $output = json_decode($output,TRUE);
        if(isset($output["openid"]))
        {
            return $output["openid"];
        }
        else
        {
            return null;
        }
    }

    public function setLocation(Request $request)
    {
        if($request->has('user_id'))
        {
            $user = User::find($request->input('user_id'));
            $user->lat = $request->input('lat');
            $user->lng = $request->input('lng');
            $path = 'storage/';

            file_put_contents($path.$user->id.'.jpg',file_get_contents($request->input('avatar')));
            $file = $this->changeImage($path.$user->id.'.jpg');



            $user->avatar = $file;
            $user->save();
            return array('status'=>1,'user'=>$user);
        }
        else
        {
            $user = new User;
            $user->name = $user->nick_name = $user->email = $request->input('nick_name');
            $user->lat = $request->input('lat');
            $user->lng = $request->input('lng');
            $user->wechat_openid = $request->input('open_id');
            $user->password = bcrypt('888888');
            $user->avatar = $request->input('avatar');
            $user->save();
            $path = 'storage/';

            file_put_contents($path.$user->id.'.jpg',file_get_contents($request->input('avatar')));
            $file = $this->changeImage($path.$user->id.'.jpg');



            $user->avatar = $file;
            $user->save();
            return array('status'=>1,'user'=>$user);
        }
    }





    public function changeImage($imgpath)
    {
        $ext     = pathinfo($imgpath );
        $src_img = null;
        switch ($ext['extension']) {
            case 'jpg':
                $src_img = imagecreatefromjpeg($imgpath);
                break;
            case 'jpeg':
                $src_img = imagecreatefromjpeg($imgpath);
                break;
            case 'png':
                $src_img = imagecreatefrompng($imgpath);
                break;
        }
        $wh  = getimagesize($imgpath);
        $w   = $wh[0];
        $h   = $wh[1];
        $w   = min($w, $h);
        $h   = $w/2*3;
        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);

        $white = imagecolorallocate($img,0x0e,0xe4,0x52);

        $r   = $w /2; //圆半径
        for ($x = 0; $x < $w; $x++) {
            for ($y = $h/3; $y < $h; $y++) {
                if(($x <= $r && $y-$h/3 < $w-2*($r-$x)) || ($x >= $r && $y-$h/3 < $w-2*($x-$r)) )
                {

                    imagesetpixel($img, $x, $y, $white);
                }


            }
        }
        $y_x = $r; //圆心X坐标
        $y_y = $r; //圆心Y坐标
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $w; $y++) {
                $rgbColor = imagecolorat($src_img, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
        }
        $filename = date('Y-m-d-H-i-s').'-'.uniqid().'.png';

        imagepng($img,$ext['dirname'].'/'.$filename);

        return $ext['dirname'].'/'.$filename;
    }
}
