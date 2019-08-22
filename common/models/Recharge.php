<?php
/**
 * Created by PhpStorm.
 * User: Zhang Li
 * Date: 2019/8/22
 * Time: 14:33
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
class Recharge extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%recharge}}';
    }
}