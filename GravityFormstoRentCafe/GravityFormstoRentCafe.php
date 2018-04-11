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

?>