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
        $this->db->order_by('display_order', 'ASC');
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
        $insert_id = $this->db->insert_id();

        // 查询当前创建的分类是项目的第几个
        $this->db->where(array('pid' => $pid, 'status' => 0));
        $rank = $this->db->count_all_results($this->table);
        
        // 更新显示顺序
        $this->db->update($this->table, array('display_order' => $rank), array('id' => $insert_id));

        return $insert_id;
    }

    /**
     * 编辑分类
     * @param  array $data 需要编辑的信息
     * @param  int $id 分类ID
     * @return bool|int 影响记录数
     */
    public function edit_category($data, $id)
    {
        $res = $this->db->update($this->table, $data, array('id' => $id));
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        return $this->db->affected_rows();
    }

    /**
     * 通过多个id获取多个分类名称
     * @param  array $ids 分类id数组
     * @return array
     */
    public function get_titles_by_ids($ids)
    {
        $this->db->select('id,title');
        $this->db->where('status', 0);

        if (count($ids) == 1) {
            $this->db->where('id', $ids[0]);
        } else {
            $this->db->where_in('id', $ids);
        }
        
        return $this->db->get($this->table)->result_array();
    }
}