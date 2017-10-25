<?php
/**
 * Created by PhpStorm.
 * User: ikhan
 * Date: 14.01.2016
 * Time: 09:51
 */

/**
 * dwstage initial
 *
 * Functions:
 *
 * dwstage: onActivate, onDeactivate
 */
class dwstage_initial
{
    // onActivate.
    public static function onActivate()
    {
        // Delete existing `dwstage` table first.
        dwstage_initial::onDeactivate();

        // Create Table.
        $create_table = "CREATE TABLE IF NOT EXISTS `dwstage` (
          `oxid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `stage_id` varchar(200) DEFAULT NULL,
          `data` longtext NOT NULL,
          `homepage` tinyint(1) DEFAULT NULL,
          `autoplay` tinyint(1) DEFAULT NULL,
          `autoloop` tinyint(1) DEFAULT NULL,
          `speed` int(10) DEFAULT NULL,
          `status` tinyint(1) DEFAULT NULL,
          `shop_id` varchar(200) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        oxDb::getDb()->Execute( $create_table );
    }

    // onDeactivate.
    public static function onDeactivate()
    {
        // Delete Table.
        $delete_table = "DROP TABLE IF EXISTS `dwstage`";
        // oxDb::getDb()->Execute( $delete_table );
    }
}