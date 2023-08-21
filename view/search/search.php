<?php
$title = 'Search';
$script = 'search';
require_once __DIR__ . '/../include/head.php';
?>
<div id="search" class="box">
    <form class="input">
        <input type="text">
        <button class="btn">Search<i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
    <div class="search"></div>
</div>
<?php
require_once __DIR__ . '/../include/foot.php';