# Multisite Image Slider
A Multisite Image Slider on PHP OXID Framework

![Alt text](pictures/backend/03.PNG?raw=true "Multisite Image Slider")

## Usage
1. Home Page.

```
[{* dwstage *}]
[{assign var="active_module" value=$oView->active_module}]
[{if !empty( $active_module ) }]
    [{block name="dwstage_start"}]
        [{oxid_include_widget cl="dwstage_start"}]
    [{/block}]
[{/if}]
```

2. Category Page.

```
[{* dwstage *}]
[{assign var="active_module" value=$oView->active_module}]
[{if !empty( $active_module ) }]
    [{block name="dwstage_list"}]
        [{oxid_include_widget cl="dwstage_list" isAbsolute=$isNotDeepestCat}]
    [{/block}]
[{/if}]
```

## See Live (Frontend Slider)
https://de.gedore-shop.com/
