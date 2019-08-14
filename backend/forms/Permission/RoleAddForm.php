<?php

namespace backend\forms\Permission;

use Yii;
use yii\base\Model;
use backend\models\RoleExtends;

class RoleAddForm extends Model
{
    public $name;
    public $menuid;

    public function rules()
    {
        return [
            [['name','menuid'],'required'],
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
            $role = new RoleExtends();
            $role->name = $this->name;
            $menu_ids = join(',',$this->menuid);
            $role->menu_ids = $menu_ids;
            $role->save();

            return !$this->hasErrors();
        }
        return false;
    }
}