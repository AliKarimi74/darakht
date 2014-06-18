<?php
// Personal information

require_once "../appvars.php";
require_once "../connection.php";

$user_id = $_GET['id'];

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
// edit profile part

if (isset($_POST['editProfileSubmit']))
{
    $dbc = connect_to_database();

    $new_first_name = $_POST['editFName'];
    $new_last_name = $_POST['editLName'];
    $new_email = $_POST['editEmail'];
    $new_phone_number = $_POST['editPhoneNumber'];
    $new_location = $_POST['editLocation'];
    $new_facebook_url = $_POST['editFacebookURL'];
    $new_google_url = $_POST['editGoogleURL'];
    $new_instagram_url = $_POST['editInstagramURL'];
    $new_linkedIn_url = $_POST['editLinkedInURL'];

    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$new_email)) {
        $edit_error_message = "ایمیل وارد شده صحیح نیست";
        $new_email = $email;
    }

    $editProfileDetails_query = "UPDATE users SET email='$new_email',
                                                  first_name='$new_first_name',
                                                  last_name='$new_last_name',
                                                  location='$new_location',
                                                  phone_number='$new_phone_number',
                                                  facebook_profile='$new_facebook_url',
                                                  google_profile='$new_google_url',
                                                  instagram_profile='$new_instagram_url',
                                                  linkin_profile='$new_linkedIn_url'
                                           WHERE id='$user_id'";
    mysqli_query($dbc, $editProfileDetails_query);

    disconnect_from_database($dbc);

    $profile_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../UserProfilePage/userProfile.php?id='.$user_id;
    header('Location: ' . $profile_url);
}

?>

<?php
// new idea part

if (isset($_POST['ideaSubmit']))
{
    $idea_title = $_POST['ideaTitle'];
    $idea_content = $_POST['ideaContent'];

    if (!empty($idea_title) && !empty($idea_content))
    {
        $dbc = connect_to_database();

        $insert_new_idea_query = "INSERT INTO ideas (user_id, title, content) VALUES ('$user_id', '$idea_title', '$idea_content')";
        mysqli_query($dbc, $insert_new_idea_query);

        disconnect_from_database($dbc);
    }
    else $idea_error_message = "تمام اطلاعات را وارد کنید";
}

?>

<?php
// new project part
require_once "../jdf.php";

if (isset($_POST['newProjectSubmit']))
{
    $new_project_title = $_POST['projectName'];
    $new_project_dead_time_day = $_POST['projectDeadTimeDay'];
    $new_project_dead_time_month = $_POST['projectDeadTimeMonth'];
    $new_project_dead_time_year = $_POST['projectDeadTimeYear'];
    if (!empty($new_project_title) && !empty($new_project_dead_time_day) && !empty($new_project_dead_time_month) && !empty($new_project_dead_time_year))
    {
        if (jcheckdate($new_project_dead_time_month, $new_project_dead_time_day, $new_project_dead_time_year))
        {
            $diff = jmktime(0,0,0,$new_project_dead_time_month, $new_project_dead_time_day, $new_project_dead_time_year) - time();
            if ($diff > 0)
            {
                $dbc = connect_to_database();

                $new_project_title = mysqli_real_escape_string($dbc, trim($new_project_title));
                $new_project_dead_time_day = mysqli_real_escape_string($dbc, trim($new_project_dead_time_day));
                $new_project_dead_time_month = mysqli_real_escape_string($dbc, trim($new_project_dead_time_month));
                $new_project_dead_time_year = mysqli_real_escape_string($dbc, trim($new_project_dead_time_year));
                $new_project_dead_date = jalali_to_gregorian($new_project_dead_time_year, $new_project_dead_time_month, $new_project_dead_time_day, '-');

                $project_insert_query = "INSERT INTO projects (client_id, admin_id, title, create_date, dead_date, percent) VALUES
                                                              ('$user_id', '$user_id', '$new_project_title', NOW(), '$new_project_dead_date', 0)";
                mysqli_query($dbc, $project_insert_query);

                $find_new_project_id_query = "SELECT id FROM projects WHERE title='$new_project_title' ORDER BY id DESC LIMIT 1";
                $new_project_id = mysqli_fetch_array(mysqli_query($dbc, $find_new_project_id_query))['id'];

                $insert_into_project_user_relation_query = "INSERT INTO project_user_relation (project_id, user_id) VALUES ('$new_project_id', '$user_id')";
                mysqli_query($dbc, $insert_into_project_user_relation_query);

                $new_goal_insert_query = "INSERT INTO goals (project_id, owner_id, is_root, title, percent, create_date, dead_date) VALUES
                                                            ('$new_project_id', '$user_id', 1, '$new_project_title', 0, NOW(), '$new_project_dead_date')";
                mysqli_query($dbc, $new_goal_insert_query);

                disconnect_from_database($dbc);
            }
            else $new_project_error_message = "چنین تاریخی در گذشته واقع است";
        }
        else $new_project_error_message = "تاریخ وارد شده وجود ندارد";
    }
    else $new_project_error_message = "لطفا تمام موارد را وارد کنید";
}

