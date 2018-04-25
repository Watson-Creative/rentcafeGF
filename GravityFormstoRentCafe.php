   <?php
   /*
   Plugin Name: Gravity Forms to Rent Cafe
   Plugin URI: https://github.com/Watson-Creative/rentcafeGF
   GitHub Plugin URI: https://github.com/Watson-Creative/rentcafeGF
   description: Add hook to RentCafe from Gravity Form submission for Rivage Portland
   Version: 1.0.1
   Author: Alex Tryon
   Author URI: http://www.alextryonpdx.com
   License: GPL2
   */



//testing Rivage form lead creation
add_action( 'gform_after_submission', 'after_submission_handler' );
function after_submission_handler( $form ) {
   if( class_exists ('GFAPI')) {
//Get Plugin Options
      $url = get_option( 'rentcafe_api_url' );
      $propCode = get_option( 'rentcafe_property_code' );
      $un = get_option( 'rentcafe_username' );
      $pw = get_option( 'rentcafe_password' );
      $lead = get_option( 'rentcafe_lead_source' );
      $addr = get_option( 'rentcafe_address' );
      $city = get_option( 'rentcafe_city' );
      $state = get_option( 'rentcafe_state' );
      $zip = get_option( 'rentcafe_zip' );

//Get Form Data
      $formData = $form;

      // echo $formData[1] . '<br>'; //firstName
      $leadString = $url . '&propertyCode=' . $propCode . '&username='. $un .'&password=' . $pw . '&addr1=' . urlencode($addr) . '&city=' . $city . '&state=' . $state . '&zipCode=' . $zip . '&source=' . urlencode($lead) . '&firstName=' . $formData[1] . '&lastName=' . $formData[2] . '&email=' . $formData[3] . '&message=' . urlencode($formData[4]) . '&phone=' . preg_replace("/[^0-9,.]/", "", $formData[5]);
      // echo $leadString;
      // echo $formData[2] . '<br>'; //lastName
      // echo $formData[3] . '<br>'; //email
      // echo urlencode($formData[4]) . '<br>'; //message
      // echo preg_replace("/[^0-9,.]/", "", $formData[5]) . '<br>';
      // echo $leadString . '<br>';
      $info = wp_remote_get( $leadString );//makes GET request
      // print_r($info);
   }
}



// Add default vaules on initial load
register_activation_hook(__FILE__,'create_default_rentcafe_values');

function create_default_rentcafe_values() {
   // DEFAULT CREDENTIALS
   $rentcafe_api_url_default = 'https://api.rentcafe.com/rentcafeapi.aspx?requestType=lead';
   $rentcafe_property_code_default = 'p0647361';
   $rentcafe_username_default = 'apileadsuser@greystar.com';
   $rentcafe_password_default = 'rentcafe1';
   $rentcafe_lead_source_default = 'Website contact form'; //encode
   $rentcafe_address_default = '2220 NW Front Ave'; //encode
   $rentcafe_city_default = 'Portland';
   $rentcafe_state_default = 'OR';
   $rentcafe_zip_default = '97209';

   if ( get_option( 'rentcafe_api_url' ) == false ) { 
            add_option("rentcafe_api_url", $rentcafe_api_url_default); 
      }
   if ( get_option( 'rentcafe_property_code' ) == false ) { 
            add_option("rentcafe_property_code", $rentcafe_property_code_default); 
      }
   if ( get_option( 'rentcafe_username' ) == false ) { 
            add_option("rentcafe_username", $rentcafe_username_default); 
      }
   if ( get_option( 'rentcafe_password' ) == false ) { 
            add_option("rentcafe_password", $rentcafe_password_default); 
      }
   if ( get_option( 'rentcafe_lead_source' ) == false ) { 
            add_option("rentcafe_lead_source", $rentcafe_lead_source_default); 
      }
   if ( get_option( 'rentcafe_address' ) == false ) { 
            add_option("rentcafe_address", $rentcafe_address_default); 
      }
   if ( get_option( 'rentcafe_city' ) == false ) { 
            add_option("rentcafe_city", $rentcafe_city_default); 
      }
   if ( get_option( 'rentcafe_state' ) == false ) { 
            add_option("rentcafe_state", $rentcafe_state_default); 
      }
   if ( get_option( 'rentcafe_zip' ) == false ) { 
            add_option("rentcafe_zip", $rentcafe_zip_default); 
      }
}

