<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-3">
        <div class="list-group">
            <a href="#" class="list-group-item disabled">个人设置</a>
            <a href="/settings/profile" class="list-group-item">账号信息</a>
            <a href="/settings/email" class="list-group-item active">Email</a>
            <a href="/settings/admin" class="list-group-item">安全设置</a>
        </div>
    </div>
    <div class="col-xs-9">
        <h4>安全设置</h4><hr>
        <ul class="list-inline settings-email-ul">
            <li>gunncoder@gmail.com</li>
            <li><span class="label label-warning">未验证</span></li>
            <li><span class="label label-success">已验证</span></li>
            <li><a href="#" class="btn btn-default btn-xs">去验证</a></li>
        </ul>
        <p>没有验证的邮箱无法收到通知</p><br>
        <div class="row">
            <div class="col-xs-7">
                <form>
                    <div class="form-group">
                        <label for="nickname">修改邮箱</label>
                        <input type="text" class="form-control" name="oldpasswd" placeholder="新邮箱">
                    </div>
                    <button type="submit" class="btn btn-lblue">修改邮箱</button>
                </form>
            </div>
        </div>
    </div>
</div>