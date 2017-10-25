<?php
/**
 * Created by PhpStorm.
 * User: imran
 * Date: 12.27.2015
 * Time: 10:28
 */

/**
 * dwstage controller
 *
 * Functions:
 *
 * Stage: insert, delete
 * Stage Data: update, delete
 */
class dwstage_controller extends oxAdminView
{
    // Template name.
    protected $template = 'dwstage.tpl';

    // Model object.
    private $stage;

    // SQL queries.
    private $all_rows;
    private $single_row;
    private $single_row_saved_stage_id;
    private $single_row_new_stage_id;
    private $oxid_id;
    private $stage_category_ids;
    private $stage_category_names;
    private $all_multisites;

    // Initial Variables.
    private static $moduleName         = 'dw_stage';
    private static $moduleTableName    = 'dwstage';
    private static $oxobject2category  = 'oxobject2category';
    private static $oxcategories       = 'oxcategories';
    private static $oxshops            = 'oxshops';

    private static $current_multisite;
    private static $all_languages;
    private static $default_language;
    private static $stage_id;

    private $dbHost;
    private $dbName;
    private $dbUser;
    private $dbPwd;

    private $shop_url;
    private $module_url;
    private $shop_dir;
    private $media_dir;
    private $module_version;
    private $image_url;
    private $image_dir;

    // Form variables.
    private $add_stage;
    private $save_data;
    private $current_language;
    private $delete_stage;
    private $delete_oxid;
    private $delete_stage_id;
    private $delete_layer;
    private $delete_image;
    private $old_stage_id;
    private $data;
    private $homepage;
    private $autoplay;
    private $autoloop;
    private $speed;
    private $status;
    private $multisite;

    // Constructor.
    public function __construct()
    {
        // Call 'dwstage_directory' class.
        $this->dwstage_directory = new dwstage_directory();

        // Call 'dwstage_image' class.
        $this->dwstage_image = new dwstage_image();
    }

    // Render.
    public function render()
    {
        parent::render();

        // Call 'initial' function.
        $this->initial();

        // Stage.
        if( !empty( $this->add_stage || $this->delete_stage ) && empty( $this->save_data ) ) {
            // 'Insert' function.
            if( !empty( $this->add_stage ) ) {
                // Fetch 'stage_id' from table.
                $fetched_single_row_saved_stage_id = oxDb::getDb()->getOne( $this->single_row_saved_stage_id );
                if( empty( $fetched_single_row_saved_stage_id ) ) {
                    // Call 'insert_stage' function.
                    $this->insert_stage();

                    // Set 'stage_added' variable to true.
                    $this->_aViewData[ 'stage_added' ] = self::$stage_id;
                }
                else {
                    // Set 'stage_id_exist' variable to give error.
                    $this->_aViewData[ 'stage_exist' ] = self::$stage_id;
                }
            }
            // 'Delete' function.
            if( !empty( $this->delete_stage ) ) {
                // Call 'delete_stage' function.
                $this->delete_stage();

                // Set 'stage_deleted' variable to true.
                $this->_aViewData[ 'stage_deleted' ] = $this->delete_stage_id;
            }
        }

        // Form Data ( update, delete (layer, image) ).
        if( !empty( $this->save_data || $this->delete_layer || $this->delete_image ) && empty( $this->add_stage ) ) {
            // Call 'form_data' function.
            $this->form_data();

            // 'Update' function.
            if( !empty( $this->save_data ) ) {
                // Call 'update_data' function.
                $this->update_data();
            }

            // 'Delete Layer' method.
            if( !empty( $this->delete_layer ) ) {
                // Array starts from 0. Counter is selected which starts from 1. So 'Counter - 1' is set.
                $delete_layer_no = $this->delete_layer - 1;
                // Decode jSon.
                $this->data = json_decode( $this->data );
                $this->data = (array)$this->data;

                // Update data array.
                $data_array = array();
                foreach( $this->data[ $this->current_language ] as $key => $value ) {
                    if( $key != $delete_layer_no ) { $data_array[] = $value; }
                    else {
                        // Call 'delete_image' function.
                        $this->dwstage_image->delete_image( $this->image_dir, $value[3] );
                    }
                }

                // Call 'organize_data' function.
                $this->data = $this->organize_data( $data_array );

                // Call 'update_data' function.
                $this->update_data();

                // Set 'layer_deleted' variable to true.
                $this->_aViewData[ 'layer_deleted' ] = true;
            }

            // 'Delete Image' method.
            if( !empty( $this->delete_image ) ) {
                // Array starts from 0. Counter is selected which starts from 1. So 'Counter - 1' is set.
                $delete_image_no = $this->delete_image - 1;
                // Decode jSon.
                $this->data = json_decode( $this->data );
                $this->data = (array)$this->data;

                // Update data array.
                $data_array = array();
                foreach( $this->data[ $this->current_language ] as $key => $value ) {
                    if( $key == $delete_image_no ) {
                        // Call 'delete_image' function.
                        $this->dwstage_image->delete_image( $this->image_dir, $value[3] );
                        // Set image field to empty.
                        $value[3] = '';
                        $data_array[] = $value;
                    }
                    else {
                        $data_array[] = $value;
                    }
                }

                // Call 'organize_data' function.
                $this->data = $this->organize_data( $data_array );

                // Call 'update_data' function.
                $this->update_data();

                // Set 'image_deleted' variable to true.
                $this->_aViewData[ 'image_deleted' ] = true;
            }
        }

        // Call 'fetch_rows' function.
        $this->fetch_rows();

        // Call 'fetch_data' function.
        $this->fetch_data();

        return $this->template;
    }

