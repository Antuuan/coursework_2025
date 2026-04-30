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
$user = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

// Error array for field-specific messages
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim all inputs to remove accidental whitespace
    $new_email=trim($_POST['email'] ?? '');
    $new_phone=trim($_POST['phone_no'] ?? '');
    $new_address=trim($_POST['address'] ?? '');
    $new_postcode=trim($_POST['postcode'] ?? '');
    $new_card_no=trim($_POST['card_no'] ?? '');
    $new_card_name=trim($_POST['card_name'] ?? '');
    $new_expiry=trim($_POST['card_expiry'] ?? '');
    $new_cvc=trim($_POST['cvc'] ?? '');
    $new_password=$_POST['new_password'] ?? '';

    // SERVER-SIDE VALIDATION – runs every time form is submitted

    // Email: must not be empty and must be valid format
    if (empty($new_email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    // Phone: must not be empty and must be exactly 11 digits
    if (empty($new_phone)) {
        $errors['phone_no'] = "Phone number is required";
    } elseif (!preg_match('/^\d{11}$/', $new_phone)) {
        $errors['phone_no'] = "Must be exactly 11 digits (no spaces or symbols)";
    }

    // Address: must not be empty
    if (empty($new_address)) {
        $errors['address'] = "Address is required";
    }

    // Postcode: must not be empty; basic UK format check (e.g. M1 1AA)
    if (empty($new_postcode)) {
        $errors['postcode'] = "Postcode is required";
    } elseif (!preg_match('/^[A-Z]{1,2}\d[A-Z\d]? \d[A-Z]{2}$/i', $new_postcode)) {
        $errors['postcode'] = "Invalid UK postcode format (e.g. SW1A 1AA)";
    }

    // Card number: must not be empty and 13–19 digits only
    if (empty($new_card_no)) {
        $errors['card_no'] = "Card number is required";
    } elseif (!preg_match('/^\d{13,19}$/', $new_card_no)) {
        $errors['card_no'] = "Card number must be 13–19 digits (no spaces)";
    }

    // Card name: must not be empty
    if (empty($new_card_name)) {
        $errors['card_name'] = "Name on card is required";
    }

    // Expiry: must not be empty and match MM/YY format
    if (empty($new_expiry)) {
        $errors['card_expiry'] = "Expiry date is required";
    } elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $new_expiry)) {
        $errors['card_expiry'] = "Use MM/YY format (e.g. 12/28)";
    }

    // CVC: must not be empty and 3–4 digits
    if (empty($new_cvc)) {
        $errors['cvc'] = "CVC is required";
    } elseif (!preg_match('/^\d{3,4}$/', $new_cvc)) {
        $errors['cvc'] = "CVC must be 3 or 4 digits";
    }

    // Password (optional): if provided, must be at least 8 characters
    if (!empty($new_password) && strlen($new_password) < 8) {
        $errors['new_password'] = "Password must be at least 8 characters";
    }

    // If no errors - proceed with database update
    if (empty($errors)) {
        try {
            if (!empty($new_password)) {
                // If password changed → hash it
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("
                    UPDATE tbl_users 
                    SET email = :email, phone_no = :phone_no, address = :address,
                        postcode = :postcode, card_no = :card_no, card_name = :card_name,
                        card_expiry = :card_expiry, cvc = :cvc, password = :password
                    WHERE user_id = :user_id
                ");
                $stmt->bindParam(":password", $hashed);
            } else {
                // No password change - skip password field
                $stmt = $conn->prepare("
                    UPDATE tbl_users 
                    SET email = :email, phone_no = :phone_no, address = :address,
                        postcode = :postcode, card_no = :card_no, card_name = :card_name,
                        card_expiry = :card_expiry, cvc = :cvc
                    WHERE user_id = :user_id
                ");
            }

            $stmt->bindParam(":email", $new_email);
            $stmt->bindParam(":phone_no", $new_phone);
            $stmt->bindParam(":address", $new_address);
            $stmt->bindParam(":postcode", $new_postcode);
            $stmt->bindParam(":card_no", $new_card_no);
            $stmt->bindParam(":card_name", $new_card_name);
            $stmt->bindParam(":card_expiry", $new_expiry);
            $stmt->bindParam(":cvc", $new_cvc);
            $stmt->bindParam(":user_id", $user_id);

            $stmt->execute();
            $success = true;

        } catch (Exception $e) {
            $error = "Update failed: " . $e->getMessage();
        }
    }
}
?>

<div class="container my-5">
    <h1 class="mb-4">Edit Profile</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">Profile updated successfully!</div>
    <?php elseif (!empty($errors) || !empty($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error ?? 'Please correct the errors below.') ?>
        </div>
    <?php endif; ?>

    <form method="post" id="editProfileForm">
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                   value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
            <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number (exactly 11 digits)</label>
            <input type="text" name="phone_no" class="form-control <?= isset($errors['phone_no']) ? 'is-invalid' : '' ?>" 
                   value="<?= htmlspecialchars($user['phone_no'] ?? '') ?>" required pattern="\d{11}">
            <?php if (isset($errors['phone_no'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['phone_no']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>" 
                   value="<?= htmlspecialchars($user['address'] ?? '') ?>" required>
            <?php if (isset($errors['address'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['address']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Postcode</label>
            <input type="text" name="postcode" class="form-control <?= isset($errors['postcode']) ? 'is-invalid' : '' ?>" 
                   value="<?= htmlspecialchars($user['postcode'] ?? '') ?>" required>
            <?php if (isset($errors['postcode'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['postcode']) ?></div>
            <?php endif; ?>
        </div>

        <h5 class="mt-4 mb-3">Card Details</h5>

        <div class="mb-3">
            <label class="form-label">Card Number (13–19 digits, no spaces)</label>
            <input type="text" name="card_no" class="form-control <?= isset($errors['card_no']) ? 'is-invalid' : '' ?>" 
                   value="<?= htmlspecialchars($user['card_no'] ?? '') ?>" required pattern="\d{13,19}">
            <?php if (isset($errors['card_no'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['card_no']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Name on Card</label>
            <input type="text" name="card_name" class="form-control <?= isset($errors['card_name']) ? 'is-invalid' : '' ?>" 
                   value="<?= htmlspecialchars($user['card_name'] ?? '') ?>" required>
            <?php if (isset($errors['card_name'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['card_name']) ?></div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Expiry Date (MM/YY)</label>
                <input type="text" name="card_expiry" class="form-control <?= isset($errors['card_expiry']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($user['card_expiry'] ?? '') ?>" required pattern="(0[1-9]|1[0-2])\/\d{2}">
                <?php if (isset($errors['card_expiry'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['card_expiry']) ?></div>
                <?php endif; ?>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">CVC (3–4 digits)</label>
                <input type="text" name="cvc" class="form-control <?= isset($errors['cvc']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($user['cvc'] ?? '') ?>" required pattern="\d{3,4}">
                <?php if (isset($errors['cvc'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['cvc']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <h5 class="mt-4 mb-3">Change Password (optional)</h5>
        <div class="mb-3">
            <label class="form-label">New Password (min 8 characters)</label>
            <input type="password" name="new_password" class="form-control <?= isset($errors['new_password']) ? 'is-invalid' : '' ?>" 
                   placeholder="Leave blank to keep current password" minlength="8">
            <?php if (isset($errors['new_password'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['new_password']) ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">Save Changes</button>
    </form>
</div>

<!-- Client-side validation (optional but enhances UX) -->
<script>
document.getElementById('editProfileForm')?.addEventListener('submit', function(e) {
    let valid = true;
    const fields = ['email', 'phone_no', 'address', 'postcode', 'card_no', 'card_name', 'card_expiry', 'cvc'];

    fields.forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (input && !input.value.trim()) {
            input.classList.add('is-invalid');
            valid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    // Password: if entered, must be ≥8 characters
    const pw = document.querySelector('[name="new_password"]');
    if (pw && pw.value && pw.value.length < 8) {
        pw.classList.add('is-invalid');
        valid = false;
    }

    if (!valid) {
        e.preventDefault();
        alert('Please correct the errors in the form.');
    }
});
</script>