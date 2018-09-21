<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<ol class="breadcrumb">
    <li><a href="/projects">我的项目</a></li>
    <li><a href="/project?pro_key=<?php echo $project_info['pro_key']; ?>"><?php echo $project_info['title']; ?></a></li>
    <li class="active">创建接口</li>
</ol>

<div class="row">
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
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
                                <p class="body-data-type">
                                    <label class="radio-inline">
                                        <input type="radio" name="body_data_type" value="0" checked> none
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="body_data_type" value="1"> form-data
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="body_data_type" value="2"> x-www-form-urlencoded
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="body_data_type" value="3"> raw
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="body_data_type" value="4"> binary
                                    </label>
                                </p>
                                <table class="table table-bordered" id="body-params-table">
                                    <tr class="ui-state-default">
                                        <th></th>
                                        <th>参数名称</th>
                                        <th>类型</th>
                                        <th>必传</th>
                                        <th>默认值</th>
                                        <th>参数说明</th>
                                    </tr>
                                    <tr class="ui-state-default">
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
                                <table class="table table-bordered" id="header-params-table">
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
                        <table class="table table-bordered" id="return-params-table">
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
    <div class="col-xs-2"></div>
</div>