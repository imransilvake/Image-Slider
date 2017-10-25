[{* debug *}]
[{oxscript include=$oViewConf->getModuleUrl($module_name, "assets/admin/custom/js/jquery-2.1.4.min.js")}]
[{oxscript include=$oViewConf->getModuleUrl($module_name, "assets/admin/jquery-ui-1.11.4/js/jquery-ui.min.js")}]
[{oxscript include=$oViewConf->getModuleUrl($module_name, "assets/admin/bootstrap-3.3.6/js/bootstrap.min.js")}]
[{oxscript include=$oViewConf->getModuleUrl($module_name, "assets/admin/bootstrap-dialog/js/bootstrap-dialog.min.js")}]
[{oxscript include=$oViewConf->getModuleUrl($module_name, "assets/admin/custom/js/tinymce/tinymce.min.js")}]
[{oxscript include=$oViewConf->getModuleUrl($module_name, "assets/admin/custom/js/script.js")}]

[{oxstyle include=$oViewConf->getModuleUrl($module_name, "assets/admin/bootstrap-3.3.6/css/bootstrap.min.css")}]
[{oxstyle include=$oViewConf->getModuleUrl($module_name, "assets/admin/bootstrap-dialog/css/bootstrap-dialog.min.css")}]
[{oxstyle include=$oViewConf->getModuleUrl($module_name, "assets/admin/font-awesome-4.5.0/css/font-awesome.min.css")}]
[{oxstyle include=$oViewConf->getModuleUrl($module_name, "assets/admin/bootstrap-checkbox/css/checkboxes.css")}]
[{oxstyle include=$oViewConf->getModuleUrl($module_name, "assets/admin/custom/css/style.css")}]

[{oxstyle}]
[{oxscript}]

