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
            <form>
                <div class="form-group">
                    <label for="nickname">昵称</label>
                    <input type="text" class="form-control" name="nickname" placeholder="昵称">
                </div>
                <div class="form-group">
                    <label for="gender">性别</label>
                    <div class="radio">
                        <label class="radio-inline">
                            <input type="radio" name="gender" value="1"> 男
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="gender" value="2"> 女
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="province">省份</label>
                    <select class="form-control" name="province" style="width: 200px;">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="city">城市</label>
                    <select class="form-control" name="city" style="width: 200px;">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-lblue">更新账号信息</button>
            </form>
            </div>
            <div class="col-xs-1"></div>
            <div class="col-xs-4">
                <h5>账号头像</h5>
                <p class="profile-avatar">
                    <img src="<?php echo $_SESSION['avatar']; ?>" alt="..." class="img-circle">
                    <a href="#" class="btn btn-default btn-block">更换头像</a>
                </p>
            </div>
        </div>
    </div>
</div>