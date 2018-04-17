<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 项目类
 */
class Projects extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
    }

    public function index()
    {
        $records = $this->projects_model->project_records($this->session->uid);

        $uids = array();
        foreach ($records as $v) {
            if (!in_array($v['update_uid'], $uids)) {
                $uids[] = $v['update_uid'];
            }
        }

        // 去查询修改项目的用户昵称
        $this->load->model('user_model');
        $user_arr = $this->user_model->get_users_by_uids($uids);
        $users = array();
        foreach ($user_arr as $v) {
            $users[$v['id']] = $v['nickname'];
        }

        $result = array();
        foreach ($records as $v) {
            $result[] = array(
                'id'          => $v['id'],
                'title'       => $v['title'],
                'update_time' => $v['update_time'],
                'update_user' => $users[$v['update_uid']]
            );
        }

        $this->add_page_css('/static/css/projects.css');
        $this->add_page_js('/static/js/projects.js');
        $this->render('projects/index', array('records' => $result));
    }

    public function do_add()
    {
        $title = trim($this->input->post('title'));
        if (empty($title)) {
            return $this->response_json_fail('请输入项目名称');
        }

        $authority = (int)$this->input->post('authority');
        if ($authority !== 0 and $authority !== 1) {
            return $this->response_json_fail('请选择项目权限');
        }

        $description = trim($this->input->post('description'));
        
        $res = $this->projects_model->add_project($this->session->uid, $title, $authority, $description);
        if (!$res) {
            return $this->response_json_fail('项目创建失败，请重试。');
        }
        $this->response_json_ok();
    }
}