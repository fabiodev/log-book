<?php

/* 
Plugin Name: Log Book of Ships 
Plugin URI: https://github.com/fabiodev/log-book 
Description: Sidebar widget that shows upcoming ship arrivals 
Version: 0.1 
Author: Fábio Silva 
Author URI: https://github.com/fabiodev/ 
License: A "Slug" license name e.g. GPL2 
. 
Any other notes about the plugin go here 
. 
*/  

function widget_logBook($args=array(), $params=array()) {
  extract($args);
  $widgettitle = get_option('logBook_widget_title');

  echo $before_widget;

  echo $before_title.$widgettitle.$after_title;

  //$file = plugins_url('log-book/escalas.txt');
        //require('escalas.txt');
        require('page-escalas.php');

  echo $after_widget;
}

function logBook_init(){

wp_register_sidebar_widget(
    'log_book_1',        // your unique widget id
    'Log Book',          // widget name
    'widget_logBook',  // callback function
    array(                  // options
        'description' => 'Description of what your widget does'
    )
);

wp_register_widget_control(
	'log_book_1',		// id
	'Log Book',		// name
	'widget_logBook_control'	// callback function
);

}

function widget_logBook_control($args=array(), $params=array()) {
	//the form is submitted, save into database
	if (isset($_POST['submitted'])) {
		update_option('logBook_widget_title', $_POST['widgettitle']);
		update_option('logBook_widget_cookieValue', $_POST['cookievalue']);
		update_option('logBook_widget_description', $_POST['description']);
	}

	//load options
	$widgettitle = get_option('logBook_widget_title');
	$cookievalue = get_option('logBook_widget_cookieValue');
	$description = get_option('about_us_widget_description');
	?>

	Widget Title:<br />
	<input type="text" style="width:100%" name="widgettitle" value="<?php echo stripslashes($widgettitle); ?>" />
	<br /><br />

	Description about you:<br />
	<textarea style="width:100%" rows="5" name="description"><?php echo stripslashes($description); ?></textarea>
	<br /><br />

	Cookie Value:<br />
	<input type="text" style="width:100%" name="cookievalue" value="<?php echo stripslashes($cookievalue); ?>" />
	<br /><br />

	<input type="hidden" name="submitted" value="1" />
	<?php
}


add_filter( 'page_template', 'escalas_page_template' );
function escalas_page_template( $page_template )
{
    if ( is_page( 'escalas' ) ) {
        $page_template = dirname( __FILE__ ) . '/mpage-escalas.php';
    }
    return $page_template;
}

add_action("plugins_loaded", "logBook_init");

?>
