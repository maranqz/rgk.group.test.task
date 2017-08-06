<?php

namespace app\assets;

use \yii\web\AssetBundle;

class ToastrAsset extends AssetBundle
{
    /** @var string $sourcePath */
    public $sourcePath = '@bower/toastr';
    /** @var array $css */
    public $css = [
        'toastr.min.css'
    ];
    /** @var array $js */
    public $js = [
        'toastr.min.js'
    ];
    /** @var array $depends */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}