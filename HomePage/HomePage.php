<?php

require_once('../connection.php');

session_start();

if (isset($_POST['signUpSubmit']))
{
    $dbc = connect_to_database();
    if (empty($_POST['email']) || empty($_POST['password1']) || empty($_POST['password2']))
    {
        $error_message = "لطفا تمام اطلاعات را وارد کنید";
    }
    else if ($_POST['password1'] != $_POST['password2']) { $error_message = "دو رمز وارد شده متفاوت است"; }
    else
    {
        $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
        $password = mysqli_real_escape_string($dbc, trim($_POST['password1']));

        $query = "SELECT * FROM users WHERE email = '$email'";
        $data = mysqli_query($dbc, $query);
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
            $error_message = "ایمیل وارد شده صحیح نیست";
        }
        else if (mysqli_num_rows($data) != 0) { $error_message = "چنین کاربری وجود دارد"; }
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
            //setcookie('user_id', $userID, time() + (60 * 60 * 24 * 1));    // expires in 1 day
            //setcookie('display_name', $display_name, time() + (60 * 60 * 24 * 1));  // expires in 1 day
            $profile_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../UserProfilePage/userProfile.php?id='.$userID;
            header('Location: ' . $profile_url);
        }
        disconnect_from_database($dbc);
    }
}

if (isset($_POST['logInSubmit']))
{
    $dbc = connect_to_database();

    $logIn_email = mysqli_real_escape_string($dbc, trim($_POST['logInEmail']));
    $logIn_password = mysqli_real_escape_string($dbc, trim($_POST['logInPassword']));

    if (!empty($logIn_email) && !empty($logIn_password)) {
        $query = "SELECT id, email, first_name, last_name FROM users WHERE email = '$logIn_email' AND password = SHA('$logIn_password')";
        $data = mysqli_query($dbc, $query);

        if (mysqli_num_rows($data) == 1) {
            session_start();
            $row = mysqli_fetch_array($data);
            $userID = $row['id'];
            if ($row['first_name'] != null && $row['last_name'] != null) {$display_name = $row['first_name'].' '.$row['last_name'];}
            else {$display_name = $row['email'];}
            $_SESSION['user_id'] = $userID;
            //setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
            //setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
            $profile_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../UserProfilePage/userProfile.php?id='.$userID;
            header('Location: ' . $profile_url);
        }
        else {
            $logIn_error_msg = 'اطلاعات وارد شده اشتباه است';
        }
    }
    else {
        $logIn_error_msg = 'تمام اطلاعات را وارد نمایید';
    }
    disconnect_from_database($dbc);
}

