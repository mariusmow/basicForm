<?php

use Marius\BasicForm\Core\Csrf;
use Marius\BasicForm\Core\Vite;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES) ?>">
    <title>Contact Portal</title>
    <?= Vite::asset('resources/js/main.js') ?>
</head>
<body class="bg-gray-50 text-gray-900">
    <div id="app" v-cloak></div>
</body>
</html>
