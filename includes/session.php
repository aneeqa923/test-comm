<?php
	include 'conn.php';
	
	// Secure session configuration
	ini_set('session.cookie_httponly', 1);
	ini_set('session.use_only_cookies', 1);
	ini_set('session.cookie_samesite', 'Lax');
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
		ini_set('session.cookie_secure', 1);
	}

    // Security Headers
    header('X-Frame-Options: SAMEORIGIN'); // Prevent Clickjacking
    header('X-Content-Type-Options: nosniff'); // Prevent MIME Sniffing
    header('X-XSS-Protection: 1; mode=block'); // XSS Protection for older browsers
	
	session_start();
	
	if(isset($_SESSION['admin'])){
		header('location: admin/home.php');
	}

	if(isset($_SESSION['user'])){
		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
			$stmt->execute(['id'=>$_SESSION['user']]);
			$user = $stmt->fetch();
		}
		catch(PDOException $e){
			echo "There is some problem in connection: " . $e->getMessage();
		}

		$pdo->close();
	}
?>
