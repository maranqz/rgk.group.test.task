<?php

return [
    'adminEmail' => 'admin@example.com',
    'modalEventPjax' => function ($id) {
        return [
            'show.bs.modal' => new \yii\web\JsExpression("
                function(e){
                    var target = e.target;
                    $('.modal-content', $(target)).load($(e.relatedTarget).attr('href')/*, function(){
                        target.setAttribute('backHref', window.location.pathname + window.location.search);
                        target.setAttribute('beforeSend', function(xhr, options) {
                          console.log(xhr, options);
                        });                                        
                        $('#adminUpdate', target).on('pjax:beforeSend', target.getAttribute('beforeSend'));
                    }*/);
                }
            "),
            'hidden.bs.modal' => new \yii\web\JsExpression("
                function(e){
                    var target = e.target;
                    $.pjax.reload('#$id');
                }
            "),
        ];
    }
];
