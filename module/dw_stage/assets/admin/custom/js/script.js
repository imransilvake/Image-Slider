/**
 * Created by imran on 31.12.15.
 */

$( document ).ready( function() {
    // Show new accordion if there are no accordion(s) present already.
    if( $( '.saved-accordion-main .panel' ).length < 1 ) {
        var new_accordion = $( '.new-accordion' ); // Select accordion.
        // Clone accordion.
        var newPanel = new_accordion.last().clone();
        // Clone accordion.
        var newPanel = new_accordion.last().clone();
        // Append accordion.
        $( '.saved-accordion-main' ).append( newPanel.fadeIn() );

        // Active new accordion.
        $( '#new_collapse_0' ).addClass( 'in' );
    }

    // Show/Delete new accordion.
    var new_accordion = $( '.new-accordion' ); // Select accordion.
    var counter = new_accordion.last().find( '.accordion-toggle' ).data( 'counter' ); // Get last counter.
    $( document ).on( 'click', '.btn-add-panel', function(ev) {
        // Prevent page refresh.
        ev.preventDefault();

        // Close all accordions.
        $( '#accordion-main' ).find( '.collapse' ).removeClass( 'in' );

        // Clone accordion.
        var newPanel = new_accordion.last().clone();
        // Set href of current accordion.
        newPanel.find( '.accordion-toggle' ).attr( 'href', '#new_collapse_' + ( ++counter ) );
        // Active the current accordion.
        newPanel.find( '.panel-collapse' ).attr( 'id', 'new_collapse_' + counter ).addClass( 'collapse' ).removeClass( 'in' );
        // Set latest counter value.
        newPanel.find( '.accordion-toggle' ).attr( 'counter', counter );
        newPanel.find( '.accordion-toggle' ).data( 'counter', counter );
        // Empty head text.
        newPanel.find( '.head-text' ).text( 'New' );
        // Empty input fields.
        newPanel.find( '.form-control' ).val( '' );
        // Set textarea id.
        newPanel.find( '.new-textarea').text( '' );
        newPanel.find( '.new-textarea' ).append( '<textarea id="new_description_0" class="tinymce-textarea form-control" name="description[]" placeholder="Description"></textarea>' );
        newPanel.find( 'textarea' ).attr( 'id',  'new_textarea_' + counter );
        // Set image id.
        newPanel.find( '.image_upload > label:first' ).attr( 'for', 'new_image_' + counter );
        newPanel.find( '.image_upload > label:first' ).attr( 'for', 'new_image_' + counter );
        newPanel.find( '.image_upload > input' ).attr( 'id', 'new_image_' + counter );
        // Set image src empty for new layer.
        var no_image = $( '.no-image' ).data( 'no_image' );
        newPanel.find( '.image_upload > div > img' ).attr( 'src', no_image );
        // Price.
        newPanel.find( '.control-main-price' ).html(
          '<div class="entry input-group col-xs-3">' +
          '<input class="form-control input-sm input-price" name="price[]" placeholder="Price" type="text">' +
                '<span class="input-group-btn">' +
                    '<button class="btn new-field-add btn-success" type="button">' +
                        '<span class="glyphicon glyphicon-plus"></span>' +
                    '</button>' +
                '</span>' +
            '</div>'
        );
        // Extra Field.
        newPanel.find( '.control-main-extra-field' ).html(
            '<div class="entry input-group col-xs-3">' +
                '<input class="form-control input-sm" name="extra_field[]" placeholder="Extra Field" type="text">' +
                '<span class="input-group-btn">' +
                    '<button class="btn btn-success new-field-add" type="button">' +
                        '<span class="glyphicon glyphicon-plus"></span>' +
                    '</button>' +
                '</span>' +
            '</div>'
        );

        // Set ids for element status.
        newPanel.find( '#new-element-status' ).data( 'element_id', counter );
        newPanel.find( '#new-element-status' ).attr(
            'id', 'new-element-status' + counter,
            'data-element_id', counter,
            'value', '0'
        );
        newPanel.find( '.new-stage-status-label' ).attr( 'for', 'new-element-status' + counter );
        newPanel.find( '.new-element-status-value' ).attr( 'id', 'new-element-status-value' + counter );

        // Append accordion.
        $( '.saved-accordion-main' ).append( newPanel.fadeIn() );

        // Open active accordion.
        newPanel.find( '.collapse' ).addClass( 'in' );

        // Focus on headline.
        newPanel.find( '.headline' ).focus();

        // Add tinymce to new textarea.
        tinymce.execCommand( 'mceAddEditor', false, 'new_textarea_' + counter );
    });

    // Collapsed 'in' clicked accordion and collapsed 'out' others.
    $( document ).on( 'click', '.accordion-toggle', function() {
        $( '#accordion-main .panel-collapse' ).removeClass( 'in' );
        $( this ).parents( '.panel' ).find( 'panel-collapse' ).addClass( 'in' );
    });

    // Scroll slowly.
    $( document ).on( 'click', 'a.accordion-toggle', function() {
        var divPosition = $( this).parents( '.panel-heading' ).offset();
        $( 'html, body' ).animate( { scrollTop:divPosition.top }, 'slow' );
    });

    // Editor - tinymce.
    if( $( 'textarea' ).length ) { tinymce.init({ selector: '.tinymce-textarea' }); }

    // Dynamically change headline.
    $( document ).on( 'keyup', '.headline', function() {
        var text = $( '<p>' ).html( $( this ).val() ).text();
        text = text.replace( /(<([^>]+)>)/ig, "" );
        $( this ).parents( '.panel' ).find( '.head-text' ).html( text );
    });

    // Remove new added layer.
    $( document ).on( 'click', '.remove-layer', function() {
        // Remove current delete layer textarea first.
        var textarea_id = $( this ).parents( '.panel' ).find( 'textarea' ).attr( 'id' );
        tinymce.execCommand( 'mceRemoveEditor', false, textarea_id );

        // Remove accordion.
        $( this ).parents( '.panel' ).get(0).remove();
    });

    // Alert success message hide.
    setTimeout( function() { $( '.alert-success' ).fadeOut( 500 ); }, 5000);

    // Preview image.
    $( document ).on( 'change', '.image', function() {
        // Variables.
        var current = $( this );
        var validFileExtensions = [ 'jpg', 'jpeg', 'png' ];
        var currentExtention = $( this ).val().split( '.' ).pop().toLowerCase();

        // Check if extention is valid or not.
        if( $.inArray( currentExtention, validFileExtensions ) != -1 ) {
            // Check if file is selected.
            if( this.files && this.files[0] ) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    current.parents( '.image_upload' ).find( '.image_src' ).attr( 'src', e.target.result );
                }
                reader.readAsDataURL( this.files[0] );
            }

            // Change classes: add remove image class.
            $( this ).parents( '.image_upload' ).find( '.preview-remove-image' ).addClass( 'remove-image' );
            $( this ).parents( '.image_upload' ).find( '.remove-image' ).removeClass( 'preview-remove-image' );
        }
        else {
            // Alert message.
            BootstrapDialog.show({
                title: "Alert",
                message: "Invalid file type. Allowed types are: <code>jpg, jpeg, png</code>",
                cssClass: 'alert-dialog',
                closable: false,
                buttons: [{
                    label: 'Ok',
                    cssClass: 'btn-danger',
                    action: function( dialogRef ){
                        dialogRef.close();
                    }
                }]
            });
        }
    });

    // Delete preview image.
    $( document ).on( 'click', '.remove-image', function() {
        // Variables.
        var no_image      =  $( '.no-image' ).data( 'no_image' );
        var img_name      =  $( this ).parents( '.image_upload' ).find( '#saved_image_name' ).val();
        var image_src     =  $( '.saved-image' ).data( 'saved_image' );
        var current_time  =  new Date( $.now() );
        current_time      =  current_time.getMilliseconds();
        var image_url     = image_src + '/' + img_name + '?' + current_time;

        if( img_name != '' && img_name != undefined ) {
            $( this ).parents( '.panel' ).find( '.image_upload > div > img' ).attr( 'src', image_url );
        }
        else {
            // Set image src empty for new layer.
            $( this ).parents( '.panel' ).find( '.image_upload > div > img' ).attr( 'src', no_image );
        }

        // Change classes: add remove image class.
        $( this ).parents( '.image_upload' ).find( '.remove-image' ).addClass( 'preview-remove-image' );
        $( this ).parents( '.image_upload' ).find( '.preview-remove-image' ).removeClass( 'remove-image' );
        $( this ).parents( '.image_upload' ).find( '.image' ).val( '' );
    });

    // Popover.
    $( '.popover-button, .popover-image, .remove-image' ).on( 'click', function(e) { e.preventDefault(); return true; });
    $( '[data-toggle=popover]' ).popover({
        html : true,
        content: function() {
            var content = $( this ).attr( 'data-popover-content' );
            return $( content ).children( '.popover-body' ).html();
        },
        title: function() {
            var title = $( this ).attr( 'data-popover-content' );
            return $( title ).children( '.popover-heading' ).html();
        }
    });

    // Add or delete new field of 'Extra Field' & 'Price, Label'.
    $( document ).on( 'click', '.new-field-add', function(e) {
        e.preventDefault();
        var controlForm = $( this ).parents( '.control-main' ),
            currentEntry = $( this ).parents('.entry:first'),
            newEntry = $( currentEntry.clone() ).appendTo( controlForm );

        newEntry.find( 'input' ).val( '' );
        controlForm.find( '.entry:not(:last) .new-field-add' )
            .removeClass( 'new-field-add' ).addClass( 'new-field-remove' )
            .removeClass( 'btn-success' ).addClass( 'btn-danger' )
            .html( '<span class="glyphicon glyphicon-minus"></span>' );
    }).on( 'click', '.new-field-remove', function(e) {
        $( this ).parents( '.entry:first' ).remove();
        e.preventDefault();
        return false;
    });

    // Search Stage.
    $( '#search-stage-input' ).bind( 'keyup', function() {
        var text = $( '#search-stage-input' ).val();
        var stage_items = $( '.stage-items' );
        stage_items.hide();
        stage_items.each( function() {
            if( $( this ).text().toUpperCase().indexOf( text.toUpperCase() ) != -1 ) {
                $( this ).show();
            }
        });
        // Detect if stage(s) are empty.
        if( stage_items.css('display') == 'none' ) {
            if( $( '.empty-stage' ).length == 0 ) {
                stage_items.parents( '.panel-body' ).append( '<p class="empty-stage alignCenter">No Stage(s) for this site.</p>' );
            }
            else {
                stage_items.parents( '.panel-body' ).find( '.empty-stage' ).show();
            }
        }
        else {
            stage_items.parents( '.panel-body' ).find( '.empty-stage' ).hide();
        }
    });

    // Check if Stage ID & Headline is empty or not.
    $( 'button[name="add_stage"], button[name="save_data"]' ).on( 'click', function(e) {
        var stage_id = $( document ).find( '.add_update_stage_input' ).val();
        if( stage_id == '' ) {
            // Hide headline message.
            $( document ).find( '.alert-dialog button' ).trigger( 'click' );
            // Prevent default.
            e.preventDefault();
            // Alert message.
            BootstrapDialog.show({
                title: "Alert",
                message: "<code>Stage ID</code> should not be empty.",
                cssClass: 'alert-dialog',
                closable: false,
                buttons: [{
                    label: 'Ok',
                    cssClass: 'btn-danger',
                    action: function( dialogRef ) {
                        dialogRef.close();
                    }
                }]
            });
        }
        else {
            var empty = $( document ).find( '.headline' ).filter( function() { return this.value == ''; });
            var bool = false;
            if( $( document ).find( '.saved-accordion-main .headline' ).length  ) {
                if( empty.length > 1 ) { bool = true; }
            }
            else {
                if( empty.length > 0 ) { bool = true; }
            }

            // If headline is empty.
            if( bool == true ) {
                // Prevent default.
                e.preventDefault();
                // Alert message.
                BootstrapDialog.show({
                    title: "Alert",
                    message: "<code>Headline</code> should not be empty in each layer.",
                    cssClass: 'alert-dialog',
                    closable: false,
                    buttons: [{
                        label: 'Ok',
                        cssClass: 'btn-danger',
                        action: function( dialogRef ) {
                            dialogRef.close();
                        }
                    }]
                });
            }
            else {
                // If all headlines are not unique.
                var values = $( 'input.headline' ).map( function() {
                    return $( this ).val().replace( /<(?:.|\n)*?>/gm, '' );
                }).get()
                var uniqueValues = $.grep( values, function( v, k ) {
                    return $.inArray( v, values ) === k;
                });
                if( uniqueValues.length !== values.length ) {
                    // Prevent default.
                    e.preventDefault();
                    // Alert message.
                    BootstrapDialog.show({
                        title: "Alert",
                        message: "<code>All</code> headlines should be unique.",
                        cssClass: 'alert-dialog',
                        closable: false,
                        buttons: [{
                            label: 'Ok',
                            cssClass: 'btn-danger',
                            action: function( dialogRef ) {
                                dialogRef.close();
                            }
                        }]
                    });
                }
            }
        }
    });

    // Multisite Dropwdown - Show selected item in title.
    $( '#multisite-menu-ul-stages-list' ).on( 'click', 'li a', function() {
        var btn_child = $( '#multisite-menu-stages-list' );
        btn_child.html( $( this ).find( 'span' ).text() + '&ensp; <span class="caret"></span>' );
        $( '.current-multisite' ).val( $( this ).data( 'multisite' ) );
    });
    $( '#multisite-menu-ul' ).on( 'click', 'li a', function() {
        var btn_child = $( '#multisite-menu' );
        btn_child.html( $( this ).find( 'span' ).text() + '&ensp; <span class="caret"></span>' );
        $( '.current-multisite' ).val( $( this ).data( 'multisite' ) );
    });
    // Language Dropwdown - Show selected item in title.
    $( '.language-dropdown-menu' ).on( 'click', 'li a', function() {
        var btn_child = $( '#language-menu' );
        btn_child.html( $( this ).find( 'span' ).text() + '&ensp; <span class="caret"></span>' );
        btn_child.val( $( this ).find( 'code' ).text() );
        $( '.current-language' ).val( $( this ).find( '.selected-language' ).val() );
    });

    // Database variables.
    var dbConfig = $( '.dbConfig' );
    var dbHost   =  dbConfig.data( 'dbhost' );
    var dbName   =  dbConfig.data( 'dbname' );
    var dbUser   =  dbConfig.data( 'dbuser' );
    var dbPwd    =  dbConfig.data( 'dbpwd' );

    // Publication - popup.
    $( document ).on( 'click', '.publication', function() {
        var dialog_url  = $( '.dialog-url' ).data( 'dialog_url' );
        var publication = $( '.publication' );
        var params      = publication.data( 'params' );
        var width       = 800;
        var height      = 680;
        var left        = ( screen.width/2 ) - width/2;
        var top         = ( screen.height/2 ) - height/2;
        var visible_on  = $( '.visibility-visible' );
        var visible_off = $( '.visibility-hidden' );

        // Window open.
        popup = window.open( dialog_url + params, 'ajaxpopup', 'width='+width+',height='+height+',scrollbars=yes,resizable=yes,left='+left+',top='+top );

        // Puts focus on the new window.
        if( window.focus ) { popup.focus(); }

        // Stage Categories.
        var module_url   =  publication.data( 'module_url' );
        var oxid_id      =  publication.data( 'oxid_id' );
        function update_publication( module_url, oxid_id ) {
            // ajax.
            $.ajax({
                url: module_url + 'assets/admin/custom/php/stage_categories.php',
                type: 'POST',
                data: {
                    dbHost  : dbHost,
                    dbName  : dbName,
                    dbUser  : dbUser,
                    dbPwd   : dbPwd,
                    oxid_id : oxid_id
                },
                dataType: 'html',
                success: function( data ) {
                    if ( data == '' ) {
                        visible_on.addClass( 'visibility-hidden' );
                        visible_on.removeClass( 'visibility-visible' );
                    }
                    else {
                        visible_off.addClass( 'visibility-visible' );
                        visible_off.removeClass( 'visibility-hidden' );
                    }
                    $( '.stage_categories_list' ).html( data );
                },
                error: function( error ) { }
            });
        }

        // On exit popup window.
        $( popup ).blur( function() {
            // Call 'update_publication' function.
            update_publication( module_url, oxid_id );
        });

        // On exit popup window.
        popup.onbeforeunload = function() {
            // Call 'update_publication' function.
            update_publication( module_url, oxid_id );
        }
    });

    // Homepage - Radio Buttons.
    $( document ).on( 'click', '.homepage', function() {
        if( $( this ).is( ':checked' ) ) {
            // Get values.
            var module_url       = $( this ).data( 'module_url' );
            var stage_id         = $( this ).val();
            var current_stage_id = $( this ).data( 'current_stage_id' );
            // ajax.
            $.ajax({
                url: module_url + 'assets/admin/custom/php/stage_homepage.php',
                type: 'POST',
                data: {
                    dbHost   : dbHost,
                    dbName   : dbName,
                    dbUser   : dbUser,
                    dbPwd    : dbPwd,
                    stage_id : stage_id
                },
                dataType: 'html',
                success: function( data ) {
                    if( stage_id == current_stage_id ) {
                        $( '.homepage_value' ).val( data );
                    }
                    else {
                        $( '.homepage_value' ).val( 0 );
                    }
                },
                error: function( error ) { }
            });
        }
    });

    // Stage Status - Active/Inactive.
    $( document ).on( 'click', '.stage-status', function() {
        // Get values.
        var module_url       = $( this ).data( 'module_url' );
        var stage_id         = $( this ).val();
        var current_stage_id = $( this ).data( 'current_stage_id' );
        // ajax.
        $.ajax({
            url: module_url + 'assets/admin/custom/php/stage_status.php',
            type: 'POST',
            data: {
                dbHost   : dbHost,
                dbName   : dbName,
                dbUser   : dbUser,
                dbPwd    : dbPwd,
                stage_id : stage_id
            },
            dataType: 'html',
            success: function( data ) {
                if( stage_id == current_stage_id ) {
                    $( '.status_value' ).val( data );
                }
                else {
                    $( '.status_value' ).val( 0 );
                }
            },
            error: function( error ) { }
        });
    });

    // Element Status - Active/Inactive (Saved Accordion).
    $( document ).on( 'click', '.element-status', function() {
        var element_id = $( this ).data( 'element_id' );
        var element_value = $( this ).val();

        element_value = ( element_value == 1 ) ? 0 : 1;
        $( '#element-status' + element_id ).val( element_value );

        var element_status_value = $( '#element-status-value' + element_id );
        element_status_value.attr( 'value', element_value );
        element_status_value.val( element_value );
    });

    // Element Status - Active/Inactive (New Accordion).
    $( document ).on( 'click', '.new-element-status', function() {
        var element_id = $( this ).data( 'element_id' );
        var element_value = $( this ).val();

        element_value = ( element_value == 1 ) ? 0 : 1;
        $( '#new-element-status' + element_id ).val( element_value );

        var new_element_status_value = $( '#new-element-status-value' + element_id );
        new_element_status_value.attr( 'value', element_value );
        new_element_status_value.val( element_value );
    });

    // Slider - Layers sortable.
    if( $( '.saved-accordion-main' ).length ) {
        $( '#sliderLayersSaved' ).sortable({
            cursor: 'move',
            handle: '.fa-sort-saved',
            start: function( e, ui ) {
                $( ui.item ).find( 'textarea' ).each( function() {
                    tinymce.execCommand( 'mceRemoveEditor', false, $( this ).attr( 'id' ) );
                });
            },
            stop: function( e, ui ) {
                $( ui.item ).find( 'textarea' ).each( function() {
                    tinymce.execCommand( 'mceAddEditor', true, $( this ).attr( 'id' ) );
                });
            }
        });
    }

    // Delete a selected layer.
    $( document ).on( 'click', '.delete-layer-click', function() {
        var counter = $( this ).parents( '.saved-accordion' ).index();
        counter = counter + 1;
        $( '.delete-layer-num' ).val( '' );
        $( this ).parents( '.saved-accordion' ).find( '.delete-layer-num' ).val( counter );
    });
});