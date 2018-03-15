<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-3">
        <div class="list-group">
            <a href="#" class="list-group-item disabled">个人设置</a>
            <a href="/settings/profile" class="list-group-item active">账号信息</a>
            <a href="/settings/email" class="list-group-item">Email</a>
            <a href="/settings/admin" class="list-group-item">安全设置</a>
        </div>
    </div>
    <div class="col-xs-9">
        <h4>账号信息</h4><hr>
        <div class="row">
            <div class="col-xs-7">
            <form action="/settings/do_profile" method="post">
                <?php if (isset($form_err) and !empty($form_err)): ?>
                <div class="alert alert-warning" role="alert"><?php echo $form_err; ?></div>
                <?php elseif (isset($form_ok) and !empty($form_ok)): ?>
                <div class="alert alert-success" role="alert"><?php echo $form_ok; ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nickname">昵称</label>
                    <input type="text" class="form-control" name="nickname" placeholder="昵称" value="<?php echo $user_info['nickname']; ?>">
                </div>
                <div class="form-group">
                    <label for="gender">性别</label>
                    <div class="radio">
                        <label class="radio-inline">
                            <input type="radio" name="gender" value="1" <?php echo $user_info['gender'] == 1 ? 'checked' : ''; ?>> 男
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gender" value="2" <?php echo $user_info['gender'] == 2 ? 'checked' : ''; ?>> 女
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="province">省份</label>
                    <select class="form-control" name="province" style="width: 200px;">
                        <option value="0">请选择</option>
                        <?php foreach ($provinces as $v): ?>
                        <option value="<?php echo $v['id']; ?>" <?php echo $v['id'] == $province_id ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="city">城市</label>
                    <select class="form-control" name="city" style="width: 200px;">
                        <?php if ($province_id != 0): ?>
                            <?php foreach ($cities[$province_id] as $v): ?>
                            <option value="<?php echo $v['id']; ?>" <?php echo $v['id'] == $user_info['location_id'] ? 'selected' : ''; ?>><?php echo $v['name']; ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="0">请选择</option>
                        <?php endif; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-lblue">更新账号信息</button>
            </form>
            </div>
            <div class="col-xs-1"></div>
            <div class="col-xs-4">
                <h5>账号头像</h5>
                <p class="profile-avatar">
                    <img src="<?php echo $_SESSION['avatar']; ?>" alt="<?php echo $_SESSION['nickname'] . '的头像'; ?>" class="img-circle" id="user-avatar">
                    <button type="button" class="btn btn-default btn-block" data-toggle="modal" data-target="#changeAvatar">更换头像</button>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changeAvatar" tabindex="-1" role="dialog" aria-labelledby="avatarModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">请选择图片</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form class="form-horizontal">
                    <div class="form-group">
                        <label for="avatar" class="col-sm-2 control-label">上传头像</label>
                        <div class="col-sm-10">
                            <input type="file" id="avatar">
                            <a class="btn btn-lblue" id="avatar-file-btn" href="javascript:void(0);">选择图片</a>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-xs-7">
                        <div id="clipArea"></div>
                    </div>
                    <div class="col-xs-5">
                        <p id="view"></p>
                        <p class="text-center"><a class="btn btn-default btn-sm" id="clipBtn">截取头像</a></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-lblue" id="upload">保存修改</button>
            </div>
        </div>
        </div>
</div>