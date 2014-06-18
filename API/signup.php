<?php
//sign up

require_once('../connection.php');

$dbc = connect_to_database();

$response = array();

$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
$password = mysqli_real_escape_string($dbc, trim($_POST['password']));

$query = "SELECT * FROM users WHERE email = '$email'";
$data = mysqli_query($dbc, $query);
if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
    $response['success'] = 1;
    $response['message'] = "invalid email";
}
else if (mysqli_num_rows($data) != 0) {
    $response['success'] = 1;
    $response['message'] = "exist user";
}
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

    $_SESSION['user_id'] = $userID;

    $response['success'] = 0;
    $response['message'] = "ok";
}

disconnect_from_database($dbc);

echo json_encode($response);

?>