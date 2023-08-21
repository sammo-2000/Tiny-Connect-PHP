<?php
$title = 'Profile Edit';
$script = 'profile-edit';
require_once __DIR__ . '/../include/head.php';
isset($userID) ? User($userID) : User()
    ?>
<form class="box login update-profile" autocomplete="off">

    <h1>Edit Profile</h1>

    <label for="image"><img class="box"></label>
    <input type="file" id="image" name="image" accept=".jpeg, .jpg, .png">

    <input type="text" id="name" name="name" placeholder="Name">

    <textarea name="bio" id="bio" placeholder="Bio" rows="10"></textarea>

    <span id="error"></span>

    <button class="btn update">Update<i class="fa-solid fa-paper-plane"></i></button>

    <button class="btn delete btn-white">Delete Account<i class="fa-solid fa-trash"></i></button>

    <span class="loading-icon">
        <i class="fa-solid fa-spinner fa-spin"></i>
    </span>

    <a href="/profile">Cancel edit</a>
</form>
<?php
require_once __DIR__ . '/../include/foot.php';