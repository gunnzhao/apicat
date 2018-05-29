<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 邀请项目成员记录表操作类
 */
class Project_invite_model extends CI_model
{

    // 表名称
    private $table = 'project_invite';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 添加邀请记录
     * @param  int $pid 项目id
     * @param  int $invite_uid 邀请人id
     * @param  int $be_invited_uid 被邀请人id
     * @return string 成功返回邀请码，失败返回false
     */
    public function add_record($pid, $invite_uid, $be_invited_uid)
    {
        $data = array(
            'invite_code'     => '',
            'pid'            => $pid,
            'invite_uid'     => $invite_uid,
            'be_invited_uid' => $be_invited_uid,
            'invite_time'    => time()
        );

        $res = $this->db->insert($this->table, $data);
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        $insert_id = $this->db->insert_id();

        $invite_code = md5($insert_id);
        $this->db->update($this->table, array('invite_code' => $invite_code), array('id' => $insert_id));
        return $invite_code;
    }

    /**
     * 获取项目下被邀请人的最后一条记录
     * @param  int $pid 项目id
     * @param  int $be_invited_uid 被邀请人id
     * @return array
     */
    public function get_last_record($pid, $be_invited_uid)
    {
        $this->db->select('id,invite_code,invite_uid,be_invited_uid,accept');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1, 0);
        $res = $this->db->get_where($this->table, array('pid' => $pid, 'be_invited_uid' => $be_invited_uid));
        if ($res->num_rows() == 1) {
            $record = $res->result_array();
            return $record[0];
        }
        return array();
    }

    /**
     * 修改一条记录
     * @param  array $data 要修改的信息
     * @param  int $id 记录id
     * @return bool|int 影响记录数
     */
    public function edit_record($data, $id)
    {
        $res = $this->db->update($this->table, $data, array('id' => $id));
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->affected_rows();
    }

    /**
     * 通过邀请码获取一条记录
     * @param  string $invite_code 邀请码
     * @return array
     */
    public function get_record_by_code($invite_code)
    {
        $this->db->select('id,invite_code,pid,invite_uid,be_invited_uid,accept');
        $res = $this->db->get_where($this->table, array('invite_code' => $invite_code));
        if ($res->num_rows() == 1) {
            $record = $res->result_array();
            return $record[0];
        }
        return array();
    }

    /**
     * 通过邀请码和被邀请人id获取记录
     * @param  string $invite_code 邀请码
     * @param  int $be_invited_uid 被邀请人id
     * @return array
     */
    public function get_record_be_invited($invite_code, $be_invited_uid)
    {
        $this->db->select('id,pid,accept');
        $res = $this->db->get_where($this->table, array('invite_code' => $invite_code, 'be_invited_uid' => $be_invited_uid));
        if ($res->num_rows() == 1) {
            $record = $res->result_array();
            return $record[0];
        }
        return array();
    }
}