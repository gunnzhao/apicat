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
}