if (isset($new_project_error_message)) echo $new_project_error_message;

?>

<?php
// reply message part

if (isset($_POST['replySubmit']))
{
    $dbc = connect_to_database();

    $reply_title = $_POST['replyTitle'];
    $reply_message = $_POST['replyMessage'];

    $insert_new_message_query = "INSERT INTO message (sender_id, receipt_id, title, content) VALUES ('$user_id', '', '$reply_title', '$reply_message')";
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


<!doctype html>
<html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fa"/>

<head>

<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>ProjecTree | Profile</title>


<!-- Script / link Includes ================================================== > -->

<link type="text/css" rel="stylesheet" href="userProfileStyle.css"/>
<link rel="Stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/themes/redmond/jquery-ui.css" />
<link type="text/css" href="styles/jquery-ui-1.8.14.css" rel="stylesheet" />


<script src="http://code.jquery.com/jquery-1.9.1.js" type="text/javascript"></script>
<script type="text/javascript" src="jquery-ui.js"> </script> 
<script src="http://code.highcharts.com/highcharts.js" type="text/javascript"></script>
<script src="http://code.highcharts.com/modules/exporting.js" type="text/javascript"></script>
<script type="text/javascript" src="fri.js"> </script>

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
    	<div id="pic"> <img src="<?php echo IMG_DIR.$picture; ?>"/> </div>
        <div id="acc" >
        	<h3 align="center" ><a href=""> <?php echo $display_name; ?></a> </h3>
            <p align="right"><?php echo $join_date; ?>  : تاریخ عضویت</p>
            <?php if (isset($last_project_title)){ ?>
                <p align="right"><?php echo $last_project_title; ?>:  آخرین پروژه  </p>
            <?php } ?>
            <p align="right"><?php echo $rate; ?>:  امتیاز  </p>
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
        

    </div> <!--end of info -->
    
    <!-- ---------------------------------- CALENDER --------------------->
    <div id="calender"  >
        <p id="h"></p>
        <div id="pop" >
            <p id="p1"></p>
            <p id="p2"></p>
        </div>
        <div id="cal" >
            <p id="title">
                <span id="last">< - -   </span>
                <span id="tarikh"></span>
                <span id="next">   - - ></span>
            </p>

            <table id="TD"  >
                <tr>
                    <th>شنبه</th>
                    <th>یک</th>
                    <th>دو</th>
                    <th>سه</th>
                    <th>چهار</th>
                    <th>پنج</th>
                    <th id="friday" >چمعه</th>
                </tr>
                <tr>
                    <td id=1> </td>
                    <td id=2> </td>
                    <td id=3> </td>
                    <td id=4> </td>
                    <td id=5> </td>
                    <td id=6> </td>
                    <td id=7> </td>
                </tr>
                <tr>
                    <td id=8> </td>
                    <td id=9> </td>
                    <td id=10> </td>
                    <td id=11> </td>
                    <td id=12> </td>
                    <td id=13> </td>
                    <td id=14> </td>
                </tr>
                <tr>
                    <td id=15> </td>
                    <td id=16> </td>
                    <td id=17> </td>
                    <td id=18> </td>
                    <td id=19> </td>
                    <td id=20> </td>
                    <td id=21> </td>
                </tr>
                <tr>
                    <td id=22> </td>
                    <td id=23> </td>
                    <td id=24> </td>
                    <td id=25> </td>
                    <td id=26> </td>
                    <td id=27> </td>
                    <td id=28> </td>

                </tr>
                <tr>
                    <td id=29> </td>
                    <td id=30> </td>
                    <td id=31> </td>
                    <td id=32> </td>
                    <td id=33> </td>
                    <td id=34> </td>
                    <td id=35> </td>

                </tr>

                <tr id="tah">
                    <td id=36> </td>
                    <td id=37> </td>
                    <td id=38> </td>
                    <td id=39> </td>
                    <td id=40> </td>
                    <td id=41> </td>
                    <td id=42> </td>
                </tr>
            </table>

        </div>
    </div>




    <!-- -------------------------------------- CALENDER -------------------->

</div> <!--end of panel 1-->

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
if (mysqli_num_rows($friend_ids_data) > 0)
{
    $no_friend = false;
    $friends = array();
    while ($a_friend = mysqli_fetch_array($friend_ids_data))
    {
        $a_friend_id = $a_friend['friend1'];
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

<div class="panel2"> 
	<div id="messages" >
        <?php if ($no_message) echo '<p align="center">شما پیامی ندارید</p>';
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
        }?>

        <div>
        	<div> <img src="<?php echo IMG_DIR ?>rz.png"  /> </div>
            <h5 align="center"> رضا شایسته پور </h5>
            <p class="co" style="float:left; font-size:9px;cursor: pointer;"> ... ادامه </p><br> 
            <p class="resp" style="float:left; font-size:9px; color:rgba(72,100,147,1);" >&nbsp; &nbsp; ارسال&nbsp; پاســـخ &nbsp; </p>
            <p align="center"> یا ایها الذین آمنو ! من دچار خستگی دایمی شدم :( </p>
        </div>
        <div class="in" style="display: none;">چرا چرا چـــــــــــرا!!:D </div>

    </div>

    <div id="friends" >
        <?php if ($no_friend) echo '<p align="center">شما دوستی ندارید</p>';
        else {
            foreach ($friends as $friend) {
                echo '<div style="display:inline-block">';
                echo '<a href="userprofileOther.php?id='.$friend['id'].'"><div> <img src="'.IMG_DIR.$friend['picture'].'" /> </div></a>';
                if (!empty($friend['first_name']) || !empty($friend['last_name'])) {echo '<a href="userprofileOther.php?id='.$friend['id'].'"><p align="center">'.$friend['first_name'].' '.$friend['last_name'].'</p></a>'; }
                else { echo '<a href="userprofileOther.php?id='.$friend['id'].'"><p align="center">'.$friend['email'].'</p></a>'; }
                echo '</div>';
            }
        }?>
            
            <div style="display:inline-block">
                <div><img src="<?php echo IMG_DIR ?>vh.png"  /></div> <p align="center">وحید خرازی</p>
            </div>

    </div> <!--end of friends -->
</div>


<div class="panel3"> 
	<div id="menuBar" style="display:block">
    	<nav class="cl-effect-3"> 
    		<a href="../logOut.php"><span id="exit" > خـــروج </span></a><a href="#"><span id="newProject"> پــــــروژه جدید </span></a> <a href="#"><span id="setting">تنظیمات کــاربـــر </span></a> <a href="<?php echo 'details.php?id='.$user_id; ?>"><span>مشـــــخصات کامل  </span></a>  <a href="#"><span id="idea"> ایـــــده </span></a>
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


<?php
// get ideas

$dbc = connect_to_database();

$ideas_query = "SELECT * FROM ideas WHERE user_id = '$user_id' ORDER BY id DESC";
$ideas_data = mysqli_query($dbc, $ideas_query);

if (mysqli_num_rows($ideas_data) > 0)
{
    $no_idea = false;
    $ideas = array();

    while ($a_idea = mysqli_fetch_array($ideas_data))
    {
        $title = $a_idea['title'];
        $content = $a_idea['content'];
        $a_idea_array = array("title" => $title,
                              "content" => $content);
        array_push($ideas, $a_idea_array);
    }
}
else $no_idea = true;

disconnect_from_database($dbc);

?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$user_id; ?>"
<div id="newProjectPop"> <br>
	<div><input type="text" name="projectName"/> <span> نام پروژه </span>  </div>
    <div>
        <span>سال</span><input type="number" name="projectDeadTimeYear" style="width: 50px;" min="1390" max="1400">
        <span>ماه</span><input type="number" name="projectDeadTimeMonth" style="width: 40px;" min="1" max="12">
        <span>روز</span><input type="number" name="projectDeadTimeDay" style="width: 40px;" min="1" max="31">
    </div>
    <div> 
    	<button id="submitNewProject" type="submit" name="newProjectSubmit"> <span> ثبــــــت پروژه جدید </span> </button>
        <button id="exitNewProject" type="button"> <span> خـــــــروج </span> </button>
    </div>
</div>
</form>

<div id="blackBG"> </div> 


<div class="ideaPop">
	<div class="tabs">
    <ul class="tab-links">
        <li class="active"><a href="#tab1">ایــــــده جدیــد</a></li>
        <li><a href="#tab2"> متن های قبلــــــی </a></li>
    </ul>
</div>
 
    <div class="tab-content">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$user_id; ?>">
        <div id="tab1" class="tab active">
        	<div><input type="text" name="ideaTitle"/> <span> عنــــــوان </span> </div>
        	<textarea rows="35" cols="42" name="ideaContent"></textarea>
            <?php if (isset($idea_error_message)) echo $idea_error_message; ?>
            <button id="submitNewIdea" name="ideaSubmit" type="submit"> <span> ثبــــــت متن جدیـــد </span> </button>
            <button id="exitNewIdea" type="button"> <span>خـــــــــــــــــــروج  </span> </button>
        </div>
        </form>
 
        <div id="tab2" class="tab">
            <ul>
                <?php if($no_idea) echo '<p align="center">شما ایده ای ندارید</p>';
                else
                {
                    foreach ($ideas as $a_idea) {
                        echo '<li> ';
                        echo '<h3 class="ideaTitle" align="right">'.$a_idea['title'].'</h3>';
                        echo '<p align="right">'.$a_idea['content'].'</p>';
                        echo '</li>';
                    }
                }?>
            </ul>
        </div>
    </div>
</div>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$user_id; ?>"
<div class="messagePop" >
    <input type="hidden" value=""/>
	<div><input type="text" name="replyTitle"/> <span> عنــــــوان </span> </div>
    <textarea rows="10" cols="30" name="replyMessage"></textarea>
    <button id="submitNewMessage" type="submit" name="replySubmit"> <span> ارسال پیــــــــام  </span> </button>
    <button id="exitNewMessage" type="button"> <span> خــــــروج  </span> </button>
</div>
</form>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$user_id; ?>">
<div class="settingPop">
    <div > <input type="text" name="editFName" value="<?php echo $first_name; ?>"/> <span> نام </span> </div>
    <div > <input type="text" name="editLName" value="<?php echo $last_name; ?>"/> <span> نام خانوادگی </span> </div>
    <div > <input type="email" name="editEmail" value="<?php echo $email; ?>"/> <span> پست الکتـــرونیک </span> </div>
    <div > <input type="text" name="editPhoneNumber" value="<?php echo $phone_number; ?>"/> <span> شماره تلفـــن </span> </div>
    <div > <input type="text" name="editLocation" value="<?php echo $location; ?>"/> <span> شهر / استـــان </span> </div>
    <div > <input type="url" name="editFacebookURL" value="<?php echo $facebook_profile; ?>"/> <span> فیسبـــوک </span> </div>
    <div > <input type="url" name="editGoogleURL" value="<?php echo $google_profile; ?>"/> <span> گوگـــل </span> </div>
    <div > <input type="url" name="editInstagramURL" value="<?php echo $instagram_profile; ?>"/> <span> اینستاگــــرام </span> </div>
    <div > <input type="url" name="editLinkedInURL" value="<?php echo $linkin_profile; ?>"/> <span> لینکد این</span> </div>
    <?php if(isset($edit_error_message)) echo '<p>'.$edit_error_message.'</p>'; ?>
    <button id="saveSetting" type="submit" name="editProfileSubmit"> <span> ثبــــــت متن جدیـــد </span> </button>
    <button id="exitSetting" type="button"> <span>خـــــــــــــــــــروج  </span> </button>
</div>
</form>
     
</body>
</html>
