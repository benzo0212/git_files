<?php
ini_set('session.gc_maxlifetime', 3600);  // Set session lifetime (1 hour)
ini_set('session.cookie_lifetime', 3600);  // Set cookie lifetime (1 hour)

session_start();  // Ensure session is started

// Check if the session is valid and active
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 3600) {
    // Session expired due to inactivity
    session_unset();
    session_destroy();
} else {
    $_SESSION['last_activity'] = time();  // Update last activity timestamp
}
?>
