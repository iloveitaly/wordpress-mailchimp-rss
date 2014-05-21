Custom WordPress RSS Feed for MailChimp RSS-to-email
----------------------------------------------------

Usage: http://domain.com/blog/?feed=mailchimp

## Customization

In your theme add `inline-email-styles.css`

## How it Works

* Links every image in a post to the post
* Converts blockquotes to a `p.blockquote`
* Adds support for the `*|RSSITEM:IMAGE|*` merge tag
* Swaps out YouTube iframe embeds with a static image (this is dependent on a as of yet unreleased plugin)