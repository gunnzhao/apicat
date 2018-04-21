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
        <div class="doc">
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
            </form>
        </div>
    </div>
</div>