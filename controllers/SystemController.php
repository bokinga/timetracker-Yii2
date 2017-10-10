<?php
/**
 * Created by PhpStorm.
 * User: zapleo
 * Date: 10.10.17
 * Time: 10:34
 */

namespace app\controllers;


use app\controllers\base\BaseController;
use app\models\User;
use yii\web\HttpException;

class SystemController extends BaseController
{

    /**
     *
     */
    public function actionIndex()
    {
        echo 'System Controller';
    }

    /**
     * @param bool $end
     * @return array|bool|mixed|string
     */
    public function actionGetDate($end = false)
    {

        $timeStart = \Yii::$app->request->get('timeStart');
        $timeEnd = \Yii::$app->request->get('timeEnd');

        $timeStart = is_null($timeStart) ? false :$timeStart;
        $timeEnd = is_null($timeEnd) ? false :$timeEnd;

        if (!$end) {

            $timeStart = \DateTime::createFromFormat('d/m/Y', $timeStart)->format('Y-m-d 00:00:00');

            return $timeStart;

        } else {

            if (!$timeEnd) {
                $timeEnd = \DateTime::createFromFormat('d/m/Y', $timeStart)->format('Y-m-d 23:59:00');
                //$timeEnd = date('Y-m-d', strtotime($timeEnd.'+1 day'));
            } else {
                $timeEnd = \DateTime::createFromFormat('d/m/Y', $timeEnd)->format('Y-m-d 23:59:00');
                //$timeEnd = date('Y-m-d', strtotime($timeEnd.'+1 day'));
            }

            return $timeEnd;

        }
    }

    /**
     * @return array
     */
    public function actionGetUsersList()
    {
        $user  = User::findOne(\Yii::$app->user->id);
        $data = [];
        if($user->isAdmin())
        {
            $users = User::find()->all();
        }
        else
            $users[] = $user;

        foreach ($users as $u)
        {
            $data[] = ['id'=> $u->id,'last_name'=>$u->last_name,'first_name'=>$u->first_name];
        }

        $this->formatJson();
        return $data;
    }

    /**
     * @param $id
     * @return array
     * @throws HttpException
     */
    public function actionGetUserInfo($id)
    {
        if($id == \Yii::$app->user->id || \Yii::$app->user->identity->isAdmin())
        {

            $info = User::findOne($id);
            if(is_null($info))
                throw new HttpException(404);
            $this->formatJson();
            return $info->toArray();
        }
        throw new HttpException(403);
    }


}