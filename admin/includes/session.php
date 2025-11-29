<?php
	include '../includes/conn.php';
	
	// Secure session configuration
	ini_set('session.cookie_httponly', 1);
	ini_set('session.use_only_cookies', 1);
	ini_set('session.cookie_samesite', 'Lax');
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
		ini_set('session.cookie_secure', 1);
	}
	
	session_start();

	if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
		header('location: ../index.php');
		exit();
	}

	$conn = $pdo->open();

	$stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
	$stmt->execute(['id'=>$_SESSION['admin']]);
	$admin = $stmt->fetch();
  
	$pdo->close();

?>