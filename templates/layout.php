<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <link href="css/normalize.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="page-wrapper">

    <?php print(include_component('comp_header.php', ['user_data' => $user_data])); ?>

    <?php $class = $hide_categories_in_header ? "class=\"container\"" : ""; ?>

    <main <?=$class;?>>
        <?php if (!$hide_categories_in_header) {
            print(include_component('comp_categories.php', ['categories' => $categories]));
        }; ?>
        <?= $content; ?></main>
</div>
<footer class="main-footer">

    <?php print(include_component('comp_categories.php', ['categories' => $categories])); ?>
    <?php print(include_component('comp_footer.php', ['user_data' => $user_data])); ?>

</footer>
</body>
</html>