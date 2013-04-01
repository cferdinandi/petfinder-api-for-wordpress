# The Petfinder API for WordPress Toolkit
A collection of PHP, CSS and JavaScript to help you display a list of animals available for adoption on your WordPress site using the Petfinder API.

This toolkit is intended for web developers, and is intended to be customized for your site design. If you don't have any coding experience, try the fantastic [Petfinder Listings plugin](http://wordpress.org/extend/plugins/petfinder-listings/).

***Note:** This is not a plugin. It's a collection of files that you will need to manually add to your theme.*

## How It Works
This toolkit contains a few different files:

* `functions.php` - The PHP script that pulls and processes the Petfinder API.
* `petfinder-api.css` - Structure and styling for the API content.
* `petfinder-api.js` - Some JavaScript progressive enhancement.
* `img > nophoto.jpg` - A fallback image for when an animal has no photos available.

Add these files to your theme (merge them with your existing files or add them separately as appropriate).

### Display Your Pet Listings
In the WordPress text editor, use the `[shelter_list]` shortcode. To include a list of animals directly in a PHP file, add `<?php echo petf_shelter_list(); ?>`.

## Requires jQuery
A few of the scripts used in this toolkit require jQuery, so make sure you include that in your theme as well.

Not sure how? [Here's a short tutorial.](http://gomakethings.com/jquery-wordpress/)

## Performance Considerations
The Petfinder API can be slow, especially if you're pulling information on a large number of animals.

For better performance, try using the [Quick Cache plugin](http://wordpress.org/extend/plugins/quick-cache/). It will pre-build the page with the Petfinder API (and all other pages on your site) once an hour, significantly increasing site performance.

### Increasing Timeout Limits
If you're pulling information on a large number of animals, your server may timeout before the request is completed.

If you're seeing the "Petfinder is down for the moment" message, try typing your API URL (the `http://api.petfinder.com/shelter...` part with all of your information added in) directly into a browser tab. If the data loads, then your server may be timing out before the request can be completed.

To fix this, increase the `max_execution_time` for your server. The process for doing this varies by hosting provider. You can find general information in the [PHP manual](http://php.net/manual/en/function.set-time-limit.php).

## Changelog
* 4/1/2013
  * Fixed display glitch for special needs categories.
* 3/28/2013
  * Replaced dropdown menu with popup modal windows.
* 3/17/2013
  * Added scripts to remove styling added by Petfinder.
* 3/14/2013
  * Updated PHP to prevent errors from showing on the site if API fails to load.
* 3/6/2013
  * Initial release.

## License
The Petfinder API for WordPress Toolkit is licensed under [WTFPL](http://www.wtfpl.net/) + "Not going to maintain this because the rent is too damn high" License.
