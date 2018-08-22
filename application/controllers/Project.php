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
            if (empty($this->session->uid)) {
                show_404();
            }
            
            if (!$this->project_members_model->check_exist($project_info['id'], $this->session->uid)) {
                show_404();
            }
        }

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
            $this->load->model('request_params_model');
            $this->load->model('response_params_model');
            $this->load->model('param_example_model');

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
            $doc['header'] = $header;
            $doc['body'] = $body;
            
            $response_params = $this->response_params_model->get_records($doc_id);
            $doc['response'] = $response_params;
            
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
            $doc['request_example'] = $request_example;
            $doc['response_success_example'] = $response_success_example;
            $doc['response_fail_example'] = $response_fail_example;

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

        $this->add_page_css('/static/css/project.index.css');
        $this->add_page_js('/static/js/project.index.js');
        $this->render('project/index', array(
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

        $this->add_page_css('/static/css/jquery.numberedtextarea.css');
        $this->add_page_css('/static/css/project.index.css');
        $this->add_page_js('/static/js/jquery.numberedtextarea.js');
        $this->add_page_js('/static/js/jquery-ui.min.js');
        $this->add_page_js('/static/js/project.add.js');
        $this->render('project/add', array('project_info' => $project_info, 'cid' => $cate_id));
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

        $this->load->model('request_params_model');
        $this->load->model('response_params_model');
        $this->load->model('param_example_model');

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
        $doc['header'] = $header;
        $doc['body'] = $body;
        
        $response_params = $this->response_params_model->get_records($doc_id);
        $doc['response'] = $response_params;
        
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
        $doc['request_example'] = $request_example;
        $doc['response_success_example'] = $response_success_example;
        $doc['response_fail_example'] = $response_fail_example;

        $members_id = $this->project_members_model->get_members_id($project_info['id']);
        if (count($members_id) > 1) {
            $members_info = $this->user_model->get_users_by_uids($members_id);
        } else {
            $members_info = array();
        }

        $this->add_page_css('/static/css/jquery.numberedtextarea.css');
        $this->add_page_css('/static/css/project.index.css');
        $this->add_page_js('/static/js/jquery.numberedtextarea.js');
        $this->add_page_js('/static/js/jquery-ui.min.js');
        $this->add_page_js('/static/js/project.add.js');
        $this->render('project/edit', array(
            'project_info'   => $project_info,
            'doc'            => $doc,
            'param_types'    => array('', 'int', 'float', 'string', 'array', 'boolean'),
            'request_types'  => array('', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'),
            'body_data_type' => array('', 'form-data', 'x-www-form-urlencoded', 'raw', 'binary'),
            'members_info'   => $members_info
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
            return $this->response_json_fail('创建失败');
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

        $body_data_type = trim($this->input->post('body_data_type'));

        $this->doc_model->edit_record(array(
            'title'          => $title,
            'url'            => $url,
            'method'         => $method,
            'body_data_type' => $body_data_type,
            'update_uid'     => $this->session->uid
        ), $doc_id);

        $header_names = $this->input->post('header_names');
        if ($header_names and !empty($header_names[0])) {
            $this->edit_header_info($doc_id);
        }

        $body_names = $this->input->post('body_names');
        if ($body_names and !empty($body_names[0])) {
            $this->edit_body_info($doc_id);
        }

        $response_names = $this->input->post('response_names');
        if ($response_names and !empty($response_names[0])) {
            $this->edit_response_info($doc_id);
        }

        $this->load->model('param_example_model');
        if ($this->input->post('request_example')) {
            $this->param_example_model->edit_record(
                $doc_id, 0, 0, str_replace("\t", "    ", $this->input->post('request_example'))
            );
        }

        if ($this->input->post('response_success')) {
            $this->param_example_model->edit_record(
                $doc_id, 1, 0, str_replace("\t", "    ", $this->input->post('response_success'))
            );
        }

        if ($this->input->post('response_fail')) {
            $this->param_example_model->edit_record(
                $doc_id, 1, 1, str_replace("\t", "    ", $this->input->post('response_fail'))
            );
        }

        $this->projects_model->edit_project_by_id(array('update_time' => time(), 'update_uid' => $this->session->uid), $pid);

        $this->response_json_ok(array('doc_id' => $doc_id));
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

    public function add_category()
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

    public function edit_category()
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
        }

        $this->load->model('request_params_model');
        $this->request_params_model->add_record($data);
    }

    private function edit_header_info($doc_id)
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

            $data[$v] = array(
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
        }
        if (!$data) {
            return;
        }

        $this->load->model('request_params_model');
        $this->request_params_model->update_params($data, $doc_id, 0);
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
        }

        $this->load->model('request_params_model');
        $this->request_params_model->add_record($data);
    }

    private function edit_body_info($doc_id)
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

            $data[$v] = array(
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
        }
        if (!$data) {
            return;
        }

        $this->load->model('request_params_model');
        $this->request_params_model->update_params($data, $doc_id);
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
                'doc_id'        => $doc_id,
                'title'         => $v,
                'type'          => $response_types[$k],
                'description'   => $response_descriptions[$k],
                'display_order' => $k,
                'insert_time'   => $now
            );
        }

        $this->load->model('response_params_model');
        $this->response_params_model->add_record($data);
    }

    private function edit_response_info($doc_id)
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

            $data[$v] = array(
                'doc_id'        => $doc_id,
                'title'         => $v,
                'type'          => $response_types[$k],
                'description'   => $response_descriptions[$k],
                'display_order' => $k,
                'insert_time'   => $now
            );
        }
        if (!$data) {
            return;
        }

        $this->load->model('response_params_model');
        $this->response_params_model->update_params($data, $doc_id);
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