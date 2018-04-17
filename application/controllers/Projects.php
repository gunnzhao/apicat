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
        $this->add_page_css('/static/css/projects.css');
        $this->add_page_js('/static/js/projects.js');
        $this->render('projects/index');
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
        
        $res = $this->projects_model->add_project($title, $authority, $description);
        if (!$res) {
            return $this->response_json_fail('项目创建失败，请重试。');
        }
        $this->response_json_ok();
    }
}