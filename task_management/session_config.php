<?php
// Custom session configuration
ini_set('session.gc_maxlifetime', 3600); // Session expires after 1 hour of inactivity
ini_set('session.cookie_lifetime', 3600); // Cookie valid for 1 hour

session_start();

// Check for inactivity timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 3600) {
    // Session expired due to inactivity
    session_unset();
    session_destroy();
} else {
    // Update last activity time
    $_SESSION['last_activity'] = time();
}
?>
