<?php
// Start de sessie
session_start();

// Verwijder alle sessievariabelen
session_unset();

// Vernietig de sessie
session_destroy();

// Stuur de gebruiker terug naar de loginpagina
header("Location: login.php");
exit();
?>
