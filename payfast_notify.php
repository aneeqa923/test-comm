<?php
include 'includes/session.php';

// PayFast Sandbox Credentials
// Replace these with your actual PayFast sandbox credentials
define('PF_MERCHANT_ID', '10000100'); // PayFast sandbox merchant ID
define('PF_MERCHANT_KEY', '46f0cd694581a'); // PayFast sandbox merchant key
define('PF_PASSPHRASE', 'jt7NOE43FZPn'); // PayFast sandbox passphrase (optional, but recommended)
define('PF_SANDBOX', true); // Set to false for production

// PayFast URLs
$pfHost = PF_SANDBOX ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';

// Get the posted data
$pfData = $_POST;

// Strip any slashes in data
foreach ($pfData as $key => $val) {
    $pfData[$key] = stripslashes($val);
}

// Convert posted variables to a string
$pfParamString = '';
foreach ($pfData as $key => $val) {
    if ($key != 'signature') {
        $pfParamString .= $key . '=' . urlencode($val) . '&';
    }
}

// Remove the last '&' from the parameter string
$pfParamString = substr($pfParamString, 0, -1);

// Generate signature and check against the one passed
if (PF_PASSPHRASE !== '') {
    $pfParamString .= '&passphrase=' . urlencode(PF_PASSPHRASE);
}
$signature = md5($pfParamString);

// Verify signature
if ($pfData['signature'] === $signature) {
    // Verify data with PayFast
    $pfParamString = '';
    foreach ($pfData as $key => $val) {
        $pfParamString .= $key . '=' . urlencode($val) . '&';
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://' . $pfHost . '/eng/query/validate');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $pfParamString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For sandbox/testing only
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === 'VALID') {
        // Payment valid, process order
        $payment_id = $pfData['m_payment_id'];
        $amount = $pfData['amount_gross'];
        $status = $pfData['payment_status'];

        if ($status == 'COMPLETE') {
            // Extract user ID from payment ID (PF_userid_timestamp_uniqid)
            $parts = explode('_', $payment_id);
            if(count($parts) >= 2){
                $user_id = $parts[1];
            } else {
                // Fallback or error
                exit();
            }

            $conn = $pdo->open();

            try {
                // Insert into sales
                $date = date('Y-m-d');
                $stmt = $conn->prepare("INSERT INTO sales (user_id, pay_id, sales_date) VALUES (:user_id, :pay_id, :sales_date)");
                $stmt->execute(['user_id'=>$user_id, 'pay_id'=>$payment_id, 'sales_date'=>$date]);
                $sales_id = $conn->lastInsertId();

                // Get cart items
                $stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user_id");
                $stmt->execute(['user_id'=>$user_id]);

                foreach($stmt as $row){
                    $stmt = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
                    $stmt->execute(['sales_id'=>$sales_id, 'product_id'=>$row['product_id'], 'quantity'=>$row['quantity']]);
                }

                // Clear cart
                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=:user_id");
                $stmt->execute(['user_id'=>$user_id]);

            } catch (PDOException $e) {
                // Log error
                error_log("Database error: " . $e->getMessage());
            }

            $pdo->close();
        }
    }
}
?>
