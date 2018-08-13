<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<ol class="breadcrumb">
    <li><a href="/projects">我的项目</a></li>
    <li><a href="/project?pro_key=<?php echo $project_info['pro_key']; ?>"><?php echo $project_info['title']; ?></a></li>
    <li class="active">项目设置</li>
</ol>

<div class="row">
    <div class="col-xs-3">
        <div class="list-group">
            <a href="#" class="list-group-item disabled">项目设置</a>
            <a href="/projects/settings?pid=<?php echo $project_info['id']; ?>" class="list-group-item">基本设置</a>
            <a href="/projects/members?pid=<?php echo $project_info['id']; ?>" class="list-group-item">成员管理</a>
            <a href="/projects/permissions?pid=<?php echo $project_info['id']; ?>" class="list-group-item active">权限管理</a>
        </div>
    </div>
    <div class="col-xs-9">
        <h4>权限管理</h4><hr>
        <div class="row">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>成员</td>
                        <td>文档编辑权限</td>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($members as $v): ?>
                    <tr>
                        <td><?php echo $v['nickname']; ?></td>
                        <td data-index="<?php echo $v['id']; ?>"><input name="permission" type="checkbox" <?php echo $members_permission[$v['id']]['can_write'] == 1 ? 'checked' : ''; ?> <?php echo $project_info['uid'] == $v['id'] ? 'disabled' : ''; ?>></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" name="pid" value="<?php echo $project_info['id']; ?>">
</div>