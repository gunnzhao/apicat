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
            <a href="/projects/settings" class="list-group-item">基本设置</a>
            <a href="/projects/members" class="list-group-item active">成员管理</a>
        </div>
    </div>
    <div class="col-xs-9">
        <h4>成员管理</h4><hr>
        <div class="row">
            <div class="col-xs-12">
                <form action="/settings/do_admin" method="post" class="form-inline">
                    <div class="form-group">
                        <label class="sr-only">invite</label>
                        <p class="form-control-static">邀请成员加入</p>
                    </div>
                    <div class="form-group">
                        <label for="email" class="sr-only">Password</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <button type="submit" class="btn btn-lblue">修改</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-2">
                <div class="members">
                    <p class="text-center">
                        <img src="http://wx.qlogo.cn/mmhead/Q3auHgzwzM55stttUxUOyUHrcJ8MnaH292VkkcRmdlwNg4ERNU1jRw/0" alt="加多宝" class="img-circle">
                    </p>
                    <p class="text-center user-name">加多宝</p>
                    <p class="text-center"><button class="btn btn-default btn-xs del-member" type="button">从项目删除</button></button></p>
                </div>
            </div>
        </div>
    </div>
</div>