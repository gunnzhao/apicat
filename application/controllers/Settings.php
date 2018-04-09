<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 登出类
 */
class Settings extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 修改账号信息
     */
    public function profile()
    {
        $this->load->helper('form_msg');
        $this->load->model('location_model');
        $this->load->model('user_model');
        
        $provinces = $this->location_model->get_all_provinces();

        $cities = array();
        $records = $this->location_model->get_all_cities();
        foreach ($records as $v) {
            $cities[$v['parentid']][] = array('id' => $v['id'], 'name' => $v['name']);
        }

        $user_info = $this->user_model->get_user_by_uid($this->session->uid);

        if ($user_info['location_id'] != 0) {
            $province_id = $this->location_model->get_pid($user_info['location_id']);
        } else {
            $province_id = 0;
        }

        $this->add_page_css('/static/css/settings.profile.css');
        $this->add_page_js('/static/js/iscroll-zoom.js');
        $this->add_page_js('/static/js/hammer.js');
        $this->add_page_js('/static/js/lrz.all.bundle.js');
        $this->add_page_js('/static/js/jquery.photoClip.min.js');
        $this->add_page_js('/static/js/settings.profile.js');

        $this->render(
            'settings/profile', 
            array(
                'provinces'   => $provinces, 
                'cities'      => $cities,
                'province_id' => $province_id, 
                'user_info'   => $user_info
            )
        );
    }

    public function do_profile()
    {
        $nickname = $this->input->post('nickname');
        if (empty($nickname)) {
            $this->form_err_data(array(
                'nickname' => $nickname,
                'gender'   => $this->input->post('gender'),
                'province' => $this->input->post('province'),
                'city'     => $this->input->post('city')
            ));
            $this->show_err('请输入您的昵称');
        }

        $data = array(
            'nickname' => $nickname,
            'gender'   => $this->input->post('gender'),
            'location_id' => $this->input->post('city')
        );

        $this->load->model('user_model');
        $res = $this->user_model->edit_user($data, $this->session->uid);
        if ($res !== false) {
            $this->show_ok('修改成功');
        } else {
            $this->show_err('修改失败，请稍后重试');
        }
    }

    /**
     * 修改Email
     */
    public function email()
    {
        $this->load->model('user_model');
        $user_info = $this->user_model->get_user_by_uid($this->session->uid);
        $tpl_data = array('is_verified' => $user_info['email_verified'], 'email' => $user_info['email']);

        $this->add_page_js('/static/js/settings.email.js');

        $this->load->helper('form_msg');

        $this->render('settings/email', $tpl_data);
    }

    public function do_email()
    {
        $new_email = $this->input->post('new_email');
        $verify_code = $this->input->post('verify_code');

        if (empty($new_email)) {
            $this->show_err('请输入新邮箱');
        }
        if (empty($verify_code)) {
            $this->show_err('请输入验证码');
        }

        if (strlen($verify_code) < 4 or strlen($verify_code) > 6) {
            $this->show_err('您输入的验证码有误');
        }

        $this->load->model('user_model');
        $email_exist = $this->user_model->get_user_by_email($new_email);
        if ($email_exist) {
            $this->show_err('该邮箱已被使用');
        }

        $this->load->model('email_verify_model');
        $record = $this->email_verify_model->get_last_record($this->session->uid);
        if (!$record) {
            $this->show_err('您输入的验证码有误');
        }
        // 超过15分钟，验证码失效
        if ((time() - $record['insert_time']) > 900) {
            $this->show_err('您输入的验证码有误');
        }
        if ($verify_code != $record['verify_code']) {
            $this->show_err('您输入的验证码有误');
        }

        $this->load->model('user_model');
        $res = $this->user_model->edit_user(array('email' => $new_email, 'email_verified' => 1), $this->session->uid);
        if ($res !== false) {
            $this->show_ok('邮箱修改成功');
        } else {
            $this->show_err('邮箱修改失败，请稍后重试。');
        }
    }

    /**
     * 修改密码
     */
    public function admin()
    {
        $this->load->helper('form_msg');
        $this->render('settings/admin');
    }

    public function do_admin()
    {
        $params = array(
            'oldpasswd'    => array('val' => $this->input->post('oldpasswd'), 'err' => '请输入您的旧密码'),
            'newpasswd'    => array('val' => $this->input->post('newpasswd'), 'err' => '请输入您的新密码'),
            're_newpasswd' => array('val' => $this->input->post('re_newpasswd'), 'err' => '请输确认您的新密码')
        );

        foreach ($params as $v) {
            if (empty($v['val'])) {
                $this->show_err($v['err']);
            }
        }

        if ($params['newpasswd']['val'] != $params['re_newpasswd']['val']) {
            $this->show_err('两次新密码输入不一致');
        }

        $this->load->library('session');
        $this->load->model('user_model');

        $user_info = $this->user_model->get_user_by_uid($this->session->uid);
        if (!password_verify($params['oldpasswd']['val'], $user_info['passwd'])) {
            $this->show_err('旧密码输入有误');
        }

        $res = $this->user_model->edit_user(
            array('passwd' => password_hash($params['newpasswd']['val'], PASSWORD_DEFAULT)),
            $this->session->uid
        );
        if ($res !== false) {
            $this->show_ok('修改成功');
        } else {
            $this->show_err('修改失败，请稍后重试');
        }
    }

    /**
     * 验证码是否正确
     */
    public function check_verify_code()
    {
        $code = $this->input->post('verify_code');
        $this->form_validation->set_message('check_verify_code', '验证码有误');

        $this->load->model('email_verify_model');
        $record = $this->email_verify_model->get_last_record($this->session->uid);
        if (!$record) {
            return false;
        }
        log_message('debug', json_encode($record));
        // 超过15分钟，验证码失效
        if ((time() - $record['insert_time']) > 900) {
            return false;
        }
        if ($code != $record['verify_code']) {
            return false;
        }
        
        return true;
    }

    /**
     * 验证邮箱是否被使用
     */
    public function check_email()
    {
        $email = $this->input->post('new_email');
        $this->form_validation->set_message('check_verify_code', '该邮箱已被使用');

        $this->load->model('user_model');
        $email_exist = $this->user_model->get_user_by_email($email);
        if ($email_exist) {
            return false;
        }
        return true;
    }
}