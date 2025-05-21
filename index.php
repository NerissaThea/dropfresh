<?php
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
session_start();
//comment out the following line in production
// Set session ini settings for security
require_once ('settings.php');
require_once ('header.inc');

if (isset($_GET['pg'])) {
    switch ($_GET['pg']) {
        case 'jobs':
            require_once ('jobs.php');
            break;

        case 'apply':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                processForm();
            }
            require_once ('apply.php');
            break;

        case 'processEOI':
            if (isset($_SESSION['eoi_number'])) {
                require_once ('processEOI.php');
            } else {
                header("Location: index.php");
                exit;
            }
            unset($_SESSION['eoi_number']); // Remove data from session after use
            break;

        case 'enhancement2':
            // Check login and access rights
            if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 1) {
                header("Location: index.php?pg=login1");
                exit;
            }
            require_once ('enhancement2.php');
            break;

        case 'login':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                include ('login_process.php');
            } else {
                include ('login.php');
            }
            break;
        case 'login1':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                include ('login_process1.php');
            } else {
                include ('login1.php');
            }
            break;
        case 'manage':
          
            if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 1) {
                header("Location: index.php?pg=login"); // Redirect unauthorized users to login page
                exit;
            } else {
                include ('manage.php'); // Allow access to manage.php for authorized users
            }
            break;
        case 'about':

            include ('about.php');
            break;
        case 'enhancement':

            include ('enhancement.php');
            break;
        case 'logout':
            // Clear user information from session
            session_unset();
            session_destroy();

            // Redirect user to the login page
            header("Location: index.php?pg=login");
            exit;
        case 'logout1':
            // Clear user information from session
            session_unset();
            session_destroy();

            // Redirect user to the login page
            header("Location: index.php?pg=login1");
            exit;

        default:
            require_once ('home.inc');
            break;
    }
} else {
    require_once ('home.inc');
}

require_once ('footer.inc');
?>