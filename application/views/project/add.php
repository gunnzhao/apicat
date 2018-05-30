<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-9">
        <h3 class="project-name" data-prokey="<?php echo $project_info['pro_key']; ?>"><?php echo $project_info['title']; ?>
        <small>API数: 123 | 团队成员: 9 | <a href="/projects/settings?pid=<?php echo $project_info['id']; ?>">设置</a></small>
        </h3>
    </div>
    <div class="col-xs-3">
        <div class="input-group">
            <input type="text" class="form-control">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button"><span class="icon-search" aria-hidden="true"></span></button>
            </span>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-xs-3">
        <ul class="api-cate">
            <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $v): ?>
            <li class="cate-node">
                <div class="cate-title">
                    <span class="icon-folder-close-alt"></span>&nbsp; <?php echo $v['title']; ?>
                </div>
                <div class="dropdown cate-icon" style="display:none">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="icon-cog" type="button"></span>
                    </a>
                    <ul class="dropdown-menu" data-cid="<?php echo $v['id']; ?>">
                        <li><a href="javascript:void(0);" class="edit-category">编辑</a></li>
                        <li><a href="javascript:void(0);" class="del-category">删除</a></li>
                    </ul>
                </div>
            </li>
            <li style="display:none;">
                <ul class="apis">
                    <li class="active">添加商户</li>
                    <li>编辑商户</li>
                    <li>
                        <a href="/project/add?pro_key=<?php echo $project_info['pro_key'] ?>&cate_id=<?php echo $v['id'] ?>" class="btn btn-default btn-xs">创建接口</a>
                    </li>
                </ul>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <p class="create-cate-input" style="display:none;">
            <input type="text" class="form-control input-sm" id="create-category" placeholder="分类名称">
            <input type="hidden" id="pid" value="<?php echo $project_info['id']; ?>">
        </p>
        <p class="text-center"><a href="javascript:void(0)" id="create-cate">创建分类</a></p>

        <div id="editCateModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">编辑分类</h4>
                    </div>
                    <div class="modal-body">
                        <form name="edit-cate-form" onsubmit="return false;">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">分类名称</label>
                                <input type="hidden" name="cid" value="0">
                                <input type="text" class="form-control" name="cate_name">
                                <input type="hidden" name="position" value="0">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-lblue" id="edit-cate">确定</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="delCateModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="del-cate-title">删除分类</h4>
                    </div>
                    <div class="modal-body">
                        <p>删除分类后，该分类下的所有内容都将被清除。</p>
                        <input type="hidden" id="wantto-del" value="0">
                        <input type="hidden" id="wantto-del-position" value="0">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-block" id="del-cate">确定删除</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-9">
        <div class="create-doc">
            <h3>创建接口</h3>
            <form id="api-doc">
                <div class="row row-form">
                    <div class="col-xs-6">
                        <input type="text" name="title" class="form-control" placeholder="接口名称">
                    </div>
                </div>
                <div class="row row-form">
                    <div class="col-xs-6">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">GET <span class="caret"></span></button>
                                <ul class="dropdown-menu" id="method">
                                    <li><a href="javascript:void(0);">GET</a></li>
                                    <li><a href="javascript:void(0);">POST</a></li>
                                    <li><a href="javascript:void(0);">PUT</a></li>
                                    <li><a href="javascript:void(0);">PATCH</a></li>
                                    <li><a href="javascript:void(0);">DELETE</a></li>
                                    <li><a href="javascript:void(0);">OPTIONS</a></li>
                                </ul>
                            </div>
                            <input type="hidden" name="method" value="1">
                            <input type="text" name="url" class="form-control" placeholder="URL">
                        </div>
                    </div>
                </div>
                <div class="row row-form">
                    <div class="col-xs-12">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#body" aria-controls="body" role="tab" data-toggle="tab">Body</a></li>
                            <li role="presentation"><a href="#header" aria-controls="header" role="tab" data-toggle="tab">Header</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th></th>
                                        <th>参数名称</th>
                                        <th>类型</th>
                                        <th>必传</th>
                                        <th>默认值</th>
                                        <th>参数说明</th>
                                    </tr>
                                    <tr>
                                        <td class="field-cancel"></td>
                                        <td class="field-name">
                                            <input type="text" name="body_names[]">
                                        </td>
                                        <td class="field-type">
                                            <select name="body_types[]">
                                                <option value="1">int</option>
                                                <option value="2">float</option>
                                                <option value="3">string</option>
                                                <option value="4">array</option>
                                                <option value="5">boolean</option>
                                            </select>
                                        </td>
                                        <td class="field-transport"><input type="checkbox" class="body_musts"></td>
                                        <td class="field-default">
                                            <input type="text" name="body_defaults[]">
                                        </td>
                                        <td class="field-description">
                                            <input type="text" name="body_descriptions[]">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="header">
                                <table class="table table-bordered">
                                    <tr>
                                        <th></th>
                                        <th>参数名称</th>
                                        <th>类型</th>
                                        <th>必传</th>
                                        <th>默认值</th>
                                        <th>参数说明</th>
                                    </tr>
                                    <tr>
                                        <td class="field-cancel"></td>
                                        <td class="field-name">
                                            <input type="text" name="header_names[]">
                                        </td>
                                        <td class="field-type">
                                            <select name="header_types[]">
                                                <option value="1">int</option>
                                                <option value="2">float</option>
                                                <option value="3">string</option>
                                                <option value="4">array</option>
                                                <option value="5">boolean</option>
                                            </select>
                                        </td>
                                        <td class="field-transport"><input type="checkbox" class="header_musts"></td>
                                        <td class="field-default">
                                            <input type="text" name="header_defaults[]">
                                        </td>
                                        <td class="field-description">
                                            <input type="text" name="header_descriptions[]">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-form">
                    <div class="col-xs-12">
                        <p><strong>请求参数示例</strong></p>
                        <textarea id="request_example" name="request_example" class="form-control" rows="14"></textarea>
                    </div>
                </div>
                <div class="row row-form">
                    <div class="col-xs-12">
                        <p><strong>返回参数</strong></p>
                        <table class="table table-bordered">
                            <tr>
                                <th></th>
                                <th>参数名称</th>
                                <th>类型</th>
                                <th>参数说明</th>
                            </tr>
                            <tr>
                                <td class="field-cancel"></td>
                                <td class="return-field-name">
                                    <input type="text" name="response_names[]">
                                </td>
                                <td class="return-field-type">
                                    <select name="response_types[]">
                                        <option value="1">int</option>
                                        <option value="2">float</option>
                                        <option value="3">string</option>
                                        <option value="4">array</option>
                                        <option value="5">boolean</option>
                                    </select>
                                </td>
                                <td class="return-field-description">
                                    <input type="text" name="response_descriptions[]">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row row-form">
                    <div class="col-xs-12">
                        <p><strong>返回参数示例</strong></p>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#success" aria-controls="success" role="tab" data-toggle="tab">正常示例</a></li>
                            <li role="presentation"><a href="#fail" aria-controls="fail" role="tab" data-toggle="tab">异常示例</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="success">
                                <textarea id="response_success" name="response_success" class="form-control" rows="14" style="margin-top: 8px"></textarea>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="fail">
                                <textarea id="response_fail" name="response_fail" class="form-control" rows="14" style="margin-top: 8px"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-form">
                    <div class="col-xs-12">
                        <input type="hidden" name="pid" value="<?php echo $project_info['id']; ?>">
                        <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                        <p class="text-center"><button type="button" id="create" class="btn btn-lblue" style="width:150px;">创建</button></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>