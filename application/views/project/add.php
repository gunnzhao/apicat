<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-3">
        <h3>ECP <a href="#"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a></h3>
        <ul class="api-cate">
            <li class="cate-node">
                <span class="cate-title">商户管理</span>
                <span class="glyphicon glyphicon-menu-down cate-icon"></span>
            </li>
            <li style="display:none;">
                <ul class="apis">
                    <li class="active">添加商户</li>
                    <li>编辑商户</li>
                </ul>
            </li>
            <li class="cate-node">
                <span class="cate-title">商户管理</span>
                <span class="glyphicon glyphicon-menu-down cate-icon"></span>
            </li>
            <li class="cate-node">
                <span class="cate-title">商户管理</span>
                <span class="glyphicon glyphicon-menu-down cate-icon"></span>
            </li>
            <li class="cate-node">
                <span class="cate-title">商户管理</span>
                <span class="glyphicon glyphicon-menu-down cate-icon"></span>
            </li>
            <li class="cate-node">
                <span class="cate-title">商户管理</span>
                <span class="glyphicon glyphicon-menu-down cate-icon"></span>
            </li>
        </ul>
        <p class="text-center"><a href="#">创建分类</a></p>
    </div>

    <div class="col-xs-9">
        <div class="create-doc">
            <h3>创建接口</h3>
            <form>
                <div class="row row-form">
                    <div class="col-xs-6">
                        <input type="text" class="form-control" placeholder="接口名称">
                    </div>
                </div>
                <div class="row row-form">
                    <div class="col-xs-6">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">GET <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">GET</a></li>
                                    <li><a href="#">POST</a></li>
                                    <li><a href="#">PUT</a></li>
                                    <li><a href="#">PATCH</a></li>
                                    <li><a href="#">DELETE</a></li>
                                    <li><a href="#">OPTIONS</a></li>
                                </ul>
                            </div>
                            <input type="text" class="form-control" placeholder="URL">
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
                                            <input type="text" name="body_names">
                                        </td>
                                        <td class="field-type">
                                            <select>
                                                <option value="">string</option>
                                                <option value="">int</option>
                                                <option value="">array</option>
                                            </select>
                                        </td>
                                        <td class="field-transport"><input type="checkbox"></td>
                                        <td class="field-default">
                                            <input type="text">
                                        </td>
                                        <td class="field-description">
                                            <input type="text">
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
                                        <td class="field-cancel"><a href="#">x</a></td>
                                        <td class="field-name">
                                            <input type="text">
                                        </td>
                                        <td class="field-type">
                                            <select>
                                                <option value="">string</option>
                                                <option value="">int</option>
                                                <option value="">array</option>
                                            </select>
                                        </td>
                                        <td class="field-transport"><input type="checkbox"></td>
                                        <td class="field-default">
                                            <input type="text">
                                        </td>
                                        <td class="field-description">
                                            <input type="text">
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
                        <textarea id="request_example" class="form-control" rows="14"></textarea>
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
                                <td class="field-cancel"><a href="#">x</a></td>
                                <td class="return-field-name">
                                    <input type="text">
                                </td>
                                <td class="return-field-type">
                                    <select>
                                        <option value="">string</option>
                                        <option value="">int</option>
                                        <option value="">array</option>
                                    </select>
                                </td>
                                <td class="return-field-description">
                                    <input type="text">
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
                                <textarea id="return_success" class="form-control" rows="14" style="margin-top: 8px"></textarea>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="fail">
                                <textarea id="return_fail" class="form-control" rows="14" style="margin-top: 8px"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>