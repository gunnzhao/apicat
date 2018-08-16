<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-9">
        <h3 class="project-name" data-prokey="<?php echo $project_info['pro_key']; ?>"><?php echo $project_info['title']; ?>
        <small>API数: <?php echo $api_nums; ?> | 团队成员: <?php echo $member_nums; ?> <?php if (isset($_SESSION['uid']) and $project_info['uid'] == $_SESSION['uid']): ?>| <a href="/projects/settings?pid=<?php echo $project_info['id']; ?>">设置</a><?php endif; ?></small>
        </h3>
    </div>
    <div class="col-xs-3">
        <div class="input-group">
            <input type="text" class="form-control" id="keyword">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button" id="go-search"><span class="icon-search" aria-hidden="true"></span></button>
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
                    <span class="<?php echo $active_cid == $v['id'] ? 'icon-folder-open-alt' : 'icon-folder-close-alt'; ?>"></span>&nbsp; <?php echo $v['title']; ?>
                </div>
                <?php if ($has_permission): ?>
                <div class="dropdown cate-icon" style="display:none">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="icon-cog" type="button"></span>
                    </a>
                    <ul class="dropdown-menu" data-cid="<?php echo $v['id']; ?>">
                        <li><a href="javascript:void(0);" class="edit-category">编辑</a></li>
                        <li><a href="javascript:void(0);" class="del-category">删除</a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </li>
            <li style="<?php echo $active_cid == $v['id'] ? 'display:block;' : 'display:none;'; ?>">
                <ul class="apis">
                    <?php if (isset($apis[$v['id']])): ?>
                    <?php foreach ($apis[$v['id']] as $v2): ?>
                    <?php if ($doc_id == $v2['id']): ?>
                    <li class="active"><?php echo $v2['title']; ?></li>
                    <?php else: ?>
                    <li><a href="/project?pro_key=<?php echo $project_info['pro_key']; ?>&doc_id=<?php echo $v2['id']; ?>"><?php echo $v2['title']; ?></a></li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($has_permission): ?>
                    <li>
                        <a href="/project/add?pro_key=<?php echo $project_info['pro_key'] ?>&cate_id=<?php echo $v['id'] ?>" class="btn btn-default btn-xs">创建接口</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <?php if ($has_permission): ?>
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
        <?php endif; ?>
    </div>
    <div class="col-xs-9">
        <?php if (!empty($doc)): ?>
        <div class="doc">
            <div class="row">
                <div class="col-xs-11"><h3><?php echo $doc['title']; ?></h3></div>
                <div class="col-xs-1">
                    <?php if ($has_permission): ?>
                    <a href="/project/edit?pro_key=<?php echo $project_info['pro_key']; ?>&doc_id=<?php echo $doc['id']; ?>" class="btn btn-lblue btn-xs edit-entrance" id="go-edit">修改</a>
                    <?php endif; ?>
                </div>
            </div>
            <small>最后修改 <?php echo date('Y-m-d H:i:s', $doc['update_time']); ?> By <?php echo $update_user; ?></small>
            
            <p><strong><?php echo $request_types[$doc['method']]; ?> <?php echo $body_data_type[$doc['body_data_type']]; ?></strong></p>
            <p><strong>URL: </strong> <code><?php echo $doc['url']; ?></code></p>

            <?php if (!empty($doc['header'])): ?>
            <p><strong>请求Header参数说明</strong></p>
            <table class="table table-bordered">
                <tr>
                    <th>参数名称</th>
                    <th>类型</th>
                    <th>是否必传</th>
                    <th>默认值</th>
                    <th>参数说明</th>
                </tr>
                <?php foreach ($doc['header'] as $v): ?>
                <tr>
                    <td><?php echo $v['title']; ?></td>
                    <td><?php echo $param_types[$v['type']]; ?></td>
                    <td><?php echo $v['is_must'] == 0 ? '否' : '是'; ?></td>
                    <td><?php echo $v['default']; ?></td>
                    <td><?php echo $v['description']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>

            <?php if (!empty($doc['body'])): ?>
            <p><strong>请求参数说明</strong></p>
            <table class="table table-bordered">
                <tr>
                    <th>参数名称</th>
                    <th>类型</th>
                    <th>是否必传</th>
                    <th>默认值</th>
                    <th>参数说明</th>
                </tr>
                <?php foreach ($doc['body'] as $v): ?>
                <tr>
                    <td><?php echo $v['title']; ?></td>
                    <td><?php echo $param_types[$v['type']]; ?></td>
                    <td><?php echo $v['is_must'] == 0 ? '否' : '是'; ?></td>
                    <td><?php echo $v['default']; ?></td>
                    <td><?php echo $v['description']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>

            <?php if (!empty($doc['request_example'])): ?>
            <p><strong>请求参数示例</strong></p>
            <pre><?php echo $doc['request_example']; ?></pre>
            <?php endif; ?>

            <?php if (!empty($doc['response'])): ?>
            <p><strong>返回参数说明</strong></p>
            <table class="table table-bordered">
                <tr>
                    <th>参数名称</th>
                    <th>类型</th>
                    <th>参数说明</th>
                </tr>
                <?php foreach ($doc['response'] as $v): ?>
                <tr>
                    <td><?php echo $v['title']; ?></td>
                    <td><?php echo $param_types[$v['type']]; ?></td>
                    <td><?php echo $v['description']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>

            <?php if (!empty($doc['response_success_example']) and !empty($doc['response_fail_example'])): ?>
            <p><strong>返回参数示例</strong></p>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#nomal" aria-controls="nomal" role="tab" data-toggle="tab">正常示例</a></li>
                <li role="presentation"><a href="#exception" aria-controls="exception" role="tab" data-toggle="tab">异常示例</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="nomal">
                    <br/><pre><?php echo $doc['response_success_example']; ?></pre>
                </div>
                <div role="tabpanel" class="tab-pane" id="exception">
                    <br/><pre><?php echo $doc['response_fail_example']; ?></pre>
                </div>
            </div>
            <?php elseif (!empty($doc['response_success_example'])): ?>
            <p><strong>返回参数示例</strong></p>
            <pre><?php echo $doc['response_success_example']; ?></pre>
            <?php elseif (!empty($doc['response_fail_example'])): ?>
            <p><strong>返回参数示例</strong></p>
            <pre><?php echo $doc['response_fail_example']; ?></pre>
            <?php endif; ?>
        </div>
        <?php else: ?>
            <p class="text-center">暂无文档</p>
        <?php endif; ?>
    </div>
</div>