////////////////////////////////////////   SETTINGS   ////////////////////////////////////////

if ( is_admin() ){ // admin actions
  add_action( 'admin_menu', 'rentcafe_create_menu' );
  add_action( 'admin_init', 'register_rentcafe_settings' );
}


function rentcafe_create_menu() {

   //create new top-level menu
   add_menu_page('RentCafe Integration', 'RentCafe Integration', 'administrator', __FILE__, 'rentcafe_settings_page', plugins_url('rc-icon.png', __FILE__ ) );

   //call register settings function
   add_action( 'admin_init', 'register_rentcafe_settings' );
}


function register_rentcafe_settings() { 
  register_setting( 'rentcafe_option-group', 'rentcafe_api_url' );
  register_setting( 'rentcafe_option-group', 'rentcafe_property_code' );
  register_setting( 'rentcafe_option-group', 'rentcafe_username' );
  register_setting( 'rentcafe_option-group', 'rentcafe_password' );
  register_setting( 'rentcafe_option-group', 'rentcafe_lead_source' );
  register_setting( 'rentcafe_option-group', 'rentcafe_address' );
  register_setting( 'rentcafe_option-group', 'rentcafe_city' );
  register_setting( 'rentcafe_option-group', 'rentcafe_state' );
  register_setting( 'rentcafe_option-group', 'rentcafe_zip' );
}
//allow customization of:
// api URL
// propertyCode
// username
// password
// source description
// property address (addr1, city, state, zip)


function rentcafe_settings_page() {
?>

<div class="wrap">
   <img id="watson-branding" src="<?php echo plugins_url('WC_Brand_Signature.png', __FILE__ ); ?>" style="max-width:400px;">
   <h1>Gravity Forms -> RentCafe Integration</h1>
   <p>make changes to these settings with extreme caution.</p>
   <form method="post" action="options.php"> 
      <?php 
      settings_fields( 'rentcafe_option-group' );
      do_settings_sections( 'rentcafe_option-group' ); ?>

      <table class="form-table rentcafe-options">

        <tr valign="top">
           <th scope="row">API URL</th>
           <td><input type="text" step="1" name="rentcafe_api_url" value="<?php echo esc_attr( get_option('rentcafe_api_url') ); ?>" /></td>
        </tr>

        <tr valign="top">
           <th scope="row">Username</th>
           <td><input type="text" step="1" name="rentcafe_username" value="<?php echo esc_attr( get_option('rentcafe_username') ); ?>" /></td>
        </tr>

        <tr valign="top">
           <th scope="row">Password</th>
           <td><input type="text" step="1" name="rentcafe_password" value="<?php echo esc_attr( get_option('rentcafe_password') ); ?>" /></td>
        </tr>

        <tr valign="top">
           <th scope="row">Lead Source Tag</th>
           <td><input type="text" step="1" name="rentcafe_lead_source" value="<?php echo esc_attr( get_option('rentcafe_lead_source') ); ?>" /></td>
        </tr>

        <tr valign="top">
           <th scope="row">Property Code</th>
           <td><input type="text" step="1" name="rentcafe_property_code" value="<?php echo esc_attr( get_option('rentcafe_property_code') ); ?>" /></td>
        </tr>

        <tr valign="top">
           <th scope="row">Property City</th>
           <td><input type="text" step="1" name="rentcafe_city" value="<?php echo esc_attr( get_option('rentcafe_city') ); ?>" /></td>
        </tr>

        <tr valign="top">
           <th scope="row">Property State (XX)</th>
           <td><input type="text" step="1" name="rentcafe_state" value="<?php echo esc_attr( get_option('rentcafe_state') ); ?>" /></td>
        </tr>

        <tr valign="top">
           <th scope="row">Property Zipcode</th>
           <td><input type="text" step="1" name="rentcafe_zip" value="<?php echo esc_attr( get_option('rentcafe_zip') ); ?>" /></td>
        </tr>

    </table>

    <?php
      submit_button('Save Changes');
      ?>
   </form>

   <?php 
}


   ?>