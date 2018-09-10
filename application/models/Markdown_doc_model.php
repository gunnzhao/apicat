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

    /**
     * 通过doc_id获取文档
     * @param  int $doc_id 总文档id
     * @return array
     */
    public function get_record_by_doc_id($doc_id)
    {
        $this->db->select('id,markdown_text,html_text');
        $res = $this->db->get_where($this->table, array('doc_id' => $doc_id));
        if ($res->num_rows() == 1) {
            $record = $res->result_array();
            return $record[0];
        }
        return array();
    }

    /**
     * 通过doc_id修改文档
     * @param  array $data 修改内容
     * @param  int $doc_id 总文档id
     * @return bool|int 影响记录数
     */
    public function edit_record_by_doc_id($data, $doc_id)
    {
        $res = $this->db->update($this->table, $data, array('doc_id' => $doc_id));
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->affected_rows();
    }

    /**
     * 删除文档
     * @param  int $id 总文档记录ID
     * @return bool|int 影响记录数
     */
    public function del_record($doc_id)
    {
        $res = $this->db->update($this->table, array('status' => 1), array('doc_id' => $doc_id));
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->affected_rows();
    }
}