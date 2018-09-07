<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Markdown文档类
 */
class Markdown extends MY_Controller
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
        $this->load->view('markdown/add_markdown', array('project_info' => $project_info, 'cid' => $cate_id));
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
            return $this->response_json_fail('请输入文档名称');
        }

        $markdown_text = trim($this->input->post('markdown_text'));
        if (!$markdown_text) {
            return $this->response_json_fail('请输入文档内容');
        }

        $html_text = trim($this->input->post('html_text'));
        if (!$html_text) {
            return $this->response_json_fail('请输入文档内容');
        }

        $this->load->model('doc_model');
        $doc_id = $this->doc_model->add_record(array(
            'pid'        => $pid,
            'cid'        => $cid,
            'title'      => $title,
            'type'       => 2,
            'update_uid' => $this->session->uid
        ));
        if (!$doc_id) {
            return $this->response_json_fail('创建失败');
        }

        $this->load->model('markdown_doc_model');
        $markdown_doc_id = $this->markdown_doc_model->add_record(array(
            'doc_id'        => $doc_id,
            'markdown_text' => $markdown_text,
            'html_text'     => htmlentities($html_text) // html_entity_decode
        ));
        if (!$markdown_doc_id) {
            return $this->response_json_fail('创建失败');
        }

        $this->response_json_ok(array('doc_id' => $doc_id));
    }
}