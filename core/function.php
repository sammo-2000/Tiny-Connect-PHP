<?php

function set_csrf()
{
    if (!isset($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(50));
    }
    echo '<input type="hidden" name="csrf" id="csrf" value="' . $_SESSION["csrf"] . '">';
}

function is_csrf_valid()
{
    if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
        return false;
    }
    if ($_SESSION['csrf'] != $_POST['csrf']) {
        return false;
    }
    return true;
}

function active($currentPage)
{
    $requestUri = $_SERVER['REQUEST_URI'];
    $uriWithoutQuery = strtok($requestUri, '?');

    if ($uriWithoutQuery === $currentPage) {
        echo "class=\"active\"";
    }

}

function pastTime($postTime)
{
    // Set the time zone to London
    date_default_timezone_set('Europe/London');

    // Convert the stored time to a Unix timestamp
    $timeStamp = strtotime($postTime);

    // Calculate the time difference
    $timeDifference = time() - $timeStamp;

    // Calculate the number of days, hours, and minutes
    $days = floor($timeDifference / (60 * 60 * 24));
    $hours = floor(($timeDifference % (60 * 60 * 24)) / (60 * 60));
    $minutes = floor(($timeDifference % (60 * 60)) / 60);

    // Build the formatted time string
    $formattedTime = '';

    if ($days > 0) {
        $formattedTime .= $days . ' day' . ($days > 1 ? 's' : '') . ' ';
    }

    if ($hours > 0 && empty($formattedTime)) {
        $formattedTime .= $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ';
    }

    if ($minutes > 0 && empty($formattedTime)) {
        $formattedTime .= $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ';
    }
    if (empty($formattedTime)):
        $formattedTime = 'Less than minute ';
    endif;

    if (!empty($formattedTime)):
        $formattedTime .= 'ago';

        // Output the formatted time
        echo $formattedTime;
    endif;
}

function JSON($string)
{
    ob_start();
    header('Content-Type: application/json');
    echo json_encode($string);
    exit;
}

function User($id = null)
{
    if ($id == null) {
        echo "<div style=\"display: none\" id=\"userID\"> " . $_SESSION['userID'] . "</div>";
    } else {
        echo "<div style=\"display: none\" id=\"userID\"> " . $id . "</div>";
    }
}