    // Initial.
    public function initial()
    {
        // Check if php error is enable or not.
        $enable_php_error = $this->getConfig()->getConfigParam( 'ENABLE_PHP_ERROR' );
        if( !empty( $enable_php_error ) ) {
            // Enable PHP errors.
            ini_set( 'display_errors', 1 );
            error_reporting( E_ALL );
        }

        // Creating model's object.
        $this->stage = oxNew( 'dwstage_model' );

        // Form action variables.
        $this->add_stage          =  isset( $_POST['add_stage'] ) ? $_POST['add_stage']: false;
        $this->save_data          =  isset( $_POST['save_data'] ) ? $_POST['save_data']: false;

        // Language.
        self::$all_languages      =  $this->getConfig()->getConfigParam( '_aConfigParams' )['aLanguages'];
        self::$default_language   =  $this->getConfig()->getConfigParam( '_aConfigParams' )['sDefaultLang'];
        self::$default_language   =  array_values( array_flip( $this->getConfig()->getConfigParam( '_aConfigParams' )['aLanguages'] ) )[self::$default_language];
        $this->current_language   =  isset( $_POST['current_language'] ) ? $_POST['current_language']: self::$default_language;

        $this->delete_stage       =  isset( $_POST['delete_stage'] ) ? $_POST['delete_stage']: false;
        $delete_stage_data        =  explode( '::', $this->delete_stage );
        $this->delete_oxid        =  isset( $delete_stage_data[0] ) ? $delete_stage_data[0]: false;
        $this->delete_stage_id    =  isset( $delete_stage_data[1] ) ? $delete_stage_data[1]: false;

        $this->delete_layer       =  isset( $_POST['delete_layer'] ) ? $_POST['delete_layer']: false;
        $this->delete_image       =  isset( $_POST['delete_image'] ) ? $_POST['delete_image']: false;

        // Stage ID.
        if( !empty( $this->add_stage ) ) {
            self::$stage_id = isset( $_POST['new_stage_id'] ) ? strtolower( preg_replace( "/[^a-zA-Z0-9]+/", "", $_POST['new_stage_id'] ) ): false;
        }
        else if( isset( $_POST['view_stage_id'] ) ) {
            self::$stage_id = isset( $_POST['view_stage_id'] ) ? $_POST['view_stage_id']: false;
        }
        else if( !empty( $this->delete_stage ) ) {
            self::$stage_id = false;
        }
        else {
            self::$stage_id = isset( $_POST['update_stage_id'] ) ? strtolower( preg_replace( "/[^a-zA-Z0-9]+/", "", $_POST['update_stage_id'] ) ): false;
        }

        $this->old_stage_id = isset( $_POST['saved_stage_id'] ) ? $_POST['saved_stage_id']: false;
        $this->old_stage_id = !empty( $this->old_stage_id ) ? $this->old_stage_id: self::$stage_id;

        // List multisites.
        $selected_multisite = isset( $_POST['selected_multisite'] ) ? $_POST['selected_multisite']: false;
        if( !empty( $selected_multisite ) ) {
            self::$current_multisite  =  $selected_multisite;
            self::$stage_id = false;
        }
        else {
            self::$current_multisite  =  isset( $_POST['current_multisite'] ) ? $_POST['current_multisite']: '';
        }
        $this->_aViewData[ 'current_multisite' ] = self::$current_multisite;

        // Stage multisite.
        $this->multisite    =  isset( $_POST['multisite'] ) ? $_POST['multisite']: false;

        // SQL queries.
        $this->all_rows                   =  "SELECT * FROM " . self::$moduleTableName . " ORDER BY stage_id";
        $this->single_row                 =  "SELECT * FROM " . self::$moduleTableName . " WHERE stage_id = '" . self::$stage_id . "'";
        $this->single_row_saved_stage_id  =  "SELECT stage_id FROM " . self::$moduleTableName . " WHERE stage_id = '" . $this->old_stage_id . "'";
        $this->single_row_new_stage_id    =  "SELECT oxid FROM " . self::$moduleTableName . " WHERE stage_id = '" . self::$stage_id . "'";
        $this->oxid_id                    =  "SELECT oxid FROM " . self::$moduleTableName . " WHERE stage_id = '" . $this->old_stage_id . "'";
        $this->stage_category_ids         =  "SELECT OXCATNID FROM " . self::$oxobject2category;
        $this->stage_category_names       =  "SELECT OXID, OXTITLE FROM " . self::$oxcategories;
        $this->all_multisites             =  "SELECT OXID, OXNAME FROM " . self::$oxshops;

        // Module variables.
        $this->dbHost            =  $this->getConfig()->getConfigParam( 'dbHost' );
        $this->dbName            =  $this->getConfig()->getConfigParam( 'dbName' );
        $this->dbUser            =  $this->getConfig()->getConfigParam( 'dbUser' );
        $this->dbPwd             =  $this->getConfig()->getConfigParam( 'dbPwd' );
        $this->shop_url          =  $this->getConfig()->getConfigParam( 'sShopURL' );
        $this->module_url        =  $this->getConfig()->getConfigParam( 'sShopURL' ) . 'modules/dw/' . self::$moduleName . '/';
        $this->shop_dir          =  $this->getConfig()->getConfigParam( 'sShopDir' );
        $this->media_dir         =  $this->shop_dir . 'out/pictures/promo/' . self::$moduleName . '/';
        $this->module_version    =  $this->getConfig()->getConfigParam( '_aConfigParams' )['aModuleVersions'][self::$moduleName];
        $this->image_dir         =  $this->shop_dir . 'out/pictures/promo/' . self::$moduleName . '/' . $this->old_stage_id . '/' . $this->current_language . '/';

        // Assign variables.
        $this->_aViewData[ 'dbHost' ]           =  $this->dbHost;
        $this->_aViewData[ 'dbName' ]           =  $this->dbName;
        $this->_aViewData[ 'dbUser' ]           =  $this->dbUser;
        $this->_aViewData[ 'dbPwd' ]            =  $this->dbPwd;

        $this->_aViewData[ 'shop_url' ]         =  $this->shop_url;
        $this->_aViewData[ 'module_url' ]       =  $this->module_url;
        $this->_aViewData[ 'shop_dir' ]         =  $this->shop_dir;
        $this->_aViewData[ 'media_dir' ]        =  $this->media_dir;
        $this->_aViewData[ 'module_version' ]   =  $this->module_version;
        $this->_aViewData[ 'module_name' ]      =  self::$moduleName;
        $this->_aViewData[ 'table_name' ]       =  self::$moduleTableName;
        $this->_aViewData[ 'stage_id' ]         =  self::$stage_id;
        $this->_aViewData[ 'image_dir' ]        =  $this->image_dir;
        $this->_aViewData[ 'current_time' ]     =  time();
        $this->_aViewData[ 'default_language' ] =  self::$default_language;
        $this->_aViewData[ 'all_languages' ]    =  self::$all_languages;
        $this->_aViewData[ 'current_language' ] =  $this->current_language;
    }

