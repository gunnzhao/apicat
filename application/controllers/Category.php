<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 文档分类类
 */
class Category extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('category_model');
        $this->load->model('projects_model');
    }

    public function do_add()
    {
        $project_id = $this->input->post('pid');
        $category_name = trim($this->input->post('title'));

        if ($project_id == 0) {
            return $this->response_json_fail('添加失败');
        }

        $this->load->model('project_members_model');
        if (!$this->project_members_model->check_write_permission($project_id, $this->session->uid)) {
            return $this->response_json_fail('添加失败，没有添加权限。');
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

        $this->projects_model->edit_project_by_id(array('update_time' => time(), 'update_uid' => $this->session->uid), $project_id);

        return $this->response_json_ok(array('cid' => $res));
    }

    public function do_edit()
    {
        $project_id = $this->input->post('pid');
        $category_id = $this->input->post('cid');
        $category_name = trim($this->input->post('title'));

        if ($project_id == 0 or $category_id == 0) {
            return $this->response_json_fail('编辑失败');
        }

        $this->load->model('project_members_model');
        if (!$this->project_members_model->check_write_permission($project_id, $this->session->uid)) {
            return $this->response_json_fail('编辑失败，没有编辑权限。');
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

        $this->projects_model->edit_project_by_id(array('update_time' => time(), 'update_uid' => $this->session->uid), $project_id);

        return $this->response_json_ok();
    }

    public function del_category()
    {
        $pid = $this->input->post('pid');

        $this->load->model('project_members_model');
        if (!$this->project_members_model->check_write_permission($pid, $this->session->uid)) {
            return $this->response_json_fail('删除失败，没有删除权限。');
        }

        $category_id = $this->input->post('cid');
        if ($category_id == 0) {
            return $this->response_json_fail('删除失败');
        }

        $res = $this->category_model->edit_category(array('status' => 1), $category_id);
        if (!$res) {
            return $this->response_json_fail('删除失败，请重试。');
        }

        $this->projects_model->edit_project_by_id(array('update_time' => time(), 'update_uid' => $this->session->uid), $pid);

        return $this->response_json_ok();
    }
}