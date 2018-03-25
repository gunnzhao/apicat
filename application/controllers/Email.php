<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 邮件发送类
 */
class Email extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function verify()
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

        $this->load->model('user_model');
        $this->user_model->edit_user(array('email_verified' => 1), $record['uid']);
        $this->email_verify_model->edit_record(array('status' => 1), $record['id']);
        $this->load->view('email/verify', array('msg' => '邮箱验证成功'));
    }

    public function send_verify_code()
    {
        $email = $this->input->post('email');
        if (!$email) {
            return $this->response_json_fail('缺少邮箱');
        }

        $this->load->model('user_model');
        $email_exist = $this->user_model->get_user_by_email($email);
        if ($email_exist) {
            return $this->response_json_fail('该邮箱已被使用');
        }

        $this->load->model('email_verify_model');
        $record = $this->email_verify_model->get_last_record($this->session->uid);
        if ((time() - $record['insert_time']) < 60) {
            return $this->response_json_fail('请勿频繁发送验证码');
        }

        $this->load->helper('email');
        if (!valid_email($email)) {
            return $this->response_json_fail('邮箱格式有误');
        }

        // 6位随机数
        $code = rand(100000, 999999);

        $res = $this->email_verify_model->add_verify_record($email, $code);
        if (!$res) {
            return $this->response_json_fail('验证码发送失败，请稍后重试');
        }

        $this->load->library('email');
        $this->email->from('baqimovie@163.com', 'ApiCat');
        $this->email->to($email);
        $this->email->subject('ApiCat修改邮箱验证码');
        $this->email->message('当前您正在进行修改邮箱操作，您的验证码是：' . $code);
        $res = $this->email->send();

        if ($res) {
            $this->response_json_ok();
        } else {
            $this->response_json_fail('邮件发送失败');
        }
    }

    public function send_verify_link()
    {
        $email = $this->input->post('email');
        if (!$email) {
            return $this->response_json_fail('缺少邮箱');
        }

        $this->load->model('email_verify_model');
        $record = $this->email_verify_model->get_last_record($this->session->uid);
        if ((time() - $record['insert_time']) < 60) {
            return $this->response_json_fail('请勿频繁验证邮箱');
        }

        // 6位随机数
        $code = rand(100000, 999999);

        $res = $this->email_verify_model->add_verify_record($email, $code);
        if (!$res) {
            return $this->response_json_fail('邮件发送失败，请稍后重试');
        }

        $link = config_item('base_url') . '/email/verify?code=' . hash('sha256', $this->session->uid . $email . $code);

        $this->load->library('email');
        $this->email->from('baqimovie@163.com', 'ApiCat');
        $this->email->to($email);
        $this->email->subject('ApiCat邮箱验证');
        $this->email->message('请点击此链接验证您的邮箱：' . $link);
        $res = $this->email->send();

        if ($res) {
            $this->response_json_ok();
        } else {
            $this->response_json_fail('邮件发送失败');
        }
    }
}