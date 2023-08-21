<?php
$title = 'Post';
$script = 'create-post';
require_once __DIR__ . '/../include/head.php';
isset($userID) ? User($userID) : User()
    ?>
<form class="box login create-post" autocomplete="off">

    <h1>Create Post</h1>

    <input type="text" id="caption" name="caption" placeholder="Caption">

    <label for="image"><img class="box" src="/images/upload.jpeg"></label>
    <input type="file" id="image" name="image" accept=".jpeg, .jpg, .png">

    <span id="error"></span>

    <button class="btn">Create<i class="fa-solid fa-plus"></i></button>

    <span class="loading-icon">
        <i class="fa-solid fa-spinner fa-spin"></i>
    </span>

    <a class="btn btn-white" href="/blog/create">Create Blog<i class="fa-solid fa-pen"></i></a>

</form>
<?php
require_once __DIR__ . '/../include/foot.php';