<?php

use Core\Form;
use Core\Dbase;

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


    public function __construct($params = []) {
        $this->arParams = $params;

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

    private function prepareClass(): void {
        require($_SERVER['DOCUMENT_ROOT'] . '/core/Form.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/core/Dbase.php');
    }

    private function prepareParams() 
    {   
        $this->arParams = array_merge(
            require(__DIR__ . '/.parameters.php'), 
            $this->arParams
        );
    }

    private function getReviews() {
        $settings = require($_SERVER['DOCUMENT_ROOT'] . '/core/.settings.php');
        $db = new Dbase($installFile);

        $arReviews = $db->getList('b_review', [
            'select' => ['*'],
            'filter' => ['ELEMENT_ID' => $this->arParams['ELEMENT_ID']]
        ]);

        $arUsersId = [];
        foreach($arReviews as $key => $arReview) {
            $arUsersId[] = $arReview['USER_ID']; 
        }

        $arUsersId = array_unique($arUsersId);

        $arUsers = $db->getList('b_users', [
            'select' => ['ID', 'LOGIN'],
            'filter' => ['ID' => $arUsersId]
        ]);

        $this->arResult['REVIEWS'] = $arReviews;
        $this->arResult['AUTHORS'] = $arUsers;
    }

    private function getForm():void {
        $this->arResult['FORM_STARS'] = $this->arParams['STARS']['VALUE'];
    }

    private function includeTemplate(): void {
        $currentTempl = (isset($this->arParams['TEMPLATE']['VALUE'])) ? $this->arParams['TEMPLATE']['VALUE'] : $this->arParams['TEMPLATE']['DEFAULT'];
        $templateDir = __DIR__ . '/' . $currentTempl . '/';

        //Мутатор данных, при необходимости изменения результирующего массива
        $mutator = $templateDir . 'mutator.php';
        if(file_exists($mutator)) {
            require $mutator;
        }

        //Подключаем дополнительную логику и внешние скрипты
        $componentPrologue = $templateDir . 'component_prologue.php';
        if(file_exists($componentPrologue)) {
            require $componentPrologue;
        }

        $templateFile = $templateDir . 'template.php';
        if(file_exists($templateFile)) {
            require $templateFile;
        }
        else {
            echo 'Файл template.php шаблона ' . $currentTempl . ' не обнаружен.';
        }
    } 
}