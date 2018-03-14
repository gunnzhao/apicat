<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 文件上传类
 */
class File_upload extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function avatar()
    {
        $data = $this->input->post('avatar');
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $data, $result)) {
            $type = $result[2];
            $filename = 'p' . $this->session->uid . time() . '.' . $type;
            $file_path = FCPATH . 'static' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'avatar' . DIRECTORY_SEPARATOR . $filename;
            $img_path = '/static/uploads/avatar/' . $filename;
            $file_content = base64_decode(str_replace($result[1], '', $data));

            if (file_put_contents($file_path, $file_content)) {
                $this->load->model('user_model');
                $this->user_model->edit_user(array('avatar' => $img_path), $this->session->uid);
                $this->session->set_userdata('avatar', $img_path);

                $this->response_json_ok(array('img' => $img_path));
            } else {
                $this->response_json_fail('图片上传失败，请稍后再试。');
            }
        } else {
            $this->response_json_fail('图片上传失败，请稍后再试。');
        }
    }
}