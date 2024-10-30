<?php
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function BusinessMonitor_settings_init() {
 // register a new setting for "BusinessMonitor" page
 register_setting( 'BusinessMonitor', 'BusinessMonitor_options' );

 // register a new section in the "BusinessMonitor" page
 add_settings_section(
 'BusinessMonitor_section_apiKey',
 __( 'API Configuration:', 'BusinessMonitor' ),
 'BusinessMonitor_section_apikey_cb',
 'BusinessMonitor'
 );

 // register a new field in the "BusinessMonitor_section_developers" section, inside the "BusinessMonitor" page
 add_settings_field(
 'BusinessMonitor_field_apikey',
 // use $args' label_for to populate the id inside the callback
 __( 'Api key', 'Api key' ), 'BusinessMonitor_field_textbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_apiKey', [ 'label_for' => 'BusinessMonitor_field_apikey', 'class' => 'BusinessMonitor_row', ]
 );

 // register a new section in the "BusinessMonitor" page
 add_settings_section(
 'BusinessMonitor_section_developers',
 __( 'Widget Configuration:', 'BusinessMonitor' ),
 'BusinessMonitor_section_developers_cb',
 'BusinessMonitor'
 );

 add_settings_field(
 'BusinessMonitor_field_itemGrade',
 // use $args' label_for to populate the id inside the callback
 __( 'Item Grade', 'Item Grade' ), 'BusinessMonitor_field_numberbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_developers', [ 'label_for' => 'BusinessMonitor_field_itemGrade', 'class' => 'BusinessMonitor_row', ]
 );

 add_settings_field(
 'BusinessMonitor_field_ratingFilterField',
 // use $args' label_for to populate the id inside the callback
 __( 'Filter Field', 'Filter Field' ), 'BusinessMonitor_field_selectbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_developers', [ 'label_for' => 'BusinessMonitor_field_ratingFilterField', 'class' => 'BusinessMonitor_row', ]
 );

  add_settings_field(
 'BusinessMonitor_field_ratingFilterValue',
 // use $args' label_for to populate the id inside the callback
 __( 'Filter Value', 'Filter Value' ), 'BusinessMonitor_field_textbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_developers', [ 'label_for' => 'BusinessMonitor_field_ratingFilterValue', 'class' => 'BusinessMonitor_row', ]
 );

  // register a new section in the "BusinessMonitor" page
 add_settings_section(
 'BusinessMonitor_section_reviewfeed',
 __( 'Review Feed Configuration:', 'BusinessMonitor' ),
 'BusinessMonitor_section_reviewfeed_cb',
 'BusinessMonitor'
 );

  add_settings_field(
 'BusinessMonitor_field_itemGradeFeed',
 // use $args' label_for to populate the id inside the callback
 __( 'Item Grade (feed)', 'Item Grade' ), 'BusinessMonitor_field_numberbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_itemGradeFeed', 'class' => 'BusinessMonitor_row', ]
 );

  add_settings_field(
 'BusinessMonitor_field_itemText',
 // use $args' label_for to populate the id inside the callback
 __( 'Item Text', 'Item Text' ), 'BusinessMonitor_field_numberbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_itemText', 'class' => 'BusinessMonitor_row', ]
 );

  add_settings_field(
 'BusinessMonitor_field_itemAgree',
 // use $args' label_for to populate the id inside the callback
 __( 'Item Agree', 'Item Agree' ), 'BusinessMonitor_field_numberbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_itemAgree', 'class' => 'BusinessMonitor_row', ]
 );

  add_settings_field(
 'BusinessMonitor_field_answerAgree',
 // use $args' label_for to populate the id inside the callback
 __( 'Answer Agree', 'Answer Agree' ), 'BusinessMonitor_field_numberbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_answerAgree', 'class' => 'BusinessMonitor_row', ]
 );

  add_settings_field(
 'BusinessMonitor_field_answerAnonymous',
 // use $args' label_for to populate the id inside the callback
 __( 'Answer Anonymous (or empty)', 'Answer Anonymous' ), 'BusinessMonitor_field_numberbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_answerAnonymous', 'class' => 'BusinessMonitor_row', ]
 );

  add_settings_field(
 'BusinessMonitor_field_fieldProduct',
 // use $args' label_for to populate the id inside the callback
 __( 'Field 1 (Product)', 'Product' ), 'BusinessMonitor_field_selectbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_fieldProduct', 'class' => 'BusinessMonitor_row', ]
 );

  add_settings_field(
 'BusinessMonitor_field_fieldWho',
 // use $args' label_for to populate the id inside the callback
 __( 'Field 2 (Who)', 'Who' ), 'BusinessMonitor_field_selectbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_fieldWho', 'class' => 'BusinessMonitor_row', ]
 );

  add_settings_field(
 'BusinessMonitor_field_field3',
 // use $args' label_for to populate the id inside the callback
 __( 'Field 3', 'Field3' ), 'BusinessMonitor_field_selectbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_field3', 'class' => 'BusinessMonitor_row', ]
 );

  add_settings_field(
 'BusinessMonitor_field_field4',
 // use $args' label_for to populate the id inside the callback
 __( 'Field 4', 'Field4' ), 'BusinessMonitor_field_selectbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_field4', 'class' => 'BusinessMonitor_row', ]
 );

  add_settings_field(
 'BusinessMonitor_field_field5',
 // use $args' label_for to populate the id inside the callback
 __( 'Field 5', 'Field5' ), 'BusinessMonitor_field_selectbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_field5', 'class' => 'BusinessMonitor_row', ]
 );

  add_settings_field(
 'BusinessMonitor_field_fieldfilter1',
 // use $args' label_for to populate the id inside the callback
 __( 'FilterField1 (only shortcode version)', 'FilterField1' ), 'BusinessMonitor_field_selectbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_fieldfilter1', 'class' => 'BusinessMonitor_row', ]
 );
  add_settings_field(
 'BusinessMonitor_field_fieldfilter2',
 // use $args' label_for to populate the id inside the callback
 __( 'FilterField2 (only shortcode version)', 'FilterField2' ), 'BusinessMonitor_field_selectbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_fieldfilter2', 'class' => 'BusinessMonitor_row', ]
 );
  add_settings_field(
  'BusinessMonitor_field_noReviewText',
  // use $args' label_for to populate the id inside the callback
 __( 'No review placeholder', 'No review placeholder' ), 'BusinessMonitor_field_textbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_noReviewText', 'class' => 'BusinessMonitor_row', ]
 );

  add_settings_field(
  'BusinessMonitor_field_anonymousText',
  // use $args' label_for to populate the id inside the callback
 __( 'Anonymous placeholder (defaults to Anonymous)', 'Anonymous placeholder' ), 'BusinessMonitor_field_textbox_cb', 'BusinessMonitor', 'BusinessMonitor_section_reviewfeed', [ 'label_for' => 'BusinessMonitor_field_anonymousText', 'class' => 'BusinessMonitor_row', ]
 );

}

 function BusinessMonitor_field_textbox_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'BusinessMonitor_options' );
 // output the field
 ?>
 <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
 name="BusinessMonitor_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
 value="<?php if(!empty($options[$args['label_for']])) {echo $options[$args['label_for']];} ?>"
 >
 <?php
}
function BusinessMonitor_field_numberbox_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'BusinessMonitor_options' );
 // output the field
 ?>
 <input type="number" id="<?php echo esc_attr( $args['label_for'] ); ?>"
 name="BusinessMonitor_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
 value="<?php if(!empty($options[$args['label_for']])) {echo $options[$args['label_for']];} ?>"
 >
 <?php
}
function BusinessMonitor_field_selectbox_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'BusinessMonitor_options' );
 // output the field
 ?>
 <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
 name="BusinessMonitor_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
	 <option value="">--</option>
	 <option value="first_name" <?php if (strtolower($options[$args['label_for']]) == "first_name") echo "selected";  ?>>
		 first_name
	 </option>
     <option value="last_name" <?php if (strtolower($options[$args['label_for']]) == "last_name") echo "selected";  ?>>
		 last_name
	 </option>
	 <?php 
     for($i=1; $i<=150; $i++){
         echo "<option value='custom_data_".$i."'";
		 if (strtolower($options[$args['label_for']]) == "custom_data_".$i) echo " selected ";
		 echo ">"."custom_data_".$i."</option>";
     }
     ?> 
 </select>
 <?php
}
/**
 * register our BusinessMonitor_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'BusinessMonitor_settings_init' );

/**
 * custom option and settings:
 * callback functions
 */

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function BusinessMonitor_section_apikey_cb( $args ) {
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Configure your api key (BusinessMonitor credentials).', 'BusinessMonitor' ); ?></p>
 <?php
}

