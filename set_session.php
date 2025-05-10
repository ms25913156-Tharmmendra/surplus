<?php
session_start();

if (isset($_POST['donar_contact'])) {
    $_SESSION['DonarContactNo'] = $_POST['donar_contact'];
    echo "success"; // must exactly echo "success"
} else {
    echo "error"; // if somehow 'donar_contact' is not sent
}
?>
