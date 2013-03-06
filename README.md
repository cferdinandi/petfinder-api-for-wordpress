# The Petfinder API for WordPress Toolkit
A collection of PHP, CSS and JavaScript to help you display a list of animals available for adoption on your WordPress site using the Petfinder API.

This toolkit is intended for web developers, and is intended to be customized for your site design. If you don't have any coding experience, try the fantastic [Petfinder Listings plugin](http://wordpress.org/extend/plugins/petfinder-listings/).

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

## Changelog
* 3/6/2013
  * Initial release.

## License
The Petfinder API for WordPress Toolkit is licensed under [WTFPL](http://www.wtfpl.net/) + "Not going to maintain this because the rent is too damn high" License.
