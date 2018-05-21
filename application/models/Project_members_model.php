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
    public function get_members($pid)
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
     * 添加项目成员
     * @param  int $pid 项目id
     * @param  int $uid 成员id
     * @return bool 成功返回true，失败返回false
     */
    public function add_member($pid, $uid)
    {
        $this->db->select('id');
        $res = $this->db->get_where($this->table, array('pid' => $pid, 'uid' => $uid));
        if ($res->num_rows() == 1) {
            $record = $res->result_array();
            $res = $this->db->update($this->table, array('insert_time' => time(), 'status' => 0), array('id' => $record[0]['id']));
            if (!$res) {
                log_message('error', $this->db->last_query());
                return false;
            }
            return true;
        } else {
            $data = array(
                'pid' => $pid,
                'uid' => $uid,
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
}