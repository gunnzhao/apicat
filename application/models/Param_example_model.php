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

    /**
     * 获取所有参数示例
     * @param  int $doc_id 文档id
     * @return array
     */
    public function get_records($doc_id)
    {
        $this->db->select('id,type,state,content');
        return $this->db->get_where($this->table, array('doc_id' => $doc_id, 'status' => 0))->result_array();
    }

    /**
     * 修改参数示例
     * @param  int $doc_id 文档id
     * @param  int $type 类型 0请求示例 1返回示例
     * @param  int $state 响应类型 0正常示例 1异常示例
     * @param  string $content 示例内容
     * @return bool|int 影响记录数
     */
    public function edit_record($doc_id, $type, $state, $content)
    {
        $record = $this->db->get_where($this->table, array('doc_id' => $doc_id, 'type' => $type, 'state' => $state));
        if ($record->num_rows() >= 1) {
            $res = $this->db->update(
                $this->table,
                array('content' => $content, 'status' => 0),
                array('doc_id' => $doc_id, 'type' => $type, 'state' => $state)
            );

            if (!$res) {
                log_message('error', $this->db->last_query());
                return false;
            }
            return $this->db->affected_rows();
        } else {
            $res = $this->db->insert($this->table, array(
                'doc_id' => $doc_id,
                'type'   => $type,
                'state'  => $state,
                'content' => $content,
                'insert_time' => time()
            ));

            if (!$res) {
                log_message('error', $this->db->last_query());
                return false;
            }
            return 1;
        }
    }

    /**
     * 根据文档ID清除记录
     * @param  int $doc_id 记文档录ID
     * @return bool|int 影响记录数
     */
    public function del_records_by_doc_id($doc_id)
    {
        $res = $this->db->update($this->table, array('status' => 1), array('doc_id' => $doc_id));
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->affected_rows();
    }
}