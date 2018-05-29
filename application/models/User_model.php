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
     * @param  string $nickname 用户昵称
     * @param  string $email 用户邮箱
     * @param  string $passwd 用户密码
     * @return int|bool 成功返回uid，失败返回false
     */
    public function add_user($nickname, $email, $passwd)
    {
        $data = array(
            'email'      => $email,
            'passwd'     => password_hash($passwd, PASSWORD_DEFAULT),
            'nickname'   => $nickname,
            'avatar'     => '/static/img/user.png',
            'reg_time'   => time(),
            'login_ip'   => ''
        );

        $res = $this->db->insert($this->table, $data);
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->insert_id();
    }

    /**
     * 编辑用户
     * @param  array $data 需要编辑的用户信息
     * @param  int $uid 用户ID
     * @return bool|int 影响记录数
     */
    public function edit_user($data, $uid)
    {
        $res = $this->db->update($this->table, $data, array('id' => $uid));
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->affected_rows();
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

    /**
     * 通过uid查询用户数据
     * @param  string $uid 用户ID
     * @return array
     */
    public function get_user_by_uid($uid)
    {
        $res = $this->db->get_where($this->table, array('id' => $uid, 'status' => 0));
        if ($res->num_rows() == 1) {
            $record = $res->result_array();
            return $record[0];
        }
        return array();
    }

    /**
     * 通过多个uid查询多个用户
     * @param  array $uids 用户id
     * @return array
     */
    public function get_users_by_uids($uids)
    {
        $this->db->select('id,nickname,avatar');

        if (count($uids) == 1) {
            $this->db->where('id', $uids[0]);
        } else {
            $this->db->where_in('id', $uids);
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * 更新登录的IP和时间
     * @param  int $uid 用户ID
     * @return bool|int 影响记录数
     */
    public function update_login_info($uid)
    {
        $res = $this->db->update(
            $this->table,
            array('login_ip' => $this->input->ip_address(), 'login_time' => time()),
            array('id' => $uid)
        );
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->affected_rows();
    }
}