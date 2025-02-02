<?php

namespace Templates;

use Core\Form;
use Core\Core;


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

        if(isset($_REQUEST['SAVE'])) {
            $arResult['SAVE_RESULT'] = $this->saveForm();
        }

        if($this->getCacheData()) {
            $this->setCacheData(); //Установили кешированную страницу
        }
        else {
            $this->getReviews(); //Ищем все отзывы пользователя о записи
            $this->getForm(); //Получаем форму и ее настройки
            $this->includeTemplate(); //Выводим на экран
        }

        
    }

    private function saveForm(): mixed {
        $settings = require($_SERVER['DOCUMENT_ROOT'] . '/core/.settings.php');
        $db = new Form($settings, $this->arParams['STARS']['LIST']);

        return $db->save();
    }

    private function getCacheData():bool {
        return false;
    }

    private function setCacheData(): bool {
        return false;
    }

    private function prepareClass(): void {
        require($_SERVER['DOCUMENT_ROOT'] . '/core/Form.php');
    }

    private function prepareParams() 
    {   
        
        $this->arParams = array_merge(
            require(__DIR__ . '/.parameters.php'), 
            $this->arParams
        );
    }

    private function addHeaderFiles($templateDir): void {
        $map =  './.map';
        $css = $templateDir . 'style.css';
        $script = $templateDir . 'script.js';

        if(file_exists($css)) {
            echo '<link rel="stylesheet" href="'.$css.'">';
            //Assets::getInstance->addExternalCSS($css);
            //Assets::setStyleFile($css);
        }

        if(file_exists($script)) {
            echo '<script src="'.$script.'"></script>';
        }
    }
 
    private function getReviews() {
        $settings = require($_SERVER['DOCUMENT_ROOT'] . '/core/.settings.php');
        $db = new Form($settings, $this->arParams['STARS']['LIST']);

        $arReviews = $db->getList('b_review', [
            'select' => ['*'],
            'filter' => ['ELEMENT_ID' => $this->arParams['ELEMENT_ID']]
        ]);

        $arUsersId = [];
        foreach($arReviews as $key => $arReview) {
            $arUsersId[] = $arReview['USER_ID'];
            $arReviews[$key]['MARKS'] = $db->getList('b_review_props', [
                'filter' => ['REVIEW_ID' => $arReview['ID']],
                'select' => ['MARK', 'CODE']
            ]);

            if(!empty($arReview['FILES'])) {
                $arFilesId = explode(',', $arReview['FILES']);
                
                $arReviews[$key]['FILE_LIST'] = [];
                
                foreach($arFilesId as $id) {
                    $file = $db->getList('b_files', [
                        'filter' => ['ID' => $id]
                    ])[0];

                    $file['SRC'] = str_replace('C:/OSPanel/home/s1', '', $file['FILE_PATH']);

                    $arReviews[$key]['FILE_LIST'][] = $file;
                }
            }

            // $arReviews[$key]['TEST'] = $db->getList('b_files', [
            //     'filter' => ['ID' => ' IN ('.$arReview['FILES'].')']
            // ])

            // WHERE ID = 1 AND ID = 2
            // WHERE ID IN (1,2,3,4)
        }

        $arUsersId = array_unique($arUsersId); // [1,1,1,12,12,3,5,7]

        $arUsers = $db->getList('b_users', [
            'select' => ['ID', 'LOGIN'],
        ]);

        $this->arResult['REVIEWS'] = $arReviews;
        $this->arResult['AUTHORS'] = $arUsers;
    }

    private function getForm():void {
        $this->arResult['FORM_STARS'] = $this->arParams['STARS']['LIST'];
    }

    private function includeTemplate(): void {
        $currentTempl = (isset($this->arParams['TEMPLATE']['VALUE'])) ? $this->arParams['TEMPLATE']['VALUE'] : $this->arParams['TEMPLATE']['DEFAULT'];
        $templateDir = __DIR__ . '/' . $currentTempl . '/';

        //Core::IncludeLocale(__DIR__, $currentTempl);

        // mb_language('ru');
        // echo mb_language();
        require($templateDir . '/lang/ru/template.php');

        $templateDir = str_replace('\\', '/', $templateDir);

        $this->addHeaderFiles($templateDir); //Подключение файлов стилей и скриптов

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
            $arResult = $this->arResult;
            $arParams = $this->arParams;
            require $templateFile;
        }
        else {
            echo 'Файл template.php шаблона ' . $currentTempl . ' не обнаружен.';
        }
    } 
}