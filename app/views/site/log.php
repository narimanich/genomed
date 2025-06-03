<!-- views/url-log/index.php -->
<?php
// views/url-log/index.php

use kartik\grid\GridView;
use yii\helpers\Html;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'url.original_url',
            'label' => 'Оригинальная ссылка',
            'value' => function ($model) {
                return $model->url->original_url;
            }
        ],

        [
            'attribute' => 'url.short_code',
            'label' => 'Короткий код',
            'value' => function ($model) {
                return $model->url->short_code;
            },
            'headerOptions' => ['style' => 'width:120px']
        ],

        [
            'label' => 'Всего переходов',
            'value' => function ($model) {
                return $model->url->clicks; // Поле clicks из модели Url
            },
            'headerOptions' => ['style' => 'width:120px']
        ],

        'ip',
        [
            'attribute' => 'visited_at',
            'format' => 'datetime',
            'filterType' => \kartik\grid\GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'yyyy-mm-dd', 'autoclose' => true]
            ]
        ],
    ],
    'toolbar' => [
        '{export}',
        '{toggleData}'
    ],
    'pjax' => true,
    'striped' => true,
    'condensed' => true,
    'responsive' => true,
    'panel' => [
        'type' => 'primary',
        'heading' => '<i class="fas fa-history"></i> Статистика переходов'
    ],
]);