<?php

use \yii\helpers\Url;

/**
 * @var string $model
 * @var int $item_id
 */
?>
New <?= $model ?>, see the link - <?= Url::to([$model . '/view', 'id' => $item_id], true) ?>
