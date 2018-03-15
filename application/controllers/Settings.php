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

        if (!empty($this->session->form_err)) {
            // 表单数据覆盖原数据
            $user_info['nickname'] = $this->session->form_err_data['nickname'];
            $user_info['gender'] = $this->session->form_err_data['gender'];
            $user_info['location_id'] = $this->session->form_err_data['city'];
            $province_id = $this->session->form_err_data['province'];

            $form_err = $this->session->form_err;
            $this->session->unset_userdata('form_err');
            $this->render(
                'settings/profile', 
                array(
                    'form_err'    => $form_err,
                    'provinces'   => $provinces, 
                    'cities'      => $cities,
                    'province_id' => $province_id, 
                    'user_info'   => $user_info
                )
            );
        } elseif (!empty($this->session->form_ok)) {
            $form_ok = $this->session->form_ok;
            $this->session->unset_userdata('form_ok');
            $this->render(
                'settings/profile', 
                array(
                    'form_ok'     => $form_ok,
                    'provinces'   => $provinces, 
                    'cities'      => $cities,
                    'province_id' => $province_id, 
                    'user_info'   => $user_info
                )
            );
        } else {
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
        $this->render('settings/email');
    }

    /**
     * 修改密码
     */
    public function admin()
    {
        if (!empty($this->session->form_err)) {
            $form_err = $this->session->form_err;
            $this->session->unset_userdata('form_err');
            $this->render('settings/admin', array('form_err' => $form_err));
        } elseif (!empty($this->session->form_ok)) {
            $form_ok = $this->session->form_ok;
            $this->session->unset_userdata('form_ok');
            $this->render('settings/admin', array('form_ok' => $form_ok));
        } else {
            $this->render('settings/admin');
        }
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
}