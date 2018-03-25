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
}