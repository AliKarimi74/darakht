<?php

require_once('../connection.php');

$dbc = connect_to_database();

$logIn_email = mysqli_real_escape_string($dbc, trim($_POST['logInEmail']));
$logIn_password = mysqli_real_escape_string($dbc, trim($_POST['logInPassword']));

$response = array();

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
        $response['success'] = 0;
        $response['message'] = $userID;
}
else {
        $response['success'] = 1;
        $response['message'] = "invalid";
}

echo json_encode($response);

disconnect_from_database($dbc);

?>