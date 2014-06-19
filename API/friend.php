<?php
// friend request

require_once('../connection.php');
require_once('../appvars.php');

$dbc = connect_to_database();

$response = array();

$id = mysqli_real_escape_string($dbc, trim($_POST['id']));

$friend_ids_query = "SELECT friend1 FROM friendship WHERE friend2 = '$id'";
$friend_ids_data = mysqli_query($dbc, $friend_ids_query);

if (mysqli_num_rows($friend_ids_data) > 0)
{
    $friends = array();
    while ($a_friend = mysqli_fetch_array($friend_ids_data))
    {
        $a_friend_id = $a_friend['friend1'];
        $friend_detail_query = "SELECT id, email, first_name, last_name, join_date, gender, picture, zone FROM users WHERE id = '$a_friend_id'";
        $friend_detail_data = mysqli_fetch_array(mysqli_query($dbc, $friend_detail_query));
        $a_friend_detail = array("id" => $friend_detail_data['id'],
            "email" => $friend_detail_data['email'],
            "first_name" => $friend_detail_data['first_name'],
            "last_name" => $friend_detail_data['last_name'],
            "join_date" => $friend_detail_data['join_date'],
            "gender" => $friend_detail_data['gender'],
            "picture" => 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . IMG_DIR . $friend_detail_data['picture'],
            "field" => $friend_detail_data['zone']);
        array_push($response, $a_friend_detail);
    }
}

disconnect_from_database($dbc);

echo json_encode($response);

?>