<?php
/*
Plugin Name: BusinessMonitor-Reviewfeed
Plugin URI: https://businessmonitor.nl/technologie
Description: Wordpress Reviewfeed
Version: 1.0.16
Author: Salesforce up to data b.v.
Author URI: https://businessmonitor.nl/contact

Changelog:
1.0.2 Changed constructor to match php7
1.0.4 aligned all version numbers for all plugin parts
1.0.7 Change init method (removes php5.2 support)
1.0.11: added the noReviewText parameter to the BusinessMonitor_ReviewFeed_Widget method call
*/

if(!defined('BUSINESSMONITOR__PLUGIN_DIR')){define( 'BUSINESSMONITOR__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );}
require_once( BUSINESSMONITOR__PLUGIN_DIR . 'businessmonitor-class.php' );

/** reviewfeed Class */
class BusinessMonitor_Reviewfeed extends WP_Widget {
    function __construct() {
        parent::__construct(false, $name = 'BusinessMonitor-Reviewfeed');
    }

    function widget($args, $instance) {
        extract( $args );
        $title       = apply_filters('widget_title', $instance['title']);
        $message    = $instance['message'];
        $includeCss = $instance['includeCss'];

        $firstColor = "B5D560";
        if(!empty($instance['firstColor']))
        {
          $firstColor = esc_attr($instance['firstColor']);
        }

            echo $before_widget;

                  if ($title)
                  {
                        echo $before_title . $title . $after_title;
                  }

                  if ($message)
                  {
                  ?>
                    <ul>
                        <li><?php echo $message; ?></li>
                  </ul>
                  <?php
                  }

                  $BusinessMonitor_Widgets = new BusinessMonitor_Widgets;

                  if ($includeCss == "on")
                  {
                    #if enabled, render the AggregateRating Widget CSS code
                    echo $BusinessMonitor_Widgets->BusinessMonitor_ReviewFeed_CSS($firstColor);
                  }

               $setting = get_option('BusinessMonitor_options');

               if (empty($_SERVER['SERVER_ADDR']))
               {
                  if (empty($_SERVER['LOCAL_ADDR']))
                  {
                     $ip = $_SERVER['LOCAL_ADDR'];
                  }
                  else
                  {
                     #php did not return a server ip address (observed on microsoft azure iis - php)
                     $ip = '127.0.0.1';
                  }
               }
               else
               {
                  $ip = $_SERVER['SERVER_ADDR'];
               }

        global $wp;
        $current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

		if (!isset($setting['BusinessMonitor_field_noReviewText'])) {
			// set the default value if not set
			$setting['BusinessMonitor_field_noReviewText'] = "There are currently no reviews yet.";
			update_option('BusinessMonitor_options', $setting);
		}

		if (!isset($setting['BusinessMonitor_field_field3'])) {
			// set the default value if not set
			$setting['BusinessMonitor_field_field3'] = "";
			update_option('BusinessMonitor_options', $setting);
		}

		if (!isset($setting['BusinessMonitor_field_field4'])) {
			// set the default value if not set
			$setting['BusinessMonitor_field_field4'] = "";
			update_option('BusinessMonitor_options', $setting);
		}

		if (!isset($setting['BusinessMonitor_field_field5'])) {
			// set the default value if not set
			$setting['BusinessMonitor_field_field5'] = "";
			update_option('BusinessMonitor_options', $setting);
		}

		if (!isset($setting['BusinessMonitor_field_answerAnonymous'])) {
			// set the default value if not set
			$setting['BusinessMonitor_field_answerAnonymous'] = "";
			update_option('BusinessMonitor_field_answerAnonymous', $setting);
		}

		if (!isset($setting['BusinessMonitor_field_anonymousText'])) {
			// set the default value if not set
			$setting['BusinessMonitor_field_anonymousText'] = "";
			update_option('BusinessMonitor_field_anonymousText', $setting);
		}

#echo "mark is cool";
#echo($setting['BusinessMonitor_field_fieldProduct'].",".$setting['BusinessMonitor_field_fieldWho']);
#var_dump(explode (",", $setting['BusinessMonitor_field_fieldProduct'].",".$setting['BusinessMonitor_field_fieldWho'].",".$setting['BusinessMonitor_field_field3'].",".$setting['BusinessMonitor_field_field4'].",".$setting['BusinessMonitor_field_field5']));

      echo $BusinessMonitor_Widgets->BusinessMonitor_ReviewFeed_Widget_Multiple_Fields(
			$setting['BusinessMonitor_field_apikey'],
			$setting['BusinessMonitor_field_itemGradeFeed'],
			$setting['BusinessMonitor_field_itemText'],
			$setting['BusinessMonitor_field_itemAgree'],
			array_map('intval', explode(",", $setting['BusinessMonitor_field_answerAgree'])),
			//$setting['BusinessMonitor_field_answerAgree'],
			explode (",", $setting['BusinessMonitor_field_fieldProduct'].",".$setting['BusinessMonitor_field_fieldWho'].",".$setting['BusinessMonitor_field_field3'].",".$setting['BusinessMonitor_field_field4'].",".$setting['BusinessMonitor_field_field5']),
			$setting['BusinessMonitor_field_noReviewText'],
			array_map('intval', explode(",", $setting['BusinessMonitor_field_answerAnonymous'])),
			$setting['BusinessMonitor_field_anonymousText'],
			$ip
			);

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['message'] = strip_tags($new_instance['message']);
        $instance['includeCss'] = strip_tags($new_instance['includeCss']);
        $instance['firstColor'] = strip_tags($new_instance['firstColor']);
        return $instance;
    }

    function form($instance) {
        $title = "";
        if(!empty($instance['title']))
        {
          $title = esc_attr($instance['title']);
        }

        $message = "";
        if(!empty($instance['message']))
        {
          $message = esc_attr($instance['message']);
        }

        $includeCss = "on"; #default enabled
        if(isset($instance['includeCss']))
        {
          $includeCss = esc_attr($instance['includeCss']);
        }

        $firstColor = "B5D560";
        if(!empty($instance['firstColor']))
        {
          $firstColor = esc_attr($instance['firstColor']);
        }
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Additional text:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>" type="text" value="<?php echo $message; ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('includeCss'); ?>"><?php _e('Include CSS in rendering:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('includeCss'); ?>" name="<?php echo $this->get_field_name('includeCss'); ?>" type="checkbox" <?php if ($includeCss == "on") echo "checked=checked"; ?> />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('firstColor'); ?>"><?php _e('Foreground color (header line):'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('firstColor'); ?>" placeholder="000000" name="<?php echo $this->get_field_name('firstColor'); ?>" type="text" value="<?php echo $firstColor; ?>" />
        </p>
            <?php
    }
} // end class
//add_action('widgets_init', create_function('', 'return register_widget("BusinessMonitor_Reviewfeed");'));
add_action( 'widgets_init', function(){
	register_widget( 'BusinessMonitor_Reviewfeed' );
});
?>