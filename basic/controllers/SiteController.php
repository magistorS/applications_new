<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\LoginForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    //Главная страница
    public function actionIndex()
    {
        return $this->render('index');
    }
    // news public
    public function actionNews()
    {
        return $this->render('../news/index');
    }
    //Регистрация
    public function actionSignup()
    {
        return $this->render('signup');
    }

    //Вход
    public function actionSignin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('signin', ['model' => $model]);
    }

    //Выход
    public function actionSignout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
