<?php
/**
 * Created by PhpStorm.
 * User: ikhan
 * Date: 28.01.2016
 * Time: 13:58
 */

// Credentials.
$dbHost = $_POST[ 'dbHost' ];   // database host name
$dbName = $_POST[ 'dbName' ];   // database name
$dbUser = $_POST[ 'dbUser' ];   // database user name
$dbPwd  = $_POST[ 'dbPwd' ];    // database user password

$conn = new PDO( "mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPwd ); // Database Connection.
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // Set the PDO error mode to exception.

// Variables.
$stage_id = $_POST[ 'stage_id' ];
$status = 0;

// Fetch and Update.
if( !empty( $stage_id ) ) {
    try {
        // Fetch sql query.
        $select_row = "SELECT `status` FROM `dwstage` WHERE `stage_id` = '" . $stage_id . "'";
        $stmt = $conn->prepare( $select_row );
        $stmt->execute();

        // Set the resulting array to associative.
        $result = $stmt->setFetchMode( PDO::FETCH_ASSOC );
        foreach( $stmt->fetchAll() as $key => $value ) { $status = $value['status']; }

        // Logic.
        echo $status = ( $status == 1 ) ? 0 : 1;

        // Update sql query.
        $update_row = "UPDATE `dwstage` SET `status`= '" . $status . "' WHERE `stage_id` = '" . $stage_id . "'";
        $stmt = $conn->prepare( $update_row );
        $stmt->execute();
    }
    catch( PDOException $e ) {
        echo $sql . $e->getMessage();
    }
}