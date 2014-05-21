<?php

/*
Plugin Name: MailChimpFeed
Description: Custom RSS feed to work better with MC
Version: 1.1
License: MIT

Author: Michael Bianco
Author URI: http://cliffsidemedia.com/
Plugin URI: https://github.com/iloveitaly/wordpress-mailchimp-rss
*/

define('MCRSS_IFRAME_REGEX', '#<iframe.+?src="([^"]+)[^>]+></iframe>#i');

define('MCRSS_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

function iloveitaly_format_feed_for_mailchimp($content) {
  // note: depends on an unreleased youtube video plugin
  if(function_exists("iloveitaly_iframe_to_image")) {
    $content = iloveitaly_iframe_to_image($content);
  }

  // can't do any iframes in HTML emails
  $content = preg_replace(MCRSS_IFRAME_REGEX, "<a target='_blank' href='".get_permalink().'\'>View in Browser</a>', $content);

  // blockquotes don't render well in HTML email
  $content = str_replace("<blockquote><p>", "<p class='blockquote'>", $content);
  $content = str_replace("</p></blockquote>", "</p>", $content);

  // link all images to the post
  $content = preg_replace('#<img [^>]*src="([^"]+)"[^>]*>#', "<a target='_blank' href='".get_permalink().'\'>$0</a>', $content);

  // https://github.com/iloveitaly/wordpress-mailchimp-widget
  if(class_exists('NS_MC_Plugin')) {
    $mc_api_key = get_option('ns_mc_api_key');

    if(!empty($mc_api_key)) {
      // MC will NOT inline css on CONTEXT_TEXT coming from an RSS feed
      // run $content through MC css inliner as a workaround

      $standard_inline_css = "<style type=\"text/css\">" . file_get_contents(MCRSS_PLUGIN_PATH . 'inline-email-styles.css') . "</style>";

      if($css_customization_file = locate_template('inline-email-styles.css')) {
        $standard_inline_css .= "<style type=\"text/css\">" . file_get_contents($css_customization_file) . "</style>";
      }

      $mc = new MCAPI($mc_api_key);
      $content = $mc->inlineCss($standard_inline_css . $content, true);
    }
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