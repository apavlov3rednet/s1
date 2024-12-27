<?php

$arParams = [
    'TEMPLATE' => [
        'TYPE' => 'LIST',
        'REQUIRED' => true,
        'TITLE'=> 'Шаблон',
        'LIST' => [
            '.default' => 'По умолчанию',
            'test' => 'test'
        ],
        'DEFAULT' => '.default'
    ]
    'ELEMENT_ID' => [
        'TYPE' => 'INTEGER',
        'REQUIRED' => true,
        'TITLE' => 'ID элемента',
        'VALUE' => ''
    ],
    'STARS' => [
        'TYPE' => 'LIST',
        'TITLE' => 'Рейтинги',
        'LIST' => [
            'QUOLITY' => 'Качество', 
            'COMFORT' => 'Удобство', 
            'BEAUTY' => 'Красота'
        ]
    ]
];