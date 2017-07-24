<?php
session_start();
?>
<html>
<head>
    <title>Rental Property Finder</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
    <script type="text/javascript">
        function changeImage(e){
            document.getElementById('image').src = e;
        }
    </script>
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
    <div id="content-sp" align="left">
        <h1 align="center" class='caption'>Property and Owner Details</h1>
        <p><br/><br/></p>
        <table cellpadding="5" cellspacing="4">
            <?php
            include "dbConn.php";
            $sql = "SELECT prop_title, prop_price, prop_type, prop_street, prop_city,
                        prop_state, prop_country, prop_zip, prop_description, first_name, last_name,
                            email, phone FROM prop_ads pa, user_info ui WHERE pa.user_id=ui.user_id AND pa.prop_id = " . $_GET['prop_id'];
            $result = mysqli_query($conn,$sql);
            $rs = mysqli_fetch_assoc($result);
            $img_dir = $_GET['prop_id'];
            $files = glob($img_dir . "/*.*");
            if (count($files) > 0) {
                $image = $files[0];
            } else {
                $image= 'Apartments.JPG';
            }
            echo "<tr><th COLSPAN='7' rowspan='7'><img src=".$image." alt='random image' height=500 width=500 id='image'/></td></th>";
            echo "<td><td></td>";
            echo "<tr><td><h2>Title:</h2><h3>". $rs['prop_title'] ."</h3></td></tr><tr><td><h2>Property Type:</h2><h3>"
                . $rs['prop_type']. "</h3></td></tr><tr><td><h2>Property Cost:</h2><h3>$"
                . $rs['prop_price']."</h3></td></tr><tr><td><h2>Property Address:</h2><h3>"
                . $rs['prop_street']. "," . $rs['prop_city']. "</br>"
                . $rs['prop_state']. "," . $rs['prop_country']. "-" . $rs['prop_zip']
                ."</h3></td></tr><tr><td><h2>Description:</h2><h3>"
                . $rs['prop_description']. "</h3></td></tr><tr><td><h2>Owner Contact Info:</h2><h3>"
                . $rs['first_name']. " " . $rs['last_name']. "</br>"
                . $rs['email'] . "</br>" . $rs['phone']. "</h3></td></tr>";
            echo "<tr><td>";
            if (count($files) > 1) {
                for ($i = 0; $i < count($files); $i++) {
                    echo "<img src='" . $files[$i] . "' name='" . $files[$i] . "' onclick='changeImage(this.name);' alt='random image' height=75 width=75 />&nbsp;&nbsp;";
                }
            }
            echo "</td></tr>";

            ?>

        </table>
    </div>

</div>
</body>
</html>