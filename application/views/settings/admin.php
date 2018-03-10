<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-3">
        <div class="list-group">
            <a href="#" class="list-group-item disabled">个人设置</a>
            <a href="/settings/profile" class="list-group-item">账号信息</a>
            <a href="/settings/email" class="list-group-item">Email</a>
            <a href="/settings/admin" class="list-group-item active">安全设置</a>
        </div>
    </div>
    <div class="col-xs-9">
        <h4>安全设置</h4><hr>
        <div class="row">
            <div class="col-xs-7">
            <form>
                <div class="form-group">
                    <label for="nickname">旧密码</label>
                    <input type="password" class="form-control" name="oldpasswd" placeholder="旧密码">
                </div>

                <div class="form-group">
                    <label for="nickname">新密码</label>
                    <input type="password" class="form-control" name="newpasswd" placeholder="新密码">
                </div>

                <div class="form-group">
                    <label for="nickname">确认新密码</label>
                    <input type="password" class="form-control" name="re_newpasswd" placeholder="确认新密码">
                </div>
                <button type="submit" class="btn btn-lblue">更新密码</button>
            </form>
            </div>
        </div>
    </div>
</div>