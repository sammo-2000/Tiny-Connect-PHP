<?php
$title = 'Blog';
require_once __DIR__ . '/../include/head.php';
isset($userID) ? User($userID) : User()
    ?>
<form class="box login" autocomplete="off">

    <h1>Create Blog</h1>

    <input type="text" id="title" name="title" placeholder="Title">

    <textarea name="blog" id="blog" placeholder="Blog" rows="10"></textarea>

    <span id="error"></span>

    <button class="btn">Create<i class="fa-solid fa-plus"></i></button>

    <a class="btn btn-white" href="/post/create">Create Post<i class="fa-solid fa-pen"></i></a>

</form>
<?php
require_once __DIR__ . '/../include/foot.php';