<?php
/*
Plugin Name: Admin Block Country
Plugin URI: http://wordpress.org/extend/plugins/admin-block-country/
Description: Blocks admin site by country.

Installation:

1) Install WordPress 3.9 or higher

2) Download the latest from:

http://wordpress.org/extend/plugins/admin-block-country

3) Login to WordPress admin, click on Plugins / Add New / Upload, then upload the zip file you just downloaded.

4) Activate the plugin.

Version: 6.0
Author: TheOnlineHero - Tom Skroza
License: GPL2
*/

if (!function_exists("geoip_open")) {
  include_once("geoip-api-php-master/geoip.inc");
  include_once("geoip-api-php-master/geoipcity.inc");
  include_once("geoip-api-php-master/geoipregionvars.php");
}
include_once("get-country-name.php");
if (!class_exists("ABCTomM8")) {
  include_once("lib/tom-m8te.php");
}

add_action( 'init', 'register_admin_block_country_block_by_country' );
function register_admin_block_country_block_by_country() {
  
  if (preg_match("/wp-login|wp-admin/", ABCTomM8::get_current_url())) {
    $my_country = admin_block_country_get_country_from_ip($_SERVER['REMOTE_ADDR']);
    if ($my_country != "") {
      foreach(admin_block_country_current_countries_blocked() as $country) {
        if ($country == $my_country) {
          exit;
        }
      }
    }
  } 
  
}

add_action( 'admin_init', 'register_admin_block_country_settings' );
function register_admin_block_country_settings() {
  register_setting( 'admin-block-country-settings-group', 'admin_block_country_list' );
  register_setting( 'admin-block-country-settings-group', 'admin_block_country_method' );
}

add_action('admin_menu', 'register_admin_block_country_page');
function register_admin_block_country_page() {
  add_menu_page('Block Country', 'Block Country', 'manage_options', 'admin-block-country/admin-block-country.php', 'admin_block_country_initial_page');
}

function admin_block_country_initial_page() {
  wp_register_script("admin_block_countries", plugins_url("/js/application.js", __FILE__));
  wp_enqueue_script("admin_block_countries");

  wp_register_style("admin_block_countries", plugins_url("/css/style.css", __FILE__));
  wp_enqueue_style("admin_block_countries");

  if ($_POST["action"] == "Upload") {
    $abc_verify_upload = wp_verify_nonce($_REQUEST["_wpnonce"], "abc-upload-abc");

    $allowedExts = array("dat");
    $temp = explode(".", $_FILES["block_country_upload_file"]["name"][0]);
    $extension = end($temp);
    if ($abc_verify_upload && $_FILES["block_country_upload_file"]["name"][0] == "GeoLiteCity.dat" && in_array($extension, $allowedExts)) {
      move_uploaded_file($_FILES["block_country_upload_file"]["tmp_name"][0],
          ABSPATH."/wp-content/uploads/".$_FILES["block_country_upload_file"]["name"][0]);
      echo("<div class='updated below-h2'><p>Upload was a success.</p></div>");
    } else {
      echo("<div class='updated below-h2'><p>Invalid upload, can only upload GeoLityCity.dat file.</p></div>");
    }
  } else if ($_POST["action"] == "Update") {
    update_option("admin_block_country_method", $_POST["block_country_method"]);
    echo("<div class='updated below-h2'><p>Service Updated</p></div>");
  } else if ($_POST["action"] == "Block") {
    update_option("admin_block_country_list", ABCTomM8::get_query_string_value("block_countries"));
    echo("<div class='updated below-h2'><p>Admin Block Was Successful.</p></div>");
  }
  foreach(admin_block_country_current_countries_blocked() as $country) {
    $i=0;
    foreach(admin_block_country_checkbox_list() as $key => $value) {
      if (str_replace(" ", "", $key) == str_replace(" ", "", $country)) {
          $_POST["block_countries_".$i] = $country;
      }
      $i++;
    }
  }

  ?>
  <div class="postbox " style="display: block; ">
  <div class="inside">
    <p>Admin Block Country is now different. You can now upload a local ip database that Admin Block Country can use. Please download the database from:</p>
    <p><a target="_blank" href="http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz">http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz</a></p>
    <p>Unzip it on your local computer and then upload the unzipped GeoLiteCity.dat file.</p>
    <form action="" method="post" id="block_countries_upload" enctype="multipart/form-data">
      <?php
      $abc_upload_abc_nonce = wp_create_nonce( "abc-upload-abc" );
      ?>
      <input type='hidden' name='_wpnonce' value='<?php echo($abc_upload_abc_nonce); ?>'/>
      <?php
        ABCTomM8::add_form_field(null, "file", "Upload GeoLiteCity.dat", "block_country_upload_file", "block_country_upload_file", array(), "p", array("class" => "file"));
      ?>
      <p><input type="submit" name="action" value="Upload"/></p>
    </form>
    <br/>
    <br/>
    <p>Please choose a backup external IP to Country service. If one fails try the other.</p>
    <form action="" method="post" id="block_countries_setting">
    <?php
      if (get_option("admin_block_country_method") == "") {
        update_option("admin_block_country_method", "1");
      }
      $_GET["block_country_method"] = get_option("admin_block_country_method");
      ABCTomM8::add_form_field(null, "radio", "IP to Country Service", "block_country_method", "block_country_method", array(), "p", array("class" => "radio"), array("2" => "Use geoplugin.net IP to Country service - http://www.geoplugin.net", "3" => "Use marketingmix IP to Country service - http://ipcountry.marketingmix.com.au", "4" => "Use utrace IP to Country service - http://xml.utrace.de"));
    ?>
    <p><input type="submit" name="action" value="Update"/></p>
    
    </form>
  </div>
  </div>
  <div class="postbox " style="display: block; ">
  <div class="inside">
    <?php 
    global $geoipcountry;
    $my_country = admin_block_country_get_country_from_ip($_SERVER['REMOTE_ADDR']); ?>
    <p>Please choose which countries you wish to exclude from accessing the admin site.</p>
    <?php if ($my_country != "ZZ") { ?>
          <p>You cannot block your own country, for your own protection. <?php echo($geoipcountry[$my_country]); ?> has been taken off the list.</p>
    <?php } ?>
    <form action="" method="post" id="block_countries_form">
      <fieldset>
      <?php 
      ABCTomM8::add_form_field(null, "checkbox", "Block Countries", "block_countries", "block_countries", array(), "p", array("class" => "checkbox"), admin_block_country_checkbox_list()); ?>
      </fieldset>
      <fieldset class="submit">
        <p><input type="submit" name="action" value="Block"/></p>
      </fieldset>
    </form>
  </div>
  </div>
  
  <?php

  ABCTomM8::add_social_share_links("http://wordpress.org/extend/plugins/admin-block-country/");
}

function admin_block_country_current_countries_blocked() {
  return explode(" ", get_option("admin_block_country_list"));
}

function admin_block_country_checkbox_list() {
  global $geoipcountry;
  $first = array("select_all" => "Select All");
  $my_country = admin_block_country_get_country_from_ip($_SERVER['REMOTE_ADDR']);
  $countries = array_merge($first, $geoipcountry);
  unset($countries[$my_country]);
  return $countries;
}

?>