<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 邀请项目成员记录表操作类
 */
class Project_invite_model extends CI_model
{

    // 表名称
    private $table = 'project_invite';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 添加邀请记录
     * @param  int $pid 项目id
     * @param  int $invite_uid 邀请人id
     * @param  int $be_invited_uid 被邀请人id
     * @return string 成功返回邀请码，失败返回false
     */
    public function add_record($pid, $invite_uid, $be_invited_uid)
    {
        $data = array(
            'invite_key'     => '',
            'pid'            => $pid,
            'invite_uid'     => $invite_uid,
            'be_invited_uid' => $be_invited_uid,
            'invite_time'    => time()
        );

        $res = $this->db->insert($this->table, $data);
        if (!$res) {
            log_message('error', $this->db->last_query());
            return false;
        }
        $insert_id = $this->db->insert_id();

        $invite_code = md5($insert_id);
        $this->db->update($this->table, array('invite_code' => $invite_code), array('id' => $insert_id));
        return $invite_code;
    }
}