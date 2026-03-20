<h1>Edit Profile</h1>

<!-- Success / Error messages would go here -->

<form method="post">
    Email Address:<br>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required><br><br>
    Phone Number:<br>
    <input type="text" name="phone_no" value="<?= htmlspecialchars($user['phone_no'] ?? '') ?>" required><br><br>
    Address:<br>
    <input type="text" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>" required><br><br>
    Postcode:<br>
    <input type="text" name="postcode" value="<?= htmlspecialchars($user['postcode'] ?? '') ?>" required><br><br>
    <h3>Card Details</h3>
    Card Number:<br>
    <input type="text" name="card_no" value="<?= htmlspecialchars($user['card_no'] ?? '') ?>" required><br><br>
    Name on Card:<br>
    <input type="text" name="card_name" value="<?= htmlspecialchars($user['card_name'] ?? '') ?>" required><br><br>
    Expiry Date (MM/YY):<br>
    <input type="text" name="card_expiry" value="<?= htmlspecialchars($user['card_expiry'] ?? '') ?>" required><br><br>
    CVC:<br>
    <input type="text" name="cvc" value="<?= htmlspecialchars($user['cvc'] ?? '') ?>" required><br><br>
    <h3>Change Password (optional)</h3>
    New Password:<br>
    <input type="password" name="new_password" placeholder="Leave blank to keep current password"><br><br>
    <button type="submit">Save Changes</button>
</form>

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