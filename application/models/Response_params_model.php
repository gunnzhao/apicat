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

    /**
     * 更新API文档的返回参数
     * @param  array $data 更新后的参数列表信息
     * @param  int $doc_id 文档id
     * @return void
     */
    public function update_params($data, $doc_id)
    {
        $records = $this->get_records($doc_id);
        if (!$records) {
            $this->add_record($data);
        }

        $data_params = array_keys($data);
        $records_params = array_column($records, 'title');
        // 取出参数名称相同的交集
        $intersects = array_intersect($data_params, $records_params);

        $origin_records = array();
        foreach ($records as $v) {
            $origin_records[$v['title']] = $v;
        }

        if ($intersects) {
            // 更新参数名称相同部分的数据
            foreach ($intersects as $v) {
                $this->db->update($this->table, $data[$v], array('id' => $origin_records[$v]['id']));
                unset($data[$v], $origin_records[$v]);
            }
        }

        $data = array_values($data);
        $origin_records = array_values($origin_records);
        if (count($data) > count($origin_records)) {
            if ($origin_records) {
                foreach ($origin_records as $k => $v) {
                    $this->db->update($this->table, $data[$k], array('id' => $v['id']));
                    unset($data[$k]);
                }
            }
            $this->add_record($data);
        } elseif (count($data) < count($origin_records)) {
            if ($data) {
                foreach ($data as $k => $v) {
                    $this->db->update($this->table, $v, array('id' => $origin_records[$k]['id']));
                    unset($origin_records[$k]);
                }
            }
            foreach ($origin_records as $v) {
                $this->db->update($this->table, array('status' => 1), array('id' => $v['id']));
            }
        } else {
            if ($data) {
                foreach ($data as $k => $v) {
                    $this->db->update($this->table, $v, array('id' => $origin_records[$k]['id']));
                }
            }
        }
    }
}