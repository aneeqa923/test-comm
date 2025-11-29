<?php
	include 'includes/session.php';
   
	$conn = $pdo->open();

	$output = array('error'=>false);
	$id = $_POST['id'];

	if(isset($_SESSION['user'])){
		try{
			// IDOR FIX: Added AND user_id=:user_id to ensure user owns the cart item
			$stmt = $conn->prepare("DELETE FROM cart WHERE id=:id AND user_id=:user_id");
			$stmt->execute(['id'=>$id, 'user_id'=>$user['id']]);
			$output['message'] = 'Deleted';
			
		}
		catch(PDOException $e){
			$output['message'] = $e->getMessage();
		}
	}
	else{
		foreach($_SESSION['cart'] as $key => $row){
			if($row['productid'] == $id){
				unset($_SESSION['cart'][$key]);
				$output['message'] = 'Deleted';
			}
		}
	}

	$pdo->close();
	echo json_encode($output);

?>