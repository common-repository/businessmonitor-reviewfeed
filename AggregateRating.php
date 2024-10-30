<?php
/*
Plugin Name: BusinessMonitor-AggregateRating-Widget
Plugin URI: https://businessmonitor.nl/technologie
Description: Wordpress AggregateRating widget
Version: 1.0.16
Author: Salesforce up to data b.v.
Author URI: https://businessmonitor.nl/contact

Changelog:
1.0.3 Changed constructor to match php7
1.0.4 Added support for configurable star colors
1.0.7 Change init method (removes php5.2 support)
1.0.8 Added support for filters
*/

if(!defined('BUSINESSMONITOR__PLUGIN_DIR'))
{
define( 'BUSINESSMONITOR__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

require_once( BUSINESSMONITOR__PLUGIN_DIR . 'businessmonitor-class.php' );

/** Widget Class */
class BusinessMonitor_AggregateRating_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(false, $name = 'BusinessMonitor-AggregateRating-Widget');
    }

    function widget($args, $instance) {
        extract( $args );
        $title       = apply_filters('widget_title', $instance['title']);
        $message    = $instance['message'];
        $includeCss = $instance['includeCss'];
        $includeMicrodata = $instance['includeMicrodata'];

        $companyname = $instance['companyname'];
        $telephone = $instance['telephone'];
        $streetAddress = $instance['streetAddress'];
        $postalCode = $instance['postalCode'];
        $addressLocality = $instance['addressLocality'];

        $firstColor = "B5D560";
        if(!empty($instance['firstColor']))
        {
          $firstColor = esc_attr($instance['firstColor']);
        }

        $secondColor = "C0C0C0";
        if(!empty($instance['secondColor']))
        {
          $secondColor = esc_attr($instance['secondColor']);
        }

            echo $before_widget;

                  if ( $title )
                  {
                        echo $before_title . $title . $after_title;
                  }

                  if ( $message)
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
                        echo $BusinessMonitor_Widgets->BusinessMonitor_AggregateRating_Widget_CSS($firstColor,$secondColor);
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

               if ($includeMicrodata == "on")
               {
                   ?>
                 <div itemscope itemprop="organization" itemtype="http://schema.org/Organization">
                 <a style='display:none' href='<?php echo $current_url; ?>/' itemprop='url'><?php echo $current_url; ?>/</a>
                  <meta itemprop='name' content='<?php echo $companyname; ?>' />
                  <meta itemprop='telephone' content='<?php echo $telephone; ?>' />
                  <div itemtype="http://schema.org/PostalAddress" itemscope itemprop="address">
                 <meta itemprop='streetAddress' content="<?php echo $streetAddress; ?>" />
                 <meta itemprop='postalCode' content='<?php echo $postalCode; ?>' />
                 <meta itemprop='addressLocality' content='<?php echo $addressLocality; ?>' />
               </div>
                 <?php
              }

              if (empty($setting['BusinessMonitor_field_ratingFilterField']))
              {
                  echo $BusinessMonitor_Widgets->BusinessMonitor_AggregateRating_Widget_Widget($setting['BusinessMonitor_field_apikey'],$setting['BusinessMonitor_field_itemGrade'],$ip);
              }
              else
              {
              	echo $BusinessMonitor_Widgets->BusinessMonitor_AggregateRating_Widget_Widget_Filtered($setting['BusinessMonitor_field_apikey'],$setting['BusinessMonitor_field_itemGrade'],$ip,$setting['BusinessMonitor_field_ratingFilterField'],$setting['BusinessMonitor_field_ratingFilterValue']);
              }

                  if ($includeMicrodata == "on")
                  {
                     echo "</div>";
                  }

            echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['message'] = strip_tags($new_instance['message']);
        $instance['includeCss'] = strip_tags($new_instance['includeCss']);
        $instance['includeMicrodata'] = strip_tags($new_instance['includeMicrodata']);
        $instance['companyname'] = strip_tags($new_instance['companyname']);
        $instance['telephone'] = strip_tags($new_instance['telephone']);
        $instance['streetAddress'] = strip_tags($new_instance['streetAddress']);
        $instance['postalCode'] = strip_tags($new_instance['postalCode']);
        $instance['addressLocality'] = strip_tags($new_instance['addressLocality']);
        $instance['firstColor'] = strip_tags($new_instance['firstColor']);
        $instance['secondColor'] = strip_tags($new_instance['secondColor']);
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

        $includeCss = "on"; #default to on
        if(isset($instance['includeCss']))
        {
          $includeCss = esc_attr($instance['includeCss']);
        }

        $includeMicrodata ="";
        if(!empty($instance['includeMicrodata']))
        {
          $includeMicrodata = esc_attr($instance['includeMicrodata']);
        }

        $companyname ="";
        if(!empty($instance['companyname']))
        {
          $companyname = esc_attr($instance['companyname']);
        }

        $telephone="";
        if(!empty($instance['telephone']))
        {
          $telephone = esc_attr($instance['telephone']);
        }

        $streetAddress="";
        if(!empty($instance['streetAddress']))
        {
          $streetAddress = esc_attr($instance['streetAddress']);
        }

        $postalCode="";
        if(!empty($instance['postalCode']))
        {
          $postalCode = esc_attr($instance['postalCode']);
        }

        $addressLocality ="";
        if(!empty($instance['addressLocality']))
        {
          $addressLocality = esc_attr($instance['addressLocality']);
        }

        $firstColor = "B5D560";
        if(!empty($instance['firstColor']))
        {
          $firstColor = esc_attr($instance['firstColor']);
        }

        $secondColor = "C0C0C0";
        if(!empty($instance['secondColor']))
        {
          $secondColor = esc_attr($instance['secondColor']);
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
          <label for="<?php echo $this->get_field_id('secondColor'); ?>"><?php _e('Background color (gray stars):'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('secondColor'); ?>" placeholder="FFFFFF" name="<?php echo $this->get_field_name('secondColor'); ?>" type="text" value="<?php echo $secondColor; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('firstColor'); ?>"><?php _e('Foreground color (star color/rating):'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('firstColor'); ?>" placeholder="000000" name="<?php echo $this->get_field_name('firstColor'); ?>" type="text" value="<?php echo $firstColor; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('includeMicrodata'); ?>"><?php _e('Include microdata in rendering:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('includeMicrodata'); ?>" name="<?php echo $this->get_field_name('includeMicrodata'); ?>" type="checkbox" <?php if ($includeMicrodata == "on") echo "checked=checked"; ?> />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('companyname'); ?>"><?php _e('name:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('companyname'); ?>" placeholder="BusinessMonitor" name="<?php echo $this->get_field_name('companyname'); ?>" type="text" value="<?php echo $companyname; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('telephone'); ?>"><?php _e('telephone:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('telephone'); ?>" placeholder="+31102802800" name="<?php echo $this->get_field_name('telephone'); ?>" type="text" value="<?php echo $telephone; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('streetAddress'); ?>"><?php _e('streetAddress:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('streetAddress'); ?>" placeholder="Beursplein 37" name="<?php echo $this->get_field_name('streetAddress'); ?>" type="text" value="<?php echo $streetAddress; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('postalCode'); ?>"><?php _e('postalCode:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('postalCode'); ?>" placeholder="3011AA" name="<?php echo $this->get_field_name('postalCode'); ?>" type="text" value="<?php echo $postalCode; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('addressLocality'); ?>"><?php _e('addressLocality:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('addressLocality'); ?>" placeholder="Rotterdam" name="<?php echo $this->get_field_name('addressLocality'); ?>" type="text" value="<?php echo $addressLocality; ?>" />
        </p>

        <?php
    }

} // end class
//add_action('widgets_init', create_function('', 'return register_widget("BusinessMonitor_AggregateRating_Widget");'));

add_action( 'widgets_init', function(){
	register_widget( 'BusinessMonitor_AggregateRating_Widget' );
});
?>