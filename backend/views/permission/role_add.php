<?php
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = $title;
$this->params['view_js'] = <<< EOF
<script type="text/javascript" src="/js/table.js"></script>
<script >
layui.use(["form"],function(){
        var e=layui.jquery,a=layui.form;
        e(".mos-role-qx").on("click",function(){
            var a=e(this).parents("fieldset").find("input");
            e(a).each(function(a,c){
                var i=e(c).next();i.hasClass("layui-form-checked")||i.click()
            })
        }),e(".mos-role-qbx").on("click",function(){
            var a=e(this).parents("fieldset").find("input");
            e(a).each(function(a,c){
                var i=e(c).next();i.hasClass("layui-form-checked")&&i.click()
            })
        }),a.on("checkbox(role_menu)",function(a){
            if(a.elem.checked){
                var c=e("input."+e(a.elem).data("input-class")+'[value="'+e(a.elem).data("pid")+'"]');
                c.next().hasClass("layui-form-checked")||c.next().click()
            }else{
                var i=e("input."+e(a.elem).data("input-class")+'[data-pid="'+a.value+'"]');
                e(i).each(function(a,c){e(c).next().hasClass("layui-form-checked")&&e(c).next().click()
                })
            }
        })
    });
</script>
EOF;
?>

<style type="text/css">
    .mos-role-menu-container .layui-form-checkbox{
        height:20px;line-height:20px
    }
    .mos-role-menu-container .layui-form-checkbox i{
        height:18px
    }
    .layui-elem-field legend{
        font-size: 14px;
    }
</style>

<form class="layui-form" action="<?php echo Html::encode(Url::to(['permission/role-add'])); ?>" onsubmit="return false;">
    <input type="hidden" name="_csrf-backend" value="<?= Yii::$app->request->csrfToken ?>">
    <div class="layui-tab mos-common-margin-top0 mos-common-margin-bottom0">
        <ul class="layui-tab-title">
            <li class="layui-this">角色信息</li>
            <li>菜单权限</li>
        </ul>
        <div class="layui-tab-content mos-common-padding0 mos-common-padding-top15">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">角色名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="RoleAddForm[name]" lay-verify="required" lay-vertype="tips" placeholder="请输入角色名" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-tab-item">
                <div class="layui-form-item">
                    <label class="layui-form-label mos-common-width100">分配菜单权限</label>
                    <div class="layui-input-block mos-common-margin-left135 mos-role-menu-container">

                        <?php foreach ($list as $v0){ ?>
                            <fieldset class="layui-elem-field">
                                <legend>
                                    <?=$v0['name']?>
                                    <span class="mos-common-font-size12">[ <a href="javascript:;" class="mos-role-qx">全选</a> / <a href="javascript:;" class="mos-role-qbx">全不选</a> ]</span>
                                </legend>
                                <div class="layui-field-box">
                                    <div style="margin-left: <?=$v0['level']*40?>px;">
                                        <input class="mos-role-menu-input" type="checkbox" name="RoleAddForm[menuid][<?=$v0['menuid']?>]" value="<?=$v0['menuid']?>" title="<?=$v0['name']?>" lay-filter="role_menu" data-pid="<?=$v0['parent_id']?>" data-input-class="mos-role-menu-input">
                                        <div class="layui-unselect layui-form-checkbox">
                                            <span><?=$v0['name']?></span>
                                            <i class="layui-icon layui-icon-ok"></i>
                                        </div>
                                    </div>

                                    <?php foreach ($v0['child'] as $v1){ ?>
                                        <div style="margin-left: <?=$v1['level']*40?>px;">
                                            <input class="mos-role-menu-input" type="checkbox" name="RoleAddForm[menuid][<?=$v1['menuid']?>]" value="<?=$v1['menuid']?>" title="<?=$v1['name']?>" lay-filter="role_menu" data-pid="<?=$v1['parent_id']?>" data-input-class="mos-role-menu-input">
                                            <div class="layui-unselect layui-form-checkbox">
                                                <span><?=$v1['name']?></span>
                                                <i class="layui-icon layui-icon-ok"></i>
                                            </div>
                                        </div>

                                        <?php foreach ($v1['child'] as $v2){ ?>
                                            <div style="margin-left: <?=$v2['level']*40?>px;">
                                                <input class="mos-role-menu-input" type="checkbox" name="RoleAddForm[menuid][<?=$v2['menuid']?>]" value="<?=$v2['menuid']?>" title="<?=$v2['name']?>" lay-filter="role_menu" data-pid="<?=$v2['parent_id']?>" data-input-class="mos-role-menu-input">
                                                <div class="layui-unselect layui-form-checkbox">
                                                    <span><?=$v2['name']?></span>
                                                    <i class="layui-icon layui-icon-ok"></i>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    <?php } ?>
                                </div>
                            </fieldset>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="mos-common-btn-form-submit" data-result="reload">确认提交</button>
        </div>
    </div>
</form>