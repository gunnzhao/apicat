<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 项目成员表操作类
 */
class Project_members_model extends CI_model
{

    // 表名称
    private $table = 'project_members';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 获取项目下所有成员的id
     * @param  int $pid 项目id
     * @return array
     */
    public function get_members_id($pid)
    {
        $this->db->select('pid,uid');
        $res = $this->db->get_where($this->table, array('pid' => $pid, 'status' => 0));
        if ($res->num_rows() > 0) {
            $records = $res->result_array();
            return array_column($records, 'uid');
        } else {
            return array();
        }
    }

    /**
     * 获取项目下一个成员的信息
     * @param  int $pid 项目id
     * @param  int $uid 成员id
     * @return array
     */
    public function get_member($pid, $uid)
    {
        $this->db->select('pid,uid,can_write,insert_time');
        $res = $this->db->get_where($this->table, array('pid' => $pid, 'uid' => $uid, 'status' => 0));
        if ($res->num_rows() > 0) {
            $records = $res->result_array();
            return $records[0];
        } else {
            return array();
        }
    }

    /**
     * 获取项目下所有成员的信息
     * @param  int $pid 项目id
     * @return array
     */
    public function get_members($pid)
    {
        $this->db->select('pid,uid,can_write,insert_time');
        return $this->db->get_where($this->table, array('pid' => $pid, 'status' => 0))->result_array();
    }

    /**
     * 添加项目成员
     * @param  int $pid 项目id
     * @param  int $uid 成员id
     * @return bool 成功返回true，失败返回false
     */
    public function add_member($pid, $uid, $can_write = 0)
    {
        $this->db->select('id');
        $res = $this->db->get_where($this->table, array('pid' => $pid, 'uid' => $uid));
        if ($res->num_rows() == 1) {
            $record = $res->result_array();
            $res = $this->db->update($this->table, array('can_write' => $can_write, 'insert_time' => time(), 'status' => 0), array('id' => $record[0]['id']));
            if (!$res) {
                log_message('error', $this->db->last_query());
                return false;
            }
            return true;
        } else {
            $data = array(
                'pid'         => $pid,
                'uid'         => $uid,
                'can_write'   => $can_write,
                'insert_time' => time()
            );
            $res = $this->db->insert($this->table, $data);
            if (!$res) {
                log_message('error', $this->db->last_query());
                return false;
            }
            return true;
        }
    }

    /**
     * 编辑项目成员
     * @param  array $data 编辑信息
     * @param  int $pid 项目id
     * @param  int $uid 成员id
     * @return bool|int 影响记录数
     */
    public function edit_member($data, $pid, $uid)
    {
        $res = $this->db->update($this->table, $data, array('pid' => $pid, 'uid' => $uid));
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->affected_rows();
    }

    /**
     * 删除项目成员
     * @param  int $pid 项目id
     * @param  int $uid 成员id
     * @return bool 成功返回true，失败返回false
     */
    public function del_member($pid, $uid)
    {
        $res = $this->db->update($this->table, array('status' => 1), array('pid' => $pid, 'uid' => $uid));
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return true;
    }
    
    /**
     * 检查某个用户是否在项目中
     * @param  int $pid 项目id
     * @param  int $uid 成员id
     * @return bool 存在返回true，不存在返回false
     */
    public function check_exist($pid, $uid)
    {
        $this->db->select('id');
        $res = $this->db->get_where($this->table, array('pid' => $pid, 'uid' => $uid, 'status' => 0));
        if ($res->num_rows() == 1) {
            return true;
        }
        return false;
    }

    /**
     * 检查某个用户在项目中是否有编辑文档的权限
     * @param  int $pid 项目id
     * @param  int $uid 成员id
     * @return bool 有返回true，没有返回false
     */
    public function check_write_permission($pid, $uid)
    {
        $this->db->select('can_write');
        $res = $this->db->get_where($this->table, array('pid' => $pid, 'uid' => $uid, 'status' => 0));
        if ($res->num_rows() == 1) {
            $records = $res->result_array();
            return $records[0]['can_write'] == 1 ? true : false;
        }
        return false;
    }

    /**
     * 获取项目下的成员数量
     * @param  int $pid 项目id
     * @return int
     */
    public function get_nums($pid)
    {
        $this->db->where(array('pid' => $pid, 'status' => 0));
        return $this->db->count_all_results($this->table);
    }

    /**
     * 获取成员参与的项目
     * @param  int $uid 成员id
     * @return array
     */
    public function get_pids($uid)
    {
        $this->db->select('pid');
        $res = $this->db->get_where($this->table, array('uid' => $uid, 'status' => 0));
        if ($res->num_rows() > 0) {
            $records = $res->result_array();
            return array_column($records, 'pid');
        } else {
            return array();
        }
    }
}