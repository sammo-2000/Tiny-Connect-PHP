<?php
$title = 'Profile';
$script = 'profile';
require_once __DIR__ . '/../include/head.php';
isset($userID) ? User($userID) : User()
    ?>
<div id="profile">
    <!-- Profile top -->
    <div class="profile-top box">
        <div class="img">
            <img id="image" src="" class="box">
        </div>
        <div class="profile-details">
            <div class="profile-detail-top">
                <div>
                    <p id="name"></p>
                    <span id="join_date"></span>
                </div>
                <div class="profile-intract">
                    <?php
                    if (isset($userID) && $userID != $_SESSION['userID']) {
                        echo "<button class=\"btn\" id=\"follow\"></button>
                              <a href=\"/chat/$userID\" class=\"btn btn-white fit\">Chat<i class=\"fa-solid fa-comment\"></i></a>";
                    } else {
                        echo '<a href="/profile/edit" class="btn fit">Edit<i class="fa-solid fa-pen-to-square"></i></a>';
                    }
                    ?>
                </div>
            </div>
            <div class="profile-detail-bottom">
                <div>
                    <p>Posts</p>
                    <span id="posts"></span>
                </div>
                <div>
                    <p>Blogs</p>
                    <span id="blogs"></span>
                </div>
                <div>
                    <p>Following</p>
                    <span id="following"></span>
                </div>
                <div>
                    <p>Followers</p>
                    <span id="Followers"></span>
                </div>
            </div>
            <p class="bio"></p>
        </div>
    </div>
    <!-- Profile top end -->

    <!-- Profile bottom -->
    <div class="box profile-bottom">
        <ul class="list">
            <li class="tab active" data-section="post">Posts</li>
            <li class="tab" data-section="blog">Blogs</li>
            <li class="tab" data-section="following">Following</li>
            <li class="tab" data-section="follower">Followers</li>
        </ul>
        <section class="post">
            <div class="no-found">No posts found</div>
        </section>
        <section class="blog">
            <div class="no-found">No blogs found</div>
        </section>
        <section class="following">
            <div class="no-found">No following found</div>
        </section>
        <section class="follower">
            <div class="no-found">No followers found</div>
        </section>
    </div>
    <!-- Profile bottom end -->
</div>
<?php
require_once __DIR__ . '/../include/foot.php';