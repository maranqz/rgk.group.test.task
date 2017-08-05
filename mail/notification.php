<?php

use yii\helpers\Html;

/**
 * @var string $model
 * @var int $item_id
 */
?>
New <?= $model ?>, see <?= Html::tag('a', 'here', [
    'href' => \yii\helpers\Url::to([$model . '/view', 'id' => $item_id], true)
]) ?>
