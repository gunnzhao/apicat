<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 注册类
 */
class Register extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        if (!empty($this->session->register_err)) {
            $err = $this->session->register_err;
            $this->session->unset_userdata('register_err');
            $this->load->view('register', array('err' => $err));
        } else {
            $this->load->view('register');
        }
    }
    
    public function do()
    {
        $this->load->helper('url');
        $this->load->helper('email');

        $params = array(
            'nickname'    => array('val' => $this->input->post('nickname'), 'err' => '请输入您的昵称'),
            'email'       => array('val' => $this->input->post('email'), 'err' => '请输入您的邮箱'),
            'passwd'      => array('val' => $this->input->post('passwd'), 'err' => '请输入您的密码'),
            're_passwd'   => array('val' => $this->input->post('re_passwd'), 'err' => '请输确认您的密码'),
            'verify_code' => array('val' => $this->input->post('verify_code'), 'err' => '请输确认您的验证码')
        );

        foreach ($params as $v) {
            if (empty($v['val'])) {
                $this->session->set_userdata('register_err', $v['err']);
                redirect('/register');
                return;
            }
        }

        if (!valid_email($params['email']['val'])) {
            $this->session->set_userdata('register_err', '邮箱格式有误');
            redirect('/register');
            return;
        }

        if ($params['passwd']['val'] != $params['re_passwd']['val']) {
            $this->session->set_userdata('register_err', '两次密码输入不一致');
            redirect('/register');
            return;
        }

        if (!isset($this->session->verify_code) or $params['verify_code']['val'] != $this->session->verify_code) {
            $this->session->set_userdata('register_err', '验证码有误');
            redirect('/register');
            return;
        }

        $this->load->model('user_model');

        $record = $this->user_model->get_user_by_email($params['email']['val']);
        if ($record) {
            $this->session->set_userdata('register_err', '该邮箱已被注册');
            redirect('/register');
            return;
        }

        $uid = $this->user_model->add_user($params['nickname']['val'], $params['email']['val'], $params['passwd']['val']);
        if ($uid !== false) {
            $this->session->set_userdata(array(
                'uid'        => $uid,
                'nickname'   => $params['nickname']['val'],
                'avatar'     => 'http://wx.qlogo.cn/mmhead/Q3auHgzwzM55stttUxUOyUHrcJ8MnaH292VkkcRmdlwNg4ERNU1jRw/0',
                'login_time' => time()
            ));
            redirect('/settings/profile');
        } else {
            $this->session->set_userdata('register_err', '注册失败，请稍后重试');
            redirect('/register');
        }
    }
}