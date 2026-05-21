<?php
session_start();
session_destroy();
header("Location: ../frontend/index.php?msg=loggedout");
exit();
?>

