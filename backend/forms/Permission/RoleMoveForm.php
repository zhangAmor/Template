<?php

namespace backend\forms\Permission;

use Yii;
use yii\base\Model;
use backend\models\RoleExtends;
use backend\models\AdminExtends;

class RoleMoveForm extends Model
{
    public $roleid;
    public $newroleid;

    public function rules()
    {
        return [
            [['roleid', 'newroleid'],'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'roleid' => '角色',
            'newroleid' => '新角色',
        ];
    }

    public function save()
    {
        if($this->validate())
        {
//            $res = AdminExtends::find()->where(['=', 'roleid', $this->roleid])->all();
//            $res->roleid = $this->newroleid;
//            $res->save();

            AdminExtends::updateAll(['roleid' => $this->newroleid], ['roleid'=> $this->roleid]);
            return !$this->hasErrors();
        }
        return false;
    }
}