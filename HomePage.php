<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link type="text/css" rel="stylesheet" href="styleHomePage.css"/>
<title>Projectree</title>
</head>

<?php
require_once('connectionvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (isset($_POST['submit']))
{
    if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['re_password']))
    {
        $error_message = "لطفا تمام اطلاعات را وارد کنید!";
    }
    else if ($_POST['password'] != $_POST['re_password']) { $error_message = "دو رمز وارد شده متفاوت است! "; }
    else
    {
        $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
        $password = mysqli_real_escape_string($dbc, trim($_POST['password']));

        $query = "SELECT * FROM users WHERE email = '$email'";
        $data = mysqli_query($dbc, $query);
        if (mysqli_num_rows($data) != 0) { $error_message = "چنین کاربری وجود دارد! "; }
        else
        {
            $query = "INSERT INTO users (email, password, join_date) VALUES ('$email', SHA('$password'), NOW())";
            mysqli_query($dbc, $query);

            //log in
            session_start();
            $query = "SELECT * FROM users WHERE email = '$email'";
            $data = mysqli_query($dbc, $query);
            $row = mysqli_fetch_array($data);
            $userID = $row['id'];
            if ($row['first_name'] != null && $row['last_name'] != null) {$display_name = $row['first_name'].' '.$row['last_name'];}
            else {$display_name = $row['email'];}
            $_SESSION['user_id'] = $userID;
            $_SESSION['display_name'] = $display_name;
            setcookie('user_id', $userID, time() + (60 * 60 * 24 * 1));    // expires in 1 day
            setcookie('display_name', $display_name, time() + (60 * 60 * 24 * 1));  // expires in 1 day
            $profile_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/Profile/userProfile.html';
            header('Location: ' . $profile_url);
        }

        mysqli_close($dbc);
    }
}
?>

<body>

<div class="toolbar" ><span> ورود به سایـــت</span></div>  
<!-- end of toolbar -->

<div class="mainPanel">
  <div class="menu" >
        
        <div id="green">
        <div><span>green</span></div>
        </div>
        
        <div id="red">
        <div><span>red</span></div>
        </div>
        
        <div id="blue">
        <div><span>blue</span></div>
        </div>
        
        <div id="yellow">
        <div><span>yellow</span></div>
        </div>
        
        <div id="purple">
        <div><span>ثــبت نــام</span></div>
        </div>
    </div>  <!-- end of menu -->
</div> <!--end of panel1-->



<div id="signupPanel"> 
	<div id="signup">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <input type="text" name="email"/>
            <input type="password" name="password"/>
            <input type="password" name="re_password"/>
            <?php
            if (isset($error_message)) echo '<p class="error">'.$error_message.'</p>';
            ?>
            <button type="submit" name = "submit"> ثبـــت نام </button>
         </form>
     </div>
</body>
</html>
