<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Html;

use common\helpers\Common;

use backend\helpers\BackendHelpers;
use backend\models\AdminExtends;
use backend\models\UserExtends;
use backend\models\RoleExtends;

use backend\forms\Permission\AdminAddForm;
use backend\forms\Permission\AdminEditForm;
use backend\forms\Permission\RoleAddForm;
use backend\forms\Permission\RoleEditForm;
use backend\forms\Permission\RoleMoveForm;

class PermissionController extends MosController
{
    public function actionAdmin()
    {
        // 分页输出
        if(Yii::$app->request->isAjax)
        {
            // 搜索条件
            $where = [];
            $and_where = ['and'];
            $search = Yii::$app->request->get('search');
            if($search)
            {
                $search['userid'] && $where[AdminExtends::tableName() . '.userid'] = $search['userid'];
                $search['name'] && $where[AdminExtends::tableName() . '.name'] = $search['name'];
                is_numeric($search['disabled']) && $where[AdminExtends::tableName() . '.disabled'] = $search['disabled'];
                $search['username'] && $and_where[] = ['like', UserExtends::tableName() . '.username', $search['username'] . '%', false];
            }

            // 分页信息
            $page = Yii::$app->request->get('page');
            $limit = Yii::$app->request->get('limit');
            $offset = ($page - 1) * $limit;
            $count = AdminExtends::find()->joinWith('user')->where($where)->andWhere($and_where)->count();

            $admin_datas = AdminExtends::find()->joinWith('user')->where($where)->andWhere($and_where)->orderBy([AdminExtends::tableName() . '.created_at' => SORT_DESC, AdminExtends::tableName() . '.userid' => SORT_DESC])->offset($offset)->limit($limit)->asArray()->all();
            $role_list = RoleExtends::roleList();

            $echo_datas = [];
            foreach($admin_datas as $k => $admin_data)
            {
                $echo_data = [
                    'userid' => Html::encode($admin_data['userid']),
                    'username' => Html::encode($admin_data['user']['username']),
                    'name' => Html::encode($admin_data['name']),
                    'role_name' => Html::encode($role_list[$admin_data['roleid']-1]['name']),
                    'disabled' => Html::encode($admin_data['disabled']),
                    'disabled_desc' => Html::encode(AdminExtends::$disabled[$admin_data['disabled']]),
                ];

                $echo_datas[] = $echo_data;
            }

            return Common::echoJson(1000, '', $echo_datas, $count);
        }

        return $this->render('admin', [
            'title' => '管理员',
            'disabled_k_v' => AdminExtends::$disabled,
        ]);
    }

    public function actionAdminAdd()
    {
        $AdminAddForm = new AdminAddForm();
        $role_list = RoleExtends::roleList('1');
        if($AdminAddForm->load(Yii::$app->request->post()))
        {
            if($AdminAddForm->save())
            {
                return Common::echoJson(1000, '保存成功');
            } else {
                return Common::echoJson(1001, implode('<br>', $AdminAddForm->getFirstErrors()));
            }
        }

        return $this->render('admin_add', [
            'title' => '添加管理员',
            'disabled_k_v' => AdminExtends::$disabled,
            'sex_k_v' => AdminExtends::$sex,
            'role_list' => $role_list,
        ]);
    }

    public function actionAdminEdit()
    {
        $userid = Yii::$app->request->get('userid');

        $admin_cache_data = Yii::$app->mcache->getByKey('admin_datas', $userid);
        $role_list = RoleExtends::roleList('1');
        if(!$admin_cache_data)
        {
            return Common::echoJson(1001, '请选择操作用户');
        } elseif($userid == 1) {
            return Common::echoJson(1002, "禁止编辑管理员“{$admin_cache_data['user']['username']}”");
        }

        $AdminEditForm = new AdminEditForm();
        if($AdminEditForm->load(Yii::$app->request->post()))
        {
            if($AdminEditForm->save($userid))
            {
                return Common::echoJson(1000, '保存成功');
            } else {
                return Common::echoJson(1003, implode('<br>', $AdminEditForm->getFirstErrors()));
            }
        }

        return $this->render('admin_edit', [
            'title' => '编辑管理员',
            'admin_data' => $admin_cache_data,
            'disabled_k_v' => AdminExtends::$disabled,
            'sex_k_v' => AdminExtends::$sex,
            'role_list' => $role_list,
        ]);
    }

    /**
     * 删除管理员
     * @return string
     */
    public function actionAdminDel()
    {
        $userid = Yii::$app->request->get('userid');

        $admin_cache_data = Yii::$app->mcache->getByKey('admin_datas', $userid);
        if(!$admin_cache_data)
        {
            return Common::echoJson(1001, '请选择操作用户');
        } elseif($userid == 1) {
            return Common::echoJson(1002, "禁止删除管理员“{$admin_cache_data['user']['username']}”");
        }

        $Admin = AdminExtends::findOne($userid);
        if($Admin->deleteRelationAll())
        {
            return Common::echoJson(1000, '删除成功');
        } else {
            return Common::echoJson(1003, implode('<br>', $Admin->getFirstErrors()));
        }
    }

