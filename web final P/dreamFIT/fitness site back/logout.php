<?php
// logout.php — End session and go back to backend login

session_start();
session_unset();
session_destroy();

header('Location: index.php');
exit;
