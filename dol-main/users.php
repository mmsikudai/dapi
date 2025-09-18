Users.php 
<?php
require_once 'conf.php';

// Connect to DB
$mysqli = new mysqli(
    $conf['db_host'],
    $conf['db_user'],
    $conf['db_pass'],
    $conf['db_name']
);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query all users in ascending order by name
$result = $mysqli->query("SELECT name, email FROM users ORDER BY name ASC");

if ($result->num_rows > 0) {
    echo "<h2>Registered Users</h2>";
    echo "<ol>"; // ordered list = numbered
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['name']} ({$row['email']})</li>";
    }
    echo "</ol>";
} else {
    echo "No users have signed up yet.";
}

$mysqli->close();
?>