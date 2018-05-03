<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 项目表操作类
 */
class Projects_model extends CI_model
{

    // 表名称
    private $table = 'projects';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 创建项目
     * @param  int $uid 用户id
     * @param  string $title 项目名称
     * @param  int $authority 项目权限
     * @param  string $description 项目描述
     * @return int|bool 成功返回uid，失败返回false
     */
    public function add_project($uid, $title, $authority, $description)
    {
        $data = array(
            'uid'         => $uid,
            'title'       => $title,
            'authority'   => $authority,
            'description' => $description,
            'insert_time' => time(),
            'update_time' => time()
        );

        $res = $this->db->insert($this->table, $data);
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        $insert_id = $this->db->insert_id();

        $this->db->update($this->table, array('pro_key' => md5($insert_id)), array('id' => $insert_id));
        return $insert_id;
    }

    /**
     * 通过id编辑项目
     * @param  array $data 需要编辑的项目信息
     * @param  int $id 项目ID
     * @return bool|int 影响记录数
     */
    public function edit_project_by_id($data, $id)
    {
        $res = $this->db->update($this->table, $data, array('id' => $id));
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->affected_rows();
    }

    /**
     * 通过项目key编辑项目
     * @param  array $data 需要编辑的项目信息
     * @param  int $key 项目key
     * @return bool|int 影响记录数
     */
    public function edit_project_by_key($data, $key)
    {
        $res = $this->db->update($this->table, $data, array('pro_key' => $key));
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->affected_rows();
    }

    /**
     * 获取指定用户的项目列表
     * @param  int $uid 用户id
     * @return array
     */
    public function project_records($uid)
    {
        $this->db->select('id,pro_key,title,authority,description,update_time,update_uid');
        $this->db->order_by('update_time', 'DESC');
        return $this->db->get_where($this->table, array('uid' => $uid, 'status' => 0))->result_array();
    }

    /**
     * 通过项目key获取项目详情
     * @param  string $pro_key 项目key
     * @return array
     */
    public function get_project_by_key($pro_key)
    {
        $this->db->select('id,uid,pro_key,title,authority');
        $res = $this->db->get_where($this->table, array('pro_key' => $pro_key, 'status' => 0));
        if ($res->num_rows() == 1) {
            $record = $res->result_array();
            return $record[0];
        }
        return array();
    }
}