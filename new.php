<?php

if (isset($_POST["submit"])) {
   $username = $_POST['username'];
   $password = $_POST['password'];
   $email = $_POST['email'];
   $passwordRepeat = $_POST["repeat_password"];
           
   $passwordHash = password_hash($password, PASSWORD_DEFAULT);

   $errors = array();

   /*if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
    array_push($errors,"All fields are required");
   }*/
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($errors, "Email is not valid");
   }
   if (strlen($password)<8) {
    array_push($errors,"Password must be at least 8 charactes long");
   }
   if ($password!==$passwordRepeat) {
    array_push($errors,"Password does not match");
   }

   
   require_once "database.php";
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $rowCount = mysqli_num_rows($result);

    if ($rowCount>0) {
        array_push($errors,"Email already exists!");
       }

   if (count($errors)>0) {
    foreach ($errors as  $error) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
   }else{
    
    $sql = "INSERT INTO users (username, email, password) VALUES ( ?, ?, ? )";
    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
    if ($prepareStmt) {
        mysqli_stmt_bind_param($stmt,"sss",$username, $email, $passwordHash);
        mysqli_stmt_execute($stmt);
        echo "<div class='alert alert-success'>You are registered successfully.</div>";
    }else{
        die("Something went wrong");
    }
   }
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="./assets/style.css">
   <!-- Bootstrap -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

   <title>PHP CRUD Application</title>
</head>

<body>

   <div class="container1">
      <div class="text-center">
         <h3>Add New User</h3>
         <p class="text-muted">Complete the form below to add a new user</p>
      </div>

      <div class="container d-flex justify-content-center">
         <form action="" method="post" style="width:20vw; min-width:300px; margin:15px">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput"  name="username" placeholder="username">
                 <label for="floatingInput">Username</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput"  name="email" placeholder="email">
                 <label for="floatingInput">Email Address</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput"  name="password" placeholder="Password">
                 <label for="floatingInput">Password</label>
            </div>
            
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput"  name="repeat_password" placeholder="Repeat Password">
                 <label for="floatingInput">Repeat Password</label>
            </div>

            <div style="text-align: center; display:flex; justify-content:space-evenly;">
               <button type="submit" class="btn btn-primary" name="submit" style="padding: 0 15px 0 15px;">Save</button>
               <a href="index.php" class="btn btn-danger">Cancel</a>
            </div>
         </form>
      </div>
   </div>

   <!-- Bootstrap -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>