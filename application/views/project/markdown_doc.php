<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-9">
        <h3 class="project-name" data-prokey="<?php echo $project_info['pro_key']; ?>"><?php echo $project_info['title']; ?>
            <small>文档数: <?php echo $api_nums; ?> | 团队成员: <?php echo $member_nums; ?> <?php if (isset($_SESSION['uid']) and $project_info['uid'] == $_SESSION['uid']): ?>| <a href="/projects/settings?pid=<?php echo $project_info['id']; ?>">设置</a><?php else: ?>| <a href="javascript:void(0);" class="quit-project">退出该项目</a><?php endif; ?></small>
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
        <p class="text-center"><a href="javascript:void(0)" id="create-cate">创建分类</a></p>
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
            <?php echo html_entity_decode($doc['html_text']); ?>
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