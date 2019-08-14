<?php
/**
 * Created by PhpStorm.
 * User: Zhang Li
 * Date: 2019/8/8
 * Time: 16:21
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
class Role extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%role}}';
    }
}