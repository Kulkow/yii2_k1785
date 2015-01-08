<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Nomenclature */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Nomenclature',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nomenclatures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nomenclature-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
