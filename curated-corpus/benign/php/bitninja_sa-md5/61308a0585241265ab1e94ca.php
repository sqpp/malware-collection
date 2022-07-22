<?php
if( isset($_REQUEST['wds_import_submit']) && ! empty($_FILES['fileimport']) ) {
    require_once(WDS()->plugin_dir . '/framework/WDW_S_Library.php');
    global $wpdb;
    $flag = FALSE;
    $file = $_FILES['fileimport'];
    $dest_dir = ABSPATH . WDS()->upload_dir;
    if ( move_uploaded_file($file["tmp_name"], $dest_dir . $file["name"]) ) {
        $zip = zip_open($dest_dir . $file["name"]);
        if ( $zip ) {
            if ( !is_dir($dest_dir . '/import') ) {
                mkdir($dest_dir . '/import', 0777, TRUE);
            }
            if ( !is_dir($dest_dir . '/import/arrows') ) {
                mkdir($dest_dir . '/import/arrows', 0777, TRUE);
            }
            if ( !is_dir($dest_dir . '/import/arrows/thumb') ) {
                mkdir($dest_dir . '/import/arrows/thumb', 0777, TRUE);
            }
            if ( !is_dir($dest_dir . '/import/arrows/.original') ) {
                mkdir($dest_dir . '/import/arrows/.original', 0777, TRUE);
            }
            if ( !is_dir($dest_dir . '/import/.original') ) {
                mkdir($dest_dir . '/import/.original', 0777, TRUE);
            }
            if ( !is_dir($dest_dir . '/import/thumb') ) {
                mkdir($dest_dir . '/import/thumb', 0777, TRUE);
            }
            $upload_dir = wp_upload_dir();
            $dest_url = $upload_dir['baseurl'] . '/slider-wd/';
            while ( $zip_entry = zip_read($zip) ) {
                if ( strripos(zip_entry_name($zip_entry), ".xml") ) {
                    if ( zip_entry_open($zip, $zip_entry, "r") ) {
                        $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                        $buf = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~', '$1', $buf);
                        $xml = simplexml_load_string($buf);
                        $slider_fields = array();
                        $slide_fields = array();
                        $slides = array();
                        $layer_fields = array();
                        $layers = array();
                        $sliders = $xml->slider;
                        foreach ( $sliders as $slider ) {
                            foreach ( $slider as $key_slider => $value_slider ) {
                                $flag = TRUE;
                                if ( strpos($value_slider["value"], WDS()->site_url_buttons_placeholder) === 0 ) {
                                    $slider_fields[$key_slider] = trim(str_replace(WDS()->site_url_buttons_placeholder, $dest_url . '/import/arrows/', $value_slider["value"]));
                                    $slider_fields[$key_slider] = trim(str_replace(site_url(), '{site_url}', $slider_fields[$key_slider]));
                                }
                                elseif ( strpos($value_slider["value"], WDS()->site_url_placeholder) === 0 ) {
                                    $slider_fields[$key_slider] = trim(str_replace(WDS()->site_url_placeholder, site_url(), $value_slider["value"]));
                                    $slider_fields[$key_slider] = trim(str_replace(site_url(), '{site_url}', $slider_fields[$key_slider]));
                                }
                                elseif ( $key_slider != "slide" && $key_slider != "id" ) {
                                    $slider_fields[$key_slider] = trim($value_slider["value"]);
                                }
                                elseif ( $key_slider == "slide" ) {
                                    foreach ( $value_slider->children() as $key_slide => $slide ) {
                                        if ( $key_slide != "layer" && $key_slide != "id" ) {
                                            $slide_fields[$key_slide] = trim($slide["value"]);
                                        }
                                        elseif ( $key_slide == "layer" ) {
                                            foreach ( $slide->children() as $key_layer => $layer ) {
                                                if ( $key_layer != "id" ) {
                                                    $layer_fields[$key_layer] = isset($layer["value"]) ? trim($layer["value"]) : trim($layer);
                                                }
                                            }
                                            array_push($layers, $layer_fields);
                                        }
                                    }
                                    array_push($slides, array( "slide" => $slide_fields, "layers" => $layers ));
                                    $layers = array();
                                }
                            }
                            // Column doesn't exist in DB
                            unset($slider_fields['bg_fit']);
                            $wpdb->insert($wpdb->prefix . 'wdsslider', $slider_fields);
                            $slider_id = $wpdb->insert_id;
                            foreach ( $slides as $slide ) {
                                $slide["slide"]["slider_id"] = $slider_id;
                                if ( strpos($slide["slide"]["image_url"], WDS()->site_url_placeholder) === 0 ) {
                                    $slide["slide"]["image_url"] = trim(str_replace(WDS()->site_url_placeholder, $dest_url . '/import/', $slide["slide"]["image_url"]));
                                    $slide["slide"]["image_url"] = trim(str_replace(site_url(), '{site_url}', $slide["slide"]["image_url"]));
                                }
                                if ( strpos($slide["slide"]["thumb_url"], WDS()->site_url_placeholder) === 0 ) {
                                    $slide["slide"]["thumb_url"] = trim(str_replace(WDS()->site_url_placeholder, $dest_url . '/import/thumb/', $slide["slide"]["thumb_url"]));
                                    $slide["slide"]["thumb_url"] = trim(str_replace(site_url(), '{site_url}', $slide["slide"]["thumb_url"]));
                                }
                                $wpdb->insert($wpdb->prefix . 'wdsslide', $slide["slide"]);
                                $slide_id = $wpdb->insert_id;
                                foreach ( $slide["layers"] as $layer ) {
                                    $layer["slide_id"] = $slide_id;
                                    if ( strpos($layer["image_url"], WDS()->site_url_placeholder) === 0 ) {
                                        $layer["image_url"] = trim(str_replace(WDS()->site_url_placeholder, $dest_url . '/import/', $layer["image_url"]));
                                        $layer["image_url"] = trim(str_replace(site_url(), '{site_url}', $layer["image_url"]));
                                    }
                                    $wpdb->insert($wpdb->prefix . 'wdslayer', $layer);
                                }
                            }
                            $slides = array();
                        }
                        zip_entry_close($zip_entry);
                    }
                }
                else {
                    $zip_r = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                    $zip_name = zip_entry_name($zip_entry);
                    if ( strpos($zip_name, 'featured_') === 0 ) {
                        $zip_name = str_replace('featured_', '', $zip_name);
                        $zip_name = get_unique_file_name($zip_name, $dest_dir . '/import/thumb/', $zip_name);
                        if ( $handlethumb = fopen($dest_dir . '/import/thumb/' . $zip_name, "w") ) {
                            fwrite($handlethumb, $zip_r);
                            fclose($handlethumb);
                        }
                    }
                    if ( strpos($zip_name, 'thumb_') === 0 ) {
                        $zip_name = str_replace('thumb_', '', $zip_name);
                        $zip_name = get_unique_file_name($zip_name, $dest_dir . '/import/thumb/', $zip_name);
                        if ( $handlethumb = fopen($dest_dir . '/import/thumb/' . $zip_name, "w") ) {
                            fwrite($handlethumb, $zip_r);
                            fclose($handlethumb);
                        }
                    }
                    elseif ( strpos($zip_name, WDS()->site_url_buttons_placeholder . '_thumb_') === 0 ) {
                        $zip_name = str_replace(WDS()->site_url_buttons_placeholder . '_thumb_', '', $zip_name);
                        $zip_name = get_unique_file_name($zip_name, $dest_dir . '/import/arrows/thumb/', $zip_name);
                        if ( $handlethumb = fopen($dest_dir . '/import/arrows/thumb/' . $zip_name, "w") ) {
                            fwrite($handlethumb, $zip_r);
                            fclose($handlethumb);
                        }
                    }
                    elseif ( strpos($zip_name, WDS()->site_url_buttons_placeholder) === 0 ) {
                        $zip_name = str_replace(WDS()->site_url_buttons_placeholder, '', $zip_name);
                        $zip_name = get_unique_file_name($zip_name, $dest_dir . '/import/arrows/.original/', $zip_name);
                        if ( $handleorg = fopen($dest_dir . '/import/arrows/.original/' . $zip_name, "w") ) {
                            fwrite($handleorg, $zip_r);
                            fclose($handleorg);
                        }
                        $zip_name = get_unique_file_name($zip_name, $dest_dir . '/import/arrows/', $zip_name);
                        if ( $handleorg = fopen($dest_dir . '/import/arrows/' . $zip_name, "w") ) {
                            fwrite($handleorg, $zip_r);
                            fclose($handleorg);
                        }
                    }
                    else {
                        $zip_name = get_unique_file_name($zip_name, $dest_dir . '/import/.original/', $zip_name);
                        if ( $handleorg = fopen($dest_dir . '/import/.original/' . $zip_name, "w") ) {
                            fwrite($handleorg, $zip_r);
                            fclose($handleorg);
                        }
                        $zip_name = get_unique_file_name($zip_name, $dest_dir . '/import/', $zip_name);
                        if ( $handleorg = fopen($dest_dir . '/import/' . $zip_name, "w") ) {
                            fwrite($handleorg, $zip_r);
                            fclose($handleorg);
                        }
                    }
                }
            }
            zip_close($zip);
        }
        unlink($dest_dir . $file["name"]);
    }

    $message_id = 24;
    if ( $flag ) {
        $message_id = 23;
    }
    WDW_S_Library::spider_redirect( add_query_arg( array( 'page' => 'sliders_wds', 'message' => $message_id), admin_url('admin.php') ) );
}