if (isset($_POST['messageSubmit']))
{
    if (!empty($_POST['viewer_email']) && !empty($_POST['viewer_name']) && !empty($_POST['viewer_message']))
    {
        $dbc = connect_to_database();

        if (isset($_SESSION['user_id']))
        {
            $userId = $_SESSION['user_id'];
            $query = "SELECT first_name, last_name, email FROM users WHERE id = '$userId'";
            $data = mysqli_query($dbc, $query);
            $viewer_email = mysqli_fetch_array($data)['email'];
            $viewer_name = mysqli_fetch_array($data)['first_name'] + " " + mysqli_fetch_array($data)['last_name'];;
        }
        else
        {
            $userId = null;
            $viewer_email = mysqli_real_escape_string($dbc, trim($_POST['viewer_email']));
            if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$viewer_email)) {
                $feedbackErrorMessage = "ایمیل وارد شده صحیح نیست";
                $validEmail = false;
            }
            $viewer_name = mysqli_real_escape_string($dbc, trim($_POST['viewer_name']));
        }
        $message = $_POST['viewer_message'];

        if (!isset($validEmail))
        {
            $query = "INSERT INTO feedback (user_id, name, email, message) VALUES ('$userId', '$viewer_name', '$viewer_email', '$message')";
            mysqli_query($dbc, $query);
        }

        disconnect_from_database($dbc);
    }
    else{
        $feedbackErrorMessage = "لطفا تمام فیلد ها را پر کنید";
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link type="text/css" rel="stylesheet" href="styleHomePage.css"/>
<script src="jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
    //------------------------------------------------------scroll---------------------------------------------------
    $("#purple").click(function (){

        $('html, body').animate({
            scrollTop: $("#signupPanel").offset().top
        }, 2000);});
    $("#red").click(function (){

        $('html, body').animate({
            scrollTop: $("#ContactUsPanel").offset().top
        }, 2000);});

    $("#grey").click(function (){

        $('html, body').animate({
            scrollTop: $("#AppPanel").offset().top
        }, 2000);});
    $("#green").click(function (){

        $('html, body').animate({
            scrollTop: $("#HowToUsePanel").offset().top
        }, 2000);});
    $("#yellow").click(function (){

        $('html, body').animate({
            scrollTop: $("#AboutUsPanel").offset().top
        }, 2000);});
    $("#blue").click(function (){

        $('html, body').animate({
            scrollTop: $("#WhyUsPanel").offset().top
        }, 2000);});

    $("#GoUp").click(function (){

        $('html, body').animate({
            scrollTop: $(".mainPanel").offset().top
        }, 2000);});
    //------------------------------------------------------scroll toolbar------------------------------------------
    $("#aboutus").click(function (){

        $('html, body').animate({
            scrollTop: $("#AboutUsPanel").offset().top
        }, 2000);});
    $("#whyus").click(function (){

        $('html, body').animate({
            scrollTop: $("#WhyUsPanel").offset().top
        }, 2000);});
    $("#howtouse").click(function (){

        $('html, body').animate({
            scrollTop: $("#HowToUsePanel").offset().top
        }, 2000);});
    $("#app").click(function (){

        $('html, body').animate({
            scrollTop: $("#AppPanel").offset().top
        }, 2000);});
    $("#sign").click(function (){

        $('html, body').animate({
            scrollTop: $("#signupPanel").offset().top
        }, 2000);});
    $("#contactus").click(function (){

        $('html, body').animate({
            scrollTop: $("#ContactUsPanel").offset().top
        }, 2000);});
    //-------------------------------------------------------hiding toolbar------------------------------------------
    idleTime = 0;

    //Increment the idle time counter every second.
    var idleInterval = setInterval(timerIncrement, 1000);

    function timerIncrement()
    {
        idleTime++;
        if (idleTime > 2)
        { clearInterval()
            doPreload(idleInterval);
        }
    }

    //Zero the idle timer on mouse movement.
    $(this).mousemove(function(e){
        idleTime = 0;
        $(".toolbar").slideDown();
    });
    $(this).keypress(function (e) {
        idleTime = 0;
        $(".toolbar").slideDown();
    });

    function doPreload()
    { if(document.body.scrollTop>700)
        $(".toolbar").slideUp();
    }

    //---------------------------------------------------login-----------------------------------
    $("#pop").hide();

    $("#forget").click(function(){
        $("#mail").val("");
        $("#msg").text("");
        $('#forgetPop').slideDown(500) ;
        $('#blackBG').show(500) ;});

    $('#exitForget').click(function(e) {
        $('#forgetPop').slideUp(500) ;
        $('#blackBG').hide(500) ;
    });
    $('#submitForget').click(function(e) {
        if($("#mail").val()!=""){
            $('#forgetPop').slideUp(500) ;
            $('#blackBG').hide(500) ;}
        else{
            $("#msg").text(".ایمیل خود را وارد کنید"); }
    });


    $("#btn").click(function(){
        $("#pop").slideUp();
    });
    $("#login").click(function(){
        $("#pop").slideDown()
    });
    $(".mainPanel").click(function(){
        $("#pop").slideUp();
    });
    $("#AboutUsPanel").click(function(){
        $("#pop").slideUp();
    });
    $("#WhyUsPanel").click(function(){
        $("#pop").slideUp();
    });
    $("#HowTousePanel").click(function(){
        $("#pop").slideUp();
    });
    $("#AppPanel").click(function(){
        $("#pop").slideUp();
    });
    $("#signupPanel").click(function(){
        $("#pop").slideUp();
    });
    $("#ContactUsPanel").click(function(){
        $("#pop").slideUp();
    });
    $("#yek").click(function(){
        $("#pop").slideUp();
    });
    //---------------------------------------------------slide show-------------------------------------------
    var list=["first.jpg","1.jpg","2.jpg","4.jpg","5.jpg"];
    var size=5;
    var i=0;
    //$("#ax2").src = "3.jpg";
    $("#ax").attr('src', list[i]);i++;
    $("#ax").attr('width', '100%');
    $("#ax2").attr('src', list[i]);i++;
    $("#ax2").attr('width', '100%');

    setInterval(function(){
        setTimeout(function(){      //jam ax
            $("#ax").animate({width:'toggle'},1000);
        },2000);
        setTimeout(function(){
            $("#ax2").css('z-index' , '0');
            $("#ax").css('z-index' , '-1');
            $("#ax").attr('src', list[i]);   //tavize ax
            $("#ax").attr('width', '100%');
            i++;
            if(i>=size)i=0;
        },3000);
        setTimeout(function(){      //pakhsh ax(hidden)
            $("#ax").animate({width:'toggle'},10);
        },4000);
        setTimeout(function(){         //jam ax2
            $("#ax2").animate({width:'toggle'},1000);
        },5000);
        setTimeout(function(){
            $("#ax2").css('z-index' , '-1');
            $("#ax").css('z-index' , '0');
            $("#ax2").attr('src', list[i]);   //tavize ax2
            $("#ax2").attr('width', '100%');
            i++;
            if(i>=size)i=0;
        },6000);
        setTimeout(function(){           //pakhsh ax2(hidden)
            $("#ax2").animate({width:'toggle'},10);
        },6500);


    },7000);

//-----------------------------------------------App SlideShow---------------------------------------------------

    var list2=["10.jpg","11.jpg","12.jpg","14.jpg","15.jpg","16.png"];
    var size2=6;
    var j=0;
    //$("#ax2").src = "3.jpg";
    $("#ax_1").attr('src', list2[j]);j++;
    $("#ax_1").attr('width', '100%');
    $("#ax_2").attr('src', list2[j]);j++;
    $("#ax_2").attr('width', '100%');

    setInterval(function(){
        setTimeout(function(){      //jam ax
            $("#ax_1").animate({width:'toggle'},1000);
        },2000);
        setTimeout(function(){
            $("#ax_2").css('z-index' , '0');
            $("#ax_1").css('z-index' , '-1');
            $("#ax_1").attr('src', list2[j]);   //tavize ax
            $("#ax_1").attr('width', '100%');
            j++;
            if(j>=size2)j=0;
        },3000);
        setTimeout(function(){      //pakhsh ax(hidden)
            $("#ax_1").animate({width:'toggle'},10);
        },4000);
        setTimeout(function(){         //jam ax2
            $("#ax_2").animate({width:'toggle'},1000);
        },5000);
        setTimeout(function(){
            $("#ax_2").css('z-index' , '-1');
            $("#ax_1").css('z-index' , '0');
            $("#ax_2").attr('src', list2[j]);   //tavize ax2
            $("#ax_2").attr('width', '100%');
            j++;
            if(j>=size2)j=0;
        },6000);
        setTimeout(function(){           //pakhsh ax2(hidden)
            $("#ax_2").animate({width:'toggle'},10);
        },6500);


    },7000);

//-----------------------------------disa :D ---------------------------
    $(window).scroll(function(){
        if(document.body.scrollTop<700){
            $("#aboutus").hide();}
        if(document.body.scrollTop>700){
            $("#aboutus").show();}

        if(document.body.scrollTop<700){
            $("#whyus").hide();}
        if(document.body.scrollTop>700){
            $("#whyus").show();}

        if(document.body.scrollTop<700){
            $("#howtouse").hide();}
        if(document.body.scrollTop>700){
            $("#howtouse").show();}

        if(document.body.scrollTop<700){
            $("#app").hide();}
        if(document.body.scrollTop>700){
            $("#app").show();}

        if(document.body.scrollTop<700){
            $("#sign").hide();}
        if(document.body.scrollTop>700){
            $("#sign").show();}

        if(document.body.scrollTop<700){
            $("#contactus").hide();}
        if(document.body.scrollTop>700){
            $("#contactus").show();}


    })


    //----------------------Excess--------------

    $("#first").click(function (){
        $('#excessPop').slideDown(500) ;
        $('#blackBG').show(500) ;});

    $('#submitExcess').click(function(e) {
        $('#excessPop').slideUp(500) ;
        $('#blackBG').hide(500) ;
    });
});

