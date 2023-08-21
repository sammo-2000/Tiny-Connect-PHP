<?php
$title = 'Chat ' . $userID;
$script = 'chat-single';
$head = false;
require_once __DIR__ . '/../include/head.php';
isset($userID) ? User($userID) : User()
    ?>
<div id="chat">
    <div class="info box">
        <div>
            <img>
            <a></a>
        </div>
        <a href="/chat"><i class="fa-solid fa-arrow-left-long fa-rotate-180"></i></a>
    </div>
    <div class="chat">
        <span class="no-found">No chat history found</span>
    </div>
    <div class="input box">
        <span id="error"></span>
        <div>
            <input type="text">
            <button class="btn btn-white">Send<i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../include/foot.php';