    // Insert stage.
    public function insert_stage()
    {
        // Assign values to table columns.
        $this->stage->dwstage__stage_id  =  new oxField( self::$stage_id );
        $this->stage->dwstage__data      =  new oxField( '' );
        $this->stage->dwstage__homepage  =  new oxField( 0 );
        $this->stage->dwstage__autoplay  =  new oxField( 1 );
        $this->stage->dwstage__autoloop  =  new oxField( 1 );
        $this->stage->dwstage__speed     =  new oxField( 5 );
        $this->stage->dwstage__status    =  new oxField( 1 );
        $this->stage->dwstage__shop_id   =  new oxField( $this->multisite );

        // Save.
        $this->stage->save();
    }

    // Delete stage.
    public function delete_stage()
    {
        // Delete stage.
        $delete_row = "DELETE FROM " . self::$moduleTableName . " WHERE oxid = '" . $this->delete_oxid . "'";
        oxDb::getDb()->Execute( $delete_row );

        // Delete stage categories.
        $delete_row = "DELETE FROM " . self::$oxobject2category . " WHERE OXOBJECTID = '" . md5( $this->delete_oxid ) . "'";
        oxDb::getDb()->Execute( $delete_row );

        // Call 'delete_directory' function.
        $stage_directory = $this->media_dir . $this->delete_stage_id;
        $this->dwstage_directory->delete_directory( $stage_directory );
    }