<div class="container-fluid marginTop15">
    [{* Database *}]
    <span class="dbConfig" data-dbhost="[{$dbHost}]" data-dbname="[{$dbName}]" data-dbuser="[{$dbUser}]" data-dbpwd="[{$dbPwd}]"></span>

    [{* Form *}]
    <form method="POST" action="[{$oViewConf->getSelfLink()}]" enctype="multipart/form-data" onkeypress="return event.keyCode!=13;">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="[{$oViewConf->getActionClassName()}]" />
        [{assign var="class_name" value=$oViewConf->getActionClassName()}]

        [{* Stage Alert Messages *}]
        [{if !empty($stage_added)}]
            [{assign var="stage_added" value="<b>$stage_added</b>"}]
            <div class="alert alert-success" role="alert">[{oxmultilang ident="STAGE_ADDED" args=$stage_added}]</div>
        [{/if}]
        [{if !empty($stage_exist)}]
            [{assign var="stage_exist" value="<b>$stage_exist</b>"}]
            <div class="alert alert-danger" role="alert">[{oxmultilang ident="STAGE_ID_EXIST" args=$stage_exist}]</div>
        [{/if}]
        [{if !empty($stage_deleted)}]
            [{assign var="stage_deleted" value="<b>$stage_deleted</b>"}]
            <div class="alert alert-success" role="alert">[{oxmultilang ident="STAGE_DELETED" args=$stage_deleted}]</div>
        [{/if}]

        [{if !empty($data_updated)}]
            <div class="alert alert-success [{if !empty($layer_deleted) || !empty($image_deleted) || !empty($upload_image_error)}]marginBottom3[{/if}]" role="alert">[{oxmultilang ident="DATA_UPDATED"}]</div>
        [{/if}]
        [{if !empty($data_updated_stage_id_exists)}]
            <div class="alert alert-success marginBottom3" role="alert">[{oxmultilang ident="DATA_UPDATED"}]</div>
            [{assign var="data_updated_stage_id_exists" value="<b>$data_updated_stage_id_exists</b>"}]
            <div class="alert alert-danger [{if !empty($image_deleted) || !empty($upload_image_error)}]marginBottom3[{/if}]" role="alert">[{oxmultilang ident="STAGE_ID_EXIST" args=$data_updated_stage_id_exists}]</div>
        [{/if}]
        [{if !empty($layer_deleted)}]
            <div class="alert alert-success" role="alert">[{oxmultilang ident="LAYER_DELETED"}]</div>
        [{/if}]
        [{if !empty($image_deleted)}]
            <div class="alert alert-success" role="alert">[{oxmultilang ident="IMAGE_DELETED"}]</div>
        [{/if}]
        [{if !empty($upload_image_error)}]
            <div class="alert alert-danger" role="alert"><b>[{oxmultilang ident="UPLOAD_IMAGE_ERROR"}]</b>[{$upload_image_error}]</div>
        [{/if}]

        [{* Stages List *}]
        [{if $num_of_rows > 0 }]
            <div class="panel-group" id="stage-list-main">
                <div class="panel-heading add-stage">
                    <h5 class="panel-title">
                        [{* Add Stage *}]
                        <a title="[{oxmultilang ident="CREATE_NEW_STAGE"}]" href="[{oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=$class_name"}]" class="btn btn-primary add-button">
                            <i class="fa fa-plus"></i>
                        </a>
                    </h5>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading stage-heading">
                        <h5 class="panel-title">
                            [{* Head Title *}]
                            <a class="accordion-toggle-stage" data-toggle="collapse" data-parent="#accordion" href="#collapse_stages_list">
                                <i class="fa fa-tag"></i> [{oxmultilang ident="STAGES"}]
                            </a>
                            [{* Multisites List *}]
                            <fieldset class="multisite-fieldset">
                                <div class="input-group">
                                    <button id="multisite-menu-stages-list" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                        [{if empty( $current_multisite ) }]
                                            [{$all_multisites|@reset}]
                                            [{assign var=shop_key value=$all_multisites|@key}]
                                        [{else}]
                                            [{$all_multisites[$current_multisite]}]
                                            [{assign var=shop_key value=$current_multisite}]
                                        [{/if}]
                                        &ensp;<span class="caret"></span>
                                    </button>
                                    <ul id="multisite-menu-ul-stages-list" class="multisite-dropdown-menu dropdown-menu" role="menu" aria-labelledby="multisite-menu">
                                        [{if !empty($all_multisites)}]
                                            [{if !empty(array_filter($all_multisites))}]
                                                [{foreach from=$all_multisites key=multisite_id item=multisite_name}]
                                                    <li role="presentation"><button type="submit" name="selected_multisite" class="selected-multisite selected-multisite-stage-list" value="[{$multisite_id}]"><span>[{$multisite_name}]</span></button></li>
                                                [{/foreach}]
                                            [{/if}]
                                        [{/if}]
                                        <input type="hidden" id="current-multisite" class="current-multisite" name="current_multisite" value="[{$shop_key}]">
                                    </ul>
                                </div>
                            </fieldset>
                            [{* Search Stage *}]
                            <div class="search-stage" id="search-stage">
                                <input type="search" class="search-stage-input" id="search-stage-input" placeholder="Search" />
                            </div>
                        </h5>
                    </div>
                    <div id="collapse_stages_list" class="panel-collapse collapse [{if empty($stage_id)}]in[{/if}]">
                        <div class="panel-body">
                            [{if !empty($stage_ids)}]
                                [{if !empty(array_filter($stage_ids))}]
                                    [{foreach from=$stage_ids key=key item=value}]
                                        <p class="stage-items">
                                            [{* View Stage *}]
                                            <a class="stage-list">
                                                <button type="submit" name="view_stage_id" id="view_stage_id" value="[{$value}]">[{$value|ucfirst}]</button>
                                            </a>
                                            [{* Delete Stage *}]
                                            <a class="btn btn-primary add-button popover-button btn-right" data-placement="left"
                                                data-popover-content="#stage_delete_popover[{$key}]" data-toggle="popover"
                                                data-trigger="focus" href="#" tabindex="0">
                                                    <label class="stage-delete-label" title="[{oxmultilang ident='DELETE_STAGE'}]">[{oxmultilang ident='DELETE_STAGE'}]</label>
                                                    <span class="glyphicon glyphicon-remove-circle pull-right" title="[{oxmultilang ident='DELETE_STAGE'}]"></span>
                                            </a>
                                            [{* Homepage - Radio Buttons *}]
                                            <a class="checkbox" title="[{oxmultilang ident='HOMEPAGE'}]">
                                                <input type="radio" id="homepage[{$key}]" class="checkbox homepage" name="homepage" data-current_stage_id="[{$stage_id}]" data-module_url="[{$module_url}]" value="[{$value}]" [{if ( $homepage_values[$key] == 1 ) }]checked[{/if}]/>
                                                <label class="stage-homepage-label" for="homepage[{$key}]">[{oxmultilang ident='HOMEPAGE'}]</label>
                                            </a>
                                            [{if ( $stage_id == $value )}]
                                                <input type="hidden" name="homepage_value" class="homepage_value" value="[{$homepage_values[$key]}]"/>
                                            [{/if}]
                                            [{* Stage Status - Active/Inactive *}]
                                            <a class="checkbox stage-status-main" title="[{oxmultilang ident='STATUS'}]">
                                                <input type="checkbox" id="stage-status[{$key}]" class="checkbox stage-status" name="stage_status" data-current_stage_id="[{$stage_id}]" data-module_url="[{$module_url}]" value="[{$value}]" [{if ( $status_values[$key] == 1 ) }]checked[{/if}]/>
                                                <label class="stage-status-label" for="stage-status[{$key}]">[{oxmultilang ident='STATUS'}]</label>
                                            </a>
                                            [{if ( $stage_id == $value )}]
                                                <input type="hidden" name="status_value" class="status_value" value="[{$status_values[$key]}]"/>
                                            [{/if}]
                                        </p>
                                        [{* Popup *}]
                                        <div class="hidden popover" id="stage_delete_popover[{$key}]">
                                            <div class="popover-heading">Delete this stage?</div>
                                            <div class="popover-body">
                                                <button class="popover-buttons red-button" name="delete_stage" value="[{$oxid_ids[$key]}]::[{$value}]">[{oxmultilang ident="YES"}]</button>
                                                <span class="popover-buttons green-button">[{oxmultilang ident="NO"}]</span>
                                            </div>
                                        </div>
                                    [{/foreach}]
                                [{else}]
                                    <div><p class="alignCenter">[{oxmultilang ident="NO_STAGE"}]</p></div>
                                [{/if}]
                            [{else}]
                                <div><p class="alignCenter">[{oxmultilang ident="NO_STAGE"}]</p></div>
                            [{/if}]
                        </div>
                    </div>
                </div>
            </div>
        [{/if}]

        [{* Add Stage / Save Data *}]
        <div class="row marginTop15">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alignRight">
                [{* Add Stage *}]
                [{if empty($stage_id)}]
                    <button type="submit" class="btn btn-success" name="add_stage" id="Add" value="[{oxmultilang ident="CREATE_NEW_STAGE"}]"><i class="glyphicon glyphicon-ok-circle"></i> [{oxmultilang ident="CREATE_NEW_STAGE"}]</button>
                [{* Save Data *}]
                [{else}]
                    <button type="submit" class="btn btn-success" name="save_data" id="save" value="[{oxmultilang ident="SAVE"}]"><i class="glyphicon glyphicon-ok-circle"></i> [{oxmultilang ident="SAVE"}]</button>
                [{/if}]
            </div>
        </div>

        [{* Stage ID *}]
        <div class="stage-id-head marginTop15">
            <div class="input-group">
                <span class="input-group-addon stage-id-label" id="stage-id-label">[{oxmultilang ident="STAGE_ID"}]</span>
                <input type="hidden" name="saved_stage_id" value="[{$stage_id}]">
                <input type="text" class="form-control whiteBG add_update_stage_input" name="[{if empty($stage_id)}]new_stage_id[{else}]update_stage_id[{/if}]" value="[{$stage_id}]" placeholder="Stage ID">
                <fieldset class="form-group multisite-fieldset">
                    <div class="input-group">
                        <button class="btn btn-default dropdown-toggle" type="button" id="multisite-menu" data-toggle="dropdown">
                            [{if empty( $saved_multisite ) }]
                                [{$all_multisites|@reset}]
                                [{assign var=shop_key value=$all_multisites|@key}]
                            [{else}]
                                [{$all_multisites[$shop_key]}]
                                [{assign var=shop_key value=$saved_multisite}]
                            [{/if}]
                            &ensp;<span class="caret"></span>
                        </button>
                        <ul id="multisite-menu-ul" class="multisite-dropdown-menu dropdown-menu" role="menu" aria-labelledby="multisite-menu">
                            [{if !empty($all_multisites)}]
                                [{if !empty(array_filter($all_multisites))}]
                                    [{foreach from=$all_multisites key=multisite_id item=multisite_name}]
                                        <li role="presentation"><a href="#" class="selected-multisite" data-multisite="[{$multisite_id}]"><span>[{$multisite_name}]</span></a></li>
                                    [{/foreach}]
                                [{/if}]
                            [{/if}]
                            <input type="hidden" id="current-multisite" class="current-multisite" name="multisite" value="[{$shop_key}]">
                        </ul>
                    </div>
                </fieldset>
            </div>
        </div>

        [{* Stage Data *}]
        [{if !empty($stage_id)}]
            [{* Settings *}]
            <div class="config-head">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label>[{oxmultilang ident="GENERAL_SETTINGS"}]</label>
                        [{* Autoplay *}]
                        <div class="checkbox marginTop0">
                            <input type="checkbox" id="autoplay" class="checkbox" name="autoplay" value="1" [{if $autoplay == 1 }]checked[{/if}]/>
                            <label for="autoplay">[{oxmultilang ident="AUTO_PLAY"}]</label>
                        </div>
                        [{* Autoloop *}]
                        <div class="checkbox">
                            <input type="checkbox" id="autoloop" class="checkbox" name="autoloop" value="1" [{if $autoloop == 1 }]checked[{/if}]/>
                            <label for="autoloop">[{oxmultilang ident="AUTO_LOOP"}]</label>
                        </div>
                        [{* Speed *}]
                        <fieldset class="form-group margin0">
                            <div class="input-group speed-input">
                                <span id="speed-label" class="input-group-addon"><b>[{oxmultilang ident="SPEED"}]</b></span>
                                <input type="number" class="form-control speed" name="speed" value="[{$speed}]">
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        [{* Language *}]
                        <fieldset class="form-group">
                            <label for="subline">[{oxmultilang ident="LANGUAGE"}]</label>
                            <div class="input-group">
                                <button class="btn btn-default dropdown-toggle" type="button" id="language-menu" data-toggle="dropdown">
                                    [{$all_languages[$current_language]}]&ensp;
                                    <span class="caret"></span>
                                </button>
                                <ul class="language-dropdown-menu dropdown-menu" role="menu" aria-labelledby="language-menu">
                                    [{if !empty($all_languages)}]
                                        [{if !empty(array_filter($all_languages))}]
                                            [{foreach from=$all_languages key=lang_short_name item=lang_full_name}]
                                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><button type="submit" name="selected_language" class="selected-language" value="[{$lang_short_name}]"><code>[{$lang_short_name}]</code> - <span>[{$lang_full_name}]</span></button></a></li>
                                            [{/foreach}]
                                        [{/if}]
                                    [{/if}]
                                    <li role="presentation" class="divider"></li>
                                    <li role="presentation" class="disabled"><a role="menuitem" tabindex="-1" href="#">Default: <code>[{$default_language}] - [{$all_languages[$default_language]}]</code></a></li>
                                </ul>
                                <input type="hidden" id="current-language" class="current-language" name="current_language" value="[{$current_language}]">
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="config-head config-publication">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        [{* Publication *}]
                        <span class="dialog-url" data-dialog_url='[{ $oViewConf->getSelfLink()|replace:"&amp;":"&" }]'></span>
                        <input type="button" class="edittext btn btn-default publication" data-oxid_id="[{$oxid}]" data-module_url="[{$module_url}]" value="[{ oxmultilang ident="PLACE_OF_PUBLICATION" }]" data-params="cl=article_extend&aoc=1&oxid=[{$oxid}]">
                        <code class="[{if empty($stage_category_names)}]visibility-hidden[{/if}]">
                            <span class="stage_categories_list">[{', '|implode:$stage_category_names}]</span>
                        </code>
                    </div>
                </div>
            </div>

            [{* No Image *}]
            [{assign var="image_link" value=$oViewConf->getModuleUrl($module_name, "assets/admin/custom/img/no-image-admin.png")}]
            [{assign var="image_link" value="$image_link?$current_time"}]
            <span class="no-image" data-no_image="[{$image_link}]"></span>
            [{* Saved Image *}]
            <span class="saved-image" data-saved_image="[{$image_url}]"></span>

            [{* Accordion(s) *}]
            <div class="panel-group marginTop15" id="accordion-main">
                [{* Saved Accordion(s) *}]
                <div id="sliderLayersSaved" class="saved-accordion-main">
                    [{if !empty($data_array)}]
                        [{if !empty(array_filter($data_array))}]
                            [{foreach from=$data_array key=key item=data}]
                                [{assign var="counter" value=$key+1}]

                                [{* Check if image exists *}]
                                [{if !empty($data[3])}]
                                    [{assign var="image_link" value="$image_url/$data[3]?$current_time"}]
                                [{else}]
                                    [{assign var="image_link" value=$oViewConf->getModuleUrl($module_name, "assets/admin/custom/img/no-image-admin.png")}]
                                    [{assign var="image_link" value="$image_link?$current_time"}]
                                [{/if}]

                                <div class="panel panel-default saved-accordion draggable-layer">
                                    [{* Layer Head *}]
                                    <div class="panel-heading">
                                        [{* Delete Layer - Popover *}]
                                        <a class="btn btn-primary add-button popover-button btn-right delete-layer-click" data-placement="left"
                                            data-popover-content="#popover[{$counter}]" data-toggle="popover"
                                            data-trigger="focus" href="#" tabindex="0">
                                            <label class="layer-delete-label" title="[{oxmultilang ident='DELETE_LAYER'}]">[{oxmultilang ident='DELETE_LAYER'}]</label>
                                            <span class="glyphicon glyphicon-remove-circle pull-right" title="[{oxmultilang ident='DELETE_LAYER'}]"></span>
                                        </a>
                                        <div class="hidden popover" id="popover[{$counter}]">
                                            <div class="popover-heading">Delete this layer?</div>
                                            <div class="popover-body">
                                                <button class="popover-buttons red-button delete-layer-num" name="delete_layer" value="">[{oxmultilang ident="YES"}]</button>
                                                <span class="popover-buttons green-button">[{oxmultilang ident="NO"}]</span>
                                            </div>
                                        </div>
                                        [{* Element Status - Active/Inactive *}]
                                        <a class="checkbox element-status-main" title="[{oxmultilang ident='STATUS'}]">
                                            <input type="checkbox" id="element-status[{$key}]" class="checkbox element-status" data-element_id="[{$key}]" value="[{$data[7]}]" [{if ( $data[7] == 1 ) }]checked[{/if}]/>
                                            <label class="stage-status-label" for="element-status[{$key}]">[{oxmultilang ident='STATUS'}]</label>
                                            <input type="hidden" id="element-status-value[{$key}]" name="element_status[]" value="[{$data[7]}]"/>
                                        </a>
                                        [{* Layer Head Text *}]
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_[{$key}]">
                                                <i class="fa fa-sort fa-sort-saved" data-textarea_id="description_[{$key}]" title="[{oxmultilang ident="SORT"}]"></i>
                                                &ensp;
                                                <span class="head-text">[{$data[0]|strip_tags}]</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_[{$key}]" class="panel-collapse collapse [{if $key == 0 }]in[{/if}]">
                                        <div class="panel-body">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                [{* Fixed Value *}]
                                                <input type="hidden" class="form-control" name="layer_id[]" value="[{$data[8]}]">
                                                [{* Headline *}]
                                                <fieldset class="form-group">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                                        <label for="headline">[{oxmultilang ident="HEAD_LINE"}] <span class="red">*</span></label>
                                                        <div class="input-group">
                                                            <span id="headline-icon" class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                                            <input type="text" class="form-control headline" name="headline[]" value="[{$data[0]}]" placeholder="[{oxmultilang ident="HEAD_LINE"}]">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                [{* Subline *}]
                                                <fieldset class="form-group">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                                        <label for="subline">[{oxmultilang ident="SUB_LINE"}]</label>
                                                        <div class="input-group">
                                                            <span id="subline-icon" class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                                            <input type="text" class="form-control" name="subline[]" value="[{$data[1]}]" placeholder="[{oxmultilang ident="SUB_LINE"}]">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                [{* Image *}]
                                                <fieldset class="form-group">
                                                    <label for="subline">[{oxmultilang ident="IMAGE"}]</label>
                                                    <div class="image_upload">
                                                        <div><img class="image_src img-thumbnail" src="[{$image_link}]" width="80" alt="Photo"/></div>
                                                        <label for="image_[{$key}]"><i class="fa fa-upload fa-1x" title="Upload"></i></label>
                                                        [{* Delete Image Popover *}]
                                                        <a class="popover-link popover-image [{if empty($data[3]) }]remove-image[{/if}]" data-placement="right"
                                                           data-popover-content="#image_popover[{$counter}]" [{if !empty($data[3]) }]data-toggle="popover"[{/if}]
                                                           data-trigger="focus" href="#" tabindex="0" title="Delete this image?">
                                                            <i class="fa fa-times [{if !empty($data[3]) }]preview-remove-image[{/if}]" title="Delete"></i>
                                                        </a>
                                                        <div class="hidden popover" id="image_popover[{$counter}]">
                                                            <div class="popover-heading">Delete this image?</div>
                                                            <div class="popover-body">
                                                                <button class="popover-buttons red-button" name="delete_image" value="[{$counter}]">[{oxmultilang ident="YES"}]</button>
                                                                <span class="popover-buttons green-button">[{oxmultilang ident="NO"}]</span>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="saved_image_name" class="saved_image_name" name="saved_image_name[]" value="[{$data[3]}]"/>
                                                        <input type="file" id="image_[{$key}]" class="image" name="image[]"/>
                                                    </div>
                                                </fieldset>
                                                [{* Price *}]
                                                <fieldset class="form-group">
                                                    <label for="description">[{oxmultilang ident="PRICE"}]</label>
                                                    <div class="control-main">
                                                        [{if !empty($data[6])}]
                                                            [{if !empty(array_filter($data[6]))}]
                                                                [{foreach from=$data[6] key=new_key item=value}]
                                                                    <div class="entry input-group col-xs-3">
                                                                        <input type="text" class="form-control input-sm input-price" name="price[]" value="[{$value}]" placeholder="Price"/>
                                                                        <span class="input-group-btn">
                                                                            [{if $new_key == count($data[6])-1 }]
                                                                                <button class="btn btn-success new-field-add" type="button">
                                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                            [{else}]
                                                                                <button class="btn new-field-remove btn-danger" type="button">
                                                                                    <span class="glyphicon glyphicon-minus"></span>
                                                                            [{/if}]
                                                                                </button>
                                                                        </span>
                                                                    </div>
                                                                [{/foreach}]
                                                            [{else}]
                                                                <div class="entry input-group col-xs-3">
                                                                    <input class="form-control input-sm" name="price[]" type="text" placeholder="Price"/>
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-success new-field-add" type="button">
                                                                            <span class="glyphicon glyphicon-plus"></span>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            [{/if}]
                                                        [{/if}]
                                                    </div>
                                                    <input type="hidden" name="price[]" value="layer-break"/>
                                                </fieldset>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                [{* Description *}]
                                                <fieldset class="form-group">
                                                    <label for="description">[{oxmultilang ident="DESCRIPTION"}]</label>
                                                    <textarea id="description_[{$key}]" class="tinymce-textarea form-control" name="description[]" placeholder="Description">[{$data[2]}]</textarea>
                                                </fieldset>
                                                [{* CTA URL *}]
                                                <fieldset class="form-group">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                                        <label for="cta_url">[{oxmultilang ident="CTA_URL"}]</label>
                                                        <div class="input-group">
                                                            <span id="cta-link-icon" class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
                                                            <input type="text" class="form-control" name="cta_url[]" value="[{$data[4]}]" placeholder="[{oxmultilang ident="CTA_URL"}]">
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                [{* Extra Fields *}]
                                                <fieldset class="form-group">
                                                    <label for="description">[{oxmultilang ident="EXTRA_FIELDS"}]</label>
                                                    <div class="control-main">
                                                        [{if !empty($data[5])}]
                                                            [{if !empty(array_filter($data[5]))}]
                                                                [{foreach from=$data[5] key=new_key item=value}]
                                                                    <div class="entry input-group col-xs-3">
                                                                        <input type="text" class="form-control input-sm" name="extra_field[]" value="[{$value}]" placeholder="Extra Field"/>
                                                                        <span class="input-group-btn">
                                                                            [{if $new_key == count($data[5])-1 }]
                                                                                <button class="btn btn-success new-field-add" type="button">
                                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                            [{else}]
                                                                                <button class="btn new-field-remove btn-danger" type="button">
                                                                                    <span class="glyphicon glyphicon-minus"></span>
                                                                            [{/if}]
                                                                                </button>
                                                                        </span>
                                                                    </div>
                                                                [{/foreach}]
                                                            [{else}]
                                                                <div class="entry input-group col-xs-3">
                                                                    <input class="form-control input-sm" name="extra_field[]" type="text" placeholder="Extra Field"/>
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-success new-field-add" type="button">
                                                                            <span class="glyphicon glyphicon-plus"></span>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            [{/if}]
                                                        [{/if}]
                                                    </div>
                                                    <input type="hidden" name="extra_field[]" value="layer-break"/>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            [{/foreach}]
                        [{/if}]
                    [{/if}]
                </div>
                [{* New Accordion *}]
                <div id="sliderLayersNew" class="new-accordion-main">
                    <div class="panel panel-default new-accordion draggable-layer">
                        [{* Layer Head *}]
                        <div class="panel-heading">
                            [{* Delete Layer *}]
                            <div class="remove-layer">
                                <label class="layer-delete-label" title="[{oxmultilang ident='DELETE_LAYER'}]">[{oxmultilang ident='DELETE_LAYER'}]</label>
                                <span class="glyphicon glyphicon-remove-circle pull-right" title="[{oxmultilang ident='DELETE_LAYER'}]"></span>
                            </div>
                            [{* Element Status - Active/Inactive *}]
                            <a class="checkbox element-status-main" title="[{oxmultilang ident='STATUS'}]">
                                <input type="checkbox" id="new-element-status" class="checkbox new-element-status" data-element_id="" value="1" checked/>
                                <label class="new-stage-status-label" for="new-element-status">[{oxmultilang ident='STATUS'}]</label>
                                <input type="hidden" id="new-element-status-value" class="new-element-status-value" name="element_status[]" value="1"/>
                            </a>
                            [{* Layer Head Text *}]
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#new_collapse_0" data-counter="0">
                                    <i class="fa fa-pencil fa-fixed-new"></i>
                                    &ensp;
                                    <span class="head-text">[{oxmultilang ident="NEW"}]</span>
                                </a>
                            </h4>
                        </div>
                        <div id="new_collapse_0" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    [{* Headline *}]
                                    <fieldset class="form-group">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                            <label for="headline">[{oxmultilang ident="HEAD_LINE"}] <span class="red">*</span></label>
                                            <div class="input-group">
                                                <span id="headline-icon" class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                                <input type="text" class="form-control headline" name="headline[]" placeholder="[{oxmultilang ident="HEAD_LINE"}]">
                                            </div>
                                        </div>
                                    </fieldset>
                                    [{* Subline *}]
                                    <fieldset class="form-group">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                            <label for="subline">[{oxmultilang ident="SUB_LINE"}]</label>
                                            <div class="input-group">
                                                <span id="subline-icon" class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                                <input type="text" class="form-control" name="subline[]" placeholder="[{oxmultilang ident="SUB_LINE"}]">
                                            </div>
                                        </div>
                                    </fieldset>
                                    [{* Image *}]
                                    <fieldset class="form-group">
                                        <label for="subline">[{oxmultilang ident="IMAGE"}]</label>
                                        <div class="image_upload">
                                            <div><img class="image_src img-thumbnail" src="[{$image_link}]" width="80" alt="Photo"/></div>
                                            <label for="new_image_0"><i class="fa fa-upload fa-1x" title="Upload"></i></label>
                                            <label><i class="fa fa-times remove-image" title="Delete"></i></label>
                                            <input type="file" id="new_image_0" class="image" name="image[]"/>
                                        </div>
                                    </fieldset>
                                    [{* Price *}]
                                    <fieldset class="form-group">
                                        <label for="description">[{oxmultilang ident="PRICE"}]</label>
                                        <div class="control-main control-main-price">
                                            <div class="entry input-group col-xs-3">
                                                <input class="form-control input-sm input-price" name="price[]" type="text" placeholder="Price"/>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-success new-field-add" type="button">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="price[]" value="layer-break"/>
                                    </fieldset>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    [{* Description *}]
                                    <fieldset class="form-group">
                                        <label for="description">[{oxmultilang ident="DESCRIPTION"}]</label>
                                        <div class="new-textarea"><textarea id="new_description_0" class="tinymce-textarea form-control" name="description[]" placeholder="Description"></textarea></div>
                                    </fieldset>
                                    [{* CTA URL *}]
                                    <fieldset class="form-group">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                            <label for="cta_url">[{oxmultilang ident="CTA_URL"}]</label>
                                            <div class="input-group">
                                                <span id="cta-link-icon" class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
                                                <input type="text" class="form-control" name="cta_url[]" placeholder="[{oxmultilang ident="CTA_URL"}]">
                                            </div>
                                        </div>
                                    </fieldset>
                                    [{* Extra Fields *}]
                                    <fieldset class="form-group">
                                        <label for="description">[{oxmultilang ident="EXTRA_FIELDS"}]</label>
                                        <div class="control-main control-main-extra-field">
                                            <div class="entry input-group col-xs-3">
                                                <input class="form-control input-sm" name="extra_field[]" type="text" placeholder="Extra Field"/>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-success new-field-add" type="button">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="extra_field[]" value="layer-break"/>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                [{* Buttons *}]
                <div class="row marginTop15">
                    [{* Add Layer *}]
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <button class="btn btn-primary btn-add-panel"><i class="glyphicon glyphicon glyphicon-plus-sign"></i> [{oxmultilang ident="ADD_ELEMENT"}]</button>
                    </div>
                    [{* Add Stage / Save Data *}]
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 alignRight">
                        [{* Add Stage *}]
                        [{if empty($stage_id)}]
                            <button type="submit" class="btn btn-success" name="add_stage" id="Add" value="[{oxmultilang ident="CREATE_NEW_STAGE"}]"><i class="glyphicon glyphicon-ok-circle"></i> [{oxmultilang ident="CREATE_NEW_STAGE"}]</button>
                        [{* Save Data *}]
                        [{else}]
                            <button type="submit" class="btn btn-success" name="save_data" id="save" value="[{oxmultilang ident="SAVE"}]"><i class="glyphicon glyphicon-ok-circle"></i> [{oxmultilang ident="SAVE"}]</button>
                        [{/if}]
                    </div>
                </div>
            </div>
        [{/if}]
    </form>
</div>