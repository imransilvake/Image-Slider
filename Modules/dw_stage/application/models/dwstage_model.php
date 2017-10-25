<?php
/**
 * Created by PhpStorm.
 * User: ikhan
 * Date: 28.12.2015
 * Time: 12:36
 */

/**
 * dwstage model
 */
class dwstage_model extends oxI18n
{
    /**
     * Name of the table.
     * @var string
     */
    protected $_sCoreTbl = 'dwstage';

    /**
     * Name of current class.
     * @var string
     */
    protected $_sClassName = 'dwstage_model';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( 'dwstage' );
    }
}