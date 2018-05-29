<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户邀请类
 */
class Invite extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('project_invite_model');
    }

    public function index()
    {
        $invite_code = $this->input->get('invite_code');
        if (!$invite_code) {
            show_404();
        }

        $record = $this->project_invite_model->get_record_by_code($invite_code);
        if (!$record) {
            show_404();
        }

        $result = array('accept' => 0, 'main_text' => '', 'link' => '');
        if ($record['accept'] == 1) {
            $result['accept'] = 1;
            $result['main_text'] = '您已经加入该项目。';
        }

        $this->load->model('user_model');
        $invite_user = $this->user_model->get_user_by_uid($record['invite_uid']);
        $be_invited_user = $this->user_model->get_user_by_uid($record['be_invited_uid']);

        $this->load->model('projects_model');
        $project_info = $this->projects_model->get_project_by_id($record['pid']);

        $result['main_text'] = $invite_user['nickname'] . '邀请您加入项目' . $project_info['title'];

        if ($be_invited_user['login_time'] > 0) {
            $result['link'] = '/invite/accept?invite_code=' . $invite_code . '&uid=' . $be_invited_user['id'];
            $this->load->view('invite/index', $result);
        } else {
            $result['uid'] = $be_invited_user['id'];
            $result['email'] = $be_invited_user['email'];
            $result['invite_code'] = $invite_code;
            $this->load->view('invite/new_user', $result);
        }
    }

    public function accept()
    {
        $invite_code = $this->input->get('invite_code');
        $is_post = false;

        if ($this->input->get('uid')) {
            $uid = $this->input->get('uid');
        } else {
            $this->load->helper('form_msg');

            $uid = $this->input->post('uid');
            if (!$uid) {
                show_404();
            }

            $password = trim($this->input->post('password'));
            if (!$password) {
                $this->show_err('请输入密码');
            }

            $is_post = true;
        }

        $record = $this->project_invite_model->get_record_be_invited($invite_code, $uid);
        if (!$record) {
            show_404();
        }

        if ($record['accept'] == 1) {
            show_404();
        }

        if ($is_post) {
            $this->load->model('user_model');
            $this->user_model->edit_user(array('passwd' => password_hash($password, PASSWORD_DEFAULT)), $uid);
        }

        $this->project_invite_model->edit_record(array('accept' => 1, 'accept_time' => time()), $record['id']);

        $this->load->model('project_members_model');
        $this->project_members_model->add_member($record['pid'], $uid);

        $this->load->helper('url');

        if ($is_post) {
            redirect('/login');
        } else {
            $this->load->model('projects_model');
            $record = $this->projects_model->get_project_by_id($record['pid']);
            redirect('/project?pro_key=' . $record['pro_key']);
        }
    }
}