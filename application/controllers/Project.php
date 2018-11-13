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

        $this->load->model('project_members_model');
        if ($project_info['authority'] == 0) {
            // 私有项目
            $this->re_login();
            
            if (!$this->project_members_model->check_exist($project_info['id'], $this->session->uid)) {
                show_404();
            }
        }

        $categories = $this->category_model->get_categories($project_info['id']);

        $this->load->model('doc_model');
        $records = $this->doc_model->get_records($project_info['id']);

        if ($this->input->get('doc_id')) {
            $doc_id = $this->input->get('doc_id');
        } else {
            $active_cid = $doc_id = 0;
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

            foreach ($apis as $k => $v) {
                $display_order_arr = array_column($v, 'display_order');
                array_multisort($display_order_arr, SORT_ASC, SORT_NUMERIC, $apis[$k]);
            }
        }

        if ($active_cid == 0 and $doc_id == 0) {
            foreach ($categories as $v) {
                if (!empty($apis[$v['id']])) {
                    $active_cid = $v['id'];
                    $doc_id = $apis[$v['id']][0]['id'];
                    $doc = $apis[$v['id']][0];
                    break;
                }
            }
        }

        $update_user = '';
        if ($doc) {
            if ($doc['type'] == 1) {
                $doc_data = $this->get_api_doc($doc_id);	
            } else {	
                $doc_data = $this->get_markdown_doc($doc_id);	
            }
            
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

            if ($doc['type'] == 1) {
                $template = 'api_doc/index';
            } else {
                $template = 'markdown/index';
            }
        } else {
            $template = 'api_doc/index';
        }

        $api_nums = $this->doc_model->get_nums($project_info['id']);

        $member_nums = $this->project_members_model->get_nums($project_info['id']);

        if (isset($this->session->uid)) {
            $permission_info = $this->project_members_model->get_member($project_info['id'], $this->session->uid);
            if (!$permission_info) {
                $has_permission = false;
            } else {
                $has_permission = $permission_info['can_write'] == 1 ? true : false;
            }
        } else {
            $has_permission = false;
        }

        $this->add_page_css_file('/static/css/highlight/default.css');
        $this->add_page_css('/static/css/project.index.css');
        $this->add_page_js_file('/static/js/highlight.pack.js');
        if ($has_permission) {
            $this->add_page_js_file('/static/js/jquery-ui.min.js');
            $this->add_page_js('/static/js/category.sort.js');
        }
        $this->add_page_js('/static/js/project.index.js');
        $this->render($template, array(
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
            'param_types'    => array('', 'int', 'float', 'string', 'array', 'boolean'),
            'request_types'  => array('', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'),
            'body_data_type' => array('', 'form-data', 'x-www-form-urlencoded', 'raw', 'binary')
        ));
    }

    public function check_edit()
    {
        $pid = trim($this->input->post('pid'));
        if (!$pid) {
            return $this->response_json_fail('无法修改');
        }

        $this->load->model('project_members_model');
        if (!$this->project_members_model->check_write_permission($pid, $this->session->uid)) {
            return $this->response_json_fail('修改失败，没有修改权限。');
        }

        $doc_id = trim($this->input->post('doc_id'));
        if (!$doc_id) {
            return $this->response_json_fail('无法修改');
        }

        $this->load->model('doc_model');
        $edit_permission = $this->doc_model->get_edit_permission($doc_id, $this->session->uid);
        if (!$edit_permission) {
            $doc_info = $this->doc_model->get_record($doc_id);
            $this->load->model('user_model');
            $user_info = $this->user_model->get_user_by_uid($doc_info['updating_uid']);
            return $this->response_json_fail('无法修改，当前' . $user_info['nickname'] . '正在修改此文档。');
        }

        $this->response_json_ok();
    }

    public function quit()
    {
        $pid = $this->input->post('pid');
        if (!$pid) {
            return $this->response_json_fail('退出失败，请重试。');
        }

        $this->load->model('project_members_model');
        if (!$this->project_members_model->check_exist($pid, $this->session->uid)) {
            return $this->response_json_fail('退出失败');
        }

        if ($this->project_members_model->del_member($pid, $this->session->uid)) {
            $this->response_json_ok();
        } else {
            $this->response_json_fail('退出失败');
        }
    }

    public function notice()
    {
        $pid = $this->input->post('pid');
        $doc_id = $this->input->post('doc_id');
        $notice_uid = $this->input->post('notice_uid');
        if (!$pid or !$doc_id or !$notice_uid) {
            return $this->response_json_fail('请求失败');
        }

        $this->load->model('projects_model');
        $project_info = $this->projects_model->get_project_by_id($pid);
        if (!$project_info) {
            // 项目不存在
            return $this->response_json_fail('请求失败');
        }

        $this->load->model('project_members_model');
        if (!$this->project_members_model->check_write_permission($pid, $this->session->uid)) {
            // 没有修改权限
            return $this->response_json_fail('请求失败');
        }

        $this->load->model('doc_model');
        $doc_info = $this->doc_model->get_record($doc_id);
        if (!$doc_info) {
            // 文档不存在
            return $this->response_json_fail('请求失败');
        }

        $uids = explode(',', $notice_uid);
        $uids = array_unique($uids);
        $this->load->model('user_model');
        $users = $this->user_model->get_users_by_uids($uids);
        if (!$users) {
            // 没有用户
            return $this->response_json_fail('请求失败');
        }

        $members = $this->project_members_model->get_members_id($pid);
        if (!$members) {
            // 成员不存在
            return $this->response_json_fail('请求失败');
        }

        foreach ($users as $v) {
            if (in_array($v['id'], $members)) {
                $this->send_email($v['email'], $project_info['pro_key'], $project_info['title'], $doc_id, $doc_info['title']);
            }
        }
        $this->response_json_ok();
    }

    private function get_api_doc($doc_id)
    {
        $this->load->model('request_params_model');
        $this->load->model('response_params_model');
        $this->load->model('param_example_model');
        $data = array();

        $request_params = $this->request_params_model->get_records($doc_id);
        $header = $body = array();
        if ($request_params) {
            foreach ($request_params as $v) {
                if ($v['source'] == 0) {
                    $header[] = $v;
                } else {
                    $body[] = $v;
                }
            }
        }
        $data['header'] = $header;
        $data['body'] = $body;
        
        $response_params = $this->response_params_model->get_records($doc_id);
        $data['response'] = $response_params;
        
        $examples = $this->param_example_model->get_records($doc_id);
        $request_example = $response_success_example = $response_fail_example = '';
        if ($examples) {
            foreach ($examples as $v) {
                if ($v['type'] == 0) {
                    $request_example = $v['content'];
                } else {
                    if ($v['state'] == 0) {
                        $response_success_example = $v['content'];
                    } else {
                        $response_fail_example = $v['content'];
                    }
                }
            }
        }
        $data['request_example'] = $request_example;
        $data['response_success_example'] = $response_success_example;
        $data['response_fail_example'] = $response_fail_example;
        return $data;
    }

    private function get_markdown_doc($doc_id)
    {
        $this->load->model('markdown_doc_model');
        $data = $this->markdown_doc_model->get_record_by_doc_id($doc_id);
        if (!$data) {
            return array('html_text' => '');
        }
        return array('html_text' => $data['html_text']);
    }

    private function send_email($email, $pro_key, $pro_title, $doc_id, $doc_title)
    {
        $subject = $pro_title . '项目下的' . $doc_title . '文档修改提醒。';
        $message = $this->session->nickname . "对" . $pro_title . "项目下的" . $doc_title . "文档进行了修改\n";
        $message .= "通过此链接查看文档详情：" . config_item('base_url') . "/project?pro_key=" . $pro_key . "&doc_id=" . $doc_id;

        $this->config->load('email');
        $this->load->library('email');
        $this->email->from($this->config->item('sender_email'), $this->config->item('useragent'));
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message);
        $res = $this->email->send();

        if (!$res) {
            return false;
        }
        return true;
    }
}
