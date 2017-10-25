[{* Stage *}]
[{if !empty($data_array) && !empty(array_filter($data_array))}]
    [{assign var="iNumSliderItems" value=$data_array|@count}]
    [{assign var="blWithSlider" value=true}]
    [{if $iNumSliderItems <= 1}]
        [{assign var="blWithSlider" value=false}]
    [{/if}]
    [{*if !empty(array_filter($data_array))*}]
        <div class="wgt-slider slider-stage auto-init"
            [{if $blWithSlider}]data-module="modules/slider" data-options='{"sliderType":"stage"}'[{/if}]
            >
            <div class="slider__wrapper">
                [{* Slider *}]
                <div class="slider [{if $blWithSlider}]hidden[{/if}]"
                    [{if $blWithSlider}]data-slick='{"autoplay": [{$autoplay}], "autoplaySpeed": [{$speed}], "infinite": [{$autoloop}]}'[{/if}]
                    >
                    [{* Items *}]
                    [{foreach from=$data_array key=key item=data}]
                        [{* Assign values to variables *}]
                        [{assign var="counter" value=$key+1}]
                        [{assign var="headline" value=$data[0]}]
                        [{assign var="subline" value=$data[1]}]
                        [{assign var="description" value=$data[2]}]
                        [{assign var="image_name" value=$data[3]}]
                        [{assign var="cta_url" value=$data[4]}]
                        [{assign var="extra_field" value=$data[5]}]
                        [{assign var="price" value=$data[6]}]
                        [{assign var="active_element" value=$data[7]}]

                        [{if ($active_element == 1) }]
                            [{* Check if image exists *}]
                            [{if !empty($image_name)}]
                                [{assign var="image_link" value="$image_url/$image_name?$current_time"}]
                            [{else}]
                                [{assign var="image_link" value=$oViewConf->getModuleUrl($module_name, "assets/admin/custom/img/no-image-frontend.png")}]
                                [{assign var="image_link" value="$image_link?$current_time"}]
                            [{/if}]

                            [{* Item *}]
                            <div class="slider-stage__item">
                                <div class="grid--stage">
                                    <div class="grid__col--text">
                                        [{* Headline & Subline *}]
                                        <p class="slider-stage__product-text">[{$headline}]<br>[{$subline}]</p>
                                        [{* Description *}]
                                        <div class="list-bullet">[{$description}]</div>
                                        [{* Price & CTA URL *}]
                                        <div class="slider-stage__item__price">
                                            [{* Price *}]
                                            <div class="left price">
                                                [{if !empty($price)}]
                                                    [{if !empty(array_filter($price))}]
                                                        [{foreach from=$price key=new_key item=value}]
                                                            <div>[{oxmultilang ident="UVP_ASTERISK"}]</div>
                                                            <div class="price__text">[{$value}]</div>
                                                        [{/foreach}]
                                                    [{/if}]
                                                [{/if}]
                                            </div>
                                            [{* CTA URL *}]
                                            <a href="[{$cta_url}]">
                                                <button class="btn btn--default" type="submit">
                                                    <span class="btn__icon icon-arrow-right-white" data-grunticon-embed></span>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="grid__col--img">
                                        <div class="img-block">
                                            [{* Placeholder with inline style padding-bottom from CMS. Calculation: image height * 100 / image width *}]
                                            <div class="img-block__placeholder" style="padding-bottom: 82.4%"></div>
                                            <picture>
                                                [{* IE 9 workaround for picture element. http://scottjehl.github.io/picturefill/#ie9 *}]
                                                <!--[if IE 9]><video style="display: none;"><![endif]-->
                                                <source media="(min-resolution: 192dpi), (-webkit-min-device-pixel-ratio: 2)" srcset="[{$image_link}] 1000w" sizes="100vw">
                                                <source srcset="[{$image_link}] 500w" sizes="100vw">
                                                <!--[if IE 9]></video><![endif]-->
                                                <img alt="" class="img-block__image" src=[{$image_link}]>
                                            </picture>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        [{/if}]
                    [{/foreach}]
                </div>
                [{* Controls *}]
                [{if $blWithSlider}]
                    <div class="slider-stage__controls">
                        [{* Previous Button *}]
                        <button class="btn slider__prev-button" type="button">
                            <span class="btn__icon icon-slide-left-blue" data-grunticon-embed></span>
                        </button>
                        [{* Slide Number *}]
                        <div class="slider__controls-paging slider-stage__controls-paging">
                            <span class="slider__controls-current-page">0</span> / <span class="slider__controls-total-pages">0</span>
                        </div>
                        [{* Next Number *}]
                        <button class="btn slider__next-button" type="button">
                            <span class="btn__icon icon-slide-right-blue" data-grunticon-embed></span>
                        </button>
                    </div>
                [{/if}]
            </div>
        </div>
    [{*/if*}]
[{/if}]