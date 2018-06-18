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
        $this->load->helper('form_msg');
        $this->load->view('login');
    }

    public function do()
    {
        $this->load->helper('url');
        $this->load->helper('email');
        $this->load->helper('form_msg');

        init_form_post(array('email'));

        $email = trim($this->input->post('email'));
        if (empty($email)) {
            set_error('请输入您的邮箱');
            return redirect('/login');
        }
        if (!valid_email($email)) {
            set_error('邮箱格式有误');
            return redirect('/login');
        }

        $passwd = trim($this->input->post('passwd'));
        if (empty($passwd)) {
            set_error('请输入您的密码');
            return redirect('/login');
        }

        $this->load->model('user_model');
        $user_info = $this->user_model->get_user_by_email($email);
        if (!password_verify($passwd, $user_info['passwd'])) {
            set_error('账号或密码有误');
            redirect('/login');
        } else {
            $this->session->set_userdata(array(
                'uid'        => $user_info['id'],
                'nickname'   => $user_info['nickname'],
                'avatar'     => $user_info['avatar'],
                'login_time' => time()
            ));

            // 更新token
            $token = md5($email . time());
            $token_valid_time = time() + 2592000; // Token有效期30天
            $this->user_model->edit_user(array('token' => $token, 'token_valid_time' => $token_valid_time), $user_info['id']);

            if ($this->input->post('remember_me') and $this->input->post('remember_me') == 1) {
                $this->input->set_cookie('token', $token, 2592000);
            }

            // 更新登录时间和IP
            $this->user_model->update_login_info($user_info['id']);
            redirect('/projects');
        }
    }

    public function auth_login()
    {
        $this->load->helper('url');

        $token = $this->input->cookie('token');
        if (!$token) {
            return redirect('/login');
        }

        $this->load->model('user_model');
        $user_info = $this->user_model->get_user_by_token($token);
        if (!$user_info) {
            return redirect('/login');
        }

        $this->session->set_userdata(array(
            'uid'        => $user_info['id'],
            'nickname'   => $user_info['nickname'],
            'avatar'     => $user_info['avatar'],
            'login_time' => time()
        ));

        $source_page = $this->input->server('HTTP_REFERER');
        redirect($source_page);
    }
}