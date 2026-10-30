<?php
session_start();

// Unset all session variables active in memory cache
session_unset();

// Obliterate the server session entirely
session_destroy();

// Safely bounce the client terminal back to the public domain feed index view
header("Location: index.php");
exit;
