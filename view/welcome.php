<?php
$title = 'Home';
require __DIR__ . '/include/head.php';
?>
Home

<script>
    fetch('/api', {
        method: 'POST',
        body: 'Hello'
    })
        .then(res => res.json())
        .then(data => console.log(data.currentMethod))
</script>
<?php
require __DIR__ . '/include/foot.php';