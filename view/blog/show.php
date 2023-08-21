<?php
$title = 'Blog';
$script = 'show-blog';
require_once __DIR__ . '/../include/head.php';
isset($blogID) ? User($blogID) : User()
    ?>
<div id="userIDs" style="display: none">
    <?= $_SESSION['userID'] ?>
</div>
<div id="blog">
    <div class="box">
        <div class="post-detail">
            <a></a>
            <span></span>
        </div>
        <h1></h1>
        <p class="blog-body"></p>
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