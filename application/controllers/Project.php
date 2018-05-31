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

        $this->load->model('doc_model');
        $records = $this->doc_model->get_records($project_info['id']);
        $apis = array();
        foreach ($records as $v) {
            if (!isset($apis[$v['cid']])) {
                $apis[$v['cid']] = array($v);
            } else {
                $apis[$v['cid']][] = $v;
            }
        }

        $this->add_page_css('/static/css/project.index.css');
        $this->add_page_js('/static/js/project.index.js');
        $this->render('project/index', array('project_info' => $project_info, 'categories' => $categories, 'apis' => $apis));
    }

    public function add()
    {
        $pro_key = $this->input->get('pro_key');
        if (!$pro_key) {
            show_404();
        }
        $cate_id = $this->input->get('cate_id');
        if (!$cate_id) {
            show_404();
        }

        $project_info = $this->projects_model->get_project_by_key($pro_key);
        if (!$project_info) {
            show_404();
        }

        $categories = $this->category_model->get_categories($project_info['id']);

        $this->add_page_css('/static/css/jquery.numberedtextarea.css');
        $this->add_page_css('/static/css/project.index.css');
        $this->add_page_js('/static/js/jquery.numberedtextarea.js');
        $this->add_page_js('/static/js/project.index.js');
        $this->add_page_js('/static/js/project.add.js');
        $this->render('project/add', array('project_info' => $project_info, 'categories' => $categories, 'cid' => $cate_id));
    }

    public function do_add()
    {
        $pid = trim($this->input->post('pid'));
        if (!$pid) {
            return $this->response_json_fail('创建失败');
        }

        $cid = trim($this->input->post('cid'));
        if (!$cid) {
            return $this->response_json_fail('创建失败');
        }

        $title = trim($this->input->post('title'));
        if (!$title or ($title < 1 and $title > 6)) {
            return $this->response_json_fail('请输入接口名称');
        }

        $method = $this->input->post('method');
        if ($method === null) {
            return $this->response_json_fail('请选择请求方式');
        }

        $url = trim($this->input->post('url'));
        if (!$url) {
            return $this->response_json_fail('请输入接口地址');
        }

        $this->load->model('doc_model');
        $doc_id = $this->doc_model->add_record($pid, $cid, $title, $url, $method);

        $header_names = $this->input->post('header_names');
        if ($header_names and !empty($header_names[0])) {
            $this->add_header_info($doc_id);
        }

        $body_names = $this->input->post('body_names');
        if ($body_names and !empty($body_names[0])) {
            $this->add_body_info($doc_id);
        }

        $response_names = $this->input->post('response_names');
        if ($response_names and !empty($response_names[0])) {
            $this->add_response_info($doc_id);
        }

        $this->load->model('param_example_model');
        if ($this->input->post('request_example')) {
            $this->param_example_model->add_record(array(
                'doc_id'  => $doc_id,
                'content' => $this->input->post('request_example')
            ));
        }

        if ($this->input->post('response_success')) {
            $this->param_example_model->add_record(array(
                'doc_id'  => $doc_id,
                'type'    => 1,
                'content' => $this->input->post('response_success')
            ));
        }

        if ($this->input->post('response_fail')) {
            $this->param_example_model->add_record(array(
                'doc_id'  => $doc_id,
                'type'    => 1,
                'state'   => 1,
                'content' => $this->input->post('response_fail')
            ));
        }

        $this->response_json_ok(array('doc_id' => $doc_id));
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

    private function add_header_info($doc_id)
    {
        $header_names = $this->input->post('header_names');
        $header_types = $this->input->post('header_types');
        $header_musts = $this->input->post('header_musts');
        $header_defaults = $this->input->post('header_defaults');
        $header_descriptions = $this->input->post('header_descriptions');

        $data = array();
        $now = time();
        foreach ($header_names as $k => $v) {
            if ($v == '') {
                continue;
            }

            $data[] = array(
                'doc_id'      => $doc_id,
                'source'      => 0,
                'title'       => $v,
                'type'        => $header_types[$k],
                'is_must'     => $header_musts[$k],
                'default'     => $header_defaults[$k],
                'description' => $header_descriptions[$k],
                'insert_time' => $now
            );
        }

        $this->load->model('request_params_model');
        $this->request_params_model->add_record($data);
    }

    private function add_body_info($doc_id)
    {
        $body_names = $this->input->post('body_names');
        $body_types = $this->input->post('body_types');
        $body_musts = $this->input->post('body_musts');
        $body_defaults = $this->input->post('body_defaults');
        $body_descriptions = $this->input->post('body_descriptions');

        $data = array();
        $now = time();
        foreach ($body_names as $k => $v) {
            if ($v == '') {
                continue;
            }

            $data[] = array(
                'doc_id'      => $doc_id,
                'source'      => 1,
                'title'       => $v,
                'type'        => $body_types[$k],
                'is_must'     => $body_musts[$k],
                'default'     => $body_defaults[$k],
                'description' => $body_descriptions[$k],
                'insert_time' => $now
            );
        }

        $this->load->model('request_params_model');
        $this->request_params_model->add_record($data);
    }

    private function add_response_info($doc_id)
    {
        $response_names = $this->input->post('response_names');
        $response_types = $this->input->post('response_types');
        $response_descriptions = $this->input->post('response_descriptions');

        $data = array();
        $now = time();
        foreach ($response_names as $k => $v) {
            if ($v == '') {
                continue;
            }

            $data[] = array(
                'doc_id'      => $doc_id,
                'title'       => $v,
                'type'        => $response_types[$k],
                'description' => $response_descriptions[$k],
                'insert_time' => $now
            );
        }

        $this->load->model('response_params_model');
        $this->response_params_model->add_record($data);
    }
}