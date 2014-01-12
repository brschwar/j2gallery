<?php

// TESTING
$mysqli = new mysqli("example.com", "user", "password", "database");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

/* Non-prepared statement */
if (!$mysqli->query("DROP TABLE IF EXISTS test") || !$mysqli->query("CREATE TABLE test(id INT)")) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("INSERT INTO test(id) VALUES (?)"))) {
     echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/* Prepared statement, stage 2: bind and execute */
$id = 1;
if (!$stmt->bind_param("i", $id)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

/* Prepared statement: repeated execution, only data transferred from client to server */
for ($id = 2; $id < 5; $id++) {
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
}

/* explicit close recommended */
$stmt->close();

/* Non-prepared statement */
$res = $mysqli->query("SELECT id FROM test");
var_dump($res->fetch_all());



/*
if (!$db->query("DROP TABLE IF EXISTS test") ||
    !$db->query("CREATE TABLE test(id INT)") ||
    !$db->query("INSERT INTO test(id) VALUES (1), (2), (3)")) {
    echo "Table creation failed: (" . $db->errno . ") " . $db->error;
}

$res = $db->query("SELECT id FROM test ORDER BY id ASC");

echo "Result: \n";
for ($row_no = $res->num_rows - 1; $row_no >= 0; $row_no--) {
    $res->data_seek($row_no);
    $row = $res->fetch_assoc();
    echo " id = " . $row['id'] . "\n";
}


    // $res = $db->query("SELECT * FROM admin ORDER BY admin_id ASC");
    // echo var_dump($row = $res->fetch_assoc());

    // echo "Results: \n";
    // for ($row_no = $res->num_rows - 1; $row_no >= 0; $row_no--) {
    //    $res->data_seek($row_no);
    //    $row = $res->fetch_assoc();
    //    echo " id = " . $row['admin_id'] . "\n";
    // }

    echo var_dump($db->last_query);
    echo var_dump($db->num_rows());
   // echo var_dump($db->affected_rows());
*/
// END TESTING

?>