<?php

namespace backend\controllers;

use Yii;

use common\helpers\Common;

use backend\models\AdminExtends;

use backend\forms\My\InfoForm;
use backend\forms\My\PwdForm;
use common\models\Recharge;
class MyController extends MosController
{
    public function actionInfo()
    {
        $InfoForm = new InfoForm();
        if($InfoForm->load(Yii::$app->request->post()))
        {
            if($InfoForm->save())
            {
                return Common::echoJson(1000, '保存成功');
            } else {
                return Common::echoJson(1001, implode('<br>', $InfoForm->getFirstErrors()));
            }
        }

        $admin_data = Yii::$app->mcache->getByKey('admin_datas', Yii::$app->user->id);

        return $this->render('info', [
            'title' => '个人资料',
            'admin_data' => $admin_data,
            'sex_k_v' => AdminExtends::$sex,
        ]);
    }

    public function actionPwd()
    {
        $PwdForm = new PwdForm();
        if($PwdForm->load(Yii::$app->request->post()))
        {
            if($PwdForm->save())
            {
                return Common::echoJson(1000, '保存成功');
            } else {
                return Common::echoJson(1001, implode('<br>', $PwdForm->getFirstErrors()));
            }
        }

        return $this->render('pwd', [
            'title' => '修改密码'
        ]);
    }

    public function actionRecharge()
    {
        if(Yii::$app->request->isAjax)
        {
            $where = [];
            $search = Yii::$app->request->get('search');
            if($search)
            {
                var_dump($search);die;
            }

            $page = Yii::$app->request->get('page');
            $limit = Yii::$app->request->get('limit');
            $offset = ($page - 1) * $limit;
            $count = Recharge::find()->where($where)->count();
            $data =  Recharge::find()->where($where)->orderBy(['id' => SORT_DESC])->offset($offset)->limit($limit)->asArray()->all();
            return Common::echoJson(1000, '', $data, $count);
        }
        return $this->render('recharge', [
            'title' => '充值记录'
        ]);
    }
}