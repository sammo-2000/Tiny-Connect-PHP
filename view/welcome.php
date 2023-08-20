<?php
if (isset($_SESSION['auth']) && $_SESSION['name'] === null) {
    header('location: /profile/edit');
}
$title = 'Welcome';
require_once __DIR__ . '/include/head.php';
?>
<!-- Main Conent -->
<?php
require_once __DIR__ . '/include/foot.php';