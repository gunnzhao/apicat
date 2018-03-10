<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 登录类
 */
class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        if (!empty($this->session->login_err)) {
            $err = $this->session->login_err;
            $this->session->unset_userdata('login_err');
            $this->load->view('login', array('err' => $err));
        } else {
            $this->load->view('login');
        }
    }

    public function do()
    {
        $this->load->helper('url');
        $this->load->helper('email');

        $email = $this->input->post('email');
        if (empty($email)) {
            $this->session->set_userdata('login_err', '请输入您的邮箱');
            redirect('/login');
            return;
        }
        if (!valid_email($email)) {
            $this->session->set_userdata('login_err', '邮箱格式有误');
            redirect('/login');
            return;
        }

        $passwd = $this->input->post('passwd');
        if (empty($passwd)) {
            $this->session->set_userdata('login_err', '请输入您的密码');
            redirect('/login');
            return;
        }

        $this->load->model('user_model');
        $user_info = $this->user_model->get_user_by_email($email);
        if (!password_verify($passwd, $user_info['passwd'])) {
            $this->session->set_userdata('login_err', '账号或密码有误');
            redirect('/login');
        } else {
            redirect('/home');
        }
    }
}