    // Form data.
    public function form_data()
    {
        // Config.
        $this->homepage        =  isset( $_POST['homepage_value'] ) ? $_POST['homepage_value']: false;
        $this->autoplay        =  isset( $_POST['autoplay'] ) ? $_POST['autoplay']: false;
        $this->autoloop        =  isset( $_POST['autoloop'] ) ? $_POST['autoloop']: false;
        $this->speed           =  isset( $_POST['speed'] ) ? $_POST['speed']: false;
        $this->status          =  isset( $_POST['status_value'] ) ? $_POST['status_value']: false;

        // Layer.
        $layer_id              =  isset( $_POST['layer_id'] ) ? $_POST['layer_id']: false;
        $element_status        =  isset( $_POST['element_status'] ) ? $_POST['element_status']: false;
        $headline              =  isset( $_POST['headline'] ) ? $_POST['headline']: false;
        $subline               =  isset( $_POST['subline'] ) ? $_POST['subline']: false;
        $description           =  isset( $_POST['description'] ) ? $_POST['description']: false;
        $cta_url               =  isset( $_POST['cta_url'] ) ? $_POST['cta_url']: false;
        // Extra Field.
        $extra_field           =  isset( $_POST['extra_field'] ) ? $_POST['extra_field']: false;
        $extra_field           =  $this->arrange_array_data( $extra_field );  // Call 'arrange_array_data' function.
        // Price.
        $price                 =  isset( $_POST['price'] ) ? $_POST['price']: false;
        $price                 =  $this->arrange_array_data( $price );  // Call 'arrange_array_data' function.
        // Image.
        $saved_image_name      =  isset( $_POST['saved_image_name'] ) ? $_POST['saved_image_name']: false; // Previously saved image name (if any).
        $image_name            =  $_FILES['image']['name']; // Image name.
        $image_tmp_name        =  $_FILES['image']['tmp_name']; // Image tmp name.
        $image_error_code      =  $_FILES['image']['error']; // Image error code.

        // Data - jSon format.
        $data = array();
        foreach( $headline as $key => $value ) {
            // Check headline input field is empty or not.
            if( !empty( $value ) ) {
                // Set layer id on newly added layers.
                if( empty( $layer_id[$key] ) ) {
                    do { $num = rand(); }
                    while( in_array( $num, array( 234, 1578 ,763 , 1274 ) ) );
                    $layer_id[$key] = $num;
                }

                // Check new image is empty or not.
                if( !empty( $image_name[$key] ) ) {
                    // Call 'create_image' function.
                    $returned_image_data = $this->dwstage_image->create_image( $this->image_dir, $image_name[$key], $image_tmp_name[$key], $image_error_code[$key], $layer_id[$key], $saved_image_name[$key] );

                    $returned_image_data = json_decode( $returned_image_data );
                    $returned_image_name = $returned_image_data->image_name;
                    $image_error         = $returned_image_data->image_error;
                    if( !empty( $image_error ) ) {
                        // Set 'upload_image_error' variable to give error.
                        $this->_aViewData[ 'upload_image_error' ] = $image_error;
                    }
                }
                else if ( !empty( $saved_image_name[$key] ) ) { $returned_image_name = $saved_image_name[$key]; }
                else { $returned_image_name = false; }

                // Store all to data array.
                $data[] = array( $headline[$key], $subline[$key], $description[$key], $returned_image_name, $cta_url[$key], $extra_field[$key], $price[$key], $element_status[$key], $layer_id[$key] );
            }
            else {}
        }
        // Call 'organize_data' function.
        $this->data = $this->organize_data( $data );
    }

