<?php

namespace backend\forms\Permission;

use Yii;
use yii\base\Model;
use backend\models\RoleExtends;

class RoleEditForm extends Model
{
    public $roleid;
    public $name;
    public $menuid;

    public function rules()
    {
        return [
            [['roleid','name','menuid'],'required'],
//            [['menuid'],'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '角色名',
            'menuid' => '权限',
        ];
    }

    public function save()
    {
        if($this->validate())
        {
            $role_info = RoleExtends::find()->where(['=', 'roleid', $this->roleid])->one();
            $role_info->name = $this->name;
            $menu_ids = join(',',$this->menuid);
            $role_info->menu_ids = $menu_ids;
            $role_info->save();

            return !$this->hasErrors();
        }
        return false;
    }
}