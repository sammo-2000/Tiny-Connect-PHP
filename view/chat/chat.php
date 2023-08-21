<?php
$title = 'Chat';
$script = 'chat';
require_once __DIR__ . '/../include/head.php';
isset($blogID) ? User($blogID) : User()
    ?>
<div id="chat-with"></div>
<?php
require_once __DIR__ . '/../include/foot.php';