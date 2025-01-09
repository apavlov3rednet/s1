   <form enctype='multipart/form-data' class="form" action="" method="post">
        <div class="form-area">
            <h4 class="from-area-header">Ваш отзыв</h4>

            <input type="hidden" name="ELEMENT_ID" value="<?=$arParams['ELEMENT_ID']?>">

            <?php foreach($arResult['FORM_STARS'] as $key => $name):?>           
                <label for="<?=$key?>" class="stars">
                    <span><?=$name?></span>
                    <input type="radio" name="<?=$key?>" value="1">
                    <input type="radio" name="<?=$key?>" value="2">
                    <input type="radio" name="<?=$key?>" value="3">
                    <input type="radio" name="<?=$key?>" value="4">
                    <input type="radio" name="<?=$key?>" value="5">
                </label>
            <?php endforeach;?>

            <label for="PREFERENCES">
                <textarea name="PREFERENCES"></textarea>
                <span>Достоинства</span>
            </label>

            <label for="NEGATIVE">
                <textarea name="NEGATIVE"></textarea>
                <span>Недостатки</span>
            </label>

            <label for="COMMENT">
                <textarea required name="COMMENT"></textarea>
                <span>Комментарий</span>
            </label>
            <input type="hidden" name="NOT_EQUALE" value="N">
            <label for="NOT_EQUALE">
                <input type="checkbox" id="NOT_EQUALE" name="NOT_EQUALE" value="Y">
                <span>Товар или комплектация не соответствует описанию на сайте</span>
            </label>

            <label for="NOT_EQUALE_TEXT" class="hidden">
                <textarea name="NOT_EQUALE_TEXT"></textarea>
                <span>Опишите проблему</span>
            </label>

            <div class="file-upload">
                <input type="file" name="FILE" multiple="Y">
            </div>

            <input type="submit" name="SAVE" value="Сохранить"/>
        </div>

        <div class="form-status">
            <span>Заполните все поля, чтобы ваш отзыв был максимально полезен.</span>

            <p>Проставьте оценки</p>
            <ul class="checklist">
                <li>Проставьте оценки <span>+20%</span></li>
                <li>Опишите достоинства <span>+20%</span></li>
                <li>Опишите недостатки <span>+20%</span></li>
                <li>Добавьте комментарий <span>+20%</span></li>
                <li>Добавьте фото <span>+20%</span></li>
            </ul>
        </div>
    </form>
