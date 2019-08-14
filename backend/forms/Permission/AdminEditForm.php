<?php

namespace backend\forms\Permission;

use Yii;
use yii\base\Model;

use common\helpers\Common;
use common\helpers\Validator;

use backend\models\AdminExtends;
use backend\models\UserExtends;

class AdminEditForm extends Model
{
    public $password;
    public $name;
    public $sex;
    public $disabled;
    public $roleid;

    public function rules()
    {
        return [
            [['name', 'roleid'], 'required'],
            [['sex', 'disabled'], 'integer'],
            [['name'], 'string', 'max' => 20],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => '密码',
            'name' => '姓名',
            'sex' => '性别',
            'disabled' => '状态',
            'roleid' => '角色',
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if(!$this->hasErrors())
        {
            if(!Validator::password($this->password))
            {
                $this->addError($attribute, Validator::$error);
            }
        }
    }

    public function save($userid)
    {
        if($this->validate())
        {
            $transaction = Yii::$app->db->beginTransaction();
            try
            {
                $Admin = AdminExtends::findOne($userid);
                $Admin->name = $this->name;
                $Admin->sex = $this->sex;
                $Admin->disabled = $this->disabled;
                $Admin->roleid = $this->roleid;

                if($Admin->save())
                {
                    if($this->password)
                    {
                        $username = UserExtends::findOne($Admin->userid)->getAttribute('username');
                        $sub_user_table_name = Common::subTable($username);
                        $sub_user_data = Yii::$app->db->createCommand("SELECT * FROM `{$sub_user_table_name}` WHERE `userid` = '{$Admin->userid}'")->queryOne();
                        $password = Common::createPassword($this->password, $sub_user_data['salt']);
                        Yii::$app->db->createCommand()->update($sub_user_table_name, ['password' => $password], 'userid = ' . $Admin->userid)->execute();
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $this->addError('password', '保存失败');
                $transaction->rollBack();
            } catch (\Throwable $e) {
                $this->addError('password', '保存失败');
                $transaction->rollBack();
            }

            return !$this->hasErrors();
        }
        return false;
    }
}