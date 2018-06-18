<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 忘记密码类
 */
class Forget extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        $this->load->helper('form_msg');
        $this->load->view('forget/index');
    }

    public function send_email()
    {
        $this->load->helper('url');
        $this->load->helper('email');
        $this->load->helper('form_msg');

        init_form_post(array('email'));
        if (!$this->input->post('email')) {
            set_error('请输入您的邮箱');
            return redirect('/forget');
        }

        if (!valid_email($this->input->post('email'))) {
            set_error('邮箱格式有误');
            return redirect('/forget');
        }

        if (!isset($this->session->verify_code) or $this->input->post('verify_code') != $this->session->verify_code) {
            set_error('验证码有误');
            return redirect('/forget');
        }

        $email = $this->input->post('email');

        $this->load->model('user_model');
        $user_info = $this->user_model->get_user_by_email($email);
        if (!$user_info) {
            set_error('该邮箱尚未注册');
            return redirect('/forget');
        }

        $this->load->model('email_verify_model');
        $record = $this->email_verify_model->get_last_record($user_info['id']);
        if ((time() - $record['insert_time']) < 60) {
            set_error('请勿频繁发送重置密码邮件');
            return redirect('/forget');
        }

        // 6位随机数
        $code = rand(100000, 999999);

        $res = $this->email_verify_model->add_verify_record($user_info['id'], $email, $code);
        if (!$res) {
            set_error('验证码发送失败，请稍后重试');
            return redirect('/forget');
        }

        $link = config_item('base_url') . '/forget/password?code=' . hash('sha256', $user_info['id'] . $email . $code);

        $this->config->load('email');
        $this->load->library('email');
        $this->email->from($this->config->item('sender_email'), $this->config->item('useragent'));
        $this->email->to($email);
        $this->email->subject('ApiCat找回密码验证码');
        $this->email->message('当前您正在进行找回密码操作，请点击此链接修改您的密码：' . $link);
        $res = $this->email->send();

        if ($res) {
            set_ok('修改密码邮件已发送至您的邮箱，请尽快修改');
            return redirect('/forget');
        } else {
            set_error('验证码发送失败，请稍后重试');
            return redirect('/forget');
        }
    }

    public function password()
    {
        $hash_val = $this->input->get('code');
        if (!$hash_val) {
            return show_404();
        }

        $this->load->model('email_verify_model');
        $record = $this->email_verify_model->get_record_by_hash_val($hash_val);
        if (!$record) {
            return show_404();
        }

        if ($record['status'] == 1) {
            return $this->load->view('email/verify', array('msg' => '该链接已失效'));
        }

        // 验证链接有效期1天
        if ((time() - $record['insert_time']) > 86400) {
            return $this->load->view('email/verify', array('msg' => '该链接已失效'));
        }

        $this->load->helper('form_msg');
        $this->load->view('forget/password', array('code' => $hash_val));
    }

    public function do_password()
    {
        $this->load->helper('url');
        $this->load->helper('form_msg');

        $hash_val = $this->input->post('code');
        if (!$hash_val) {
            set_error('修改失败');
            return redirect('/forget');
        }

        $newpasswd = $this->input->post('newpasswd');
        if (!$newpasswd) {
            set_error('请填写新密码');
            return redirect('/forget/password?code=' . $hash_val);
        }

        $re_newpasswd = $this->input->post('re_newpasswd');
        if (!$re_newpasswd) {
            set_error('请再次确认新密码');
            return redirect('/forget/password?code=' . $hash_val);
        }

        if ($newpasswd != $re_newpasswd) {
            set_error('两次新密码输入不一致');
            return redirect('/forget/password?code=' . $hash_val);
        }

        $this->load->model('email_verify_model');
        $record = $this->email_verify_model->get_record_by_hash_val($hash_val);
        if (!$record) {
            set_error('修改失败');
            return redirect('/forget');
        }

        $this->load->model('user_model');
        $res = $this->user_model->edit_user(
            array('passwd' => password_hash($newpasswd, PASSWORD_DEFAULT)),
            $record['uid']
        );
        if ($res !== false) {
            redirect('/login');
        } else {
            set_error('修改失败，请稍后重试');
            redirect('/forget/password?code=' . $hash_val);
        }
    }
}