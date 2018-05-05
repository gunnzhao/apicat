<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API分类表操作类
 */
class Category_model extends CI_model
{

    // 表名称
    private $table = 'category';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 获取API的分类
     * @param  int $pid 项目id
     * @return array
     */
    public function get_categories($pid)
    {
        $this->db->select('id,title');
        $this->db->order_by('display_order', 'DESC');
        return $this->db->get_where($this->table, array('pid' => $pid, 'status' => 0))->result_array();
    }

    /**
     * 检查同项目下分类名称是否已存在
     * @param  int $pid 项目id
     * @param  string $title 分类名称
     * @return bool 存在true 不存在false
     */
    public function check_exist($pid, $title)
    {
        $this->db->select('id');
        $res = $this->db->get_where($this->table, array('pid' => $pid, 'status' => 0, 'title' => $title));
        if ($res->num_rows() >= 1) {
            return true;
        }
        return false;
    }

    public function add_category($pid, $title)
    {
        $data = array(
            'pid'         => $pid,
            'title'       => $title,
            'insert_time' => time()
        );

        $res = $this->db->insert($this->table, $data);
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->insert_id();
    }
}