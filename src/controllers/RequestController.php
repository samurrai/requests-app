<?php

namespace app\controllers;

use app\models\Manager;
use Yii;
use app\models\Request;
use app\models\RequestSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RequestController extends Controller
{
    public function actionIndex($manager_id = null)
    {
        $searchModel = new RequestSearch();
        if($manager_id){
            $query = Request::find()->where(['manager_id' => $manager_id]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                    'defaultOrder' => ['id' => SORT_DESC],
                ]
            ]);
        }
        else{
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Request();

        if ($model->load(Yii::$app->request->post())) {
            $copy = Request::getCopy($model);
            if($copy){
                $manager = Manager::findOne($copy->manager_id);
                if($manager && $manager->is_works){
                    $model->manager_id = $manager->id;
                }
                else{
                    $manager_id = Manager::getFreeManagerId();
                    $model->manager_id = $manager_id;
                }
            }
            else{
                $manager_id = Manager::getFreeManagerId();
                $model->manager_id = $manager_id;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Request::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
