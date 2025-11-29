<?php
	include 'includes/session.php';
    include 'includes/security.php'; // Include security helper functions
	$conn = $pdo->open();

	if(isset($_POST['login'])){
		
		$email = $_POST['email'];
		$password = $_POST['password'];

        // Rate Limiting: Check if user is blocked
        // Key is based on IP address to prevent brute force from same location
        $ip = get_client_ip();
        $rate_limit_key = 'login_attempt_' . md5($ip);
        $limit_check = check_rate_limit($rate_limit_key, 5, 900); // 5 attempts per 15 minutes

        if (!$limit_check['allowed']) {
            $wait_minutes = ceil(($limit_check['reset_time'] - time()) / 60);
            $_SESSION['error'] = "Too many failed login attempts. Please try again in $wait_minutes minutes.";
            header('location: login.php');
            exit();
        }
    
		try{

			$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM users WHERE email = :email");
			$stmt->execute(['email'=>$email]);
			$row = $stmt->fetch();
			if($row['numrows'] > 0){
				if($row['status']){
					if(password_verify($password, $row['password'])){
						// Prevent session fixation attack
						session_regenerate_id(true);
						
                        // Reset rate limit on successful login
                        reset_rate_limit($rate_limit_key);

						if($row['type']){
							$_SESSION['admin'] = $row['id'];
						}
						else{
							$_SESSION['user'] = $row['id'];
						}
					}
					else{
                        // Increment rate limit on failed password
                        increment_rate_limit($rate_limit_key);
						$_SESSION['error'] = 'Incorrect Password';
					}
				}
				else{
					$_SESSION['error'] = 'Account not activated.';
				}
			}
			else{
                // Increment rate limit on email not found (prevent user enumeration)
                increment_rate_limit($rate_limit_key);
				$_SESSION['error'] = 'Email not found';
			}
		}
		catch(PDOException $e){
			echo "There is some problem in connection: " . $e->getMessage();
		}

	}
	else{
		$_SESSION['error'] = 'Input login credentails first';
	}

	$pdo->close();

	header('location: login.php');

?>