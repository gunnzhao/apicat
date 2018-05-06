<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 项目类
 */
class Project extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('category_model');
        $this->load->model('projects_model');
    }

    public function index()
    {
        $pro_key = $this->input->get('pro_key');
        if (!$pro_key) {
            show_404();
        }

        $project_info = $this->projects_model->get_project_by_key($pro_key);
        if (!$project_info) {
            show_404();
        }

        $categories = $this->category_model->get_categories($project_info['id']);

        $this->add_page_css('/static/css/project.index.css');
        $this->add_page_js('/static/js/project.index.js');
        $this->render('project/index', array('project_info' => $project_info, 'categories' => $categories));
    }

    public function add()
    {
        $this->add_page_css('/static/css/jquery.numberedtextarea.css');
        $this->add_page_css('/static/css/project.index.css');
        $this->add_page_js('/static/js/jquery.numberedtextarea.js');
        $this->add_page_js('/static/js/project.index.js');
        $this->add_page_js('/static/js/project.add.js');
        $this->render('project/add');
    }

    public function add_category()
    {
        $project_id = $this->input->post('pid');
        $category_name = trim($this->input->post('title'));

        if ($project_id == 0) {
            return $this->response_json_fail('添加失败');
        }

        if (empty($category_name)) {
            return $this->response_json_fail('名称不能为空');
        }

        // 检查分类是否已经存在
        $exist = $this->category_model->check_exist($project_id, $category_name);
        if ($exist) {
            return $this->response_json_fail('该名称已经存在，请勿重复添加。');
        }

        $res = $this->category_model->add_category($project_id, $category_name);
        if (!$res) {
            return $this->response_json_fail('添加失败，请重试。');
        }
        return $this->response_json_ok(array('cid' => $res));
    }

    public function edit_category()
    {
        $project_id = $this->input->post('pid');
        $category_id = $this->input->post('cid');
        $category_name = trim($this->input->post('title'));

        if ($project_id == 0 or $category_id == 0) {
            return $this->response_json_fail('编辑失败');
        }

        if (empty($category_name)) {
            return $this->response_json_fail('名称不能为空');
        }

        // 检查分类是否已经存在
        $exist = $this->category_model->check_exist($project_id, $category_name);
        if ($exist) {
            return $this->response_json_fail('该名称已经存在');
        }

        $res = $this->category_model->edit_category(array('title' => $category_name), $category_id);
        if (!$res) {
            return $this->response_json_fail('编辑失败，请重试。');
        }
        return $this->response_json_ok();
    }

    public function del_category()
    {
        $category_id = $this->input->post('cid');
        if ($category_id == 0) {
            return $this->response_json_fail('删除失败');
        }

        $res = $this->category_model->edit_category(array('status' => 1), $category_id);
        if (!$res) {
            return $this->response_json_fail('删除失败，请重试。');
        }
        return $this->response_json_ok();
    }
}