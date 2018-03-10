<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 验证码类
 */
class Verifycode
{
    // 随机因子
    private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';

    // 验证码
    private $code = '';

    // 验证码长度
    private $codelen = 4;

    // 图片宽度
    private $width = 130;

    // 图片高度
    private $height = 50;

    // 图片资源句柄
    private $img;

    // 字体
    private $font;

    // 字体大小
    private $fontsize = 20;

    // 字体颜色
    private $fontcolor;

    public function __construct()
    {
        $this->font = FCPATH . 'static/font/elephant.ttf';
    }

    /**
     * 生成验证码图片
     */
    public function get_img()
    {
        if (!function_exists('imagettftext')) {
            $this->height = 34;
        }
        $this->create_backgroud();
        $this->create_code();
        $this->create_interference();
        $this->create_font();
        $this->output();
    }

    /**
     * 获取验证码文本
     * @return string
     */
    public function get_code()
    {
        return strtolower($this->code);
    }

    /**
     * 生成验证码
     */
    public function create_code()
    {
        if ($this->code == '') {
            $charset_len = strlen($this->charset) - 1;
            for ($i = 0; $i < $this->codelen; $i++) {
                $rand_index = mt_rand(0, $charset_len);
                $this->code .= $this->charset[$rand_index];
            }
        }
    }

    /**
     * 生成背景
     */
    private function create_backgroud()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    /**
     * 生成文字
     */
    private function create_font()
    {
        $_x = $this->width / $this->codelen;

        if (function_exists('imagettftext')) {
            for ($i = 0; $i < $this->codelen; $i++) {
                $this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
                imagettftext($this->img, $this->fontsize, mt_rand(-30, 30), $_x * $i + mt_rand(1, 5), $this->height / 1.4, $this->fontcolor, $this->font, $this->code[$i]);
            }
        } else {
            $this->fontsize = 5;
            for ($i = 0; $i < $this->codelen; $i++) {
                $this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
                imagechar($this->img, $this->fontsize, $_x * $i + mt_rand(1, 5), $this->height / 3, $this->code[$i], $this->fontcolor);
            }
        }
    }

    /**
     * 生成干扰(线条和噪点)
     */
    private function create_interference()
    {
        for ($i = 0; $i < 6; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }

        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    /**
     * 输出
     */
    private function output()
    {
        header('Content-type:image/png');
        imagepng($this->img);
        magedestroy($this->img);
    }
}