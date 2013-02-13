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

function sampleLog() 
{
  $file = plugins_url('escalas.txt');
	//require('escalas.txt');
	require('page-escalas.php');
}
 
function widget_logBook($args) {
  extract($args);
  echo $before_widget;
  echo $before_title;?>Escalas<?php echo $after_title;
  sampleLog();
  echo $after_widget;
}
 
function logBook_init()
{
  //register_sidebar_widget(__('Log Book'), 'widget_logBook');

wp_register_sidebar_widget(
    'log_book_1',        // your unique widget id
    'Log Book',          // widget name
    'widget_logBook',  // callback function
    array(                  // options
        'description' => 'Description of what your widget does'
    )
);

}
add_action("plugins_loaded", "logBook_init");

?>
