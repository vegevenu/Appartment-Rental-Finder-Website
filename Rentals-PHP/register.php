<?php
session_start();
if (isset($_SESSION['status'])) {
    header("location:user_profile.php");
} else if($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST["reguser"] && $_POST["regpass"] && $_POST["confpass"]
            && $_POST["firstname"] && $_POST["lastname"] && $_POST["email"]
                && $_POST["city"] && $_POST["state"] && $_POST["country"]) {
        if ($_POST["regpass"] == $_POST["confpass"]) {
            include 'dbConn.php';
            $usersql = "insert into users (userName,password)values('$_POST[reguser]','$_POST[regpass]')";
            mysqli_query($conn, $usersql) or die(mysqli_error($conn));
            $inserted_id = mysqli_insert_id($conn);
            $infosql = "insert into user_info (user_id, first_name, last_name, street, city, state, country, email, phone) values
                      ($inserted_id, '$_POST[firstname]', '$_POST[lastname]', '$_POST[street]', '$_POST[city]',
                              '$_POST[state]', '$_POST[country]', '$_POST[email]', '$_POST[phone]')";
            $result = mysqli_query($conn, $infosql) or die(mysqli_error($conn));
            $_SESSION['username']=$_POST[reguser];
            $_SESSION['status']="logged";
            $_SESSION['user_id']=$inserted_id;
            header("location:index.php");
        } else
            $_SESSION['msg'] = "Password Does not match. Please try again!!";
    } else
        $_SESSION['msg'] = "Invalid Data. Please enter valid info!!";
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
    <div id="content-sp" align="center">
        <form action="" method=post>
            <h1 class="caption"> Welcome To Registration </h1>
            <span style="color:red;font-size:20px;">
                <?php
                    if(isset($_SESSION['msg'])) {
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                    }
                ?>
            </span>
            <table cellspacing="10">
                <tr>
                    <td><label>User Name</label>*</td>
                    <td><input name="reguser" type="text" class="searchtxt" placeholder="Enter User Name"></td>
                </tr>
                <tr>
                    <td><label>Password</label>*</td>
                    <td><input name="regpass" type="password" class="searchtxt" placeholder="Enter Password"></td>
                </tr>
                <tr>
                    <td><label>Confirm Password</label>*</td>
                    <td><input name="confpass" type="password" class="searchtxt" placeholder="Re-enter same password"></td>
                </tr>
                <tr>
                    <td><label>First Name</label>*</td>
                    <td><input name="firstname" type="text" class="searchtxt" placeholder="Enter First Name"></td>
                </tr>
                <tr>
                    <td><label>Last Name</label>*</td>
                    <td><input name="lastname" type="text" class="searchtxt" placeholder="Enter Last Name"></td>
                </tr>
                <tr>
                    <td><label>Email</label>*</td>
                    <td><input name="email" type="text" class="searchtxt" placeholder="xxxx@xxxx.xxx"></td>
                </tr>
                <tr>
                    <td><label>Street</label></td>
                    <td><input name="street" type="text" class="searchtxt" placeholder="Enter Street Address"></td>
                </tr>
                <tr>
                    <td><label>City</label>*</td>
                    <td><input name="city" type="text" class="searchtxt" placeholder="Enter City"></td>
                </tr>
                <tr>
                    <td><label>State</label>*</td>
                    <td><input name="state" type="text" class="searchtxt" placeholder="Enter State"></td>
                </tr>
                <tr>
                    <td><label>Country</label>*</td>
                    <td><input name="country" type="text" value="USA" class="searchtxt" placeholder="Enter Country"></td>
                </tr>
                <tr>
                    <td><label>Phone Number</label></td>
                    <td><input name="phone" type="text" class="searchtxt" placeholder="xxx-xxx-xxxx"></td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Register" class="searchtxt">
                    </td>
                    <td>
                        <input type="reset" value="Clear" class="searchtxt">
                    </td>
                </tr>
            </table>
        </FORM>
    </div>
</div>
</body>
</html>