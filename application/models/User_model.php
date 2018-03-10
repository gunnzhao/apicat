<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户表操作类
 */
class User_model extends CI_model
{

    // 表名称
    private $table = 'user';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 创建用户
     * @param  string $email 用户邮箱
     * @param  string $passwd 用户密码
     * @return bool 成功返回uid，失败返回false
     */
    public function add_user($email, $passwd)
    {
        $data = array(
            'email' => $email,
            'passwd' => password_hash($passwd, PASSWORD_DEFAULT),
            'reg_time' => time(),
            'login_ip' => $this->input->ip_address(),
            'login_time' => time()
        );

        $res = $this->db->insert($this->table, $data);
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->insert_id();
    }

    /**
     * 通过email查询用户数据
     * @param  string $email 用户邮箱
     * @return array
     */
    public function get_user_by_email($email)
    {
        $res = $this->db->get_where($this->table, array('email' => $email, 'status' => 0));
        if ($res->num_rows() == 1) {
            $record = $res->result_array();
            return $record[0];
        }
        return array();
    }
}