    /**
     * 角色管理
     * @return string
     */
    public function actionRole()
    {
        //分页输出
        if(Yii::$app->request->isAjax)
        {
            // 搜索条件
            $where = [];
            $and_where = ['and'];

            // 分页信息
            $page = Yii::$app->request->get('page');
            $limit = Yii::$app->request->get('limit');
            $offset = ($page - 1) * $limit;
            $count = RoleExtends::find()->where($where)->andWhere($and_where)->count();

            $admin_datas = RoleExtends::find()->where($where)->andWhere($and_where)->orderBy([RoleExtends::tableName() . '.roleid' => SORT_ASC])->offset($offset)->limit($limit)->asArray()->all();

            $echo_datas = [];
            foreach($admin_datas as $k => $admin_data)
            {
                $echo_data = [
                    'roleid' => Html::encode($admin_data['roleid']),
                    'name' => Html::encode($admin_data['name']),
                    'admin_nums' => Html::encode($admin_data['admin_nums']),
                    'is_system' => Html::encode($admin_data['is_system']),
                    'is_system_desc' => Html::encode(RoleExtends::$is_system[$admin_data['is_system']]),
                ];
                $echo_datas[] = $echo_data;
            }
            return Common::echoJson(1000, '', $echo_datas, $count);
        }
        return $this->render('role', [
            'title' => '角色',
        ]);
    }

    /**
     * 添加角色
     * @return string
     */
    public function actionRoleAdd()
    {
        $RoleAddForm = new RoleAddForm();
        if($RoleAddForm->load(Yii::$app->request->post()))
        {
            if($RoleAddForm->save())
            {
                return Common::echoJson(1000, '保存成功');
            } else {
                return Common::echoJson(1001, implode('<br>', $RoleAddForm->getFirstErrors()));
            }
        }

        $menu = BackendHelpers::getMenuLevelDatas();
        $list = BackendHelpers::demo($menu,0);

        return $this->render('role_add', [
            'title' => '添加角色',
            'list' => $list,
        ]);
    }

    /**
     * 角色修改
     * @return string
     */
    public function actionRoleEdit()
    {
        $roleid = Yii::$app->request->get('roleid');

        //修改
        $RoleEditForm = new RoleEditForm();
        if($RoleEditForm->load(Yii::$app->request->post()))
        {
            if($RoleEditForm->save())
            {
                return Common::echoJson(1000, '保存成功');
            } else {
                return Common::echoJson(1003, implode('<br>', $RoleEditForm->getFirstErrors()));
            }
        }

        $role_info = RoleExtends::find()->where(['=','roleid',$roleid])->one();
        $menu_arr = explode(',',$role_info['menu_ids']);

        $menu = BackendHelpers::getMenuLevelDatas();
        $list = BackendHelpers::demo($menu,0);

        return $this->render('role_edit', [
            'title' => '编辑角色',
            'role_info' => $role_info,
            'list' => $list,
            'menu_arr' => $menu_arr,
        ]);
    }

    /**
     * 删除角色
     * @return string
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionRoleDel()
    {
        $roleid = Yii::$app->request->get('roleid');

        $num = AdminExtends::find()->where(['=','roleid',$roleid])->count();
        if($num > 0)
        {
            return Common::echoJson(1003, '该角色下存在管理员，删除前请将管理员转移到其它角色');
        }

        $customer = RoleExtends::findOne($roleid);
        $res = $customer->delete();

        if($res)
        {
            return Common::echoJson(1, '删除角色成功');
        }
        else
        {
            return Common::echoJson(0, '删除角色失败');
        }
    }

    /**
     * 转移角色管理员
     */
    public function actionRoleMove()
    {
        $roleid = Yii::$app->request->get('roleid');
        $role_info = RoleExtends::find()->where(['=','roleid',$roleid])->one();
        $role_list = RoleExtends::roleList('1');

        //修改
        $RoleMoveForm = new RoleMoveForm();
        if($RoleMoveForm->load(Yii::$app->request->post()))
        {
            if($RoleMoveForm->save())
            {
                return Common::echoJson(1000, '保存成功');
            } else {
                return Common::echoJson(1003, implode('<br>', $RoleMoveForm->getFirstErrors()));
            }
        }

        return $this->render('role_move', [
            'title' => '转移角色管理员',
            'role_info' => $role_info,
            'role_list' => $role_list,
        ]);
    }
}