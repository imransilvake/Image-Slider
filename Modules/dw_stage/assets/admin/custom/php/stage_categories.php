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
$oxid_id = $_POST[ 'oxid_id' ];

// Fetch.
if( !empty( $oxid_id ) ) {
    try {
        // SQL Queries.
        $stage_category_ids    =  "SELECT OXCATNID FROM `oxobject2category`";
        $stage_category_names  =  "SELECT OXID, OXTITLE FROM `oxcategories`";

        // Fetch stage category ids.
        $stage_category_ids = $stage_category_ids . " WHERE OXOBJECTID = '" . $oxid_id . "'";
        $stmt = $conn->prepare( $stage_category_ids );
        $stmt->execute();
        // Set the resulting array to associative.
        $result = $stmt->setFetchMode( PDO::FETCH_ASSOC );
        $category_ids = array();
        foreach( $stmt->fetchAll() as $key => $value ) {
            $category_ids[] = $value['OXCATNID'];
        }

        // Fetch stage category names.
        $category_names = array();
        if( !empty( $category_ids ) ) {
            if( !empty( array_filter( $category_ids ) ) ) {
                foreach( $category_ids as $key => $category_id ) {
                    // Fetch single category from table.
                    $stage_category_name = $stage_category_names . " WHERE OXID = '" . $category_id . "'";
                    $stmt = $conn->prepare( $stage_category_name );
                    $stmt->execute();
                    // Set the resulting array to associative.
                    $result = $stmt->setFetchMode( PDO::FETCH_ASSOC );
                    $category_ids = array();
                    foreach( $stmt->fetchAll() as $value ) {
                        $category_names[] = mb_convert_encoding( $value['OXTITLE'], 'UTF-8', 'HTML-ENTITIES' );
                    }
                }
            }
        }
        sort( $category_names ); // ASC order.
        echo implode( ', ', $category_names );
    }
    catch( PDOException $e ) {
        echo $sql . $e->getMessage();
    }
}