<?php

namespace backend\forms\Permission;

use backend\models\RoleExtends;
use Yii;
use yii\base\Model;

use common\helpers\Common;
use common\helpers\Validator;

use backend\models\UserExtends;
use backend\models\AdminExtends;

class AdminAddForm extends Model
{
    public $username;
    public $password;
    public $name;
    public $sex;
    public $disabled;
    public $roleid;

    public function rules()
    {
        return [
            [['username', 'password', 'name', 'roleid'], 'required'],
            [['sex', 'disabled'], 'integer'],
            [['name'], 'string', 'max' => 20],
            ['username', 'validateUsername'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'name' => '姓名',
            'sex' => '性别',
            'disabled' => '状态',
            'roleid' => '角色',
        ];
    }

    public function validateUsername($attribute, $params)
    {
        if(!$this->hasErrors())
        {
            if(Validator::username($this->username))
            {
                $user = UserExtends::findOne(['username' => strtolower($this->username)]);
                if($user)
                {
                    $this->addError($attribute, '用户名已存在');
                }
            } else {
                $this->addError($attribute, Validator::$error);
            }
        }
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

    public function save()
    {
        if($this->validate())
        {
            $transaction = Yii::$app->db->beginTransaction();
            try
            {
                $User = new UserExtends();
                $data['UserExtends'] = [
                    'username' => strtolower($this->username),
                ];
                if($User->load($data) && $User->save())
                {
                    $sub_user_table_name = Common::subTable($User->username);
                    $salt = Common::randChars(6);
                    $password = Common::createPassword($this->password, $salt);
                    $sub_user_data = ['userid' => $User->userid, 'username' => $User->username, 'password' => $password, 'salt' => $salt];
                    Yii::$app->db->createCommand()->insert($sub_user_table_name, $sub_user_data)->execute();

                    $Admin = new AdminExtends();
                    $data['AdminExtends'] = [
                        'userid'   => $User->userid,
                        'roleid'   => $this->roleid,
                        'name'     => $this->name,
                        'sex'      => $this->sex,
                        'disabled' => $this->disabled,
                    ];
                    $Admin->load($data) && $Admin->save();

//                    $Role = new RoleExtends();
//                    $role_info = $Role::find()->where(['=', 'roleid', $this->roleid])->one();
//                    $role_info->admin_nums = $role_info->admin_nums + 1;
//                    $role_info->save();

                } else {
                    $this->addError('username', '保存失败');
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $this->addError('username', '保存失败');
                $transaction->rollBack();
            } catch (\Throwable $e) {
                $this->addError('username', '保存失败');
                $transaction->rollBack();
            }

            return !$this->hasErrors();
        }
        return false;
    }
}