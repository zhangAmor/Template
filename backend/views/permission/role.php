<?php
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = $title;
$this->params['view_js'] = <<< EOF
<script type="text/javascript" src="/js/table.js"></script>
EOF;
?>

<!--<form class="layui-form layui-form-pane" onsubmit="return false;">
    <div class="layui-form-item mos-common-margin-bottom0">
        <div class="layui-input-block mos-common-margin-left0">
            <button class="layui-btn mos-common-btn-layer-open" data-title="添加角色" data-url="<?php /*echo Html::encode(Url::to(['permission/role-add'])); */?>">添加角色</button><button class="layui-btn layui-btn-primary mos-common-btn-table-refresh" data-tableid="mos-table-role" data-page="current" title="刷新"><i class="layui-icon layui-icon-refresh-3"></i></button>
        </div>
    </div>
</form>-->

<div>
    <div class="mos-common-float-left">
        <button class="layui-btn mos-common-btn-layer-open" data-title="添加角色" data-url="<?php echo Html::encode(Url::to(['permission/role-add'])); ?>">添加角色</button><button class="layui-btn layui-btn-primary mos-common-btn-table-refresh" data-tableid="mos-table-role" title="刷新"><i class="layui-icon layui-icon-refresh-3"></i></button>
    </div>
    <div class="layui-clear"></div>
</div>

<!-- 数据列表 -->
<table id="mos-table-role" lay-filter="mos-table" data-url="<?php echo Html::encode(Url::to(['permission/role'])); ?>"></table>

<!-- 是否系统默认角色处理 -->
<script type="text/html" id="mos-table-role-col-is-system">
    {{# if(d.is_system === '1'){ }}
    <span style="color: #5FB878;">{{ d.is_system_desc }}</span>
    {{# } else if(d.is_system === '0') { }}
    <span style="color: #FF5722;">{{ d.is_system_desc }}</span>
    {{# } }}
</script>

<!-- 操作按钮 -->
<script type="text/html" id="mos-table-bar">
    {{# if(d.roleid !== '1'){ }}<a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="mos-common-btn-layer-open" data-title="编辑角色" data-url="<?php echo Html::encode(Url::to(['permission/role-edit'])); ?>" data-parameters="roleid={{ d.roleid }}" title="编辑"><i class="layui-icon layui-icon-edit"></i></a>{{# if(d.is_system !== '1'){ }}<a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="mos-common-btn-layer-confirm" data-title="确定删除角色 ID = {{ d.roleid }} 的记录吗？" data-url="<?php echo Html::encode(Url::to(['permission/role-del'])); ?>" data-parameters="roleid={{ d.roleid }}" data-result="remove" title="删除"><i class="layui-icon layui-icon-delete"></i></a>{{# } }}<a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="mos-common-btn-layer-open" data-title="转移角色管理员" data-url="<?php echo Html::encode(Url::to(['permission/role-move'])); ?>" data-parameters="roleid={{ d.roleid }}">转移角色管理员</a>{{# } }}
</script>