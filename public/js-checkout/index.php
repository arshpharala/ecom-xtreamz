<?php
?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
    }

    * {
      box-sizing: border-box;
    }

    input[type=text],
    select,
    textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      margin-top: 6px;
      margin-bottom: 16px;
      resize: vertical;
    }

    input[type=submit] {
      background-color: #4CAF50;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    input[type=submit]:hover {
      background-color: #45a049;
    }

    .container {
      border-radius: 5px;
      background-color: #f2f2f2;
      padding: 20px;
    }
  </style>
</head>

<body>
  <h3>Checkout Form</h3>
  <div class="container">
    <form action="/index_1.php" method="POST">
      <label for="meid">Id <span style="color:red">*</span></label>
      <input type="text" id="meid" name="meid" placeholder="Merchant Id..." required="true">

      <label for="key">Key <span style="color:red">*</span></label>
      <input type="text" id="key" name="key" placeholder="Encryption Key..." required="true">

      <label for="amount">Amount <span style="color:red">*</span></label>
      <input type="text" id="amount" name="amount" placeholder="Amount..." required="true">

      <label for="uniqueId">UniqueId</label>
      <input type="text" id="uniqueId" name="uniqueId" placeholder="uniqueId...">

      <label for="planId">planId</label>
      <input type="text" id="planId" value="997336434" name="planId" placeholder="planId...">

      <input type="submit" value="Submit">
    </form>
  </div>
</body>

</html>