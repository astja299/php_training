<form class="form form--add-lot container form--invalid" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <?php $classname = isset($errors['name']) ? "form__item--invalid" : "";
        $value = isset($lot['name']) ? strip_tags($lot['name']) : ""; ?>
        <div class="form__item <?=$classname;?>">
            <label for="lot-name">Наименование</label>
            <input value="<?=$value;?>" id="lot-name" type="text" name="lot[name]" placeholder="Введите наименование лота" >
            <span class="form__error">Введите наименование лота</span>
        </div>
        <div class="form__item">
            <label for="category">Категория</label>
            <select id="category" name="lot[category]" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?=$category['id'];?>">
                        <?=$category['name'];?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error">Выберите категорию</span>
        </div>
    </div>
    <?php $classname = isset($errors['description']) ? "form__item--invalid" : "";
    $value = isset($lot['description']) ? strip_tags($lot['description']) : ""; ?>
    <div class="form__item form__item--wide <?=$classname;?> " >
        <label for="message">Описание</label>
        <textarea id="message" name="lot[description]" placeholder="Напишите описание лота" ><?=$value;?></textarea>
        <span class="form__error">Напишите описание лота</span>
    </div>

    <?php $classname = isset($_FILES['new_image']['name']) ? "form__item--uploaded" : "";
    $value = isset($_FILES['new_image']['name']) ? strip_tags($_FILES['new_image']['name']) : ""; ?>

    <div class="form__item form__item--file <?=$classname;?> "> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="<?=$value;?>" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" name="new_image" type="file" id="photo2" value="<?=$value;?>">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>


    <div class="form__container-three">
        <?php $classname = isset($errors['st_price']) ? "form__item--invalid" : "";
        $value = isset($lot['st_price']) ? strip_tags($lot['st_price']) : ""; ?>
        <div class="form__item form__item--small <?=$classname;?>">
            <label for="lot-rate">Начальная цена</label>
            <input value="<?=$value;?>" id="lot-rate" type="number" name="lot[st_price]" placeholder="0" >
            <span class="form__error">Введите начальную цену</span>
        </div>
        <?php $classname = isset($errors['bet_step']) ? "form__item--invalid" : "";
        $value = isset($lot['bet_step']) ? strip_tags($lot['bet_step']) : ""; ?>
        <div class="form__item form__item--small <?=$classname;?>">
            <label for="lot-step">Шаг ставки</label>
            <input value="<?=$value;?>" id="lot-step" type="number" name="lot[bet_step]" placeholder="0" >
            <span class="form__error">Введите шаг ставки</span>
        </div>
        <?php $classname = isset($errors['end_date']) ? "form__item--invalid" : "";
        $value = isset($lot['end_date']) ? strip_tags($lot['end_date']) : ""; ?>
        <div class="form__item <?=$classname;?>">
            <label for="lot-date">Дата окончания торгов</label>
            <input value="<?=$value;?>"  class="form__input-date" id="lot-date" type="date" name="lot[end_date]" >
            <span class="form__error">Введите дату завершения торгов</span>
        </div>
    </div>
    <?php if (isset($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.

            <ul>
                <?php foreach($errors as $err => $val): ?>
                    <li><strong><?=$dict[$err];?>:</strong> <?=$val;?></li>
                <?php endforeach; ?>
            </ul>
        </span>
    <?php endif; ?>
    <button type="submit" class="button">Добавить лот</button>
</form>
