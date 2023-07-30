<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $_ENV['APP_NAME'] ?> -
        <?= $title ?>
    </title>

    <link rel="stylesheet" href="/public/css/reset.css">
    <link rel="stylesheet" href="/public/css/main.css">

    <script src="/public/js/main.js" defer></script>
    <?= isset($script) ? "<script src=\"{$script}\" defer></script>" : null ?>
</head>

<body>
    <header>
        <div class="container head"></div>
    </header>
    <main>
        <div class="container main">