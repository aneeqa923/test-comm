<?php
	include 'includes/session.php';

	$conn = $pdo->open();

	$output = array('error'=>false);

	$id = $_POST['id'];
	$qty = $_POST['qty'];

	if(isset($_SESSION['user'])){
		try{
			// IDOR FIX: Added AND user_id=:user_id to ensure user owns the cart item
			$stmt = $conn->prepare("UPDATE cart SET quantity=:quantity WHERE id=:id AND user_id=:user_id");
			$stmt->execute(['quantity'=>$qty, 'id'=>$id, 'user_id'=>$user['id']]);
			$output['message'] = 'Updated';
		}
		catch(PDOException $e){
			$output['message'] = $e->getMessage();
		}
	}
	else{
		foreach($_SESSION['cart'] as $key => $row){
			if($row['productid'] == $id){
				$_SESSION['cart'][$key]['quantity'] = $qty;
				$output['message'] = 'Updated';
			}
		}
	}

	$pdo->close();
	echo json_encode($output);

?>