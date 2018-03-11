<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    // 模板变量
    protected $tpldata = array(
        '_page_description' => '',
        '_page_title'       => 'ApiCat',
        '_page_css'         => array(),
        '_page_js'          => array(),
        '_page_nickname'    => '',
        '_page_avatar'      => ''
    );

    public function __construct()
    {
        parent::__construct();
        $this->check_login();
        $this->load->library('layout');
    }

    /**
     * 模板渲染输出
     * @param  string $tpl 模板文件
     * @return void
     */
    protected function render($tpl, $data = null)
    {

        $this->tpldata['subpage_data'] = $data ? $data : array();
        $this->layout->view($tpl, $this->tpldata);
    }

    /**
     * 设置模板变量
     * @param  string $key 模板变量名称
     * @param  mix $val 模板变量值
     * @return void
     */
    protected function set_tpldata($key, $val)
    {
        $this->tpldata[$key] = $val;
    }

    /**
     * 添加页面js文件
     * @param  string $js js文件的路径
     * @return void
     */
    protected function add_page_js($js)
    {
        $this->tpldata['page_js'][] = $js . '?v=' . microtime(true);
    }

    /**
     * 添加页面css文件
     * @param  string $css css文件的路径
     * @return void
     */
    protected function add_page_css($css)
    {
        $this->tpldata['page_css'][] = $css . '?v=' . microtime(true);
    }

    /**
     * 登录验证
     */
    protected function check_login()
    {
        $this->load->library('session');
        if (empty($this->session->uid)) {
            $this->load->helper('url');
            redirect('/login');
        }

        $this->set_tpldata('_page_nickname', $this->session->nickname);
        $this->set_tpldata('_page_avatar', $this->session->avatar);
    }

    /**
     * 返回错误信息到上一级表单页面
     * @param  string $err_info 错误信息
     * @return void
     */
    protected function show_err($err_info)
    {
        $this->return_show_msg($err_info, false);
    }

    /**
     * 返回成功信息到上一级表单页面
     * @param  string $ok_info 错误信息
     * @return void
     */
    protected function show_ok($ok_info)
    {
        $this->return_show_msg($ok_info, true);
    }

    /**
     * 返回信息到上一级表单页面
     * @param  string $msg 信息内容
     * @param  bool $result 内容含义：true成功 false失败
     * @return void
     */
    private function return_show_msg($msg, $result = true)
    {
        $this->load->library('session');
        $this->load->helper('url');

        if ($result) {
            $this->session->set_userdata('form_ok', $msg);
        } else {
            $this->session->set_userdata('form_err', $msg);
        }
        
        $source_page = $this->input->server('HTTP_REFERER');
        redirect($source_page);
    }
}