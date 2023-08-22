<?php
$title = 'Password Reset';
$script = 'password';
require_once __DIR__ . '/../include/head.php';
?>
<form class="box login update-profile" autocomplete="off">

    <h1>Reset Password</h1>

    <input type="text" id="email" name="email" placeholder="Email">

    <input type="text" id="OTP" name="OTP" placeholder="OTP (123456)">

    <input type="password" id="password" name="password" placeholder="New Password">

    <span id="error"></span>

    <button id="reset" class="btn update">Reset<i class="fa-solid fa-paper-plane"></i></button>

    <span class="loading-icon">
        <i class="fa-solid fa-spinner fa-spin"></i>
    </span>

    <a class="btn btn-white" href="/login">Login</a>
</form>
<?php
require_once __DIR__ . '/../include/foot.php';