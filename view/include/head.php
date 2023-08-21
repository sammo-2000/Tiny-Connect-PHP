<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $_ENV['APP_NAME'] ?> -
        <?= $title ?>
    </title>

    <script src="https://kit.fontawesome.com/bc0bae8e00.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/utilities.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/desktop.css" media="only screen and (min-width: 600px)">

    <script src="/js/main.js" defer></script>
    <?= isset($script) ? "<script src=\"/js/${script}.js\" defer></script>" : null ?>
</head>

<body>
    <header>
        <!-- Top section -->
        <div>
            <img src="/images/logo-black.png" alt="">
            <a href="/">
                <?= $_ENV['APP_NAME'] ?>
            </a>
        </div>
        <!-- Button section -->
        <nav>
            <ul class="list">
                <?php if (isset($_SESSION['auth'])) { ?>
                    <li <?= active('/search') ?>><a href="/search"><i class="fa-solid fa-magnifying-glass"></i>Search</a>
                    </li>
                    <li <?= active('/') ?>><a href="/"><i class="fa-solid fa-house"></i>Home</a></li>
                    <li <?= active('/post/create') ?>     <?= active('/blog/create') ?>><a href="/post/create"><i
                                class="fa-solid fa-pen"></i>New</a></li>
                    <li <?= active('/chat') ?>><a href="/chat"><i class="fa-solid fa-comment"></i>Chat</a></li>
                    <li <?= active('/profile') ?>><a href="/profile"><i class="fa-solid fa-user"></i>Profile</a></li>
                    <li <?= active('/admin') ?>><a href="/admin"><i class="fa-solid fa-unlock"></i>Admin</a></li>
                    <li><a href="/logout"><i class="fa-solid fa-right-from-bracket"></i>Logout</a></li>
                <?php } else { ?>
                    <li <?= active('/') ?>><a href="/"><i class="fa-solid fa-house"></i>Home</a></li>
                    <li <?= active('/login') ?>><a href="/login"><i class="fa-solid fa-right-to-bracket"></i>Login</a></li>
                    <li <?= active('/signup') ?>><a href="/signup"><i class="fa-solid fa-right-to-bracket"></i>Signup</a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </header>
    <main>