function get_unique_file_name($filename, $foldername, $zip_name) {
    if (file_exists($foldername . $filename)) {
        $p = 1;
        $fileName1 = $zip_name;
        while (file_exists($foldername . $fileName1)) {
            $to = strrpos($fileName1, '.');
            $fileName1 = substr($fileName1, 0, $to) . '(' . $p . ')' . substr($fileName1, $to);
            $p++;
        }
        $zip_name = $fileName1;
    }
    return $zip_name;
}

function spider_demo_sliders() {
  $demo_sliders = array(
    'layer-slider' => __('LAYER SLIDER', WDS()->prefix),
    'slider-pro-2' => __('LAYER SLIDER 2', WDS()->prefix),
    'slide1' => __('MULTY LAYER SLIDER', WDS()->prefix),
    'news-site-or-blog' => __('NEWS SITE OR BLOG SLIDER', WDS()->prefix),
    'post-feed-demo' => __('POST FEED DEMO SLIDER', WDS()->prefix),
    'online-store' => __('ONLINE STORE SLIDER', WDS()->prefix),
    'portfolio' => __('PORTFOLIO SLIDER', WDS()->prefix),
    'slide2' => __('3D FULL-WIDTH SLIDER', WDS()->prefix),
    'slide3' => __('FILMSTRIP SLIDER', WDS()->prefix),
    'slide4' => __('ZOOM EFFECT SLIDER', WDS()->prefix),
    'wordpress-slider-wd-carusel' => __('CAROUSEL SLIDER', WDS()->prefix),
    'parallax' => __('PARALLAX SLIDER', WDS()->prefix),
    'hotspot' => __('HOTSPOT SLIDER', WDS()->prefix),
    'video-slider' => __('VIDEO SLIDER SLIDER', WDS()->prefix),
  );
  ?>
  <div id="main_featured_sliders_page">
    <div class="wd-table">
      <div class="wd-table-col wd-table-col-50 wd-table-col-left">
        <div class="wd-box-section">
          <div class="wd-box-title">
            <strong><?php _e('Import a slider', WDS()->prefix); ?></strong>
          </div>
          <div class="wd-box-content">
            <?php
            if ( WDS()->is_free ) {
              echo WDW_S_Library::message_id(0, __('This functionality is disabled in free version.', WDS()->prefix), 'error wd-notice-margin');
            }
            ?>
            <form method="post" enctype="multipart/form-data">
              <div class="wd-group">
                <input <?php echo WDS()->is_free ? 'disabled="disabled"' : ''; ?> type="file" name="fileimport" id="fileimport">
                <input <?php echo WDS()->is_free ? 'disabled="disabled"' : ''; ?> type="submit" name="wds_import_submit" class="button button-primary" onclick="<?php echo(WDS()->is_free ? 'alert(\'' . addslashes(__('This functionality is disabled in free version.', WDS()->prefix)) . '\'); return false;' : 'if(!wds_getfileextension(document.getElementById(\'fileimport\').value)){ return false; }'); ?>" value="<?php _e('Import', WDS()->prefix); ?>">
                <p class="description"><?php _e('Browse the .zip file of the slider.', WDS()->prefix); ?></p>
              </div>
            </form>
          </div>
        </div>
        <div class="wd-box-section">
          <div class="wd-box-title">
            <strong><?php _e('Download sliders', WDS()->prefix); ?></strong>
          </div>
          <div class="wd-box-content">
            <p><?php _e('You can download and import these demo sliders to your website using Import feature of Slider WD.', WDS()->prefix); ?></p>
            <ul id="featured-sliders-list">
              <?php
              foreach ( $demo_sliders as $key => $demo_slider ) {
                ?>
                <li class="<?php echo $key; ?>">
                  <div class="product"></div>
                  <a target="_blank" href="http://wpdemo.web-dorado.com/<?php echo $key; ?>" class="download"><span><?php _e('DOWNLOAD', WDS()->prefix); ?><?php echo $demo_slider; ?></span></a>
                </li>
                <?php
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
}