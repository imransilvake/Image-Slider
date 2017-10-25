<?php
/**
 * Created by PhpStorm.
 * User: ikhan
 * Date: 15.01.2016
 * Time: 09:18
 */

/**
 * dwstage start
 *
 * Function:
 *
 * Stage: view
 */
class dwstage_start extends dwstage_start_parent
{
    // SQL query.
    private $sql_stage_id;
    private $single_row;

    // Initial Variables
    private static $moduleName  = 'dw_stage';
    private static $tableName   = 'dwstage';
    private static $stage_id;

    private static $all_languages;
    private static $default_language;
    private static $current_language;

    private $shop_url;
    private $image_url;

    private $multisite_id;

    // Render.
    public function render()
    {
        // Call 'initial' function.
        $this->initial();

        // Call 'fetch_data' function.
        $this->fetch_data();

        return parent::render();
    }

    // Initial.
    public function initial()
    {
        // Fetch sql query.
        $this->sql_stage_id = "SELECT stage_id FROM " . self::$tableName . " WHERE homepage = '1' AND status = '1'";
        self::$stage_id = oxDb::getDb()->getOne( $this->sql_stage_id );

        // Module variables.
        self::$all_languages     =  array_values( array_flip( $this->getConfig()->getConfigParam( '_aConfigParams' )['aLanguages'] ) );
        self::$default_language  =  $this->getConfig()->getConfigParam( '_aConfigParams' )['sDefaultLang'];
        self::$current_language  =  $this->getConfig()->getConfigParam( '_oActShop' )->_iLanguage;
        self::$current_language  =  self::$all_languages[self::$current_language];
        $this->shop_url          =  $this->getConfig()->getConfigParam( 'sShopURL' );
        $this->image_url         =  $this->shop_url . 'out/pictures/promo/' . self::$moduleName . '/' . self::$stage_id . '/' . self::$current_language;
        $multisite               =  $this->getConfig()->getConfigParam( '_oActShop' );
        $this->multisite_id      =  $multisite->oxshops__oxid;

        // Assign variables.
        $this->_aViewData[ 'shop_url' ]       =  $this->shop_url;
        $this->_aViewData[ 'module_name' ]    =  self::$moduleName;
        $this->_aViewData[ 'table_name' ]     =  self::$tableName;
        $this->_aViewData[ 'image_url' ]      =  $this->image_url;
        $this->_aViewData[ 'current_time' ]   =  time();

        // Fetch sql query.
        $this->single_row = "SELECT * FROM " . self::$tableName . " WHERE stage_id = '" . self::$stage_id . "'";
    }

    // Fetch data.
    public function fetch_data()
    {
        // Fetch single row data from table.
        $row_data = oxDb::getDb( true )->Execute( $this->single_row );
        if( $row_data != false && $row_data->recordCount() == 1 ) {
            // List selected multisite stages.
            $saved_multisite_id = $row_data->fields[8];
            if( $this->multisite_id == $saved_multisite_id ) {
                // Assign variables.
                $this->_aViewData['data_array'] = json_decode($row_data->fields[2]);
                $current_language = self::$current_language;
                $this->_aViewData['data_array'] = $this->_aViewData['data_array']->$current_language;
                $this->_aViewData['autoplay'] = (json_decode($row_data->fields[4]) == 1) ? 'true' : 'false';
                $this->_aViewData['autoloop'] = (json_decode($row_data->fields[5]) == 1) ? 'true' : 'false';
                $this->_aViewData['speed'] = json_decode($row_data->fields[6]) * 1000; // e.g: 3*1000 = 3 seconds
            }
        }
    }
}
