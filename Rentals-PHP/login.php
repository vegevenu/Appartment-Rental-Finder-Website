<?php
session_start();
if (isset($_SESSION['status'])) {
    header("location: index.php");
} else if($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbConn.php';

    $username=$_POST['username'];
    $password=$_POST['password'];

    $sql="SELECT id FROM users WHERE userName='$username' and password='$password'";
    $result=mysqli_query($conn, $sql);
    $num_of_rows=mysqli_num_rows($result);
    mysqli_close($conn);

    if($num_of_rows==1){
        $my_id_array=mysqli_fetch_assoc($result);
        $user_id=$my_id_array['id'];
        $_SESSION['username']=$username;
        $_SESSION['status']="logged";
        $_SESSION['user_id']=$user_id;
        header("location:index.php");
    }
    else {
        $_SESSION['error'] = "Your Login Name or Password is invalid";
    }
}
?>
<html>
<head>
    <title>Rental Property Finder</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<div id="page">
    <a href="index.php" id="logo">Rental Property Finder</a>
    <div id="nav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="register.php">Sign-up</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </div>
    <div id="content" align="center">
        <form method="post" action="" align="center">
            <h1 class="caption">Member Log-In</h1>
            <span style="color:red;font-size:15px;align:center">
            <?php
                if(isset($_SESSION['error'])) {
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                }
            ?>
            </span>
            <table align="center" cellspacing="10" cellpadding="10">
                <tr>
                    <td><label>User Name:</label>*</td>
                    <td><input name="username" type="text" class="searchtxt" placeholder="Enter your user name"/></td>
                </tr>
                <tr>
                    <td><label>Password:</label>*</td>
                    <td><input name="password" type="password" class="searchtxt" placeholder="Enter your password"/></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Login" class="searchtxt"></td>
                    <td><input type="reset" value="Clear" class="searchtxt"></td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>
</html>