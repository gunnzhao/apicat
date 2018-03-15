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

    // json返回变量
    protected $json_data = array('status' => 0, 'data' => '', 'msg' => '');

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->check_login();
        $this->json_data['data'] = new stdClass();
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
        $this->tpldata['_page_js'][] = $js . '?v=' . microtime(true);
    }

    /**
     * 添加页面css文件
     * @param  string $css css文件的路径
     * @return void
     */
    protected function add_page_css($css)
    {
        $this->tpldata['_page_css'][] = $css . '?v=' . microtime(true);
    }

    /**
     * 登录验证
     */
    protected function check_login()
    {
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
        $this->load->helper('url');

        if ($result) {
            $this->session->set_userdata('form_ok', $msg);
        } else {
            $this->session->set_userdata('form_err', $msg);
        }
        
        $source_page = $this->input->server('HTTP_REFERER');
        redirect($source_page);
    }

    /**
     * 错误页面的表单数据
     */
    protected function form_err_data($data)
    {
        $this->session->set_userdata('form_err_data', $data);
    }

    /**
     * 以Json字符串作为返回数据返回给客户端
     * @param  array $data 返回数据内容
     * @return void
     */
    protected function response_json_ok($data = null) {
        if ($data) {
            $this->json_data['data'] = $data;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($this->json_data));
    }

    /**
     * 以Json字符串作为返回数据，报错给客户端
     * @param  string $msg 错误原因
     * @return void
     */
    protected function response_json_fail($msg) {
        $this->json_data['status'] = -1;
        $this->json_data['msg'] = $msg;
        $this->output->set_content_type('application/json')->set_output(json_encode($this->json_data));
    }
}