<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Markdown文档表操作类
 */
class Markdown_doc_model extends CI_model
{

    // 表名称
    private $table = 'markdown_doc';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 增加文档记录
     * @param  array $data 添加的文档信息
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