</script>
<title>ProjecTree</title>
</head>


<body>
<!-- ----------------------------------------------------slideshow---------------------------------------------------------  -->
<div id="yek">
        <img id="ax" />
        <img id="ax2" />
 
    </div>

<?php
$userIsLogIn = false;
if (isset($_SESSION['user_id'])) // || isset($_COOKIE['user_id']))
{
    /*if (!isset($_SESSION['user_id'])) {
        echo "coolie";
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['display_name'] = $_COOKIE['display_name'];
    }*/
    $dbc = connect_to_database();
    $user_id = $_SESSION['user_id'];
    $qry = "SELECT email, first_name, last_name FROM users WHERE id = '$user_id'";
    $data = mysqli_query($dbc, $qry);
    $row = mysqli_fetch_array($data);
    $email = $row['email'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    if (!empty($first_name) || !empty($last_name)) $display_name = $first_name.' '.$last_name;
    else $display_name = $email;
    disconnect_from_database($dbc);
    $userIsLogIn = true;
    $firstButtonName = $display_name;
}
else
{
$firstButtonName = "ورود به سایـــت";
?>
<!-- -------------------------------------------------------login---------------------------------------------------------- -->
<div id="pop"> <br/>
    <div id="btn">
        <img id="close" src="cross.jpg">
    </div>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <input type="text" name="logInEmail" placeholder="ایــمیـل  "/>
        <input type="password" name="logInPassword" placeholder="رمــز عبور "/>
        <label><?php if (isset($logIn_error_msg)) echo '<p class="error">'.$logIn_error_msg.'</p>'; ?></label>
        <div id="register">
            <span id="forget" style="margin-right:7%"> .رمز عبور خود را فراموش کرده ام</span>
            <button type="submit" name="logInSubmit"> ورود </button>
        </div> <!-- end of register -->
    </form>
</div>   <!-- end of pop-->
<div id="blackBG">
</div>   <!-- end of blackBG -->
<div id="forgetPop">
     <span style="text-align:right ; margin-left:45%">ارسال مجدد کلمه ی عبور</span> <br/> <br/>
     <input id="mail" type="email"  placeholder="ایــمیـل" style=" margin-left:40% ; text-align:right" />
     <span id="msg" style="margin-left:45%"></span>
     <div id="btnForget" style="margin-left:10%">
          <button id="exitForget"> خروج </button>
          <button id="submitForget"> تایید </button>
     </div>   <!-- end of btnForget -->
</div>    <!-- end of forgetPop -->

<?php
}
?>

<!-- -------------------------------------------------------toolbar----------------------------------------------------- -->    
<div class="toolbar" >
    <?php if ($userIsLogIn) { echo '<a href="'.'../UserProfilePage/userProfile.php?id='.$_SESSION['user_id']; } ?>">
        <span id="login"><?php echo $firstButtonName; ?></span>
    <?php if ($userIsLogIn) { echo '</a>'; } ?>
    <span id="aboutus">دربـاره مـا</span>
    <span id="whyus" >جــرا مـا</span>
    <span id="howtouse">نحـوه اسـتفاده</span>
    <span id="app">اپلـکیشـن</span>
    <span id="sign">ثبـت نـام</span>
    <span id="contactus">تـماس بـا مـا</span>
    <?php if ($userIsLogIn) { ?>
        <a href="../logOut.php"><span id="exit">خروج</span> </a>
    <?php } ?>
   
</div>  
<!-- end of toolbar -->
<!--  -------------------------------------------------------GoUp--------------------------------------------------------  -->
<div id="GoUp">
</div>  <!-- end of GoUp -->
<!-- -------------------------------------------------------mainPanel----------------------------------------------------- --> 
<div class="mainPanel">
  <div class="menu" >
  		
        <div id="red">
        <div><a href="#"><span>تـماس با مـا</span></a></div>
        </div>
        
        <div id="purple">
        <div><a href="#"><span>ثــبت نــام</span></a></div>
        </div>
        
        <div id="grey">
        <div><a href="#"><span>اپلیکیشن</span></a></div>
        </div>
        
        <div id="green">
        <div><a href="#"><span>نحوه استفاده</span></a></div>
        </div>
        
        <div id="blue">
        <div><a href="#"><span>چــرا مـا</span></a></div>
        </div>
        
        <div id="yellow">
        <div><a href="#"><span>دربـاره مـا</span></a></div>
        </div>
                
    </div>  <!-- end of menu -->
    

</div> <!--end of mainPanel-->


<!-- ------------------------------------------------------AboutUsPanel--------------------------------------------------  -->     
<div id="AboutUsPanel">
	<div id="We">
    	<div style="display:inline-block">
        	<div><img src="RK.jpg" width="100%" height="100%"  /></div> <p align="center">رضـا کــرمی</p>
        </div>
        <div style="display:inline-block">
        	<div><img src="AV.jpg" width="100%" height="100%"  /></div> <p align="center">عـلی خـوش ویشـکایی</p>
        </div>
        <div style="display:inline-block">
        	<div><img src="EM.jpg" width="100%" height="100%"  /></div> <p align="center">الهـام میـرافضـلی</p>
        </div>
        <div style="display:inline-block">
        	<div><img src="MR.jpg" width="100%" height="100%"  /></div> <p align="center">مـژده ربـاطی</p>
        </div>
        <div style="display:inline-block">
        	<div><img src="AK.png" width="100%" height="100%"  /></div> <p align="center">علـی کـریمی</p>
        </div>
        <div style="display:inline-block">
        	<div><img src="kp.png" width="100%" height="100%"  /></div> <p align="center">کیـان پیـمانی</p>
        </div>
     </div>  <!-- end of We -->
</div>   <!-- end of AboutUsPanel -->
<!-- -------------------------------------------------WhyUsPanel--------------------------------------------------------  -->
<div id="WhyUsPanel">
	
</div>  <!-- end of WhyUsPanel -->
<!-- ------------------------------------------------HowToUsePanel-------------------------------------------------------  -->
<div id="HowToUsePanel">

</div>  <!-- end of HowToUse -->
<!-- ----------------------------------------------------AppPanel----------------------------------------------------------  -->
<div id="AppPanel">
    <!-- ---------------AppSlideshow-------------- -->
    <div id="mobile" style="display:inline-block">
        <img id="ax_1" />
        <img id="ax_2" />

    </div>
    <div id="appDiscript"  style="display:inline-block">
    </div>


</div>  <!-- end of AppPanel -->
<!--  ---------------------------------------------------signupPanel-------------------------------------------------------  -->
<div id="signupPanel">
	<div id="signup">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <div id="input1"> <!--Username-->
                <input type="text" name="email" placeholder="پـست الکترونیـک  "/>
            </div> <!-- end of input1 -->

            <div id="input2"> <!--password-->
                <input type="password" name="password1" placeholder="رمــز عبور  "/>
            </div> <!-- end of input2 -->

            <div id="input3"> <!--text-->
                <input type="password" name="password2" placeholder="تاییــد رمز  "/>
            </div> <!-- end of input3 -->
            <?php
            if (isset($error_message)) echo '<p class="error">'.$error_message.'</p>';
            ?>
            <div id="button">
                <button type="submit" name="signUpSubmit"> ثبـــت نام </button>
            </div> <!-- end of button -->
      	</form>
     </div> <!-- end of signup -->
     <div id="signupText">
     	<p align="center">
     		<h2  >به ما ملحق شوید </h2>
           : دوست گــرامـی سـلام <br/>
            ...اگر شما فـردی خـلاق و تـوانگر و مشـتاق به فضـاهای مجــازی هسـتید<br/>
           ... اگـر به دنبـال همــکاری دورادور بـا هــم تـیمی های خـود هـستید<br/>
            ...و در کل اگـر به دنبـال مدیـریت پروژه های خـود به نحـو احسـن هستید<br/>
            <br/>
            با مـا تجـربه کنید            
        </p>
     </div>  <!-- end of signupText -->
 </div>  <!--end of signupPanel -->


<!-- ---------------------------------------------------ContactUsPanel----------------------------------------------------  -->
<div id="ContactUsPanel">
    <div id="SocialNetworks">
        <div  style="display:inline-block"><img src="fb.png"  height="45em" width="45em" /> </div>
        <div  style="display:inline-block"><img src="g.png"  height="45em" width="45em" />  </div>
        <div  style="display:inline-block"><img src="in.png" height="45em" width="45em" /> </div>
        <div  style="display:inline-block "><img src="6.png" height="45em" width="45em" /> </div>
        <div  style="display:inline-block"><img src="ig.jpg" height="45em" width="45em" /> </div>
        <div  style="display:inline-block"><img src="7.jpg" height="45em" width="45em" /> </div>

    </div>  <!--end of SocialNetworks -->
    <div id="AH">
        <button id="first">innnn</button>
	<div id="ContactTitle" >ارســـال پـیـام</div>
	<div id="ContactSend">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <?php
            if (!isset($_SESSION['user_id']))
            {
            ?>
        	<div id="name">
                <input id="namebtn" type="text" name="viewer_name" value="<?php if (isset($viewer_name)) echo $viewer_name; ?>"/>
            	<label id="label1" align="right">نـــام</label>
            </div> <!-- end of name -->
            <br/>

            <div id="email">
                <input id="emailbtn" type="email" name="viewer_email"  value="<?php if (isset($viewer_email)) echo $viewer_email; ?>"/>
            	<label id="label2" align="right">پسـت الکترونیـک</label>
            </div> <!-- end of email -->
            <br/>

            <?php
            }
            ?>

            <div id="content">
            	<label id="label3" align="right">پیـــام</label> <br/>
                <textarea style="resize:none" id="message" rows="5" cols="40" name="viewer_message"><?php if (isset($message)) echo $message; ?></textarea>

             <?php if (isset($feedbackErrorMessage)) echo '<p class="error">'.$feedbackErrorMessage.'</p>'; ?>
            </div> <!-- end of content -->
            <!--<div id="submit">-->
            <input id="submit" type="submit" name="messageSubmit" value="ا ر ســــــــا ل" />
            <!--</div>--> <!-- end of submit -->
        </form>
    </div> <!-- end of AH -->
</div>  <!-- end of ContactSend -->

    <!-- ----------------------ExcessServer-->
    <div id="excessPop">
        <span id="excessmsg" >متن پیام سرور</span><br/>
        <button id="submitExcess">تایید</button>
    </div>
    
</div>  <!-- end of ContactUsPanel -->
</body>
</html>
