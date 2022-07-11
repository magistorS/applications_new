<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>
    <header>
        <div class="menu">
            <a href="<?= Yii::$app->homeUrl ?>"><img src="/favicon.ico" alt="" class="logo"><?= Yii::$app->name ?></a>
            <?php
                echo Html::a('Регистрация',Url::toRoute(['site/signup']), $options = ['class'=>'font-style', 'style'=>'color:white']);
                echo Html::a('Вход',Url::toRoute(['site/signin']), $options = ['class'=>'font-style', 'style'=>'color:white']);
                echo Html::a('Новости',Url::toRoute(['site/news']), $options = ['class'=>'font-style', 'style'=>'color:white']);
                echo Html::a('Выход',Url::toRoute(['site/signout']), $options = ['class'=>'font-style', 'style'=>'color:white']);
                echo Html::a('Профиль',Url::toRoute(['user/index']), $options = ['class'=>'font-style', 'style'=>'color:white']);
            ?>
        
        </div>
    </header>
    <style>
        .menu{
            background: rgb(10,3,128);
            background: linear-gradient(90deg, rgba(10,3,128,1) 0%, rgba(121,9,89,1) 51%, rgba(0,212,255,1) 100%);
            color: white;
        }
       
    </style>

    <main>
        <?= $content ?>
    </main>
    <footer>
    </footer>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>