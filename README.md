# Multisite Image Slider
A Multisite Image Slider build to handle companies multiple websites from a central place. It is built using PHP OXID Framework.

![Alt text](pictures/backend/03.PNG?raw=true "Multisite Image Slider")


## Usage

### Home Page
```
[{* dwstage *}]
[{assign var="active_module" value=$oView->active_module}]
[{if !empty( $active_module ) }]
    [{block name="dwstage_start"}]
        [{oxid_include_widget cl="dwstage_start"}]
    [{/block}]
[{/if}]
```

### Category Page
```
[{* dwstage *}]
[{assign var="active_module" value=$oView->active_module}]
[{if !empty( $active_module ) }]
    [{block name="dwstage_list"}]
        [{oxid_include_widget cl="dwstage_list" isAbsolute=$isNotDeepestCat}]
    [{/block}]
[{/if}]
```

## See Live
https://de.gedore-shop.com/
