<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-9">
        <h3 class="project-name"><?php echo $project_info['title']; ?>
        <small>API数: 123 | 团队成员: 9 | <a href="#">设置</a></small>
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
                <span class="cate-title"><span class="icon-folder-close-alt"></span>&nbsp; <?php echo $v['title']; ?></span>
                <span class="icon-cog cate-icon" style="display:none"></span>
            </li>
            <li style="display:none;">
                <ul class="apis">
                    <li class="active">添加商户添加商户添加商户添加商户添加商户</li>
                    <li>编辑商户</li>
                    <li>
                        <a href="#" class="btn btn-default btn-xs">创建接口</a>
                    </li>
                </ul>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <p class="create-cate-input" style="display:none;">
            <input type="text" class="form-control input-sm" id="create-category">
            <input type="hidden" id="pid" value="<?php echo $project_info['id']; ?>">
        </p>
        <p class="text-center"><a href="javascript:void(0)" id="create-cate">创建分类</a></p>
    </div>
    <div class="col-xs-9">
        <div class="doc">
            <div class="row">
                <div class="col-xs-11"><h3>添加商户</h3></div>
                <div class="col-xs-1">
                    <?php if ($project_info['uid'] == $_SESSION['uid']): ?>
                    <button type="button" class="btn btn-lblue btn-xs edit-entrance">修改</button>
                    <?php endif; ?>
                </div>
            </div>
            <small>最后修改 2018-04-21 12:12:12 By Gunn</small>
            
            <p><strong>HTTP Post-raw</strong></p>
            <p><strong>URL: </strong> <code>https://ecapi.parkingwang.com/v4/shopAdd</code></p>

            <p><strong>请求参数说明</strong></p>
            <table class="table table-bordered">
                <tr>
                    <th>参数名称</th>
                    <th>类型</th>
                    <th>是否必传</th>
                    <th>默认值</th>
                    <th>参数说明</th>
                </tr>
                <tr>
                    <td>token</td>
                    <td>string</td>
                    <td>是</td>
                    <td>-</td>
                    <td>访问令牌</td>
                </tr>
            </table>

            <p><strong>请求参数示例</strong></p>
            <pre>
{
    "a": 1,
    "b": 2
}</pre>

            <p><strong>返回参数说明</strong></p>
            <table class="table table-bordered">
                <tr>
                    <th>参数名称</th>
                    <th>类型</th>
                    <th>参数说明</th>
                </tr>
                <tr>
                    <td>token</td>
                    <td>string</td>
                    <td>访问令牌</td>
                </tr>
            </table>

            <p><strong>返回参数示例</strong></p>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#nomal" aria-controls="nomal" role="tab" data-toggle="tab">正常示例</a></li>
                <li role="presentation"><a href="#exception" aria-controls="exception" role="tab" data-toggle="tab">异常示例</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="nomal">
                    <br/><pre>
{
    "a": 1,
    "b": 2
}</pre>
                </div>
                <div role="tabpanel" class="tab-pane" id="exception">
                    <br/><pre>
{
    "a": 1,
    "b": 2
}</pre>
                </div>
            </div>
        </div>
    </div>
</div>