<?php
$title = 'Login';
$script = 'login';
require_once __DIR__ . '/include/head.php';
?>
<form class="box login" autocomplete="off">

    <h1>Login Form</h1>

    <div>
        <a class="btn" href="/login">Login</a>
        <a class="btn" href="/signup">Signup</a>
    </div>

    <input type="text" id="email" name="email" placeholder="Email">

    <input type="password" id="password" name="password" placeholder="Password">

    <a href="/password-reset">Forgot Password</a>

    <span id="error"></span>

    <button class="btn">Login</button>

    <p>Not a member? <a href="/signup">Signup now</a></p>

</form>
<?php
require_once __DIR__ . '/include/foot.php';