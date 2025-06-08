<?php
include 'db.php';
session_start();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['psw'];

  $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $name, $hashed, $role);
    $stmt->fetch();

    if (password_verify($password, $hashed)) {
      $_SESSION['user_id'] = $id;
      $_SESSION['name'] = $name;
      $_SESSION['role'] = $role;

      // Redirect based on user role
      if ($role === 'admin') {
        header("Location: admin.php");
      } else {
        header("Location: dashboard.php");
      }
      exit;
    } else {
      $error = "Invalid password.";
    }
  } else {
    $error = "No user found.";
  }

  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Login.css">
</head>
<body>
    <div class="Shopsphere">

    </div>
    <form action="action_page.php" method="post">
        <div class="Logo"><img  src="images/logo-transparent.png" height="200px" width="200px" alt=""></div>
        
      
        <div class="container">
          <label for="uname"><b>Username</b></label>
          <input type="text" placeholder="Enter Username" name="uname" required>
      
          <label for="psw"><b>Password</b></label>
          <input type="password" placeholder="Enter Password" name="psw" required>
      
          <button type="submit">Login</button>
          <label>
            <input type="checkbox" checked="checked" name="remember"> Remember me
          </label>
        </div>
      
        <div class="container" style="background-color:#f1f1f1">
          <span class="psw">No Account?<a href="Signup.html">Sign in Here</a></span>
        </div>
      </form>
    
</body>
</html>
