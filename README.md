# Image-Slider
An Image Slider on PHP OXID Framework

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

## See Live
https://de.gedore-shop.com/
