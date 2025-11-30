<?php
// Script to show tables in the jurnalguru database

$mysqli = new mysqli("localhost", "root", "", "jurnalguru");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$result = $mysqli->query("SHOW TABLES");

echo "Tables in jurnalguru database:\n";
while ($row = $result->fetch_row()) {
    echo $row[0] . "\n";
}

$mysqli->close();