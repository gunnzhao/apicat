<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<ol class="breadcrumb">
    <li><a href="/projects">我的项目</a></li>
    <li><a href="/project?pro_key=c4ca4238a0b923820dcc509a6f75849b">ECP</a></li>
    <li class="active">项目设置</li>
</ol>

<div class="row">
    <div class="col-xs-3">
        <div class="list-group">
            <a href="#" class="list-group-item disabled">项目设置</a>
            <a href="/projects/settings" class="list-group-item active">基本设置</a>
            <a href="/projects/members" class="list-group-item">成员管理</a>
        </div>
    </div>
    <div class="col-xs-9">
        <h4>基本设置</h4><hr>
        <div class="row">
            <div class="col-xs-7">
            <?php echo form_error('<div class="alert alert-warning" role="alert">', '</div>'); ?>
            <?php echo form_ok('<div class="alert alert-success" role="alert">', '</div>'); ?>
            <form action="/settings/do_admin" method="post">
                <div class="form-group">
                    <label for="title">项目名称</label>
                    <input type="text" class="form-control" name="title">
                </div>

                <div class="form-group">
                    <label for="authority">权限</label>
                    <select class="form-control" name="authority">
                        <option value="0">私有</option>
                        <option value="1">公开</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">项目描述</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-lblue">修改</button>
            </form>
            </div>
        </div>
    </div>
</div>