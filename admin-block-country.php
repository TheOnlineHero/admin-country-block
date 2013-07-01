<?php
/*
Plugin Name: Admin Block Country
Plugin URI: http://wordpress.org/extend/plugins/admin-block-country/
Description: Blocks admin site by country.

Installation:

1) Install WordPress 3.5.2 or higher

2) Download the latest from:

http://wordpress.org/extend/plugins/tom-m8te 

http://wordpress.org/extend/plugins/admin-block-country

3) Login to WordPress admin, click on Plugins / Add New / Upload, then upload the zip file you just downloaded.

4) Activate the plugin.

Version: 1.0
Author: TheOnlineHero - Tom Skroza
License: GPL2
*/
include_once("get-country-name.php");


function are_admin_block_country_dependencies_installed() {
  return is_plugin_active("tom-m8te/tom-m8te.php");
}

add_action( 'admin_notices', 'admin_block_country_notice_notice' );
function admin_block_country_notice_notice(){
  $activate_nonce = wp_create_nonce( "activate-admin-block-country-dependencies" );
  $tom_active = is_plugin_active("tom-m8te/tom-m8te.php");
  if (!($tom_active)) { ?>
    <div class='updated below-h2'><p>Before you can use Admin Block Country, please install/activate the following plugin:</p>
    <ul>
      <?php if (!$tom_active) { ?>
        <li>
          <a target="_blank" href="http://wordpress.org/extend/plugins/tom-m8te/">Tom M8te</a> 
           &#8211; 
          <?php if (file_exists(ABSPATH."/wp-content/plugins/tom-m8te/tom-m8te.php")) { ?>
            <a href="<?php echo(get_option("siteurl")); ?>/wp-admin/?admin_block_country_install_dependency=tom-m8te&_wpnonce=<?php echo($activate_nonce); ?>">Activate</a>
          <?php } else { ?>
            <a href="<?php echo(get_option("siteurl")); ?>/wp-admin/plugin-install.php?tab=plugin-information&plugin=tom-m8te&_wpnonce=<?php echo($activate_nonce); ?>&TB_iframe=true&width=640&height=876">Install</a> 
          <?php } ?>
        </li>
      <?php } ?>
    </ul>
    </div>
    <?php
  }

}

add_action( 'admin_init', 'register_admin_block_country_install_dependency_settings' );
function register_admin_block_country_install_dependency_settings() {
  if (isset($_GET["admin_block_country_install_dependency"])) {
    if (wp_verify_nonce($_REQUEST['_wpnonce'], "activate-admin-block-country-dependencies")) {
      switch ($_GET["admin_block_country_install_dependency"]) {
        case 'tom-m8te':  
          activate_plugin('tom-m8te/tom-m8te.php', 'plugins.php?error=false&plugin=tom-m8te.php');
          wp_redirect(get_option("siteurl")."/wp-admin/admin.php?page=admin-block-country/admin-block-country.php");
          exit();
          break;   
        default:
          throw new Exception("Sorry unable to install plugin.");
          break;
      }
    } else {
      die("Security Check Failed.");
    }
  }
}


add_action( 'admin_init', 'register_admin_block_country_block_by_country' );
function register_admin_block_country_block_by_country() {
  if (are_admin_block_country_dependencies_installed()) {
    $my_country = admin_block_country_get_country_from_ip($_SERVER['REMOTE_ADDR']);
    foreach(admin_block_country_current_countries_blocked() as $country) {
      if ($country == $my_country) {
        exit;
      }
    } 
  }
}

add_action( 'admin_init', 'register_admin_block_country_settings' );
function register_admin_block_country_settings() {
  register_setting( 'admin-block-country-settings-group', 'admin_block_country_list' );
}

add_action('admin_menu', 'register_admin_block_country_page');
function register_admin_block_country_page() {
  if (are_admin_block_country_dependencies_installed()) {
	  add_menu_page('Block Country', 'Block Country', 'manage_options', 'admin-block-country/admin-block-country.php', 'admin_block_country_initial_page');
  }
}

function admin_block_country_initial_page() {
  if (are_admin_block_country_dependencies_installed()) {
    wp_register_script("admin_block_countries", plugins_url("/js/application.js", __FILE__));
    wp_enqueue_script("admin_block_countries");
  
    wp_register_style("admin_block_countries", plugins_url("/css/style.css", __FILE__));
    wp_enqueue_style("admin_block_countries");
  
    if ($_POST["action"] == "Block") {
      update_option("admin_block_country_list", tom_get_query_string_value("block_countries"));
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
      <?php 
      global $geoipcountry;
      $my_country = admin_block_country_get_country_from_ip($_SERVER['REMOTE_ADDR']); ?>
      <p>Please choose which countries you wish to exclude from accessing the admin site.</p>
      <?php if ($my_country != "ZZ") { ?>
            <p>You cannot block your own country, for your own protect. <?php echo($geoipcountry[$my_country]); ?> has been taken off the list.</p>
      <?php } ?>
      <form action="" method="post" id="block_countries_form">
        <fieldset>
        <?php 
        tom_add_form_field(null, "checkbox", "Block Countries", "block_countries", "block_countries", array(), "p", array("class" => "checkbox"), admin_block_country_checkbox_list()); ?>
        </fieldset>
        <fieldset class="submit">
          <p><input type="submit" name="action" value="Block"/></p>
        </fieldset>
      </form>
    </div>
    </div>
  
    <?php
  }
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