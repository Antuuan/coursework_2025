<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <!-- 
    Defines character encoding (very important to prevent weird character issues)
    UTF-8 supports almost all languages and special characters
  -->
  
  <title>User Registration Form</title> <!-- Title shown in browser tab -->

  <style>
    /* 
      Basic page and form styling 
      Makes the form look clean and readable
    */
    body { 
      font-family: Arial, sans-serif;
      max-width: 600px;                        /* Prevents form from becoming too wide on large screens */
      margin: 30px auto;                       /* Centers the content with some top/bottom spacing */
    }
    .form-group { 
      margin: 15px 0;                          /* Adds vertical spacing between form fields */
    }
    label { 
      display: inline-block; 
      width: 160px;                            /* Fixed width so all labels line up nicely */
      vertical-align: top;                     /* Aligns label text to top of input when multi-line */
    }
    input { 
      width: 280px;                            /* Consistent input width */
      padding: 6px;                            /* Makes text inside input more comfortable to read */
    }
    .error { 
      color: #c00;                             /* Red color for error messages */
      font-size: 0.9em;                        /* Slightly smaller than normal text */
      margin-left: 8px;                        /* Small gap between input and error text */
    }
    button { 
      margin-left: 160px;                      /* Aligns with the input fields (same offset as labels) */
      padding: 10px 24px;                      /* Nice button size */
    }
  </style>
<?php
session_start();
include_once("connection.php");
?>
</head>

<body>

  <!-- 
    The form that sends data to the server
    action = where the data is sent (add_users.php)
    method = POST (better for sensitive data like passwords)
    id = used by JavaScript to find this form
    novalidate = turns OFF default browser bubbles → we show our own nicer errors
  -->
  <form action="add_users.php" method="post" id="regForm" novalidate>

    <!-- USERNAME FIELD -->
    <!-- Must be filled in -->
    <!-- Minimum 3 characters -->
    <!-- Maximum 30 characters -->
    <!-- Only allows letters, numbers, _ and - -->
    <div class="form-group">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username"
             required                     
             minlength="3"                
             maxlength="30"               
             pattern="[a-zA-Z0-9_-]{3,30}"
             title="3–30 characters, letters, numbers, underscore or hyphen only">
      <span class="error"></span>        <!-- Empty span where we show error messages -->
    </div>

    <!-- EMAIL FIELD -->
    <!-- Shows example when field is empty -->
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email"
             required 
             placeholder="user@example.com"   
             title="Please enter a valid email address">
      <span class="error"></span>
    </div>

    <!-- PASSWORD FIELD -->
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" id="password" name="password"
             required 
             minlength="8"
             title="At least 8 characters">
      <span class="error"></span>
    </div>

    <!-- PHONE NUMBER FIELD -->
    <!-- Exactly 11 digits, no spaces/dashes -->
    <div class="form-group">
      <label for="phone_no">Phone Number:</label>
      <input type="tel" id="phone_no" name="phone_no"
             required 
             pattern="[0-9]{11}"
             maxlength="11" minlength="11"
             placeholder="07123456789"
             title="Exactly 11 digits (no spaces or dashes)">
      <span class="error"></span>
    </div>

    <!-- ADDRESS FIELD -->
    <div class="form-group">
      <label for="address">Address:</label>
      <input type="text" id="address" name="address"
             required 
             minlength="5" 
             maxlength="150">
      <span class="error"></span>
    </div>

    <!-- POSTCODE FIELD (UK format) -->
    <div class="form-group">
      <label for="postcode">Postcode:</label>
      <input type="text" id="postcode" name="postcode"
             required 
             pattern="[A-Za-z]{1,2}[0-9Rr][0-9A-Za-z]? ?[0-9][A-Za-z]{2}"
             placeholder="SW1A 1AA" 
             title="Valid UK postcode format">
      <span class="error"></span>
    </div>

    <hr style="margin: 25px 0;"> <!-- Horizontal line to separate sections -->

    <!-- CARD NUMBER -->
    <!-- Most cards are 13–19 digits -->
    <!-- Shows numeric keyboard on mobile -->
    <div class="form-group">
      <label for="card_no">Card Number:</label>
      <input type="text" id="card_no" name="card_no"
             required 
             pattern="[0-9]{13,19}"
             inputmode="numeric"
             title="13 to 19 digits">
      <span class="error"></span>
    </div>

    <!-- NAME ON CARD -->
    <div class="form-group">
      <label for="card_name">Name on Card:</label>
      <input type="text" id="card_name" name="card_name"
             required 
             minlength="2" 
             maxlength="60">
      <span class="error"></span>
    </div>

    <!-- EXPIRY DATE -->
    <!-- 01/YY to 12/YY -->
    <div class="form-group">
      <label for="card_expiry">Expiry (MM/YY):</label>
      <input type="text" id="card_expiry" name="card_expiry"
             required 
             pattern="(0[1-9]|1[0-2])\/[0-9]{2}"  
             maxlength="5"
             placeholder="03/29" 
             title="Format: MM/YY">
      <span class="error"></span>
    </div>

    <!-- CVC / CVV -->
    <!-- 3 or 4 digits -->
    <div class="form-group">
      <label for="cvc">CVC:</label>
      <input type="text" id="cvc" name="cvc"
             required 
             pattern="[0-9]{3,4}"             
             maxlength="4" 
             inputmode="numeric"
             title="3 or 4 digits">
      <span class="error"></span>
    </div>

    <!-- SUBMIT BUTTON -->
    <div style="margin-top: 30px;">
      <button type="submit">Register</button>
    </div>

  </form>

  <!-- 
    JavaScript for real-time form validation feedback
    Shows red error messages next to fields as user types or leaves them
  -->
  <script>
    // Select ALL input elements inside the form with id="regForm"
    document.querySelectorAll('#regForm input').forEach(field => {
      
      // Find the <span class="error"> right after each input
      const errorElement = field.nextElementSibling;

      // When user is typing (real-time feedback)
      field.addEventListener('input', () => {
        if (field.validity.valid) {
          errorElement.textContent = '';           // Clear error if now valid
        } else {
          errorElement.textContent = field.validationMessage; // Show browser's error message
        }
      });

      // When user leaves the field (blur event)
      field.addEventListener('blur', () => {
        if (field.validity.valid) {
          errorElement.textContent = '';
        } else {
          errorElement.textContent = field.validationMessage;
        }
      });
    });
  </script>

</body>
</html>