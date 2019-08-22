<?php
/**
 * Created by PhpStorm.
 * User: Zhang Li
 * Date: 2019/8/8
 * Time: 16:20
 */

namespace backend\models;

use common\models\Role;
class RoleExtends extends Role
{
    public static $is_system = [0 => '否', 1 => '是'];

    public static function roleList($not_in_ids = '')
    {
        if(empty($not_in_ids))
        {
            $role_list = self::find()->select('roleid,name')->all();
        }
        else
        {
            $role_list = self::find()->where(['<>', 'roleid', $not_in_ids])->select('roleid,name')->all();
        }
        return $role_list;
    }
}