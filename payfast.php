<?php
include 'includes/session.php';

if(!isset($_SESSION['user'])){
	header('location: login.php');
	exit();
}

// PayFast Sandbox Credentials
// Replace these with your actual PayFast sandbox credentials
define('PF_MERCHANT_ID', '10000100'); // PayFast sandbox merchant ID
define('PF_MERCHANT_KEY', '46f0cd694581a'); // PayFast sandbox merchant key
define('PF_PASSPHRASE', 'jt7NOE43FZPn'); // PayFast sandbox passphrase (optional, but recommended)
define('PF_SANDBOX', true); // Set to false for production

// PayFast URLs
$pfHost = PF_SANDBOX ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
$pfUrl = 'https://' . $pfHost . '/eng/process';

// Get cart total
$conn = $pdo->open();
$stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN products on products.id=cart.product_id WHERE user_id=:user_id");
$stmt->execute(['user_id'=>$user['id']]);

$total = 0;
$item_name = 'Cart Items';
foreach($stmt as $row){
	$subtotal = $row['price'] * $row['quantity'];
	$total += $subtotal;
}

$pdo->close();

// Generate unique payment reference
// Format: PF_userid_timestamp_uniqid (includes user_id for ITN processing)
$payment_id = 'PF_' . $user['id'] . '_' . time() . '_' . uniqid();

// Format cell number for PayFast
// PayFast requires format: 27XXXXXXXXX (country code 27 + 9 digits, no leading 0, total 11 digits)
// Remove all non-numeric characters first
$cell_number = isset($user['contact_info']) ? $user['contact_info'] : '';
$cell_number = preg_replace('/[^0-9]/', '', $cell_number); // Remove all non-numeric characters

// Format the number for PayFast
if(!empty($cell_number)){
	// If number starts with 0, replace with 27 (South African country code)
	if(substr($cell_number, 0, 1) == '0' && strlen($cell_number) == 10){
		$cell_number = '27' . substr($cell_number, 1);
	}
	// If number doesn't start with 27 and has 9 digits, add 27 prefix (assuming SA number)
	elseif(substr($cell_number, 0, 2) != '27' && strlen($cell_number) == 9){
		$cell_number = '27' . $cell_number;
	}
	// If already starts with 27 and has correct length, use as is
	elseif(substr($cell_number, 0, 2) == '27' && strlen($cell_number) == 11){
		// Already in correct format
	}
	// If invalid format, set to empty (will be omitted from data)
	else{
		$cell_number = '';
	}
}

// PayFast payment data
$data = array(
	'merchant_id' => PF_MERCHANT_ID,
	'merchant_key' => PF_MERCHANT_KEY,
	'return_url' => 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/sales.php?pay=' . $payment_id,
	'cancel_url' => 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/cart_view.php',
	'notify_url' => 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/payfast_notify.php',
	'name_first' => isset($user['firstname']) ? $user['firstname'] : '',
	'name_last' => isset($user['lastname']) ? $user['lastname'] : '',
	'email_address' => isset($user['email']) ? $user['email'] : '',
	'm_payment_id' => $payment_id,
	'amount' => number_format(sprintf('%.2f', $total), 2, '.', ''),
	'item_name' => $item_name
);

// Only add cell_number if it's properly formatted
if(!empty($cell_number) && strlen($cell_number) == 11){
	$data['cell_number'] = $cell_number;
}

// Create parameter string
$pfParamString = '';
foreach($data as $key => $val){
	if($val !== ''){
		$pfParamString .= $key . '=' . urlencode($val) . '&';
	}
}

// Remove last ampersand
$pfParamString = substr($pfParamString, 0, -1);

// Generate signature
if(PF_PASSPHRASE !== ''){
	$pfParamString .= '&passphrase=' . urlencode(PF_PASSPHRASE);
}
$signature = md5($pfParamString);
$data['signature'] = $signature;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Redirecting to PayFast...</title>
</head>
<body>
	<form action="<?php echo $pfUrl; ?>" method="post" id="payfast_form">
		<?php foreach($data as $name => $value): ?>
			<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
		<?php endforeach; ?>
	</form>
	<script>
		document.getElementById('payfast_form').submit();
	</script>
	<p>Redirecting to PayFast...</p>
</body>
</html>
