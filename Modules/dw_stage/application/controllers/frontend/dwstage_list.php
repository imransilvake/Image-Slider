<?php
/**
 * Created by PhpStorm.
 * User: ikhan
 * Date: 15.01.2016
 * Time: 09:18
 */

/**
 * dwstage list
 *
 * Function:
 *
 * Stage: view
 */
class dwstage_list extends dwstage_list_parent
{
    // SQL query.
    private $sql_stage_id;
    private $single_row;

    // Initial Variables.
    public $active_module              = 'active';
    private static $moduleName         = 'dw_stage';
    private static $tableName          = 'dwstage';
    private static $oxobject2category  = 'oxobject2category';
    private static $oxcategories       = 'oxcategories';

    private static $stage_id;
    private static $all_languages;
    private static $default_language;
    private static $current_language;

    private $stage_category_ids;
    private $stage_category_names;
    private $current_category;

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
        // SQL queries.
        $this->oxid_stage_ids        =  "SELECT oxid, stage_id FROM " . self::$tableName . " WHERE status = '1'";
        $this->stage_category_ids    =  "SELECT OXCATNID, OXOBJECTID FROM " . self::$oxobject2category;
        $this->stage_category_names  =  "SELECT OXID, OXTITLE FROM " . self::$oxcategories;
        $multisite                   =  $this->getConfig()->getConfigParam( '_oActShop' );
        $this->multisite_id          =  $multisite->oxshops__oxid;

        // Fetch all oxid & stage (ids) list.
        $rows = oxDb::getDb( true )->Execute( $this->oxid_stage_ids );
        $oxid_ids = array();
        $stage_ids = array();
        if( $rows != false && $rows->recordCount() > 0 ) {
            while ( !$rows->EOF ) {
                // get a field value of the current row.
                $oxid_ids[]  = md5( $rows->fields[0] );
                $stage_ids[] = $rows->fields[1];
                // move to the next row!!
                $rows->moveNext();
            }
        }
        $oxid_ids_in = implode( "','", $oxid_ids );

        // Fetch oxid & stage category ids.
        $this->stage_category_ids  =  $this->stage_category_ids . " WHERE OXOBJECTID IN ( '" . $oxid_ids_in . "' )";
        $rows = oxDb::getDb( true )->Execute( $this->stage_category_ids );
        $stage_category_ids = array();
        $stage_oxid_ids = array();
        if( $rows != false && $rows->recordCount() > 0 ) {
            while ( !$rows->EOF ) {
                // get a field values of the current row.
                $stage_category_ids[] = $rows->fields[0];
                $stage_oxid_ids[]     = $rows->fields[1];
                // move to the next row!!
                $rows->moveNext();
            }
        }

        // Group categories with stage ids.
        $group_stage_categories = array();
        foreach( $stage_category_ids as $key => $stage_category_id ) {
            $group_stage_categories[ $stage_oxid_ids[$key] ][$key] = $stage_category_id;
        }

        // Current category.
        $this->current_category = $this->_oActCategory->oxcategories__oxtitle;

        // Fetch stage category names.
        $stage_id_names = array();
        if( !empty( $group_stage_categories ) ) {
            if( !empty( array_filter( $group_stage_categories ) ) ) {
                foreach( $group_stage_categories as $key => $group_stage_ids ) {
                    foreach( $group_stage_ids as $category_id ) {
                        // Fetch single category from table.
                        $stage_category_name = $this->stage_category_names . " WHERE OXID = '" . $category_id . "'";
                        $row_data = oxDb::getDb( true )->Execute( $stage_category_name );

                        if( strtolower( $this->current_category ) == strtolower( $row_data->fields[1] ) ) {
                            $stage_id_names[] = $key;
                        }
                    }
                }
            }
        }
        sort( $stage_id_names ); // ASC order.

        // Check if there is any stage id of current category.
        foreach( $oxid_ids as $key => $oxid_id ) {
            if( in_array( $oxid_id, $stage_id_names ) ) {
                self::$stage_id = $stage_ids[$key];
                // To display first stage if same category exits in more than one stage.
                break;
            }
        }

        // Module variables.
        self::$all_languages     =  array_values( array_flip( $this->getConfig()->getConfigParam( '_aConfigParams' )['aLanguages'] ) );
        self::$default_language  =  $this->getConfig()->getConfigParam( '_aConfigParams' )['sDefaultLang'];
        self::$current_language  =  $this->getConfig()->getConfigParam( '_oActShop' )->_iLanguage;
        self::$current_language  =  self::$all_languages[self::$current_language];
        $this->shop_url          =  $this->getConfig()->getConfigParam( 'sShopURL' );
        $this->image_url         =  $this->shop_url . 'out/pictures/promo/' . self::$moduleName . '/' . self::$stage_id . '/' . self::$current_language;

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
