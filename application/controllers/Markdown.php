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
        $this->load->model('markdown_doc_model');
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

        $this->load->model('project_members_model');
        if ($project_info['authority'] == 0) {
            // 私有项目
            $this->re_login();
            
            if (!$this->project_members_model->check_exist($project_info['id'], $this->session->uid)) {
                show_404();
            }
        }

        $this->load->model('category_model');
        $categories = $this->category_model->get_categories($project_info['id']);

        $this->load->model('doc_model');
        $records = $this->doc_model->get_records($project_info['id']);

        if ($records) {
            if ($this->input->get('doc_id')) {
                $doc_id = $this->input->get('doc_id');
            } else {
                if ($records) {
                    $active_cid = $records[0]['cid'];
                    $doc_id = $records[0]['id'];
                } else {
                    $active_cid = 0;
                    $doc_id = 0;
                }
            }
        } else {
            $active_cid = 0;
            $doc_id = 0;
        }

        $apis = array();
        $doc = array();
        if ($records) {
            foreach ($records as $v) {
                if (!isset($apis[$v['cid']])) {
                    $apis[$v['cid']] = array($v);
                } else {
                    $apis[$v['cid']][] = $v;
                }

                if ($doc_id == $v['id']) {
                    $active_cid = $v['cid'];
                    $doc = $v;
                }
            }
        }

        $update_user = '';
        if ($doc) {
            if ($doc['type'] != 2) {
                show_404();
            }
            
            $doc_data = $this->get_markdown_doc($doc_id);
            $doc = array_merge($doc, $doc_data);

            if (isset($this->session->uid)) {
                if ($doc['update_uid'] != $this->session->uid) {
                    $this->load->model('user_model');
                    $user_info = $this->user_model->get_user_by_uid($doc['update_uid']);
                    $update_user = $user_info['nickname'];
                } else {
                    $update_user = $this->session->nickname;
                }
            } else {
                $this->load->model('user_model');
                $user_info = $this->user_model->get_user_by_uid($doc['update_uid']);
                $update_user = $user_info['nickname'];
            }
        }

        $api_nums = $this->doc_model->get_nums($project_info['id']);

        $member_nums = $this->project_members_model->get_nums($project_info['id']);

        if (isset($this->session->uid)) {
            $permission_info = $this->project_members_model->get_member($project_info['id'], $this->session->uid);
            $has_permission = $permission_info['can_write'] == 1 ? true : false;
        } else {
            $has_permission = false;
        }

        $this->add_page_css('/static/css/highlight/default.css');
        $this->add_page_css('/static/css/project.index.css');
        $this->add_page_js('/static/js/highlight.pack.js');
        $this->add_page_js('/static/js/project.index.js');
        $this->render('markdown/index', array(
            'project_info'   => $project_info,
            'api_nums'       => $api_nums,
            'member_nums'    => $member_nums,
            'categories'     => $categories,
            'apis'           => $apis,
            'active_cid'     => $active_cid,
            'doc_id'         => $doc_id,
            'doc'            => $doc,
            'update_user'    => $update_user,
            'has_permission' => $has_permission,
        ));
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

    public function edit()
    {
        $pro_key = $this->input->get('pro_key');
        if (!$pro_key) {
            show_404();
        }
        $doc_id = $this->input->get('doc_id');
        if (!$doc_id) {
            show_404();
        }

        $project_info = $this->projects_model->get_project_by_key($pro_key);
        if (!$project_info) {
            show_404();
        }

        // 检查是否对文档操作的权限
        $this->load->model('project_members_model');
        if (!$this->project_members_model->check_write_permission($project_info['id'], $this->session->uid)) {
            show_404();
        }

        $this->load->model('doc_model');
        $doc = $this->doc_model->get_record($doc_id);
        if (!$doc) {
            show_404();
        }

        $this->load->model('user_model');
        // 判断文档当前的修改人是否为本人
        if ($doc['updating_uid'] != $this->session->uid) {
            $user_info = $this->user_model->get_user_by_uid($doc['updating_uid']);
            $this->add_page_js('/static/js/project.addfail.js');
            return $this->render('/project/edit_fail', array('pro_key' => $pro_key, 'doc_id' => $doc_id, 'edit_user' => $user_info['nickname']));
        }

        $doc_data = $this->get_markdown_doc($doc_id);
        $doc = array_merge($doc, $doc_data);

        $members_id = $this->project_members_model->get_members_id($project_info['id']);
        if (count($members_id) > 1) {
            $members_info = $this->user_model->get_users_by_uids($members_id);
        } else {
            $members_info = array();
        }

        $this->load->view('markdown/edit_markdown', array(
            'project_info' => $project_info,
            'doc'          => $doc,
            'members_info' => $members_info
        ));
    }

    public function do_edit()
    {
        $pid = trim($this->input->post('pid'));

        $this->load->model('project_members_model');
        if (!$this->project_members_model->check_write_permission($pid, $this->session->uid)) {
            return $this->response_json_fail('修改失败，没有修改权限。');
        }

        $doc_id = trim($this->input->post('doc_id'));
        if (!$doc_id) {
            return $this->response_json_fail('修改失败');
        }

        // 判断文档当前的修改人是否为本人
        $this->load->model('doc_model');
        $doc = $this->doc_model->get_record($doc_id);
        if ($doc['updating_uid'] != $this->session->uid) {
            $this->load->model('user_model');
            $user_info = $this->user_model->get_user_by_uid($doc['updating_uid']);
            return $this->response_json_fail('无法修改，当前' . $user_info['nickname'] . '正在修改此文档。');
        }

        $title = trim($this->input->post('title'));
        if (!$title) {
            return $this->response_json_fail('请输入接口名称');
        }

        $markdown_text = trim($this->input->post('markdown_text'));
        if (!$markdown_text) {
            return $this->response_json_fail('请输入文档内容');
        }

        $html_text = trim($this->input->post('html_text'));
        if (!$html_text) {
            return $this->response_json_fail('请输入文档内容');
        }

        $this->doc_model->edit_record(array(
            'title'          => $title,
            'update_uid'     => $this->session->uid
        ), $doc_id);

        $res = $this->markdown_doc_model->edit_record_by_doc_id(array(
            'markdown_text' => $markdown_text,
            'html_text'     => htmlentities($html_text)
        ), $doc_id);
        if ($res === false) {
            return $this->response_json_fail('文档编辑失败，请重试。');
        }

        $this->projects_model->edit_project_by_id(array('update_time' => time(), 'update_uid' => $this->session->uid), $pid);

        $this->response_json_ok();
    }

    private function get_markdown_doc($doc_id)
    {
        $data = $this->markdown_doc_model->get_record_by_doc_id($doc_id);
        if (!$data) {
            return array('markdown_text' => '', 'html_text' => '');
        }
        return array('markdown_text' => $data['markdown_text'], 'html_text' => $data['html_text']);
    }
}