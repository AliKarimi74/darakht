<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'projectree');

function connect_to_database()
{
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    mysqli_query($dbc, "SET NAMES 'utf8'");
    mysqli_query($dbc, "SET CHARACTER SET 'utf8'");
    mysqli_query($dbc, "SET character_set_connection = 'utf8'");
    return $dbc;
}

function disconnect_from_database($db)
{
    mysqli_close($db);
}

?>