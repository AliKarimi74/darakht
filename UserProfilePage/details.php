<?php

require_once "../appvars.php";
require_once "../connection.php";

$user_id = $_GET['id'];

if(!empty($user_id))
{
    session_start();
    if (isset($_SESSION['user_id']))
    {
        if ($_SESSION['user_id'] == $user_id){ $own_profile = true;}
        else{
            $own_profile = false;
            $other_profile_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '../userProfileOther.php?id='.$user_id;
            header('Location: ' . $other_profile_url);
        }
    }
    else {
        $homepage_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../homepage/homepage.php';
        header('Location: ' . $homepage_url);
    }
}
else {
    $homepage_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../homepage/homepage.php';
    header('Location: ' . $homepage_url);
}

$dbc = connect_to_database();

$user_information_query = "SELECT * FROM users WHERE id='$user_id'";
$user_information = mysqli_query($dbc, $user_information_query);
$row = mysqli_fetch_array($user_information);
$email = $row['email'];
$location = $row['location'];
$status = $row['status'];
$first_name = $row['first_name'];
$last_name = $row['last_name'];
$join_date = $row['join_date'];
$birth_day = $row['birth_day'];
$gender = $row['gender'];
$picture = $row['picture'];
$education = $row['education'];
$zone = $row['zone'];
$phone_number = $row['phone_number'];
$address = $row['address'];
$facebook_profile = $row['facebook_profile'];
$google_profile = $row['google_profile'];
$instagram_profile = $row['instagram_profile'];
$linkin_profile = $row['linkin_profile'];
$rate = $row['rate'];

disconnect_from_database($dbc);

?>

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>مشخصات کاربر</title>
<link type="text/css" rel="stylesheet" href="details.css"/> 
</head>

<body>

    <!--<div class="details">-->
    <div class="detailsPop">
    	<div id="title">
        	<span id="det">مشـخـصــات    کـامــل</span>
            <span id="back"> بازگشــت </span>
        </div>    <!-- end of title -->
      
        <div id="left">
            <div id="pic"> <img src="<?php echo IMG_DIR.$picture; ?>"   /> </div>
            <?php if (!empty($facebook_profile)) { ?><div > <div id="fbBD"><a href="<?php echo $facebook_profile; ?>" target="_blank"><span id="fb"><?php echo $facebook_profile; ?></span></a> </div> <span>فیسبــوک <img src="fb.png" height="20em" width="20em" /></span> </div><?php } ?>
            <?php if (!empty($google_profile)) { ?><div > <div id="googleBD"><a href="<?php echo $google_profile; ?>" target="_blank"><span id="google"><?php echo $google_profile; ?></span></a></div><span>گوگــل <img src="g.png"  height="20em" width="20em"  /> </span> </div><?php } ?>
            <?php if (!empty($instagram_profile)) { ?><div > <div id="instaBD"><a href="<?php echo $instagram_profile; ?>" target="_blank"><span id="insta"><?php echo $instagram_profile; ?></span></a></div> <span>اینستاگـرام <img src="ig.png"  height="20em" width="20em"/> </span> </div><?php } ?>
            <?php if (!empty($linkin_profile)) { ?><div > <div id="linkedinBD"><a href="<?php echo $linkin_profile; ?>" target="_blank"><span id="linkedin"><?php echo $linkin_profile; ?></span></a></div> <span>لینکـدین <img src="in.png"  height="20em" width="20em" /> </span> </div><?php } ?>
          
        </div>     <!-- end of left -->
       
    	<div id="right">
            <?php if (!empty($first_name)) { ?><div ><div id="nameBD"> <span id="name"><?php echo $first_name; ?></span></div><span>نـام </span> </div><?php } ?>
            <?php if (!empty($last_name)) { ?><div ><div id="lastnameBD"><span id="lastname"><?php echo $last_name; ?></span></div><span >نـام خـانوادگی</span></div><?php } ?>
            <div ><div id="birthBD"><span id="birth">25/3/1374</span></div><span>تـاریـخ تولـد </span></div>
            <div ><div id="genderBD"><span id="gender">مــرد</span></div><span>جنســیت </span></div>
            <div ><div id="degreeBD"><span id="degree">دیـپلم</span></div><span>میــزان تحصـیلات</span></div>
            <div ><div id="zoneBD"><span id="zone">کامپیوتر</span></div><span>حــوزه فعـالیـت </span></div>
            <div ><div id="emailBD"><span id="email"><?php echo $email; ?></span></div><span>پست الکتـــرونیک </span></div>
            <?php if (!empty($phone_number)) { ?><div ><div id="phoneNumBD"><span id="phoneNum"><?php echo $phone_number; ?></span></div><span>شمـاره تلفـــن </span></div><?php } ?>
            <?php if (!empty($location)) { ?><div ><div id="locationBD"><span id="location"><?php echo $location; ?></span></div><span>شهـر / استـان </span> </div><?php } ?>
                                         
        </div>     <!-- end of right -->
     </div>   <!-- end of detailsPop -->     
   
</body>
</html>

