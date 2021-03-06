<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户项目类
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
        // 用户参与的项目
        $this->load->model('project_members_model');
        $participate_pids = $this->project_members_model->get_pids($this->session->uid);
        if ($participate_pids) {
            $records = $this->projects_model->get_project_by_ids($participate_pids);
        } else {
            $records = array();
        }

        $uids = array();

        if ($records) {
            foreach ($records as $v) {
                if (!in_array($v['update_uid'], $uids)) {
                    $uids[] = $v['update_uid'];
                }
            }
        }

        if ($uids) {
            // 去查询修改项目的用户昵称
            $this->load->model('user_model');
            $user_arr = $this->user_model->get_users_by_uids($uids);
            $users = array();
            foreach ($user_arr as $v) {
                $users[$v['id']] = $v['nickname'];
            }
        }

        $result = array();
        if ($records) {
            foreach ($records as $v) {
                $result[] = array(
                    'id'          => $v['id'],
                    'pro_key'     => $v['pro_key'],
                    'title'       => $v['title'],
                    'update_time' => $v['update_time'],
                    'update_user' => $users[$v['update_uid']]
                );
            }
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

        $this->load->model('project_members_model');
        $this->project_members_model->add_member($res, $this->session->uid, 1);
        $this->response_json_ok();
    }

    public function settings()
    {
        $pid = $this->input->get('pid');
        if (!$pid) {
            show_404();
        }

        $project_info = $this->projects_model->get_project_by_id($pid);
        if (!$project_info) {
            show_404();
        }

        if ($project_info['uid'] != $this->session->uid) {
            show_404();
        }

        $this->load->helper('form_msg');
        $this->render('projects/settings', array('project_info' => $project_info));
    }

    public function do_settings()
    {
        $this->load->helper('form_msg');
        init_form_post(array('pid', 'title', 'authority', 'description'));
        $pid = trim($this->input->post('pid'));
        if (!$pid) {
            $this->show_err('修改失败');
        }

        $title = trim($this->input->post('title'));
        if (!$title) {
            $this->show_err('请输入项目名称');
        }

        $authority = trim($this->input->post('authority'));
        if ($authority != 0 and $authority != 1) {
            $this->show_err('修改失败');
        }

        $description = trim($this->input->post('description'));
        $owner = $this->projects_model->check_owner($this->session->uid, $pid);
        if (!$owner) {
            $this->show_err('修改失败');
        }

        $is_repeat = $this->projects_model->check_title_repeat($this->session->uid, $pid, $title);
        if ($is_repeat) {
            $this->show_err('项目名称重复');
        }

        $res = $this->projects_model->edit_project_by_id(
            array(
                'title'       => $title,
                'authority'   => $authority,
                'description' => $description,
                'update_time' => time(),
                'update_uid'  => $this->session->uid
            ),
            $pid
        );
        if ($res !== false) {
            $this->show_ok('修改成功');
        } else {
            $this->show_err('修改失败，请稍后重试');
        }
    }

    public function members()
    {
        $pid = $this->input->get('pid');
        if (!$pid) {
            show_404();
        }

        $project_info = $this->projects_model->get_project_by_id($pid);
        if (!$project_info) {
            show_404();
        }

        if ($project_info['uid'] != $this->session->uid) {
            show_404();
        }

        $this->load->model('project_members_model');
        $uids = $this->project_members_model->get_members_id($pid);

        $this->load->model('user_model');
        $members = $this->user_model->get_users_by_uids($uids);

        $this->add_page_css('/static/css/projects.members.css');
        $this->add_page_js('/static/js/projects.members.js');
        $this->render('projects/members', array('project_info' => $project_info, 'members' => $members));
    }

    public function add_member()
    {
        $email = $this->input->post('email');
        if (!$email) {
            return $this->response_json_fail('请输入邮箱');
        }

        $pid = $this->input->post('pid');
        if (!$pid) {
            return $this->response_json_fail('邀请失败');
        }
        $project_info = $this->projects_model->get_project_by_id($pid);
        if (!$project_info) {
            // 项目不存在
            return $this->response_json_fail('邀请失败');
        }
        if ($project_info['uid'] != $this->session->uid) {
            // 项目创建人和邀请人不一致
            return $this->response_json_fail('邀请失败');
        }

        $this->load->helper('email');
        if (!valid_email($email)) {
            return $this->response_json_fail('邮箱格式有误');
        }
        
        $this->load->model('user_model');
        $user = $this->user_model->get_user_by_email($email);
        if ($user) {
            $this->load->model('project_members_model');
            if ($this->project_members_model->check_exist($pid, $user['id'])) {
                return $this->response_json_fail('该成员已在您的项目中');
            }
            $be_invited_uid = $user['id'];
        } else {
            $nickname_arr = explode('@', $email);
            $be_invited_uid = $this->user_model->add_user($nickname_arr[0], $email, '123456');
            if (!$be_invited_uid) {
                return $this->response_json_fail('邀请失败，请重试');
            }
        }

        $this->load->model('project_invite_model');
        $record = $this->project_invite_model->get_last_record($pid, $be_invited_uid);
        if ($record) {
            if ($record['accept'] == 1) {
                return $this->response_json_fail('该成员已在您的项目中');
            }

            $this->project_invite_model->edit_record(array('invite_time' => time()), $record['id']);
            $invite_code = $record['invite_code'];
        } else {
            $invite_code = $this->project_invite_model->add_record($pid, $this->session->uid, $be_invited_uid);
            if (!$invite_code) {
                return $this->response_json_fail('邀请失败，请重试');
            }
        }
        $this->send_email($email, $pid, $invite_code);
        $this->response_json_ok();
    }

    public function del_member()
    {
        $pid = $this->input->post('pid');
        if (!$pid) {
            return $this->response_json_fail('删除失败');
        }

        $uid = $this->input->post('uid');
        if (!$uid) {
            return $this->response_json_fail('请选择要删除的用户');
        }

        $this->load->model('project_members_model');
        $res = $this->project_members_model->del_member($pid, $uid);
        if ($res === false) {
            return $this->response_json_fail('删除失败');
        }
        $this->response_json_ok();
    }

    public function permissions()
    {
        $pid = $this->input->get('pid');
        if (!$pid) {
            show_404();
        }

        $project_info = $this->projects_model->get_project_by_id($pid);
        if (!$project_info) {
            show_404();
        }

        if ($project_info['uid'] != $this->session->uid) {
            show_404();
        }

        $this->load->model('project_members_model');
        $members_permission = $this->project_members_model->get_members($pid);

        $uids = array();
        foreach ($members_permission as $v) {
            $uids[$v['uid']] = $v;
        }

        $members_permission = $uids;
        $uids = array_keys($members_permission);

        $this->load->model('user_model');
        $members = $this->user_model->get_users_by_uids($uids);

        $this->add_page_js('/static/js/projects.js');
        $this->render('projects/permissions', array('project_info' => $project_info, 'members' => $members, 'members_permission' => $members_permission));
    }

    public function do_permission()
    {
        $pid = $this->input->post('pid');
        if (!$pid) {
            return $this->response_json_fail('修改失败');
        }

        $uid = $this->input->post('uid');
        if (!$uid) {
            return $this->response_json_fail('请选择要修改权限的用户');
        }

        if ($this->input->post('permission')) {
            $permission = $this->input->post('permission') > 0 ? 1 : 0;
        } else {
            $permission = 0;
        }

        $this->load->model('project_members_model');
        $res = $this->project_members_model->edit_member(array('can_write' => $permission), $pid, $uid);
        if ($res === false) {
            return $this->response_json_fail('修改失败');
        }
        $this->response_json_ok();
    }

    private function send_email($email, $pid, $invite_code)
    {
        $project_info = $this->projects_model->get_project_by_id($pid);
        $subject = $this->session->nickname . '邀请您加入' . $project_info['title'] . '项目';
        $message = $this->session->nickname . "邀请您加入" . $project_info['title'] . "项目\n";
        $message .= "通过此链接加入：" . config_item('base_url') . "/invite?invite_code=" . $invite_code;

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