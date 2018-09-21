<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API文档类
 */
class Api_doc extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('projects_model');
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

        $this->load->model('project_members_model');
        if (!$this->project_members_model->check_write_permission($project_info['id'], $this->session->uid)) {
            show_404();
        }

        $this->add_page_css_file('/static/css/jquery.numberedtextarea.css');
        $this->add_page_css('/static/css/project.index.css');
        $this->add_page_js_file('/static/js/jquery.numberedtextarea.js');
        $this->add_page_js_file('/static/js/jquery-ui.min.js');
        $this->add_page_js('/static/js/project.add.js');
        $this->render('api_doc/add', array('project_info' => $project_info, 'cid' => $cate_id));
    }

    public function do_add()
    {
        $pid = trim($this->input->post('pid'));
        if (!$pid) {
            return $this->response_json_fail('创建失败');
        }

        $this->load->model('project_members_model');
        if (!$this->project_members_model->check_write_permission($pid, $this->session->uid)) {
            return $this->response_json_fail('创建失败，没有创建权限。');
        }

        $cid = trim($this->input->post('cid'));
        if (!$cid) {
            return $this->response_json_fail('创建失败');
        }

        $title = trim($this->input->post('title'));
        if (!$title) {
            return $this->response_json_fail('请输入接口名称');
        }

        $method = $this->input->post('method');
        if ($method === null or $method < 1 or $method > 6) {
            return $this->response_json_fail('请选择请求方式');
        }

        $url = trim($this->input->post('url'));
        if (!$url) {
            return $this->response_json_fail('请输入接口地址');
        }

        $body_data_type = trim($this->input->post('body_data_type'));

        $this->load->model('doc_model');
        $doc_id = $this->doc_model->add_record(array(
            'pid'            => $pid,
            'cid'            => $cid,
            'title'          => $title,
            'url'            => $url,
            'method'         => $method,
            'body_data_type' => $body_data_type,
            'update_uid'     => $this->session->uid
        ));
        if (!$doc_id) {
            return $this->response_json_fail('创建失败');
        }

        $header_names = $this->input->post('header_names');
        if ($header_names and !empty($header_names[0])) {
            $this->set_header_info($doc_id);
        }

        $body_names = $this->input->post('body_names');
        if ($body_names and !empty($body_names[0])) {
            $this->set_body_info($doc_id);
        }

        $response_names = $this->input->post('response_names');
        if ($response_names and !empty($response_names[0])) {
            $this->set_response_info($doc_id);
        }

        $this->load->model('param_example_model');
        if ($this->input->post('request_example')) {
            $this->param_example_model->add_record(array(
                'doc_id'  => $doc_id,
                'content' => str_replace("\t", "    ", $this->input->post('request_example'))
            ));
        }

        if ($this->input->post('response_success')) {
            $this->param_example_model->add_record(array(
                'doc_id'  => $doc_id,
                'type'    => 1,
                'content' => str_replace("\t", "    ", $this->input->post('response_success'))
            ));
        }

        if ($this->input->post('response_fail')) {
            $this->param_example_model->add_record(array(
                'doc_id'  => $doc_id,
                'type'    => 1,
                'state'   => 1,
                'content' => str_replace("\t", "    ", $this->input->post('response_fail'))
            ));
        }

        $this->projects_model->edit_project_by_id(array('update_time' => time(), 'update_uid' => $this->session->uid), $pid);

        $this->response_json_ok(array('doc_id' => $doc_id));
    }

    private function set_header_info($doc_id, $insert = true)
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

            $tmp = array(
                'doc_id'        => $doc_id,
                'source'        => 0,
                'title'         => $v,
                'type'          => $header_types[$k],
                'is_must'       => $header_musts[$k],
                'default'       => $header_defaults[$k],
                'description'   => $header_descriptions[$k],
                'display_order' => $k,
                'insert_time'   => $now
            );

            if ($insert) {
                $data[] = $tmp;
            } else {
                $data[$v] = $tmp;
            }
        }
        if (!$data) {
            return;
        }

        $this->load->model('request_params_model');

        if ($insert) {
            $this->request_params_model->add_record($data);
        } else {
            $this->request_params_model->update_params($data, $doc_id, 0);
        }
    }

    private function set_body_info($doc_id, $insert = true)
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

            $tmp = array(
                'doc_id'        => $doc_id,
                'source'        => 1,
                'title'         => $v,
                'type'          => $body_types[$k],
                'is_must'       => $body_musts[$k],
                'default'       => $body_defaults[$k],
                'description'   => $body_descriptions[$k],
                'display_order' => $k,
                'insert_time'   => $now
            );

            if ($insert) {
                $data[] = $tmp;
            } else {
                $data[$v] = $tmp;
            }
        }

        $this->load->model('request_params_model');
        if ($insert) {
            $this->request_params_model->add_record($data);
        } else {
            $this->request_params_model->update_params($data, $doc_id);
        }
    }

    private function set_response_info($doc_id, $insert = true)
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

            $tmp = array(
                'doc_id'        => $doc_id,
                'title'         => $v,
                'type'          => $response_types[$k],
                'description'   => $response_descriptions[$k],
                'display_order' => $k,
                'insert_time'   => $now
            );

            if ($insert) {
                $data[] = $tmp;
            } else {
                $data[$v] = $tmp;
            }
        }
        if (!$data) {
            return;
        }

        $this->load->model('response_params_model');
        if ($insert) {
            $this->response_params_model->add_record($data);
        } else {
            $this->response_params_model->update_params($data, $doc_id);
        }
    }
}