    // Update data.
    public function update_data()
    {
        // Fetch 'oxid' (id) from table.
        $fetched_oxid_id = oxDb::getDb()->getOne( $this->oxid_id );

        // Fetch saved 'stage_id' from table.
        $fetched_saved_stage_id = oxDb::getDb()->getOne( $this->single_row_saved_stage_id );

        // Fetch new 'stage_id' from table.
        $fetched_new_stage_id = oxDb::getDb()->getOne( $this->single_row_new_stage_id );

        // Assign values to table columns.
        $this->stage->setId( $fetched_oxid_id );

        // Directories paths.
        $old_directory = $this->media_dir . $this->old_stage_id;
        $new_directory = $this->media_dir . self::$stage_id;

        // Stage ID: Saved != new.
        if( $fetched_saved_stage_id != self::$stage_id ) {
            // Stage ID: new is not empty.
            if( !empty( $fetched_new_stage_id ) ) {
                // Saved stage id.
                $this->stage->dwstage__stage_id = new oxField( $fetched_saved_stage_id );

                // Set stage id.
                $this->_aViewData[ 'stage_id' ] = $fetched_saved_stage_id;

                // Edit single row.
                $this->single_row = str_replace( self::$stage_id, $fetched_saved_stage_id, $this->single_row );

                // Unchanged new directory.
                $new_directory = $this->media_dir . $fetched_saved_stage_id;

                // Image path.
                $this->image_url = $this->shop_url . 'out/pictures/promo/' . self::$moduleName . '/' . $fetched_saved_stage_id . '/' . $this->current_language;

                // Set 'data_updated_stage_id_exists' variable to true.
                $this->_aViewData[ 'data_updated_stage_id_exists' ] = self::$stage_id;
            }
            else {
                // New stage id.
                $this->stage->dwstage__stage_id = new oxField( self::$stage_id );

                // Set 'data_updated' variable to true.
                $this->_aViewData[ 'data_updated' ] = true;
            }
        }
        else {
            // Saved stage id.
            $this->stage->dwstage__stage_id = new oxField( self::$stage_id );

            // Set 'data_updated' variable to true.
            $this->_aViewData[ 'data_updated' ] = true;
        }

        $this->stage->dwstage__data      =  new oxField( $this->data );
        $this->stage->dwstage__homepage  =  new oxField( $this->homepage );
        $this->stage->dwstage__autoplay  =  new oxField( $this->autoplay );
        $this->stage->dwstage__autoloop  =  new oxField( $this->autoloop );
        $this->stage->dwstage__speed     =  new oxField( $this->speed );
        $this->stage->dwstage__status    =  new oxField( $this->status );
        $this->stage->dwstage__shop_id   =  new oxField( $this->multisite );

        // Save.
        $this->stage->save();

        // Call 'rename_directory' function.
        $this->dwstage_directory->rename_directory( $old_directory, $new_directory );
    }

