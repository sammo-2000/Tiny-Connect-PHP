<?php
if ($_SESSION['name'] === null) {
    header('location: /profile/edit');
}
$title = 'Home';
require_once __DIR__ . '/include/head.php';
?>
<!-- Main Conent -->
<h1>Home</h1>
<?php
require_once __DIR__ . '/include/foot.php';