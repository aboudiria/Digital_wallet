<?php
$host = "localhost";
$db_name = "digital_wallet";
$username = "root";
$password = "";

// Configure mysqli to throw exceptions for errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect($host, $username, $password, $db_name);
   
    mysqli_set_charset($conn, "utf8");
    echo "Connected successfully!";
} catch (mysqli_sql_exception $exception) {
    echo "Connection error: " . $exception->getMessage();
}
?>