    // Fetch data.
    public function fetch_data()
    {
        // Fetch single row data from table.
        $row_data = oxDb::getDb( true )->Execute( $this->single_row );
        if( $row_data != false && $row_data->recordCount() == 1 ) {
            // Assign variables.
            $this->_aViewData[ 'oxid' ]               =  md5( json_decode( $row_data->fields[ 0 ] ) );

            $this->_aViewData[ 'data_array' ]         =  json_decode( $row_data->fields[ 2 ] );
            $current_language                         =  $this->current_language;
            $this->_aViewData[ 'data_array' ]         =  $this->_aViewData[ 'data_array' ]->$current_language;

            $this->_aViewData[ 'autoplay' ]           =  $row_data->fields[ 4 ];
            $this->_aViewData[ 'autoloop' ]           =  $row_data->fields[ 5 ];
            $this->_aViewData[ 'speed' ]              =  $row_data->fields[ 6 ];
            $this->_aViewData[ 'saved_multisite' ]    =  $row_data->fields[ 8 ];

            // Fetch stage category ids.
            $this->stage_category_ids = $this->stage_category_ids . " WHERE OXOBJECTID = '" . $this->_aViewData[ 'oxid' ] . "'";
            $rows = oxDb::getDb( true )->Execute( $this->stage_category_ids );
            $stage_category_ids = array();
            if( $rows != false && $rows->recordCount() > 0 ) {
                while ( !$rows->EOF ) {
                    // get a field value of the current row.
                    $stage_category_ids[] = $rows->fields[0];
                    // move to the next row.
                    $rows->moveNext();
                }
            }

            // Fetch stage category names.
            $stage_category_names = array();
            if( !empty( $stage_category_ids ) ) {
                if( !empty( array_filter( $stage_category_ids ) ) ) {
                    foreach( $stage_category_ids as $key => $stage_category_id ) {
                        // Fetch single category from table.
                        $stage_category_name = $this->stage_category_names . " WHERE OXID = '" . $stage_category_id . "'";
                        $row_data = oxDb::getDb( true )->Execute( $stage_category_name );

                        $stage_category_names[] = $row_data->fields[1];
                    }
                }
            }
            sort( $stage_category_names ); // ASC order.

            // Assign variables.
            $this->_aViewData[ 'num_of_categories' ]     = $rows->_numOfRows;
            $this->_aViewData[ 'stage_category_names' ]  = $stage_category_names;

            // Image path.
            $this->image_url = ( !empty( $this->image_url ) ) ? $this->image_url : $this->shop_url . 'out/pictures/promo/' . self::$moduleName . '/' . self::$stage_id . '/' . $this->current_language;
            $this->_aViewData[ 'image_url' ] = $this->image_url;
        }
    }

    // Fetch rows.
    public function fetch_rows()
    {
        // Fetch all sites.
        $rows = oxDb::getDb( true )->Execute( $this->all_multisites );
        $all_multisites_ids = array();
        if( $rows != false && $rows->recordCount() > 0 ) {
            while ( !$rows->EOF ) {
                // Set shop id & shop name value.
                $all_multisites_ids[$rows->fields[0]] = $rows->fields[1];
                // Check if currently any site is selected or not.
                if( empty( self::$current_multisite ) ) {
                    self::$current_multisite = $rows->fields[0];
                }
                // move to the next row.
                $rows->moveNext();
            }
        }
        // Assign variables.
        $this->_aViewData[ 'all_multisites' ] = $all_multisites_ids;

        // Fetch all rows from table.
        $rows = oxDb::getDb( true )->Execute( $this->all_rows );
        $oxid_ids        = array();
        $stage_ids       = array();
        $homepage_values = array();
        $status_values   = array();
        if( $rows != false && $rows->recordCount() > 0 ) {
            while ( !$rows->EOF ) {
                // List selected multisite stages.
                if( self::$current_multisite == $rows->fields[8] ) {
                    // Set oxid id.
                    $oxid_ids[] = $rows->fields[0];
                    // Set stage id.
                    $stage_ids[] = $rows->fields[1];
                    // Set homepage value.
                    $homepage_values[] = $rows->fields[3];
                    // Set status value.
                    $status_values[] = $rows->fields[7];
                }
                // move to the next row.
                $rows->moveNext();
            }
        }
        // Assign variables.
        $this->_aViewData[ 'num_of_rows' ]       = $rows->_numOfRows;
        $this->_aViewData[ 'oxid_ids' ]          = $oxid_ids;
        $this->_aViewData[ 'stage_ids' ]         = $stage_ids;
        $this->_aViewData[ 'homepage_values' ]   = $homepage_values;
        $this->_aViewData[ 'status_values' ]     = $status_values;
    }

