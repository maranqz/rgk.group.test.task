<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\Nav;

?>

<?= Nav::widget([
    'options' => [
        'class' => 'nav-tabs',
        'style' => 'margin-bottom: 15px',
    ],
    'items' => [
        [
            'label' => Yii::t('user', 'Users'),
            'url' => ['/user/admin/index'],
        ],
        '<li>'.\yii\bootstrap\Modal::widget([
            'id' => 'adminCreate-modal',
            'toggleButton' => [
                'data-target' => '#adminCreate-modal',
                'label' => Yii::t('user', 'New user'),
                'tag' => 'a',
                'href' => \yii\helpers\Url::to(['/user/admin/create']),
            ],
            'size' => \yii\bootstrap\Modal::SIZE_LARGE,
            'clientEvents' => [
                'show.bs.modal' => new \yii\web\JsExpression("
                    function(e){
                        var target = e.target;
                        $('.modal-content', $(target)).load($(e.relatedTarget).attr('href'));
                    }
                "),
            ]
        ]).'</li>',
    ],
]) ?>
