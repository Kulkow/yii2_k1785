<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Nomenclature */

$this->title = 'Create Nomenclature';
$this->params['breadcrumbs'][] = ['label' => 'Nomenclatures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nomenclature-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'category' => $category
    ]) ?>

</div>
