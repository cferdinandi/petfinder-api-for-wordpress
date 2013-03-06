# The Petfinder API for WordPress Toolkit
A collection of PHP, CSS and JavaScript to help you implement the Petfinder API on your WordPress site.

## How It Works
This toolkit contains a few different files:

* `functions.php` - The PHP script that pulls and processes the Petfinder API.
* `petfinder-api.css` - Structure and styling for the API content.
* `petfinder-api.js` - Some JavaScript progressive enhancement.
* `img > nophoto.jpg` - A fallback image for when an animal has no photos available.

Here's how to get started...

***1. Add `functions.php`***
If your WordPress theme does not have a `functions.php` file, add this file to your theme. If `functions.php` already exists, copy-and-paste the text in the included `functions.php` file into it.

***2. Add `petfinder-api.css`***
Call `petfinder-api.css` in your header: `<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/petfinder-api.css">`.

***3. Add `petfinder-api.js`***
Call `petfinder-api.js` in your footer: `<script src="<?php bloginfo('stylesheet_directory'); ?>/petfinder-api.js"></script>`.

***4. Add `img > nophoto.jpg`***
If you already have an `img` folder in your theme folder, copy `nophoto.jpg` into it. Otherwise, add the `img` folder to your theme directory.

## Changelog
* DATE
  * Initial release.

## License
The Petfinder API for WordPress Toolkit is licensed under [WTFPL](http://www.wtfpl.net/).
