   <?php
   /*
   Plugin Name: Gravity Forms to Rent Cafe
   Plugin URI: https://github.com/Watson-Creative/rentcafeGF
   GitHub Plugin URI: https://github.com/Watson-Creative/rentcafeGF
   description: AAdd hook to RentCafe from Gravity Form submission for Rivage Portland
   Version: 1.0
   Author: Alex Tryon
   Author URI: http://www.alextryonpdx.com
   License: GPL2
   */



//testing Rivage form lead creation
add_action( 'gform_after_submission', 'after_submission_handler' );
function after_submission_handler( $form ) {
   if( class_exists ('GFAPI')) {
    $formData = $form;

    echo $formData[1] . '<br>'; //firstName
   $leadString = 'https://api.rentcafe.com/rentcafeapi.aspx?requestType=lead&propertyCode=p0647361&username=apileadsuser@greystar.com&password=rentcafe1&addr1=2220%20NW%20Front%20Ave&city=Portland&state=OR&zipCode=97209&source=Website%20contact%20form&' . 'firstName=' . $formData[1] . '&lastName=' . $formData[2] . '&email=' . $formData[3] . '&message=' . urlencode($formData[4]) . '&phone=' . preg_replace("/[^0-9,.]/", "", $formData[5]);
    echo $formData[2] . '<br>'; //lastName
    echo $formData[3] . '<br>'; //email
    echo urlencode($formData[4]) . '<br>'; //message
    echo preg_replace("/[^0-9,.]/", "", $formData[5]) . '<br>';
    echo $leadString . '<br>';
    $info = wp_remote_get( $leadString );//makes GET request
    print_r($info);
}
}



// Add default vaules on initial load
register_activation_hook(__FILE__,'create_default_rentcafe_values');

function create_default_rentcafe_values() {
   if ( get_option( 'freeze_delay' ) == false ) { 
            add_option("freeze_delay", $freeze_delay_default); 
         }
   }

////////////////////////////////////////   SETTINGS   ////////////////////////////////////////

if ( is_admin() ){ // admin actions
  add_action( 'admin_menu', 'rentcafe_create_menu' );
  add_action( 'admin_init', 'register_rentcafe_settings' );
}


function rentcafe_create_menu() {

   //create new top-level menu
   add_menu_page('WC Content Freeze Settings', 'Freeze Settings', 'administrator', __FILE__, 'rentcafe_settings_page', plugins_url('img/WC_Brand-20.png', __FILE__ ) );

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
?>