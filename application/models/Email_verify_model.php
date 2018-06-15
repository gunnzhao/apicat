<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Email验证表操作类
 */
class Email_verify_model extends CI_model
{

    // 表名称
    private $table = 'email_verify';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 插入一条邮箱验证的记录
     * @param  int 用户id
     * @param  string $email 邮箱
     * @param  int $code 验证码
     * @return bool 成功返回uid，失败返回false
     */
    public function add_verify_record($uid, $email, $code)
    {
        $data = array(
            'uid'         => $uid,
            'email'       => $email,
            'verify_code' => $code,
            'insert_time' => time(),
            'hash_val'    => hash('sha256', $uid . $email . $code)
        );

        $res = $this->db->insert($this->table, $data);
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->insert_id();
    }

    /**
     * 获取最后一条Email验证操作记录
     * @param  int $uid 用户id
     * @return array
     */
    public function get_last_record($uid)
    {
        $this->db->order_by('id', 'desc');
        $this->db->limit(1, 0);
        $res = $this->db->get_where($this->table, array('uid' => $uid, 'status' => 0));
        if ($res->num_rows() == 1) {
            $record = $res->result_array();
            return $record[0];
        }
        return array();
    }

    /**
     * 通过hash_val获取一条记录
     * @param  string $hash_val 表中的hash值
     * @return array
     */
    public function get_record_by_hash_val($hash_val)
    {
        $res = $this->db->get_where($this->table, array('hash_val' => $hash_val));
        if ($res->num_rows() == 1) {
            $record = $res->result_array();
            return $record[0];
        }
        return array();
    }

    /**
     * 编辑记录
     * @param  array $data 需要编辑的信息
     * @param  int $id 记录ID
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
}