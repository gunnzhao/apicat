<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 参数示例表操作类
 */
class Param_example_model extends CI_model
{

    // 表名称
    private $table = 'param_example';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 增加示例记录
     * @param  array $data 示例数据
     * @return int|bool 成功返回uid，失败返回false
     */
    public function add_record($data)
    {
        $data['insert_time'] = time();

        $res = $this->db->insert($this->table, $data);
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->insert_id();
    }
}