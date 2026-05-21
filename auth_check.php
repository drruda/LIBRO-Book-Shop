<?php
session_start();
if (empty($_SESSION['username'])) {
    header('Location: homePage.html');
    exit;
}
