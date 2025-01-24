   <form enctype='multipart/form-data' class="form" action="" method="post" id="voteForm">
        <div class="form-area">
            <h4 class="from-area-header">Ваш отзыв</h4>

            <input type="hidden" name="ELEMENT_ID" value="<?=$arParams['ELEMENT_ID']?>">
            <input type="hidden" name="USER_ID" value='1'/>

            <?php foreach($arResult['FORM_STARS'] as $key => $name):?>           
                <label for="<?=$key?>" class="stars" id="setStarGroup<?=$key?>">
                    <span><?=$name?></span>
                    <input type="radio" name="<?=$key?>" value="1">
                    <input type="radio" name="<?=$key?>" value="2">
                    <input type="radio" name="<?=$key?>" value="3">
                    <input type="radio" name="<?=$key?>" value="4">
                    <input type="radio" name="<?=$key?>" value="5">
                    <span class="setStar-<?=$key?>"></span>
                </label>

                <script>
                            $(".setStar-<?=$key?>").starRating({
                                initialRating: 0,
                                strokeColor: '#894A00',
                                strokeWidth: 10,
                                starSize: 15,
                                useFullStars: true,
                                callback: function(currentRating) {
                                    let radio = document
                                        .getElementById('setStarGroup<?=$key?>')
                                        .querySelector('[value="' + currentRating + '"]');
                                    
                                    radio.checked = true;
                                    document
                                        .getElementById('checkList')
                                        .querySelector('li')
                                        .classList.add('active');
                                }
                            });
                </script>
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

            <label id="NOT_EQUALE_TEXT" for="NOT_EQUALE_TEXT" class="hidden">
                <textarea name="NOT_EQUALE_TEXT"></textarea>
                <span>Опишите проблему</span>
            </label>

            <div class="file-upload">
                <input type="file" name="FILE[]" multiple="Y">
            </div>

            <input type="submit" name="SAVE" value="Сохранить"/>
        </div>

        <div class="form-status">
            <span>Заполните все поля, чтобы ваш отзыв был максимально полезен.</span>

            <p>Проставьте оценки</p>
            <ul class="checklist" id="checkList">
                <li data-name="marks">Проставьте оценки <span>+20%</span></li>
                <li data-name="PREFERENCES">Опишите достоинства <span>+20%</span></li>
                <li data-name="NEGATIVE">Опишите недостатки <span>+20%</span></li>
                <li data-name="COMMENT">Добавьте комментарий <span>+20%</span></li>
                <li data-name="files">Добавьте фото <span>+20%</span></li>
            </ul>

            <div class="progressbar"></div>
        </div>
    </form>

    <div class="reviews">
        <?php foreach($arResult['REVIEWS'] as $arReview):?>
            <div class="item">
                <div class="stars">
                    <?php foreach($arReview['MARKS'] as $mark):?>
                        <?=$MESS[$mark['CODE']]?>: <span class="star star-<?=$mark['CODE']?>"></span> &nbsp;
                        <script>
                            $(".star-<?=$mark['CODE']?>").starRating({
                                initialRating: <?=$mark['MARK']?>,
                                strokeColor: '#894A00',
                                strokeWidth: 10,
                                starSize: 15,
                                readOnly: true
                            });
                        </script>
                    <?php endforeach;?>
                </div>

                <?php if(!empty($arReview['PREFERENCES'])):?>
                <div class="text">
                    <h3>Достоинства</h3>
                    <div><?=$arReview['PREFERENCES']?></div>
                </div>
                <?php endif;?>

                <?php if(!empty($arReview['NEGATIVE'])):?>
                <div class="text">
                    <h3>Недостатки</h3>
                    <div><?=$arReview['NEGATIVE']?></div>
                </div>
                <?php endif;?>

                <?php if(!empty($arReview['COMMENT'])):?>
                <div class="text">
                    <h3>Комментарий</h3>
                    <div><?=$arReview['COMMENT']?></div>
                </div>
                <?php endif;?>

                <?php if(!empty($arReview['NOT_EQUALE_TEXT'])):?>
                <div class="text">
                    <h3>Чего не хватало в комплекте</h3>
                    <div><?=$arReview['NOT_EQUALE_TEXT']?></div>
                </div>
                <?php endif;?>

                <div class="files">
                    <?php foreach($arReview['FILE_LIST'] as $file):?>
                        <img data-fancybox href="<?=$file['SRC']?>" src="<?=$file['SRC']?>" alt="" width="80px">
                    <?php endforeach;?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
