<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-9">
        <h3 class="project-name" data-prokey="<?php echo $project_info['pro_key']; ?>"><?php echo $project_info['title']; ?>
            <small>API数: <?php echo $api_nums; ?> | 团队成员: <?php echo $member_nums; ?> <?php if (isset($_SESSION['uid']) and $project_info['uid'] == $_SESSION['uid']): ?>| <a href="/projects/settings?pid=<?php echo $project_info['id']; ?>">设置</a><?php elseif (isset($_SESSION['uid']) and $project_info['uid'] != $_SESSION['uid']): ?>| <a href="javascript:void(0);" class="quit-project">退出该项目</a><?php endif; ?></small>
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
        <ul class="list-group cates">
            <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $v): ?>
            <li class="list-group-item cates-node">
                <p class="cate-title"><i class="<?php echo $active_cid == $v['id'] ? 'icon-folder-open-alt' : 'icon-folder-close-alt'; ?>"></i> <?php echo $v['title']; ?></p>
                <ul class="list-unstyled docs" style="<?php echo $active_cid == $v['id'] ? 'display:block;' : 'display:none;'; ?>">
                    <?php if (isset($apis[$v['id']])): ?>
                    <?php foreach ($apis[$v['id']] as $v2): ?>
                    <?php if ($doc_id == $v2['id']): ?>
                    <li class="docs-node active"><?php echo $v2['title']; ?></li>
                    <?php else: ?>
                    <li class="docs-node"><a href="/project?pro_key=<?php echo $project_info['pro_key']; ?>&doc_id=<?php echo $v2['id']; ?>"><?php echo $v2['title']; ?></a></li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($has_permission): ?>
                    <li class="docs-node">
                        <div class="btn-group">
                            <a href="/project/add?pro_key=<?php echo $project_info['pro_key'] ?>&cate_id=<?php echo $v['id'] ?>" class="btn btn-default btn-xs">+ 新建文档</a>
                            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="/project/add?pro_key=<?php echo $project_info['pro_key'] ?>&cate_id=<?php echo $v['id'] ?>">新建API文档</a></li>
                                <li><a href="/markdown/add?pro_key=<?php echo $project_info['pro_key'] ?>&cate_id=<?php echo $v['id'] ?>">新建Markdown文档</a></li>
                            </ul>
                        </div>
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

        <div class="row">
            <div class="col-xs-9"><p class="text-left"><a href="javascript:void(0)" id="create-cate">新建分类</a></div>
            <div class="col-xs-3"><p class="text-right"><a href="javascript:void(0)"><i class="icon-cog" title="分类管理"></i></a></p></div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-xs-9">
        <?php if (!empty($doc)): ?>
        <div class="row">
            <div class="col-xs-8"><h4><?php echo $doc['title']; ?></h4></div>
            <div class="col-xs-4">
                <p class="text-right tools-bar">
                    <?php if ($has_permission): ?>
                    <a href="/project/edit?pro_key=<?php echo $project_info['pro_key']; ?>&doc_id=<?php echo $doc['id']; ?>" id="go-edit">&nbsp;&nbsp;编辑&nbsp;&nbsp;</a>
                    <?php endif; ?>
                    <i class="icon-info-sign" id="go-info"></i>
                    <?php if ($has_permission): ?>
                    <i class="icon-trash" id="go-del" title="删除"></i>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <p><small>最后修改 <?php echo date('Y-m-d H:i:s', $doc['update_time']); ?> By <?php echo $update_user; ?></small></p>

        <div class="doc">
            <p><strong><?php echo $request_types[$doc['method']]; ?> <?php echo $body_data_type[$doc['body_data_type']]; ?></strong></p>
            <p><strong>URL: </strong> <code><?php echo $doc['url']; ?></code></p>

            <?php if (!empty($doc['header'])): ?>
            <p><strong>请求Header参数说明</strong></p>
            <table class="table table-bordered">
                <tr class="active">
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
                <tr class="active">
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
            <pre><code class="lang-json"><?php echo $doc['request_example']; ?></code></pre>
            <?php endif; ?>

            <?php if (!empty($doc['response'])): ?>
            <p><strong>返回参数说明</strong></p>
            <table class="table table-bordered">
                <tr class="active">
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
                    <br/><pre><code class="lang-json"><?php echo $doc['response_success_example']; ?></code></pre>
                </div>
                <div role="tabpanel" class="tab-pane" id="exception">
                    <br/><pre><code class="lang-json"><?php echo $doc['response_fail_example']; ?></code></pre>
                </div>
            </div>
            <?php elseif (!empty($doc['response_success_example'])): ?>
            <p><strong>返回参数示例</strong></p>
            <pre><code class="lang-json"><?php echo $doc['response_success_example']; ?></code></pre>
            <?php elseif (!empty($doc['response_fail_example'])): ?>
            <p><strong>返回参数示例</strong></p>
            <pre><code class="lang-json"><?php echo $doc['response_fail_example']; ?></code></pre>
            <?php endif; ?>
        </div>
        <?php else: ?>
            <p class="text-center">暂无文档</p>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($_SESSION['uid']) and $project_info['uid'] != $_SESSION['uid']): ?>
<div id="quitProjectModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">退出项目</h4>
            </div>
            <div class="modal-body">
                <p>退出项目后，将不能浏览该项目内容。</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-block" id="quit-project">确定退出</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($has_permission): ?>
<div id="delDocModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">删除文档</h4>
            </div>
            <div class="modal-body">
                <p>确定删除该文档吗？</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-block" id="del-api-doc">确定删除</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>