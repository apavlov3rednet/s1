<?php

class Component {
    /**
     * Summary of arResult
     * @var array
     * 'REVIEWS' = [],
     * 'USERS' = [],
     * 'FORM' = []
     */
    public array $arResult;

    public array $arParams;


    public function __construct() {
        $this->prepareClass();  //Подготовка классов
        $this->prepareParams(); //Подготовка параметров компонента

        if($this->getCacheData()) {
            $this->setCacheData(); //Установили кешированную страницу
        }
        else {
            $this->getReviews(); //Ищем все отзывы пользователя о записи
            $this->getForm(); //Получаем форму и ее настройки
            $this->includeTemplate(); //Выводим на экран
        }
        
    }

    public function prepareParams() 
    {   
        $this->arParams = require('./.parameters.php');
    }
}