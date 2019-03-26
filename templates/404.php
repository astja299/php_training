      <section class="lot-item container">
          <?php $value = isset($code) ? $code : "404"; ?>

          <h2><?=$value;?> Страница не найдена</h2>
          <?php if (!isset($code)) :?>
            <p>Данной страницы не существует на сайте.</p>
          <?php endif; ?>
            <p><?=$error;?></p>
        </section>
