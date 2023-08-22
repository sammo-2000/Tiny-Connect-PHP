<?php
if ($_SESSION['name'] === null) {
    header('location: /profile/edit');
}
$title = 'Home';
$script = 'home';
require_once __DIR__ . '/include/head.php';
?>
<div class="box">
    <div class="home-tabs">
        <ul class="list">
            <li class="tab postBtn active" data-section="post">Posts</li>
            <li class="tab blogBtn" data-section="blog">Blogs</li>
            <li class="tab PostBtnFriend" data-section="post-following">Posts Following</li>
            <li class="tab blogBtnFriend" data-section="blog-following">Blogs Following</li>
        </ul>
        <section class="post">
            <div class="post-section">
                <div class="no-found">No posts found</div>
            </div>
            <button class="btn btn-white fit"><i class="fa-solid fa-download"></i></button>
        </section>
        <section class="blog">
            <div class="blog-section">
                <div class="no-found">No blogs found</div>
            </div>
            <button class="btn btn-white fit"><i class="fa-solid fa-download"></i></button>
        </section>
        <section class="post-following">
            <div class="post-section-following">
                <div class="no-found">No Posts From Your Followed Users</div>
            </div>
            <button class="btn btn-white fit"><i class="fa-solid fa-download"></i></button>
        </section>
        <section class="blog-following">
            <div class="blog-section-following">
                <div class="no-found">No Blogs From Your Followed Users</div>
            </div>
            <button class="btn btn-white fit"><i class="fa-solid fa-download"></i></button>
        </section>
    </div>
</div>
<?php
require_once __DIR__ . '/include/foot.php';