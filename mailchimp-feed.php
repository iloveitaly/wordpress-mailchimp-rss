<?php

/*
Plugin Name: MailChimpFeed
Description: Custom RSS feed to work better with MC
Version: 1.1
Author: Michael Bianco
Author URI: http://github.com/iloveitaly/

*/

function iloveitaly_format_feed_for_mailchimp($content) {
  if(is_feed()) {
  	// note: depends on an unreleased youtube video plugin
  	if(function_exists("iloveitaly_iframe_to_image")) {
    	$content = iloveitaly_iframe_to_image($content);
  	}

    // blockquotes don't render well in HTML email
    $content = str_replace("<blockquote><p>", "<p class='blockquote'>", $content);
    $content = str_replace("</p></blockquote>", "</p>", $content);

    // link all images to the post
    $content = preg_replace('#<img [^>]*src="([^"]+)"[^>]*>#', "<a target='_blank' href='".get_permalink().'\'>$0</a>', $content);
  }

  return $content;
}

define( 'MC_FEED_PLUGIN_PATH', dirname( __FILE__ ) );

add_action('after_setup_theme', 'iloveitaly_setup_mailchimp_feed' );
function iloveitaly_setup_mailchimp_feed() {
  add_feed( 'mailchimp', 'iloveitaly_mailchimp_feed');
}

function iloveitaly_mailchimp_feed() {
	load_template(MC_FEED_PLUGIN_PATH . '/feed-mailchimp.php');
}

?>