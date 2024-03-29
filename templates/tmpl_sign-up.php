<form class="form container" action="sign-up.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Регистрация нового аккаунта</h2>
    <?php $classname = isset($errors['email']) ? "form__item--invalid" : ""; ?>
      <div class="form__item <?=$classname;?>">
          <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="signup[email]" placeholder="Введите e-mail" value="<?=strip_tags($values['email']) ?? ''; ?>">
        <span class="form__error">Введите e-mail</span>
      </div>
    <?php $classname = isset($errors['password']) ? "form__item--invalid" : ""; ?>
      <div class="form__item <?=$classname;?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="signup[password]" placeholder="Введите пароль" value="<?=strip_tags($values['password']) ?? ''; ?>" >
        <span class="form__error">Введите пароль</span>
      </div>
    <?php $classname = isset($errors['name']) ? "form__item--invalid" : ""; ?>
      <div class="form__item <?=$classname;?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="signup[name]" placeholder="Введите имя" value="<?=strip_tags($values['name']) ?? ''; ?>" >
        <span class="form__error">Введите имя</span>
      </div>
    <?php $classname = isset($errors['message']) ? "form__item--invalid" : ""; ?>
      <div class="form__item <?=$classname;?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="signup[message]" placeholder="Напишите как с вами связаться"><?=strip_tags($values['message']) ?? ''; ?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
      </div>

    <?php $classname = isset($_FILES['avatar']['name']) ? "form__item--uploaded" : "";
    $value = isset($_FILES['avatar']['name']) ? strip_tags($_FILES['avatar']['name']) : ""; ?>

    <div class="form__item form__item--file form__item--last">
        <label>Аватар</label>
        <div class="preview">
          <button class="preview__remove" type="button">x</button>
          <div class="preview__img">
            <img src="<?=$value;?>" width="113" height="113" alt="Ваш аватар">
          </div>
        </div>
        <div class="form__input-file">
          <input class="visually-hidden" name="avatar" type="file" id="photo2" value="<?=$value;?>">
          <label for="photo2">
            <span>+ Добавить</span>
          </label>
        </div>
      </div>

    <?php if (isset($errors)): ?>
        <span style="color: red" class="form__errors">  <!-- Верстка с оригинальным классом не работала, добавила цвет вручную -->
            <p>Пожалуйста, исправьте следующие ошибки:</p>
            <ul>
                <?php foreach ($errors as $error => $val): ?>
                    <li><strong><?=$dict[$error];?>:</strong>  <?=$val;?></li>
                <?php endforeach; ?>
            </ul>
        </span>
    <?php endif; ?>

      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>
