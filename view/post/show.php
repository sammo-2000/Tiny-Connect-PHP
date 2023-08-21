<?php
$title = 'Post';
$script = 'show-post';
require_once __DIR__ . '/../include/head.php';
isset($postID) ? User($postID) : User()
    ?>
<div id="post">
    <div class="box">
        <div class="post-detail">
            <a></a>
            <span></span>
        </div>
        <h1></h1>
        <img class="box">
    </div>
    <div class="box show-comments">
        <div class="add-comment">
            <input type="text">
            <button class="btn btn-white send">Send<i class="fa-solid fa-pen"></i></button>
        </div>
        <span id="error"></span>
        <div class="comments">
            <div class="no-found">No comment found</div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../include/foot.php';