<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    // 模板变量
    protected $tpldata = array(
        '_page_description' => '',
        '_page_title'       => 'ApiCat',
        '_page_css'         => array(),
        '_page_js'          => array(),
        '_page_nickname'    => ''
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
    public function render($tpl, $data = null)
    {
        if ($data) {
            $this->tpldata['subpage_data'] = $data;
        }
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
    }
}