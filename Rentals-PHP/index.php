<?php
session_start();
$sql = "SELECT prop_id, prop_title, prop_price, prop_type FROM prop_ads ORDER BY prop_id DESC LIMIT 4";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $is_zip=false;$is_type=false;$is_min_price=false;$is_max_price=false;
    if (empty($_POST['zip']) && empty($_POST['min_price']) && empty($_POST['max_price']) && empty($_POST['type'])) {
        $_SESSION['search_error'] = "Enter atleast one value to search";
    } else {
        $sql = "SELECT prop_id, prop_title, prop_price, prop_type FROM prop_ads WHERE ";
        if (!empty($_POST['zip'])) {
            $sub_zip = substr($_POST['zip'], 0, 3);
            $sql = $sql." prop_zip like '$sub_zip%' ";
            $is_zip = true;
        }
        if (!empty($_POST['min_price'])) {
            if ($is_zip) {
                $sql = $sql. " AND prop_price >= $_POST[min_price]";
            }
            else
                $sql = $sql. " prop_price >= $_POST[min_price]";
            $is_min_price = true;
        }
        if (!empty($_POST['max_price'])) {
            if ($is_zip || $is_min_price) {
                $sql = $sql. " AND prop_price <= $_POST[max_price]";
            } else
                $sql = $sql. " prop_price <= $_POST[max_price]";
            $is_max_price = true;
        }
        if (!empty($_POST['type'])) {
            if ($is_zip || $is_min_price || $is_max_price) {
                $sql = $sql. " AND prop_type like '$_POST[type]'";
            } else
                $sql = $sql. " prop_type like '$_POST[type]'";
            $is_type = true;
        }
    }
    include 'dbConn.php';
    $result = mysqli_query($conn,$sql);
    if (!mysqli_num_rows($result)) {
        $_SESSION['search_error'] = "No matching results found!!!";
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
            <?php if(!isset($_SESSION['status'])) {
                echo "<li><a href='register.php'>Sign-up</a></li> ";
                echo "<li><a href='login.php''>Login</a></li>";
            } else {
                echo "<li><a href='user_profile.php'>Profile</a></li> ";
                echo "<li><a href='postad.php'>Post Ad</a></li> ";
                echo "<li><a href='logout.php'>Logout</a></li>";
            }
            ?>
        </ul>
    </div>
    <div id="content" align="center">
        <h1 class="caption">Find your Dream House</h1>
        <h2><i>Scroll Down To See Recent Ads Posted</i></h2>
        <span style="color:red;font-size:15px;align:center">
            <?php
            if(isset($_SESSION['search_error'])) {
                echo $_SESSION['search_error'];
                unset($_SESSION['search_error']);
            }
            ?>
        </span>
        <form method="post" action="#search">
            <table cellpadding="10" cellspacing="10">
                <tr>
                    <td><label>Zip Code: </label></td>
                    <td><input name="zip" type="text" class="searchtxt" placeholder="xxxxx"/></td>
                </tr>
                <tr>
                    <td><label>Min. Price</label></td>
                    <td><input name="min_price" type="text" class="searchtxt" placeholder="$$"/></td>
                </tr><tr>
                    <td><label>Max. Price</label></td>
                    <td><input name="max_price" type="text" class="searchtxt" placeholder="$$"/></td>
                </tr>
                <tr>
                    <td><label>Type:</label></td>
                    <td><select name="type" class="searchtxt">
                            <option value="" selected></option>
                            <option value="Apartment">Apartment</option>
                            <option value="Condo">Condo</option>
                            <option value="Townhouse">Town House</option>
                            <option value="Individual">Individual House</option>
                            <option value="Other">Other</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="submit" value="Search" class="searchtxt"></td>
                    <td><input type="reset" value="Clear" class="searchtxt"></td>
                </tr>
            </table>
        </form>
                <span style="color:red;font-size:15px;align:center">
    </div>
    <div id="search" class="listings" align="center">
        <ul class="properties_list">
        <?php
            include 'dbConn.php';
            $result = mysqli_query($conn,$sql);
            if (mysqli_num_rows($result)) {
                while($row = mysqli_fetch_assoc($result)) {
                    $ad_url = "ad_details.php?prop_id=" . $row['prop_id'];
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
        ?>
    </div>
</div>
</body>
</html>
