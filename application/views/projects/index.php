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
    <div class="col-xs-3">
        <div class="projects">
            <h3><a href="#">ECP</a></h3>
            <p>最后一次修改 2018-04-14 21:53</p>
            <p>By Gunn</p>
        </div>
    </div>
</div>

<div class="modal fade" id="createProjectModal" tabindex="-1" role="dialog" aria-labelledby="createProjectModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">创建项目</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="projectName" class="col-sm-2 control-label">项目名称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="project_name" placeholder="项目名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="authority" class="col-sm-2 control-label">权限</label>
                        <div class="col-sm-10">
                            <select class="form-control">
                                <option>公开</option>
                                <option>私有</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">项目描述</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lblue">确认</button>
            </div>
        </div>
    </div>
</div>