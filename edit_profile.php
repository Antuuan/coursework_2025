<?php
include_once("connection.php");
include("navbar.php");

// Must be logged in
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: login.php?error=login_required");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// Fetch current user data
$stmt = $conn->prepare("
    SELECT email, phone_no, address, postcode, 
           card_no, card_name, card_expiry, cvc 
    FROM tbl_users 
    WHERE user_id = :user_id
");
$stmt->bindParam(":user_id", $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$success = false;
$error   = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// trim simply strips any white space in the data
    $new_email=trim($_POST['email'] ?? '');
    $new_phone=trim($_POST['phone_no'] ?? '');
    $new_address=trim($_POST['address'] ?? '');
    $new_postcode=trim($_POST['postcode'] ?? '');
    $new_card_no=trim($_POST['card_no'] ?? '');
    $new_card_name=trim($_POST['card_name'] ?? '');
    $new_expiry=trim($_POST['card_expiry'] ?? '');
    $new_cvc=trim($_POST['cvc'] ?? '');
    $new_password=$_POST['new_password'] ?? '';

    // Simple validation to make sure none of these fields are empty
    if (empty($new_email) || empty($new_phone) || empty($new_address)) {
        $error = "Email, phone number and address are required.";
    } else {
        try {
            if (!empty($new_password)) {
                // Update with new password (hashed)
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("
                    UPDATE tbl_users 
                    SET email = :email,
                        phone_no = :phone_no,
                        address = :address,
                        postcode = :postcode,
                        card_no = :card_no,
                        card_name = :card_name,
                        card_expiry = :card_expiry,
                        cvc = :cvc,
                        password = :password
                    WHERE user_id = :user_id
                ");
                $stmt->bindParam(":password", $hashed);
            } else {
                // Update without changing password
                $stmt = $conn->prepare("
                    UPDATE tbl_users 
                    SET email = :email,
                        phone_no = :phone_no,
                        address = :address,
                        postcode = :postcode,
                        card_no = :card_no,
                        card_name = :card_name,
                        card_expiry = :card_expiry,
                        cvc = :cvc
                    WHERE user_id = :user_id
                ");
            }

            $stmt->bindParam(":email",$new_email);
            $stmt->bindParam(":phone_no",$new_phone);
            $stmt->bindParam(":address",$new_address);
            $stmt->bindParam(":postcode",$new_postcode);
            $stmt->bindParam(":card_no",$new_card_no);
            $stmt->bindParam(":card_name",$new_card_name);
            $stmt->bindParam(":card_expiry",$new_expiry);
            $stmt->bindParam(":cvc",$new_cvc);
            $stmt->bindParam(":user_id",$user_id);

            $stmt->execute();

            $success = true;

        } catch (Exception $e) {
            $error = "Update failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
</head>
<body class="bg-light">

<div class="container my-5">
    <h1 class="mb-4">Edit Profile</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">
            Profile updated successfully!
        </div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Phone Number</label>
                        <input type="text" name="phone_no" class="form-control" 
                               value="<?= htmlspecialchars($user['phone_no'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control" 
                           value="<?= htmlspecialchars($user['address'] ?? '') ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Postcode</label>
                        <input type="text" name="postcode" class="form-control" 
                               value="<?= htmlspecialchars($user['postcode'] ?? '') ?>" required>
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Card Details</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Card Number</label>
                        <input type="text" name="card_no" class="form-control" 
                               value="<?= htmlspecialchars($user['card_no'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Name on Card</label>
                        <input type="text" name="card_name" class="form-control" 
                               value="<?= htmlspecialchars($user['card_name'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Expiry Date (MM/YY)</label>
                        <input type="text" name="card_expiry" class="form-control" 
                               value="<?= htmlspecialchars($user['card_expiry'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>CVC</label>
                        <input type="text" name="cvc" class="form-control" 
                               value="<?= htmlspecialchars($user['cvc'] ?? '') ?>" required>
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Change Password (optional)</h5>
                <div class="mb-3">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control" 
                           placeholder="Leave blank to keep current password">
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>