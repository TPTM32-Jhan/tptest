<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In page</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="u_bcolor">
<section class="login-section">
  <div class="container">
    <form class="frm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <header class="header">Welcome</header>
      <p>Log in</p>
      <p>Please fill in this form to log in</p>

      <label for="username">Username</label>
      <input type="text" placeholder="Enter Username" class="un_input" name="username" required>

      <label for="psw">Password</label>
      <input type="password" placeholder="Enter Password" class="un_input" name="psw" required>
          
      <button type="submit" name="login" id="logInBtn" class="frmbtn">Log in</button>

      <span>Create a new Account? <a href="sign_up.php" class="link"> Sign up</a></span>
      <span id="adm">Click to <a href="#" class="link">Log in as Admin</a></span>
      
    </form>
  </div>
</section>

</body>
</html>


<?php


if(isset($_POST['login'])){
    require_once('connectdb.php');
    echo "You are now in Log in database";

    if(isset($_POST['username'],$_POST['psw'])){
        $username = filter_input(INPUT_POST,$_POST['username'],FILTER_SANITIZE_STRING);
        $password = password_hash($_POST['psw'],PASSWORD_DEFAULT);

    } else {
        exit("Please Log in with your details again");
    }


    try{
      $username = $_POST['username'];
      $password = $_POST['psw'];
      
      $stat = $db->prepare("SELECT password FROM customer WHERE username = :username");
      $stat->bindParam(':username', $username, PDO::PARAM_STR, 50);
      
      $stat->execute();
      
      if($stat->rowCount() > 0){
        
        $row = $stat->fetch();
        // Checks that password entered in login form and database are matched
        if(password_verify($password, $row['password'])){
          // Both Passwords match from database compared to the browser
          // I will use this but not certain what its output will be like
          session_start();
          $_SESSION["login_username"] = $username;
          $_SESSION["password"] = $password;
          
          header("Location:products.php");
          exit();

        } else {
          echo "Incorrect Password dont match";
          exit();
        }
      } else {
        echo "Username not found";
        exit();
      }
      // After all that customer is taken to the products page. 
      // header("Location:Products_page");
    } catch(PDOexception $error){
      echo "Sorry, a database error occurred! <br>";
      echo "Error details: <em>". $error->getMessage()."</em>";
    }
}


?>