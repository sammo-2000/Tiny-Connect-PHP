<?php

function set_csrf()
{
    if (!isset($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(50));
    }
    echo '<input type="hidden" name="csrf" id="csrf" value="' . $_SESSION["csrf"] . '">';
}

function Method($method)
{
    $currentMethod = $_SERVER['REQUEST_METHOD'];
    $methodToCompare = strtoupper($method);
    // echo "Current method: " . $currentMethod . ", Method to compare: " . $methodToCompare . "\n";
    return $currentMethod == $methodToCompare;
}

function JSON($string)
{
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($string);
}