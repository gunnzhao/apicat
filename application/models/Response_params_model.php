<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 响应参数表操作类
 */
class Response_params_model extends CI_model
{

    // 表名称
    private $table = 'response_params';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 添加响应参数记录
     * @param  array $data 需要添加的数据
     * @return int|bool 成功返回uid，失败返回false
     */
    public function add_record($data)
    {
        if (count($data) == 1) {
            $res = $this->db->insert($this->table, $data[0]);
        } else {
            $res = $this->db->insert_batch($this->table, $data);
        }

        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->insert_id();
    }

    /**
     * 获取所有返回参数
     * @param  int $doc_id 文档id
     * @return array
     */
    public function get_records($doc_id)
    {
        $this->db->select('id,title,type,description');
        return $this->db->get_where($this->table, array('doc_id' => $doc_id, 'status' => 0))->result_array();
    }
}