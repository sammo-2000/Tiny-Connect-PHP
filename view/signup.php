<?php
$title = 'Signup';
$script = 'signup';
require_once __DIR__ . '/include/head.php';
?>
<form class="box login" autocomplete="off">

    <h1>Signup Form</h1>

    <div>
        <a class="btn" href="/login">Login</a>
        <a class="btn" href="/signup">Signup</a>
    </div>

    <input type="text" id="email" name="email" placeholder="Email">

    <input type="password" id="password" name="password" placeholder="Password">

    <input type="password" id="password_repeat" name="password_repeat" placeholder="Password Repeat">

    <!-- <div>
        <span><a href="/terms-and-conditions">Term & Conditions</a></span>
        <input type="checkbox" name="terms" required>
    </div> -->

    <span id="error"></span>

    <button class="btn">Login</button>

    <p>Already member? <a href="/login">Login now</a></p>

</form>
<?php
require_once __DIR__ . '/include/foot.php';