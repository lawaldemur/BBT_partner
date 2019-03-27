<?php
session_start();
// remove session if it's exist
if (isset($_SESSION['logged']))
	unset($_SESSION['logged']);
