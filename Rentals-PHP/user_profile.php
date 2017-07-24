<?php
session_start();
if (!isset($_SESSION['status'])) {
    header("location:index.php");
}
 if($_SERVER["REQUEST_METHOD"] == "POST") {
     include 'dbConn.php';
     if (isset($_POST['profile'])) {
         if ($_POST["firstname"] && $_POST["lastname"] && $_POST["email"]
             && $_POST["city"] && $_POST["state"] && $_POST["country"]) {
             $updatesql = "update user_info set first_name='$_POST[firstname]', last_name='$_POST[lastname]', street='$_POST[street]',
                              city='$_POST[city]',state='$_POST[state]',country='$_POST[country]',email='$_POST[email]',phone='$_POST[phone]'
                                  WHERE user_id =" . $_SESSION['user_id'];
             $result = mysqli_query($conn, $updatesql) or die(mysqli_error($conn));
             $_SESSION['usr_msg'] = "User Profile Updated Successfully.";
         } else
             $_SESSION['usr_msg'] = "Invalid Data. Please enter valid info!!";
     } elseif(isset($_POST['password'])) {
         if ($_POST["old_pass"] && $_POST["new_pass"] && $_POST["conf_pass"]) {
             $sql="SELECT * FROM users WHERE id= " . $_SESSION['user_id'] . " AND password= '$_POST[old_pass]'";
             $result=mysqli_query($conn, $sql);
             $num_of_rows=mysqli_num_rows($result);
             if($num_of_rows==1){
                 if ($_POST["new_pass"] == $_POST["conf_pass"]) {
                     $updatesql = "UPDATE users SET password='$_POST[new_pass]' WHERE id =" . $_SESSION['user_id'];
                     $result = mysqli_query($conn, $updatesql) or die(mysqli_error($conn));
                     $_SESSION['usr_msg'] = "Password Updated Successfully.";
                 } else
                     $_SESSION['usr_msg'] = "Confirm Password Didn't Match. Try Again!!";
             } else
                 $_SESSION['usr_msg'] = "Old Password Is Invalid. Try Again!!";
         } else {
             $_SESSION['usr_msg'] = "Enter Valid Data To Update Password!!";
         }
     }
     mysqli_close($conn);
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
            <li><a href='user_profile.php'>Profile</a></li>
            <li><a href='postad.php'>Post Ad</a></li>
            <li><a href='logout.php'>Logout</a></li>
        </ul>
    </div>
    <div id="content-sp" align="center">
        <?php
            include 'dbConn.php';
            $sql="SELECT first_name, last_name, street, city, state, country, email, phone FROM user_info WHERE user_id = " . $_SESSION['user_id'];
            $result=mysqli_query($conn, $sql);
            mysqli_close($conn);
            $user_info=mysqli_fetch_assoc($result);
            echo "<form action='' method=post>";
            echo "<h1 class='caption'>Hi " . $_SESSION['username'] . "</h1>";
            echo "<h2><i>Scroll Down To See All Your Ads And Click Any Ad To Edit/Delete</i></h2>";

            echo "<span style='color:red;font-size:20px;'>";
            if(isset($_SESSION['usr_msg'])) {
                echo $_SESSION['usr_msg'];
                unset($_SESSION['usr_msg']);
            }
            echo "</span>";
            echo "<table cellspacing='10'>";
            echo "<tr><td><label>First Name</label>*</td><td><input name='firstname' type='text' class='searchtxt' value='". $user_info['first_name'] . "'></td></tr>";
            echo "<tr><td><label>Last Name</label>*</td><td><input name='lastname' type='text' class='searchtxt' value='". $user_info['last_name'] . "'></td></tr>";
            echo "<tr><td><label>Email</label>*</td><td><input name='email' type='text' class='searchtxt' value='". $user_info['email'] . "'></td></tr>";
            echo "<tr><td><label>Street</label></td><td><input name='street' type='text' class='searchtxt' value='". $user_info['street'] . "'></td></tr>";
            echo "<tr><td><label>City</label>*</td><td><input name='city' type='text' class='searchtxt' value='". $user_info['city'] . "'></td></tr>";
            echo "<tr><td><label>State</label>*</td><td><input name='state' type='text' class='searchtxt' value='". $user_info['state'] . "'></td></tr>";
            echo "<tr><td><label>Country</label>*</td><td><input name='country' type='text' class='searchtxt' value='". $user_info['country'] . "'></td></tr>";
            echo "<tr><td><label>Phone Number</label></td><td><input name='phone' type='text' class='searchtxt' value='". $user_info['phone'] . "'></td></tr>";
            echo "<tr><td><input type='submit' value='Update Profile' name='profile' class='searchtxt'></td><td><input type='reset' value='Reset' class='searchtxt'></td></tr>";
            echo "</table></FORM>";
        ?>
        <div>
        <form action="" method=post>
            <table cellspacing="10">
                <tr>
                    <td><label>Old Password</label>*</td>
                    <td><input name="old_pass" type="password" class="searchtxt" placeholder="Enter your old password"></td>
                </tr>
                <tr>
                    <td><label>New Password</label>*</td>
                    <td><input name="new_pass" type="password" class="searchtxt" placeholder="Enter new password"></td>
                </tr>
                <tr>
                    <td><label>Confirm Password</label>*</td>
                    <td><input name="conf_pass" type="password" class="searchtxt" placeholder="Re-enter new password"></td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Update Password" name='password' class="searchtxt">
                    </td>
                    <td>
                        <input type="reset" value="Clear" class="searchtxt">
                    </td>
                </tr>
            </table>
        </FORM></div>
    </div>
    <div id="ads" class="listings" align="center">
        <ul class="properties_list">
            <?php
            $sql = "SELECT prop_id, prop_title, prop_price, prop_type FROM prop_ads WHERE user_id = " . $_SESSION['user_id'];
            include 'dbConn.php';
            $result = mysqli_query($conn,$sql);
            if ($result) {
                while($row = mysqli_fetch_assoc($result)) {
                    $ad_url = "edit_ad.php?prop_id=" . $row['prop_id'];
                    $img_dir = $row['prop_id'];
                    $files = glob($img_dir . "/*.*");
                    if (count($files) > 0) {
                        $image = $files[0];
                    } else {
                        $image= 'Apartments.JPG';
                    }
                    echo "<li><a href='" . $ad_url . "'><img src=".$image." alt='random image' height = 200 width = 340/></a>";
                    echo "<span class='price'>$" .$row['prop_price'] . "</span>";
                    echo "<div class='property_details'>";
                    echo "<h1><a href='" . $ad_url . "'>" . $row['prop_title'] . "</a></h1>";
                    echo  "<h2><b>" . $row['prop_type'] . "</b></h2>";
                    echo "</div></li>";
                }
            }
            mysqli_close($conn);
            ?>
    </div>
</div>
</body>
</html>