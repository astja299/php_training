<form class="form container" action="login.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Вход</h2>
    <?php $classname = isset($errors['email']) ? "form__item--invalid" : ""; ?>
      <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=strip_tags($form['email']) ?? ''; ?>">
        <span class="form__error"> <?= $errors['email'];  ?></span>
      </div>
    <?php $classname = isset($errors['password']) ? "form__item--invalid" : ""; ?>
      <div class="form__item form__item--last <?=$classname;?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="password" placeholder="Введите пароль"  value="<?=strip_tags($form['password']) ?? ''; ?>">
        <span class="form__error">Введите пароль</span>
      </div>
      <button type="submit" class="button">Войти</button>
    </form>


