<?php
!defined(IN_MY_PHP) && die(0);
/**
 * 一个字体可控的验证码生成类
 * @author netmou <leiyanfo@sina.com>
 */
class ValidateCode {

    private $charset = 'abcdefghkmnprstuvwxy3456789'; //随机因子
    private $code = null; //验证码
    private $codelen = 4; //验证码长度
    private $width = 80; //宽度
    private $height = 24; //高度
    private $img; //图形资源句柄
    private $font; //指定的字体
    private $fontsize = 14; //指定字体大小
    private $fontcolor; //指定字体颜色

    //构造方法初始化

    public function __construct($width = 80, $height = 24, $codelen = 4, $fontsize = 16) {
        $this->width = $width;
        $this->height = $height;
        $this->codelen = $codelen;
        $this->fontsize = $fontsize;
        $this->font = dirname(__FILE__) . '/font/elephant.ttf';
    }

    //生成随机码
    private function createCode() {
        for ($i = 0; $i < $this->codelen; $i++) {
            $this->code .= $this->charset[mt_rand(0, strlen($this->charset) - 1)];
        }
    }

    //生成背景
    private function createBg() {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    //生成文字
    private function createFont() {
        $_x = $this->width / $this->codelen;
        for ($i = 0; $i < $this->codelen; $i++) {
            $this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 128), mt_rand(0, 128), mt_rand(0, 128));
            imagettftext($this->img, $this->fontsize, mt_rand(-30, 30), $_x * $i + mt_rand(1, 5), $this->height / 1.4, $this->fontcolor, $this->font, $this->code[$i]);
        }
    }

    //生成线条、雪花
    private function createLine() {
        //线条
        for ($i = 0; $i < 3; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(128, 255), mt_rand(128, 255), mt_rand(128, 255));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        //雪花
        for ($i = 0; $i < 30; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    //输出
    private function outPut() {
        header("Expires:0");
        header("Pragma:no-cache");
        header("Cache-Control:no-cache");
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    //对外生成
    public function doimg() {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }

    //获取验证码
    public function getCode() {
        return strtolower($this->code);
    }

}

?>
