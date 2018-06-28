<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use app\modules\admin\models\Eventlog;
use yii\data\ActiveDataProvider;
use Yii;

class EventsController extends Controller
{
    public $layout = '@app/views/layouts/main.php';

    public function actionIndex()
    {
        $query = Eventlog::find()->where(['is_read'=>0]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($type){
        $query = Eventlog::find()->where(['type'=>$type]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'type' => $type,
        ]);
    }

    public function actionClearLog(){
        if(Yii::$app->user->can('admin')) {
            if (\Yii::$app->request->isAjax) {
                $query=Yii::$app->db->createCommand("delete from eventlog where type != 'error' and is_read = 0");
                $logs = $query->execute();
                if($logs)
                    return 'Журнал событий очищен';
            }
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    /**
     * Deletes an existing Visitor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->can('admin')) {
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        }
        else{
            throw new HttpException(404 ,'Доступ запрещен');
        }
    }

    /**
     * Finds the Visitor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Visitor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Eventlog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
