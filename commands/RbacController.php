<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
/**
 * Инициализатор RBAC выполняется в консоли php yii rbac/init
 */
class RbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;

        $auth->removeAll(); //На всякий случай удаляем старые данные из БД...

        // Создаем роли
        $admin = $auth->createRole('admin');
        $security = $auth->createRole('security');
        $operator = $auth->createRole('operator');


        // запишем их в БД
        $auth->add($admin);
        $auth->add($security);
        $auth->add($operator);

        // Создаем разрешения.
        $adminTask = $auth->createPermission('adminTask');
        $adminTask->description = 'Задачи администратора';
        $securityTask = $auth->createPermission('securityTask');
        $securityTask->description = 'Задачи начальника охраны';
        $operatorTask = $auth->createPermission('operatorTask');
        $operatorTask->description = 'Задачи оператора';

        // Запишем эти разрешения в БД
        $auth->add($adminTask);
        $auth->add($securityTask);
        $auth->add($operatorTask);

        // Теперь добавим наследования.

        $auth->addChild($security,$operator);
        $auth->addChild($security,$securityTask);

        // админ наследует все роли. Он же админ, должен уметь всё! :D
        $auth->addChild($admin, $security);
        $auth->addChild($admin, $operator);

        // Еще админ имеет собственное разрешение - «Просмотр админки»
        $auth->addChild($admin, $adminTask);

        // Назначаем роль admin пользователю с ID 1
        $auth->assign($admin, 1);

        // Назначаем роль editor пользователю с ID 2
        //$auth->assign($market, 2);
    }
}

