<?php
// Personal information

require_once "../appvars.php";
require_once "../connection.php";

$user_id = $_GET['id'];

session_start();
if (isset($_SESSION['user_id']))
{
    if ($_SESSION['user_id'] != $user_id){ $own_profile = false;}
    else{
        $own_profile = true;
        $other_profile_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '../userProfile.php?id='.$user_id;
        header('Location: ' . $other_profile_url);
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
$permission = $row['permission'];
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

if (!empty($first_name) || !empty($lase_name)) $display_name =  $first_name . " " . $last_name;
else $display_name = $email;

$last_project_query = "SELECT project_id FROM project_user_relation WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
$last_project_info = mysqli_query($dbc, $last_project_query);
if (mysqli_num_rows($last_project_info) == 1)
{
    $last_project_id = mysqli_fetch_array($last_project_info)['project_id'];
    $last_project_title_query = "SELECT title FROM projects WHERE id = '$last_project_id'";
    $last_project_title = mysqli_fetch_array(mysqli_query($dbc, $last_project_title_query))['title'];
}

disconnect_from_database($dbc);

?>

<?php
// friend request

if (isset($_POST['friendRequestSubmit']))
{
    echo 1;
    $friend1 = $user_id;
    $friend2 = $_SESSION['user_id'];

    $dbc = connect_to_database();

    $search_query = "SELECT * FROM friendship WHERE friend1='$friend1' AND friend2='$friend2'";
    $search_result = mysqli_query($dbc, $search_query);
    if (mysqli_num_rows($search_result) == 0)
    {
        $friend_request_query = "INSERT INTO friendship (friend1, friend2) VALUES ('$friend1', '$friend2')";
        mysqli_query($dbc, $friend_request_query);
        $friend_request_query = "INSERT INTO friendship (friend1, friend2) VALUES ('$friend2', '$friend1')";
        mysqli_query($dbc, $friend_request_query);
    }

    disconnect_from_database($dbc);
}
?>

<?php
// send message part

if (isset($_POST['messageSubmit']))
{
    $dbc = connect_to_database();

    $sender_id = $_SESSION['user_id'];
    $send_message_title = mysqli_real_escape_string($dbc, trim($_POST['messageTitle']));
    $send_message_content = mysqli_real_escape_string($dbc, trim($_POST['messageContent']));

    $insert_new_message_query = "INSERT INTO message (sender_id, receipt_id, time, title, message) VALUES ('$sender_id', '$user_id', NOW(), '$send_message_title', '$send_message_content')";
    mysqli_query($dbc, $insert_new_message_query);

    disconnect_from_database($dbc);
}
?>


<?php
// projects information

require_once "../jdf.php";

$dbc = connect_to_database();

$get_project_ids_query = "SELECT project_id FROM project_user_relation WHERE user_id='$user_id' ORDER BY id DESC ";
$project_ids = mysqli_query($dbc, $get_project_ids_query);

if (mysqli_num_rows($project_ids) > 0)
{
    list($now_year_miladi, $now_month_miladi, $now_day_miladi) = explode('-', date("Y-m-d"));
    list($now_year, $now_month, $now_day) = explode('-', gregorian_to_jalali($now_year_miladi, $now_month_miladi, $now_day_miladi, '-'));
    $no_project = false;

    $projects = array();

    $projects_in_month = array();
    $temp_year = $now_year;
    $temp_month = $now_month;
    for ($x = 0; $x < 6; $x++)
    {
        $str = $temp_year.'-'.$temp_month;
        $projects_in_month[$str] = 0;
        $temp_month--;
        if ($temp_month == 0)
        {
            $temp_month = 12;
            $temp_year--;
        }
    }

    $last_project_details = array();

    while ($a_project = mysqli_fetch_array($project_ids))
    {
        $a_project_id = $a_project['project_id'];
        $project_detail_query = "SELECT admin_id, title FROM projects WHERE id = '$a_project_id'";
        $project_info = mysqli_fetch_array(mysqli_query($dbc, $project_detail_query));
        $project_title = $project_info['title'];
        $project_admin_id = $project_info['admin_id'];

        $goal_detail_query = "SELECT id, title, importance, percent, dead_date, done_date FROM goals WHERE project_id = '$a_project_id' AND owner_id = '$user_id'";
        $goal_detail_data = mysqli_fetch_array(mysqli_query($dbc, $goal_detail_query));
        $current_vertex = $goal_detail_data['id'];
        $goal_title = $goal_detail_data['title'];
        $goal_importance = $goal_detail_data['importance'];
        $goal_percent = $goal_detail_data['percent'];
        $goal_dead_date = $goal_detail_data['dead_date'];
        $goal_done_date = $goal_detail_data['done_date'];
        list($year, $month, $day) = explode('-', $goal_dead_date);
        $jalali_dead_date = gregorian_to_jalali($year, $month, $day, ' / ');
        $diff_day = strtotime($goal_dead_date) - time();
        $remain_days = floor($diff_day/(60*60*24));

        if ($goal_percent >= 100)
        {
            list($done_year, $done_month, $done_day) = explode('-', $goal_done_date);
            list($done_year_jalali, $done_month_jalali, $done_day_jalali) = explode('-', gregorian_to_jalali($done_year, $done_month, $done_day, '-'));
            if (isset($projects_in_month[$done_year_jalali.'-'.$done_month_jalali])) $projects_in_month[$done_year_jalali.'-'.$done_month_jalali]++;
        }
        $jalali_done_date = gregorian_to_jalali($year, $month, $day, '-');

        if (count($last_project_details) < 11)
        {
            $a_project_detail_for_chart = array();
            $a_project_detail_for_chart['title'] = $goal_title;
            $a_project_detail_for_chart['percent'] = $goal_percent;
            $a_project_detail_for_chart['dead_date'] = $goal_dead_date;
            $a_project_detail_for_chart['done_date'] = $goal_done_date;
            array_push($last_project_details, $a_project_detail_for_chart);
        }


        if ($user_id = $project_admin_id)
        {
            $own_project = true;
            $upper_vertex_id = null;
            $upper_vertex = null;
        }
        else
        {
            $own_project = false;
            $upper_vertex_query = "SELECT upper_vertex FROM goals_relation WHERE downer_vertex = '$current_vertex'";
            $upper_vertex_id = mysqli_fetch_array(mysqli_query($dbc, $upper_vertex_query))['upper_vertex'];
            $upper_vertex_detail_query = "SELECT email, first_name, last_name FROM users WHERE id = '$upper_vertex_id'";
            $upper_vertex_email = mysqli_fetch_array(mysqli_query($dbc, $upper_vertex_query))['email'];
            $upper_vertex_first_name = mysqli_fetch_array(mysqli_query($dbc, $upper_vertex_query))['first_name'];
            $upper_vertex_last_name = mysqli_fetch_array(mysqli_query($dbc, $upper_vertex_query))['last_name'];
            if (!empty($upper_vertex_first_name) || !empty($upper_vertex_last_name)) $upper_vertex = $upper_vertex_first_name.' '.$upper_vertex_last_name;
            else $upper_vertex = $upper_vertex_email;
        }

        if ($goal_percent < 100)
        {
            $a_goal_detail = array("id" => $goal_detail_data['id'],
                "own_project" => $own_project,
                "project_id" => $a_project_id,
                "project_title" => $project_title,
                "upper_vertex_id" => $upper_vertex_id,
                "upper_vertex" => $upper_vertex,
                "title" => $goal_title,
                "importance" => $goal_importance,
                "percent" => $goal_percent,
                "remain_days" => $remain_days,
                "dead_date" => $jalali_dead_date);
            array_push($projects, $a_goal_detail);
        }

    }
}
else $no_project = true;

disconnect_from_database($dbc);

?>

<?php
// prepare data for charts

function persian_date ($year, $month, $day='')
{
    switch($month)
    {
        case 1:
            $persian_month = "فروردین";
            break;
        case 2:
            $persian_month = "اردیبهشت";
            break;
        case 3:
            $persian_month = "خرداد";
            break;
        case 4:
            $persian_month = "تیر";
            break;
        case 5:
            $persian_month = "مرداد";
            break;
        case 6:
            $persian_month = "شهریور";
            break;
        case 7:
            $persian_month = "مهر";
            break;
        case 8:
            $persian_month = "آبان";
            break;
        case 9:
            $persian_month = "آذر";
            break;
        case 10:
            $persian_month = "دی";
            break;
        case 11:
            $persian_month = "بهمن";
            break;
        case 12:
            $persian_month = "اسفند";
            break;
    }
    return $day.' '.$persian_month.' '.$year;
}

$recent_month_projects = array();

$first_chart_xAxis = array();
$first_chart_data = array();

if (isset($projects_in_month))
{
    foreach ($projects_in_month as $key => $value)
    {
        if ($value > 0)
        {
            list($year, $month) = explode('-', $key);
            $recent_month_projects[persian_date($year, $month)] = $value;
            array_push($xAxis, persian_date($year, $month));
            array_push($first_chart_data, $value);
        }
    }
}

$second_chart_xAxis = array();
$second_chart_data = array();

if (isset($last_project_details))
{
    foreach ($last_project_details as $a_project)
    {
        array_push($second_chart_xAxis, $a_project['title']);
        array_push($second_chart_data, (int)$a_project['percent']);
    }
}

?>

<?php
// message part

$dbc = connect_to_database();

$messages_query = "SELECT * FROM message WHERE receipt_id = '$user_id'";
$messages_data = mysqli_query($dbc, $messages_query);

if (mysqli_num_rows($messages_data) > 0)
{
    $no_message = false;
    $messages = array();

    while ($a_message = mysqli_fetch_array($messages_data))
    {
        $sender_id = $a_message['sender_id'];
        $sender_detail_query = "SELECT email, first_name, last_name, picture FROM users WHERE id = '$sender_id'";
        $sender_detail_data = mysqli_fetch_array(mysqli_query($dbc, $sender_detail_query));
        if (!empty($sender_detail_data['first_name']) && !empty($sender_detail_data['last_name'])) $sender = $sender_detail_data['first_name'].' '.$sender_detail_data['last_name'];
        else $sender = $sender_detail_data['email'];
        $sender_picture = $sender_detail_data['picture'];
        $message_title = $a_message['title'];
        $message_content = $a_message['message'];
        $message_array = array("sender_id" => $sender_id,
            "sender" => $sender,
            "sender_picture" => $sender_picture,
            "title" => $message_title,
            "content" => $message_content);
        array_push($messages, $message_array);
    }
}
else $no_message = true;

disconnect_from_database($dbc);

?>

<?php
// friends info

$dbc = connect_to_database();

$friend_ids_query = "SELECT friend1 FROM friendship WHERE friend2 = '$user_id'";
$friend_ids_data = mysqli_query($dbc, $friend_ids_query);
$is_friend_with_me = false;
if (mysqli_num_rows($friend_ids_data) > 0)
{
    $no_friend = false;
    $friends = array();
    while ($a_friend = mysqli_fetch_array($friend_ids_data))
    {
        $a_friend_id = $a_friend['friend1'];
        if ($a_friend_id == $_SESSION['user_id']) $is_friend_with_me = true;
        $friend_detail_query = "SELECT id, email, first_name, last_name, status, picture FROM users WHERE id = '$a_friend_id'";
        $friend_detail_data = mysqli_fetch_array(mysqli_query($dbc, $friend_detail_query));
        $a_friend_detail = array("id" => $friend_detail_data['id'],
            "email" => $friend_detail_data['email'],
            "first_name" => $friend_detail_data['first_name'],
            "last_name" => $friend_detail_data['last_name'],
            "status" => $friend_detail_data['status'],
            "picture" => $friend_detail_data['picture']);
        array_push($friends, $a_friend_detail);
    }
}
else $no_friend = true;

disconnect_from_database($dbc);

?>


<!doctype html>
<html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fa"/>

<head>

<!-- ali --> 

<meta charset="utf-8">
<title>Projectree</title>


<!-- Script / link Includes ================================================== > -->

<link type="text/css" rel="stylesheet" href="userProfileStyleOther.css"/>
<link rel="Stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/themes/redmond/jquery-ui.css" />


<script src="http://code.jquery.com/jquery-1.9.1.js" type="text/javascript"></script>
<script type="text/javascript" src="jquery-ui.js"> </script> 
<script src="http://code.highcharts.com/highcharts.js" type="text/javascript"></script>
<script src="http://code.highcharts.com/modules/exporting.js" type="text/javascript"></script>
<script type="text/javascript">

        $(document).ready(function(e) {
            $('#blackBG').hide(1) ;

            var m=10;
            $(".co").click(function(){
                if(typeof(m)!="number"){
                    m.slideUp();}
                m=$(this).parent().next()
                m.slideDown();
            });

            $('.resp').click(function(e) {
                $('.messagePop').slideDown(500) ;
                $('#blackBG').show(500) ;
            });

            $("#newProject").click(function(e) {
                $('#newProjectPop').slideDown(500) ;
                $('#blackBG').show(500) ;
            });
            $('#exitNewProject').click(function(e) {
                $('#newProjectPop').slideUp(500) ;
                $('#blackBG').hide(500) ;
            });

            $("#idea").click(function(e) {
                $('.ideaPop').slideDown(500) ;
                $('#blackBG').show(500) ;
            });
            $('#exitNewIdea').click(function(e) {
                $('.ideaPop').slideUp(500) ;
                $('#blackBG').hide(500) ;
            });

            $("#setting").click(function(e) {
                $('.settingPop').slideDown(500) ;
                $('#blackBG').show(500) ;
            });
            $('#exitSetting').click(function(e) {
                $('.settingPop').slideUp(500) ;
                $('#blackBG').hide(500) ;
            });

            $('#sendMessage').click(function(e) {
                $('.messagePop').slideDown(500) ;
                $('#blackBG').show(500) ;
            });

            $('#exitNewMessage').click(function(e) {
                $('.messagePop').slideUp(500) ;
                $('#blackBG').hide(500) ;
            });

            $("#addFriend").click(function(e) {
                $("#addFriend").hide(500) ;
                $("#sendMessage").css("margin-left" , "10em") ;
            });


            //$('.projectPClick').on('click',function(e) {
//		e.preventDefault();
//        $(this).toggleClass('projectPClick');
//	});

            $('#red').on('click',function(e) {
                e.preventDefault();
                $(this).toggleClass('projectPClick');
            });

            $('#green').on('click',function(e) {
                e.preventDefault();
                $(this).toggleClass('projectPClick');
            });

            $('#yellow').on('click',function(e) {
                e.preventDefault();
                $(this).toggleClass('projectPClick');
            });


            $('.tabs .tab-links a').on('click', function(e)  {
                var currentAttrValue = $(this).attr('href');
                $('.ideaPop ' + currentAttrValue).show().siblings().hide();
                $(this).parent('li').addClass('active').siblings().removeClass('active');
                e.preventDefault();
            });




            $('#lineChart').highcharts({
                chart: {
                    type: 'line' ,
                    style: { fontFamily : 'B Homa' }
                },
                title: {
                    text: 'پروژه های تکمیل شده' ,
                    style : {
                        fontFamily : 'B Homa',
                    }
                },
                xAxis: { min:0 ,
                    categories: <?php echo json_encode($first_chart_xAxis); ?> ,
                    labels : {
                        style : {
                            fontFamily : 'B Homa'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'تعداد پروژه'
                    }
                },
                series: [{
                    name: <?php echo json_encode($display_name); ?> ,
                    data: <?php echo json_encode($first_chart_data); ?>
                }]

            });

            $('#barChart').highcharts({
                chart: {
                    type: 'column' ,
                    style: { fontFamily : 'B Homa' }
                },
                title: {
                    text: 'نمودار فعالیت در پروژه ها ' ,
                    style : {
                        fontFamily : 'B Homa'
                    }
                },
                xAxis: {
                    categories: <?php echo json_encode($second_chart_xAxis); ?> ,
                    labels : {
                        style : {
                            fontFamily : 'B Homa'
                        }
                    }
                },
                yAxis: {
                    min : 0 ,
                    max : 100 ,
                    title: {
                        text: 'درصد تکمیل وظیفه'
                    }
                },
                series: [{
                    name: <?php echo json_encode($display_name); ?> ,
                    data: <?php echo json_encode($second_chart_data); ?>
                }],

                plotOptions: {
                    series: {borderWidth: 1, borderColor: 'black'}
                }
            });

        });

    </script>

</head>

<body>


<div class="panel1">
	<div id="info"> 
    	<div id="pic"> <img src="<?php echo IMG_DIR.$picture; ?>"/>"/> </div>
        <div id="acc" >
            <h3 align="center" ><a href=""> <?php echo $display_name; ?></a> </h3>
            <p align="right"><?php echo $join_date; ?>  : تاریخ عضویت</p>
            <?php if (isset($last_project_title)){ ?>
                <p align="right"><?php echo $last_project_title; ?>:  آخرین پروژه  </p>
            <?php } ?>
            <p align="right"><?php echo $rate; ?>:  امتیاز  </p>
            <p align="right"> مشاهـــــــــــده اطلاعات کامل </p>

            <?php if (!empty($facebook_profile) || !empty($google_profile) || !empty($instagram_profile) || !empty($linkin_profile)) {?>
                <div id="social" >
                    <?php if (!empty($facebook_profile)) { ?>
                        <div style="display:inline-block"><a href="<?php echo $facebook_profile; ?>" target="_blank"><img src="fb.png"  /></a> </div> <?php } ?>
                    <?php if (!empty($google_profile)) { ?>
                        <div  style="display:inline-block"><a href="<?php echo $google_profile; ?>" target="_blank"><img src="g.png"  /></a>  </div> <?php } ?>
                    <?php if (!empty($instagram_profile)) { ?>
                        <div  style="display:inline-block"><a href="<?php echo $instagram_profile; ?>" target="_blank"><img src="ig.png" /></a> </div> <?php } ?>
                    <?php if (!empty($linkin_profile)) { ?>
                        <div  style="display:inline-block"><a href="<?php echo $linkin_profile; ?>" target="_blank"><img src="in.png" /></a> </div> <?php } ?>
                </div> <!--end of social -->
            <?php } ?>
        </div>  <!--end of acc -->


        <form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$user_id; ?>"
        <div id="contact">
            <?php if (!$is_friend_with_me) { ?>
                <button id="addFriend" type="submit" name="friendRequestSubmit">اضافـــه کردن به لیست دوستان</button>
            <?php } ?>
            <button id="sendMessage" type="button" >ارسال پیـــــــــــــــام</button>
        </div>
        </form>
    </div> <!--end of info -->
    
    <div id="calender" style="display:none"  >
   
    </div> 

</div> <!--end of panel 1-->

<div class="panel2">
	<div id="messages" >
        <?php if ($permission) {
            if ($no_message) echo '<p align="center">شما پیامی ندارید</p>';
            else {
                foreach ($messages as $message) {
                    echo '<div>';
                    echo '<div><img src="'.IMG_DIR.$message['sender_picture'].'" /></div>';
                    echo '<h5 align="center">'.$message['sender'].'</h5>';
                    echo '<p class="co" style="float:left; font-size:9px;cursor:pointer;" > ... ادامه </p><br> ';
                    echo '<p class="resp" style="float:left; font-size:9px; color:rgba(72,100,147,1);" >&nbsp; &nbsp; ارسال&nbsp; پاســـخ &nbsp; </p>';
                    echo '<p align="center">'.$message['title'].'</p>';
                    echo '</div>';
                    echo '<div class="in" style="display: none;">'.$message['content'].'</div>';
                }
            }
        }
        else echo "شما اجازه دسترسی به پیامها را ندارید";
        ?>

    </div>

    <div id="friends" >
        <?php if ($permission) {
            if ($no_friend) echo '<p align="center">شما دوستی ندارید</p>';
            else {
                foreach ($friends as $friend) {
                    echo '<div style="display:inline-block">';
                    echo '<a href="userprofileOther.php?id='.$friend['id'].'"><div> <img src="'.IMG_DIR.$friend['picture'].'" /> </div></a>';
                    if (!empty($friend['first_name']) || !empty($friend['last_name'])) {echo '<a href="userprofileOther.php?id='.$friend['id'].'"><p align="center">'.$friend['first_name'].' '.$friend['last_name'].'</p></a>'; }
                    else { echo '<a href="userprofileOther.php?id='.$friend['id'].'"><p align="center">'.$friend['email'].'</p></a>'; }
                    echo '</div>';
                }
            }
        }
        else echo "شما اجازه دسترسی به لیست دوستان را ندارید";
        ?>

    </div> <!--end of friends -->

</div>


<div class="panel3"> 
	<div id="menuBar">
    	<nav class="cl-effect-3"> 
    		<a href="<?php echo 'userProfile.php?id='.$_SESSION['user_id']; ?>"><span> صفـــــحه خانگی مــن </span></a> <a href="../homepage/homepage.php"><span> صفــــحه اصلــی </span></a> <a href="../logOut.php"><span> خـــــــــروج </span></a>
		</nav>
    </div> 
    
    <div id="Project">
        <?php

        if (isset($projects))
        {
            foreach ($projects as $project)
            {
                if ($project['own_project'])
                {
                    echo '<div class="ownProject">';
                    $color = "red";
                }
                else
                {
                    echo '<div class="otherProject">';
                    $color = "blue";
                }
                echo '<div id="projectInfo">';
                if (!$project['upper_vertex']) $manager = "خودم";
                else $manager = $project['upper_vertex'];
                echo '<span>'.$project['project_title'].'</span>'.'<span>'.$project['title'].'</span>'.'<span>'.$manager.'</span>'.'<span>'.$project['remain_days'].'</span>';
                echo '</div>';
                echo '<div class="progress">';
                echo '<span class="'.$color.'" style="width: '.$project['percent'].'%;"><span>'.$project['percent'].'%</span></span>';
                echo '</div>';
                echo '</div>';
            }
        }
        else echo "شما پروژه ای در حال انجام ندارید.";

        ?>

       <div class="otherProject">
            <div id="projectInfo">
                 <span>نام پروژه  </span><span>وظیفه من  </span><span>مدیر پروژه </span>  <span>زمان تحویل </span>   
            </div>
            <div class="progress">
      			<span class="blue" style="width: 70%;"><span>70%</span></span>
                
   			</div> 
       </div> <!--end of other project type -->
       
       <div class="ownProject">
            <div id="projectInfo">
                 <span>نام پروژه  </span><span>وظیفه من  </span><span>لیست اعضاء </span>  <span>زمان تحویل </span>   
            </div>
            <div class="progress">
      			<span class="red" style="width: 27%;"><span>27%</span></span>
   			</div> 
       </div> <!--end of other project type -->
       
       <div class="ownProject">
            <div id="projectInfo">
                 <span>نام پروژه  </span><span>وظیفه من  </span><span>لیست اعضاء </span>  <span>زمان تحویل </span>   
            </div>
            <div class="progress">
      			<span class="red" style="width: 57%;"><span>57%</span></span>
   			</div> 
       </div> <!--end of other project type -->
       
    </div> <!--end of project -->
    
    <div id="Statistics">
    	<div id="lineChart" > </div>
        <div id="barChart" > </div>
        <button> بارگذاری مجدد </button>
    </div>
    
</div>


<div id="blackBG"> </div>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$user_id; ?>"
<div class="messagePop" >
    <div><input type="text" name="messageTitle"/> <span> عنــــــوان </span> </div>
    <textarea rows="10" cols="30" name="messageContent"></textarea>
    <button id="submitNewMessage" type="submit" name="messageSubmit"> <span> ارسال پیــــــــام  </span> </button>
    <button id="exitNewMessage" type="button"> <span> خــــــروج  </span> </button>
</div>
</form>

    
    
    
<!--

<div id="newProjectPop"> <br>
	<div><input type="text"/> <span> نام پروژه </span> </div>
    <div><input id="newProjectDate" type="text"/> <span> تاریخ اتمام </span> </div>
    <div>
    	<div class="projectP" id="red" > </div><div class="projectP" id="yellow" > </div><div class="projectP" id="green" > </div>
    </div>
    <div> 
    	<button id="submitNewProject" type="submit"> <span> ثبــــــت پروژه جدید </span> </button>
        <button id="exitNewProject" type="submit"> <span> خـــــــروج </span> </button>
    </div>
</div> 




<div class="ideaPop">
	<div class="tabs">
    <ul class="tab-links">
        <li class="active"><a href="#tab1">ایــــــده جدیــد</a></li>
        <li><a href="#tab2"> متن های قبلــــــی </a></li>
    </ul>
</div>
 
    <div class="tab-content">
        <div id="tab1" class="tab active">
        	<div><input type="text"/> <span> عنــــــوان </span> </div>        	
        	<textarea rows="35" cols="42" ></textarea>
            <button id="submitNewIdea"> <span> ثبــــــت متن جدیـــد </span> </button>
            <button id="exitNewIdea"> <span>خـــــــــــــــــــروج  </span> </button>
        </div>
 
        <div id="tab2" class="tab">
            <ul>
            	<li> 
                	<h3 class="ideaTitle" align="right"> عنوان ایده اول </h3>	
                    <p align="right"> تسیباشسنیبلسمینابلشسیبانکتیاب </p>
                </li>
                <li class="activeIdea"> 
                	<h3 class="ideaTitle" align="right"> عنوان ایده دوم </h3>	
                    <p align="right"> تسیباشسنیبلسمینابلشسیبانکتیاب </p>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="settingPop">
	<div > <input type="text"/> <span> نام </span> </div>	
    <div > <input type="text"/> <span> نام خانوادگی </span> </div>	
    <div > <input type="text"/> <span> پست الکتـــرونیک </span> </div>	
    <div > <input type="text"/> <span> شماره تلفـــن </span> </div>	
    <div > <input type="text"/> <span> شهر / استـــان </span> </div>
    <div > <input type="text"/> <span> فیسبـــوک </span> </div>
    <div > <input type="text"/> <span> گوگـــل </span> </div>
    <div > <input type="text"/> <span> اینستاگــــرام </span> </div>
    <div > <input type="text"/> <span> توییتـــر </span> </div>
    <button id="saveSetting"> <span> ثبــــــت متن جدیـــد </span> </button>
    <button id="exitSetting"> <span>خـــــــــــــــــــروج  </span> </button>
</div>-->
     
</body>
</html>
