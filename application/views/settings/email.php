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
            <li><?php echo $email; ?></li>
            <?php if ($is_verified == 0): ?>
            <li><span class="label label-warning">未验证</span></li>
            <li><a href="#" class="btn btn-default btn-xs">去验证</a></li>
            <?php else: ?>
            <li><span class="label label-success">已验证</span></li>
            <?php endif; ?>
        </ul>

        <?php if ($is_verified == 0): ?>
        <p class="text-danger">没有验证的邮箱无法收到通知</p><br>
        <?php endif; ?>

        <hr>
        <h4>修改邮箱</h4>
        <div class="row">
            <div class="col-xs-7">
                <?php echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>'); ?>
                <?php if (isset($result) and $result): ?>
                <div class="alert alert-success" role="alert">修改成功</div>
                <?php elseif (isset($result) and !$result): ?>
                <div class="alert alert-warning" role="alert">修改失败，请稍后重试</div>
                <?php endif; ?>
                <form class="form-horizontal" action="/settings/email" method="post">
                    <div class="form-group">
                        <label for="new_email" class="col-sm-2 control-label">新邮箱</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="new_email" placeholder="新邮箱" value="<?php echo set_value('new_email', ''); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="verify_code" class="col-sm-2 control-label">验证码</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="verify_code" placeholder="验证码" value="<?php echo set_value('verify_code', ''); ?>">
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-default" id="get_verify_code">获取验证码</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-lblue">修改邮箱</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>