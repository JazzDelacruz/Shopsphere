<?php
include 'db.php';
$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name1'];
  $lastname = $_POST['lname'];
  $birthdate = $_POST['bday'];
  $email = $_POST['email'];
  $password = $_POST['psw'];
  $confirm = $_POST['psw-repeat'];

  if ($password !== $confirm) {
    $error = "Passwords do not match.";
  } else {
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $error = "Email is already registered.";
    } else {
      $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sssss", $name, $lastname, $birthdate, $email, $password);
      if ($stmt->execute()) {
        $success = "Registration successful! <a href='login.php'>Login now</a>.";
      } else {
        $error = "Error: " . $stmt->error;
      }
      $stmt->close();
    }
    $check->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Signup.css">
    <link rel="stylesheet" href=
"https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity=
"sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk"
        crossorigin="anonymous">
    <script src="signup.js"></script>
</head>
<body>
  <div class="container">
    <h2>Create an Account</h2>
    <?php if ($error) echo "<p class='error-msg'>$error</p>"; ?>
    <?php if ($success) echo "<p class='success-msg'>$success</p>"; ?>
    <form method="POST" id="signup" class="signup" action="" style="border:1px solid #ccc">
    <h1>Sign Up</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>

    <label for="name"><b>Name</b></label>
    <input type="text" id="username" placeholder="Enter Name" name="name1" required>

    <label for="lastname"><b>LastName</b></label>
    <input type="text" placeholder="Enter LastName" name="lname" required>

    <label for="date"><b>BirthDate</b></label>
    <input type="date" placeholder="Enter BirthDate" name="bday" required>

    <label for="email"><b>Email</b></label>
    <input type="email" id="email" placeholder="Enter Email" name="email" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" id="password" placeholder="Enter Password" name="psw" required>

    <label for="psw-repeat"><b>Repeat Password</b></label>
    <input type="password" placeholder="Repeat Password" name="psw-repeat" required>

    <label>
      <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Remember me
    </label>

    <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>

    <label>Enter the CAPTCHA:</label><br>
    <canvas id="captchaCanvas" width="150" height="50"></canvas>
    <button type="button" onclick="generateCaptcha()">Refresh CAPTCHA</button><br><br>

    <input type="text" id="captchaInput" placeholder="Enter CAPTCHA here" ><br><br>

    <p id="key"></p>

    <button id="signup" type="submit">Sign Up</button>
    <button type="button" class="cancelbtn" onclick="location.href='Login.php'">Cancel</button>
  </div>
</form>
    
</body>
</html>