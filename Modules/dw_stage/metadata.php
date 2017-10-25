<?php
/**
 * Created by PhpStorm.
 * User: imran
 * Date: 24.12.15
 * Time: 15:00
 */

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'dw_stage',
    'title'       => 'dw Stage Slider',
    'description' => array(
        'en' => 'Dynamic stage area which can be edit from backend.',
        'de' => 'Dynamischer Produkt-Slider für den Bühnenbereich.'
    ),
    'thumbnail'   => 'out/pictures/denkwerk-logo.png',
    'version'     => '2.0',
    'author'      => 'denkwerk',
    'email'       => 'hello@denkwerk.com',
    'url'         => 'https://www.denkwerk.com/',
    'extend'      => array(
        'start'         => 'dw/dw_stage/application/controllers/frontend/dwstage_start',
        'alist'         => 'dw/dw_stage/application/controllers/frontend/dwstage_list',
        'oxviewconfig'  => 'dw/dw_stage/application/models/DwStage_oxviewconfig'
    ),
    'files'       => array(
        'dwstage_model'         => 'dw/dw_stage/application/models/dwstage_model.php',
        'dwstage_initial'       => 'dw/dw_stage/application/controllers/admin/dwstage_initial.php',
        'dwstage_controller'    => 'dw/dw_stage/application/controllers/admin/dwstage_controller.php'
    ),
    'templates'   => array(
        'dwstage.tpl'           => 'dw/dw_stage/application/views/admin/tpl/dwstage.tpl'
    ),
    'blocks'      => array(
        array(
            'template'  => 'page/shop/start.tpl',
            'block'     => 'dwstage_start',
            'file'      => 'views/blocks/dwstage.tpl'
        ),
        array(
            'template'  => 'page/list/list.tpl',
            'block'     => 'dwstage_list',
            'file'      => 'views/blocks/dwstage.tpl'
        )
    ),
    'settings' => array(
        array(
            'group'     => 'SETTINGS',
            'name'      => 'ENABLE_PHP_ERROR',
            'type'      => 'bool',
            'value'     => 'false'
        ),
    ),
    'events'      => array(
        'onActivate'            => 'dwstage_initial::onActivate',
        'onDeactivate'          => 'dwstage_initial::onDeactivate'
    )
);