    // Organize data.
    public function organize_data( $data )
    {
        // Current data.
        $current_data = array();
        $current_data[ $this->current_language ] = $data;

        // Fetch saved 'stage_id' from table.
        $fetched_saved_stage_id = oxDb::getDb()->getOne( $this->single_row_saved_stage_id );
        // Edit single row.
        $single_row = str_replace( self::$stage_id, $fetched_saved_stage_id, $this->single_row );

        $row_data = oxDb::getDb( true )->Execute( $single_row );
        if( $row_data != false && $row_data->recordCount() == 1 ) {
            // Fetch saved data.
            $saved_data = json_decode( $row_data->fields[ 2 ] );
            $saved_data = (array)$saved_data;

            // Merge data.
            $new_data = array_merge( $current_data, $saved_data );
            // Update current index data.
            $new_data[ $this->current_language ] = $data;

            $this->data = json_encode( $new_data );
            return $this->data;
        }
    }

    // Arrange array data.
    public function arrange_array_data( $input_array )
    {
        $store_values = array();
        $return_array = array();
        foreach( $input_array as $key => $value ) {
            if( $value == 'layer-break' ) {
                $return_array[] = $store_values;
                $store_values = array();
            }
            else { $store_values[] = $value; }
        }
        return $return_array;
    }
}

/**
 * dwstage directory
 *
 * Functions:
 *
 * directory: create, rename, delete
 */
class dwstage_directory
{
    // Create directory.
    public function create_directory( $image_dir )
    {
        if( !file_exists( $image_dir ) ) {
            mkdir( $image_dir, 0755, true );
        }
    }

    // Rename directory.
    public function rename_directory( $old_directory, $new_directory )
    {
        rename( $old_directory, $new_directory );
    }

    // Delete directory.
    public function delete_directory( $stage_directory )
    {
        if( file_exists( $stage_directory ) ) {
            $files = array_diff( scandir( $stage_directory ), array( '.', '..' ) );
            foreach( $files as $file ) {
                ( is_dir( "$stage_directory/$file" ) ) ? $this->delete_directory( "$stage_directory/$file" ) : unlink( "$stage_directory/$file" );
            }
            return rmdir( $stage_directory );
        }
    }
}

/**
 * dwstage image
 *
 * Functions:
 *
 * image: create, delete
 */
class dwstage_image
{
    // Constructor.
    public function __construct()
    {
        // Call 'dwstage_directory' class.
        $this->dwstage_directory = new dwstage_directory();
    }

    // Create image.
    public function create_image( $image_directory, $image_name, $image_tmp_name, $image_error_code, $layer_id, $saved_image_name )
    {
        // Call 'create_directory' function.
        $this->dwstage_directory->create_directory( $image_directory );

        // Call 'delete_image' function.
        isset( $saved_image_name ) ? $this->delete_image( $image_directory, $saved_image_name ) : false;

        // Image extention.
        $image_extention = pathinfo( $image_name, PATHINFO_EXTENSION );
        // New image name.
        $new_image_name = $layer_id . '.' . $image_extention;
        // Image path.
        $image_path = $image_directory . $new_image_name;
        // Image error.
        $image_error = '';

        // Save image to a directory.
        if( move_uploaded_file( $image_tmp_name, $image_path ) ) {
            // Call 'delete_image' function.
            $return_image_name = $new_image_name;
        }
        else {
            // Switch statement to show correct information related to error code.
            switch( $image_error_code ) {
                case 1: $image_error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; break;
                case 2: $image_error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.'; break;
                case 3: $image_error = 'The uploaded file was only partially uploaded.'; break;
                case 4: $image_error = 'No file was uploaded.'; break;
                case 6: $image_error = 'Missing a temporary folder.'; break;
                case 7: $image_error = 'Failed to write file to disk.'; break;
                case 8: $image_error = 'A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help.'; break;
            }
        }

        // Check return image name is empty or not.
        $return_image_name = !empty( $return_image_name ) ? $return_image_name : false;

        // Image return data.
        $image_data = array(
            'image_name'   =>  $return_image_name,
            'image_error'  =>  $image_error
        );
        $image_data = json_encode( $image_data );
        return $image_data;
    }

    // Delete image.
    public function delete_image( $image_directory, $image_name )
    {
        if( !empty( $image_name ) ) {
            unlink( $image_directory . $image_name );
        }
    }
}
