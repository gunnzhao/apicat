<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-12">
        <h2>我的项目</h2><hr/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <p class="text-right"><a href="#" data-toggle="modal" data-target="#createProjectModal">创建项目</a></p>
    </div>
    <?php if (empty($records)): ?>
    <div class="col-xs-12">
        <p class="text-center">暂无项目</p>
    </div>
    <?php else: ?>
        <?php foreach($records as $v): ?>
            <div class="col-xs-3">
                <div class="projects">
                    <h3><a href="/project?id=<?php echo $v['id']; ?>"><?php echo $v['title']; ?></a></h3>
                    <p>最后更新 <?php echo date('Y-m-d H:i', $v['update_time']); ?></p>
                    <p>By <?php echo $v['update_user']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="modal fade" id="createProjectModal" tabindex="-1" role="dialog" aria-labelledby="createProjectModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">创建项目</h4>
            </div>
            <div class="modal-body">
                <div id="err-msg" class="alert alert-warning" style="display:none;" role="alert">123</div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">项目名称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="title" placeholder="项目名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="authority" class="col-sm-2 control-label">权限</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="authority">
                                <option value="0">私有</option>
                                <option value="1">公开</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">项目描述</label>
                        <div class="col-sm-10">
                            <textarea id="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lblue" id="add">确认</button>
            </div>
        </div>
    </div>
</div>