function BusinessMonitor_section_developers_cb( $args ) {
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Configure the widget using with your item id. Leave filter field/value empty if you want to use all results.', 'BusinessMonitor' ); ?></p>
 <?php
}

function BusinessMonitor_section_reviewfeed_cb( $args ) {
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Configure the review feed using your item id\'s.', 'BusinessMonitor' ); ?></p>
 <?php
}


/**
 * top level menu
 */
function BusinessMonitor_options_page() {
 // add top level menu page
 add_menu_page(
 'BusinessMonitor',
 'BusinessMonitor Options',
 'manage_options',
 'BusinessMonitor',
 'BusinessMonitor_options_page_html'
 );
}

/**
 * register our BusinessMonitor_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'BusinessMonitor_options_page' );

/**
 * top level menu:
 * callback functions
 */
function BusinessMonitor_options_page_html() {
 // check user capabilities
 if ( ! current_user_can( 'manage_options' ) ) {
 return;
 }

 // add error/update messages

 // check if the user have submitted the settings
 // wordpress will add the "settings-updated" $_GET parameter to the url
 if ( isset( $_POST['settings-updated'] ) ) {
 // add settings saved message with the class of "updated"
 add_settings_error( 'BusinessMonitor_messages', 'BusinessMonitor_message', __( 'Settings Saved', 'BusinessMonitor' ), 'updated' );
 }

 // show error/update messages
 settings_errors( 'BusinessMonitor_messages' );
 ?>
 <div class="wrap">
 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
 <form action="options.php" method="post">
 <?php
 // output security fields for the registered setting "BusinessMonitor"
 settings_fields( 'BusinessMonitor' );
  // output setting sections and their fields
 // (sections are registered for "BusinessMonitor", each field is registered to a specific section)
 do_settings_sections( 'BusinessMonitor' );
  // output save settings button
 submit_button( 'Save Settings' );
 ?>
 </form>
 </div>
 <?php
}
 ?>