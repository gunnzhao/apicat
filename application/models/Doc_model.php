<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API文档表操作类
 */
class Doc_model extends CI_model
{

    // 表名称
    private $table = 'doc';
    
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
        $data['insert_time'] = $data['update_time'] = time();

        $res = $this->db->insert($this->table, $data);
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }

        $insert_id = $this->db->insert_id();

        // 查询当前创建的文档是分类的第几个
        $this->db->where(array('pid' => $data['pid'], 'cid' => $data['cid'], 'status' => 0));
        $rank = $this->db->count_all_results($this->table);
        
        // 更新显示顺序
        $this->db->update($this->table, array('display_order' => $rank), array('id' => $insert_id));

        return $insert_id;
    }

    /**
     * 获取API的列表
     * @param  int $pid 项目id
     * @return array
     */
    public function get_records($pid)
    {
        $this->db->select('id,pid,cid,title,type,url,method,body_data_type,display_order,update_uid,update_time');
        $this->db->order_by('display_order', 'ASC');
        return $this->db->get_where($this->table, array('pid' => $pid, 'status' => 0))->result_array();
    }

    /**
     * 获取文档详情
     * @param  int $doc_id 文档id
     * @return array
     */
    public function get_record($doc_id)
    {
        $this->db->select('id,pid,cid,title,type,url,method,body_data_type,display_order,updating_uid');
        $res = $this->db->get_where($this->table, array('id' => $doc_id, 'status' => 0));
        if ($res->num_rows() == 1) {
            $record = $res->result_array();
            return $record[0];
        }
        return array();
    }

    /**
     * 修改文档
     * @param  array $data 需要修改的信息
     * @param  int $id 记录ID
     * @return bool|int 影响记录数
     */
    public function edit_record($data, $id)
    {
        $data['update_time'] = time();
        $res = $this->db->update($this->table, $data, array('id' => $id));
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->affected_rows();
    }

    /**
     * 获取项目下的文档数量
     * @param  int $pid 项目id
     * @return int
     */
    public function get_nums($pid)
    {
        $this->db->where(array('pid' => $pid, 'status' => 0));
        return $this->db->count_all_results($this->table);
    }

    /**
     * 获取文档编辑权限
     * @param  int $doc_id 文档id
     * @param  int $uid 用户id
     * @return bool true获取成功 false获取失败
     */
    public function get_edit_permission($doc_id, $uid)
    {
        $table = 't_' . $this->table;

        $this->db->trans_start();
        $sql = 'SELECT updating_uid,updating_time FROM ' . $table . ' WHERE id=' . $doc_id . ' FOR UPDATE';
        $record = $this->db->query($sql)->result_array();
        if (!$record) {
            $this->db->trans_complete();
            return false;
        }

        if ($record[0]['updating_uid'] > 0 and $record[0]['updating_uid'] != $uid) {
            if (time() - $record[0]['updating_time'] < 300) {
                // 当前文档别人正在修改，且修改时间小于5分钟
                $this->db->trans_complete();
                return false;
            }
        }

        $this->edit_record(array('updating_uid' => $uid, 'updating_time' => time()), $doc_id);
        $this->db->trans_complete();
        return true;
    }

    /**
     * 通过文档title搜索
     * @param  int $pid 项目id
     * @param  string $keyword 关键字
     * @return array
     */
    public function search_by_title($pid, $keyword)
    {
        $this->db->select('id,pid,cid,title,update_uid,update_time');
        $this->db->where(array('pid' => $pid, 'status' => 0));
        $this->db->like('title', $keyword);
        $this->db->order_by('update_time', 'DESC');
        $this->db->limit(50, 0);
        return $this->db->get($this->table)->result_array();
    }

    /**
     * 删除文档
     * @param  int $id 记录ID
     * @return bool|int 影响记录数
     */
    public function del_record($id)
    {
        $res = $this->db->update($this->table, array('status' => 1), array('id' => $id));
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->affected_rows();
    }
}