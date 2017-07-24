<?php
session_start();
if (!isset($_SESSION['status'])) {
    header("location:login.php");
} else if($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST["prop_title"] && $_POST["prop_type"] && $_POST["prop_zip"]
            && $_POST["prop_price"] && $_POST["prop_city"] && $_POST["prop_state"] && $_POST["prop_country"]) {

        include 'dbConn.php';
        $sql = "insert into prop_ads (user_id, prop_title, prop_type, prop_street, prop_city,
                    prop_state, prop_country, prop_zip, prop_description, prop_price)values
                        ('$_SESSION[user_id]', '$_POST[prop_title]','$_POST[prop_type]','$_POST[prop_street]','$_POST[prop_city]',
                            '$_POST[prop_state]','$_POST[prop_country]','$_POST[prop_zip]','$_POST[prop_desc]', $_POST[prop_price])";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        $inserted_prop_id = mysqli_insert_id($conn);

        if($_FILES['prop_image1']['name'] || $_FILES['prop_image2']['name'] || $_FILES['prop_image3']['name'] || $_FILES['prop_image4']['name']) {
            if(!$_FILES['prop_image1']['error'] || !$_FILES['prop_image2']['error'] || !$_FILES['prop_image3']['error'] || !$_FILES['prop_image4']['error']) {
                if (!is_dir($inserted_prop_id)) {
                    mkdir($inserted_prop_id);
                }
                $image_target = $inserted_prop_id;
                move_uploaded_file($_FILES['prop_image1']['tmp_name'], "$image_target/".$_FILES['prop_image1']['name']);
                move_uploaded_file($_FILES['prop_image2']['tmp_name'], "$image_target/".$_FILES['prop_image2']['name']);
                move_uploaded_file($_FILES['prop_image3']['tmp_name'], "$image_target/".$_FILES['prop_image3']['name']);
                move_uploaded_file($_FILES['prop_image4']['tmp_name'], "$image_target/".$_FILES['prop_image4']['name']);
            } else
                $_SESSION['msg'] = "Error Uploading Image!!";
        }
        $_SESSION['msg'] = "Ad posted successfully. Go to <a href='index.php'> Home </a> Page or post another Ad";

    } else
    $_SESSION['msg'] = "Enter all required Info!!";
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
        <form action="" method="post" enctype="multipart/form-data">
            <h1 class="caption">Post Ad For Your Rental Property</h1>
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
                    <td><label>Property Title</label>*</td>
                    <td><input name="prop_title" type="text" class="searchtxt" placeholder="Enter Property Title"></td>
                </tr>
                <tr>
                    <td><label>Property Type</label>*</td>
                    <td><select name="prop_type" class="searchtxt">
                            <option value="Apartment" selected>Apartment</option>
                            <option value="Condo">Condo</option>
                            <option value="Townhouse">Town House</option>
                            <option value="Individual">Individual House</option>
                            <option value="Other">Other</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label>Street</label></td>
                    <td><input name="prop_street" type="text" class="searchtxt" placeholder="Enter Street address"></td>
                </tr>
                <tr>
                    <td><label>City</label>*</td>
                    <td><input name="prop_city" type="text" class="searchtxt" placeholder="Enter City"></td>
                </tr>
                <tr>
                    <td><label>State</label>*</td>
                    <td><input name="prop_state" type="text" class="searchtxt" placeholder="Enter State"></td>
                </tr>
                <tr>
                    <td><label>Country</label>*</td>
                    <td><input name="prop_country" type="text" value="USA" class="searchtxt" placeholder="Enter Country"></td>
                </tr>
                <tr>
                    <td><label>Zip Code</label>*</td>
                    <td><input name="prop_zip" type="text" class="searchtxt" placeholder="xxxxx"></td>
                </tr>
                <tr>
                    <td><label>Your Price</label>*</td>
                    <td><input name="prop_price" type="text" class="searchtxt" placeholder="$$"></td>
                </tr>
                <tr>
                    <td><label>Description</label></td>
                    <td><textarea rows="8" cols="33" name="prop_desc" placeholder="Enter property Description"></textarea></td>
                </tr>
                <tr>
                    <td><label>Upload Image</label></td>
                    <td><input name="prop_image1" accept="image/jpeg" type="file" id="prop_image1"><input name="prop_image2" accept="image/jpeg" type="file" id="prop_image2"></td></tr>
                <tr><td/><td><input name="prop_image3" accept="image/jpeg" type="file" id="prop_image3"><input name="prop_image4" accept="image/jpeg" type="file" id="prop_image4"></td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Post Ad" class="searchtxt">
                    </td>
                    <td>
                        <input type="reset" value="Clear" class="searchtxt">
                    </td>
                </tr>
            </table>
        </FORM>
</div>
</body>
</html>