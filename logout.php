<?php
	session_start();
	session_unset();

	header("location: mylogin.php");
?>
