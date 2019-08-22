<?php
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = $title;
$this->params['view_js'] = <<< EOF

EOF;
?>

<form class="layui-form" action="<?php echo Html::encode(Url::to(['permission/role-move'])); ?>" onsubmit="return false;">
    <input type="hidden" name="<?php echo Html::encode(Yii::$app->request->csrfParam); ?>" value="<?php echo Html::encode(Yii::$app->request->getCsrfToken()); ?>">
    <input type="hidden" name="RoleMoveForm[roleid]" value="<?=$role_info->roleid?>">
    <div class="layui-form-item">
        <label class="layui-form-label">用户 ID</label>
        <div class="layui-form-mid layui-word-aux"><?=$role_info->roleid?></div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">角色名</label>
        <div class="layui-form-mid layui-word-aux"><?=$role_info->name?></div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">管理员数</label>
        <div class="layui-form-mid layui-word-aux"><?=$role_info->admin_nums?></div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">转移角色管理员到</label>
        <div class="layui-input-inline">
            <select name="RoleMoveForm[newroleid]">
                <option value="">请选择角色</option>
                <?php foreach($role_list as $v): ?>
                    <option value="<?php echo Html::encode($v['roleid']); ?>" <?php if($v['roleid'] == $role_info['roleid']){echo 'disabled';}?>><?php echo Html::encode($v['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="mos-common-btn-form-submit" data-result="reload">确认提交</button>
        </div>
    </div>
</form>