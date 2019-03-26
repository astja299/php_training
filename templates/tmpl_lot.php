<section class="lot-item container">
    <h2><?=$lot['title'];?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src=<?=strip_tags($lot['image']);?> width="730" height="548" alt="Картинка">
            </div>
            <p class="lot-item__category">Категория: <span><?=strip_tags($lot['name']);?></span></p>
            <p class="lot-item__description"><?=strip_tags($lot['description']);?></p>
        </div>
        <div class="lot-item__right">

            <?php if (!empty($user_data) && $has_bet == 0 && $is_author == 0) : ?>
            <div class="lot-item__state">
                <div class="lot-item__timer timer">
                    <?=time_remaining($price_data['end_date']);?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?=price_format(strip_tags($price_data['current_price']));?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?=price_format(strip_tags($price_data['next_bet'])); ?></span>
                    </div>
                </div>
                <form class="lot-item__form" action="lot.php?id=<?=$lot['id'];?>" method="post">
                    <?php $classname = isset($errors) ? "form__item--invalid" : "";
                    $value = isset($cost) ? $cost : ""; ?>
                    <p class="lot-item__form-item form__item <?=$classname;?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" value="<?=$value;?>" placeholder="<?=price_format((strip_tags($price_data['next_bet']))); ?>">
                        <span class="form__error"> <?=$errors;?> </span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (!empty($history_bets)) : ?>
            <div class="history">
                <h3>История ставок (<span><?=count($history_bets);?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($history_bets as $key => $val): ?>
                    <tr class="history__item">
                        <td class="history__name"><?=strip_tags($val['name']);?></td>
                        <td class="history__price"><?=price_format(strip_tags($val['bet_value']));?></td>
                        <td class="history__time"><?=strip_tags($val['my_date']);?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>