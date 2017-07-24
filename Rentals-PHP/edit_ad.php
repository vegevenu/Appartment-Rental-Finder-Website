<?php
session_start();
if (!isset($_SESSION['status'])) {
    header("location:login.php");
} else if($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbConn.php';
    if (isset($_POST['update'])) {
        if ($_POST["prop_title"] && $_POST["prop_type"] && $_POST["prop_zip"]
            && $_POST["prop_price"] && $_POST["prop_city"] && $_POST["prop_state"] && $_POST["prop_country"]) {

            $sql = "UPDATE prop_ads SET prop_title='$_POST[prop_title]', prop_type='$_POST[prop_type]',
                  prop_street='$_POST[prop_street]', prop_city='$_POST[prop_city]', prop_state='$_POST[prop_state]',
                    prop_country='$_POST[prop_country]', prop_zip='$_POST[prop_zip]', prop_description='$_POST[prop_desc]', prop_price= $_POST[prop_price]
                        WHERE prop_id = " . $_GET['prop_id'];
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
            $_SESSION['edit_msg'] = "Ad updated successfully";

            if($_FILES['prop_image']['name']) {
                if(!$_FILES['prop_image']['error']) {
                    if (!is_dir($_GET['prop_id'])) {
                        mkdir($_GET['prop_id']);
                    }
                    $image_target = $_GET['prop_id'];
                    move_uploaded_file($_FILES['prop_image']['tmp_name'], "$image_target/".$_FILES['prop_image']['name']);
                } else
                    $_SESSION['msg'] = "Error Uploading Image!!";
            }
        } else
            $_SESSION['edit_msg'] = "Enter all required Info!!";
    } elseif (isset($_POST['delete'])) {
        $sql = "DELETE FROM prop_ads WHERE prop_id = " . $_GET['prop_id'];
        if (mysqli_query($conn, $sql)) {
            if (is_dir($_GET['prop_id'])) {
                $image_target = $_GET['prop_id'];
                $files = glob($image_target . "/*.*");
                foreach ($files as $file) {
                    unlink($file);
                }
                rmdir($image_target);
            }
            $_SESSION['usr_msg'] = "Ad Deleted Successfully!!";
            header("location:user_profile.php");
        }
        else
            $_SESSION['edit_msg'] = "Failed to Delete Ad. Try Again!!";
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
        $sql="SELECT prop_title, prop_type, prop_street, prop_city, prop_state, prop_country, prop_zip, prop_description, prop_price FROM prop_ads WHERE prop_id = " . $_GET['prop_id'];
        $result=mysqli_query($conn, $sql);
        mysqli_close($conn);
        $prop_info=mysqli_fetch_assoc($result);
        echo "<form action='' method=post enctype='multipart/form-data'>";
        echo "<h1 class='caption'> Make Changes And Submit To Update </h1>";
        echo "<span style=color:red;font-size:20px>";
        if(isset($_SESSION['edit_msg'])) {
            echo $_SESSION['edit_msg'];
            unset($_SESSION['edit_msg']);
        }
        echo "</span>";
        echo "<table cellspacing=10>";
        echo "<tr><td><label>Property Title</label>*</td><td><input name='prop_title' type='text' class='searchtxt' value='". $prop_info['prop_title'] . "'></td></tr>";
        echo "<tr><td><label>Property Type</label>*</td><td><select name='prop_type' class='searchtxt'><OPTION VALUE=" . $prop_info['prop_type'] ." >" . $prop_info['prop_type'] . "</option>";
            echo "<option value='Apartment'>Apartment</option><option value='Condo'>Condo</option><option value='Townhouse'>Town House</option>";
            echo "<option value='Individual'>Individual House</option><option value='Other'>Other</option></td></tr>";
        echo "<tr><td><label>Property Price</label>*</td><td><input name='prop_price' type='text' class='searchtxt' value='". $prop_info['prop_price'] . "'></td></tr>";
        echo "<tr><td><label>Street</label></td><td><input name='prop_street' type='text' class='searchtxt' value='". $prop_info['prop_street'] . "'></td></tr>";
        echo "<tr><td><label>City</label>*</td><td><input name='prop_city' type='text' class='searchtxt' value='". $prop_info['prop_city'] . "'></td></tr>";
        echo "<tr><td><label>State</label>*</td><td><input name='prop_state' type='text' class='searchtxt' value='". $prop_info['prop_state'] . "'></td></tr>";
        echo "<tr><td><label>Country</label>*</td><td><input name='prop_country' type='text' class='searchtxt' value='". $prop_info['prop_country'] . "'></td></tr>";
        echo "<tr><td><label>Zip Code</label>*</td><td><input name='prop_zip' type='text' class='searchtxt' value='". $prop_info['prop_zip'] . "'></td></tr>";
        echo "<tr><td><label>Description</label></td><td><textarea rows='8' cols='33' name='prop_desc' placeholder='". $prop_info['prop_description'] . "'></textarea></td></tr>";
        echo "<tr><td><label>Upload New Image</label></td><td><input name='prop_image' accept='image/jpeg' type='file' id='prop_image'></tr>";
        echo "<tr><td><input type='submit' value='Update Ad' name='update' class='searchtxt'></td><td><input type='submit' value='Delete' name='delete' class='searchtxt'></td></tr>";
        echo "</table></FORM>";
        ?>
</div>
</body>
</html>