# Petfinder API for WordPress

Petfinder for WordPress is a set of functions that makes it easy to import and work with data from Petfinder:

* Import info on all pets for any shelter.
* Grab photos in multiple sizes.
* Dynamically create lists of breeds, ages, sizes and more.
* Convert values into classes you can use for filtering and sorting.
* Remove weird text and formatting from names and descriptions.
* Convert Petfinder's default values (ex. "Small" instead of "S").

**Note:** Petfinder for WordPress is a plugin boilerplate and is designed to be a starting point that you can customize. A working knowledge of PHP and WordPress functions is required. If you're looking for a plug-and-play solution, check out the [Petfinder Listings plugin](http://wordpress.org/extend/plugins/petfinder-listings/).


**In This Documentation**

1. [Getting Started](#getting-started)
2. [Conversions](#conversions)
3. [Photos](#photos)
4. [Cleanups](#cleanups)
5. [Condensor](#condensor)
6. [Lists](#lists)
7. [All Pets](#all-pets)
8. [Individual Pets](#individual-pets)
9. [Shortcode](#shortcode)
10. [Performance Considerations](#performance-considerations)
11. [How to Contribute](#how-to-contribute)
12. [License](#license)
13. [Changelog](#changelog)



## Getting Started

Getting started with Petfinder API for WordPress is as simple as installing a plugin:

1. Upload the `petfinder-api-for-wordpress` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the Plugins menu in WordPress.

### Displaying Pets

Use a simple shortcode in the WordPress editor to display your pets:

```php
[petfinder_list api_key="$api_key" shelter_id="$shelter_id" count="$count"]
```

* `$api_key` - Your Petfinder API key.
* `$shelter_id` - The ID of the shelter you want to import info for.
* `$count` - The number of animals to return info on.

Alternatively, you can call the function directly from any WordPress template:

```php
<?php
	$atts = array(
		'api_key' => $api_key,
		'shelter_id' => $shelter_id,
		'count' => $count
	);
	echo display_petfinder_list($atts);
?>
```

Keep reading to learn how to customize the plugin for your own project.



## Conversions

Petfinder for WordPress includes functions that convert the default values Petfinder returns into custom values.

* `get_pet_type()` - The species or category of animal.
* `get_pet_size()` - The size of the pet.
* `get_pet_age()` - The age of the pet.
* `get_pet_gender()` - The gender of the pet.
* `get_pet_options()` - Special needs, suitability with other animals and more.
* `get_text_urls()` - Converts plain text URLs into functioning links.
* `get_text_emails()` - Converts plain text email addresses into mailto links.

The `get_text_urls()` and `get_text_emails()` functions are used in the `get_pet_descriptions` function. [Learn more.](#cleanups)

### Converting a Value

As an example, Petfinder returns values of `S`, `M`, `L`, and `XL` as the size of the pet. Using `get_pet_size()`, you can easily convert these into more descriptive values:

```php
function get_pet_size($pet_size) {
	if ($pet_size == 'S') return 'Small';
	if ($pet_size == 'M') return 'Medium';
	if ($pet_size == 'L') return 'Large';
	if ($pet_size == 'XL') return 'Extra Large';
	return 'Not Known';
}
```

You can easily adjust the Petfinder for WordPress defaults by changing the value after return. For example:

```php
function get_pet_size($pet_size) {
	if ($pet_size == 'S') return 'Mini';
	if ($pet_size == 'M') return 'Average';
	if ($pet_size == 'L') return 'Grande';
	if ($pet_size == 'XL') return 'Ginormous';
	return 'Not Known';
}
```



## Photos

Petfinder API for WordPress uses the `get_pet_photos()` function to import photos of each pet from Petfinder. This function is called from the `get_all_pets()` and `get_one_pet()` functions. It accepts a few arguments:

* `$pet` - The pet you're requesting photos for.
* `$photo_size` - The size of the photos.
* `$limit` - Limit to one photo or get all photos.

### Photo Size

There are five `$photo_size` options [available from Petfinder](http://www.petfinder.com/developers/api-docs/faq.html):

* `large` - Original, up to 500x500 pixels.
* `medium` - Up to 320x250 pixels.
* `thumb_small` - Scaled to 50 pixels tall.
* `thumb_medium` - Scaled to 60 pixels wide.
* `thumb_large` - Scaled to 95 pixels wide.

### Limit Number

Set `$limit` to `true` (default) to return just one photo. Set it to `false` to return all photos of the requested size.

### Formatting the Output

You can adjust the formatting of each photo under the `$pet_photos` variable within the function. By default, each image is wrapped in a paragraph tag and contains an `alt` tag with the pet's name:

```php
$pet_photos = '<p><img alt="Photo of ' . $pet_name . '" src="' . $photo . '"></p>';
```

You may also wish to add a fallback image for when a pet does not have any uploaded images:

```
$pet_photos = 'No Photo Available';
```



## Cleanups

If multiple people are entering data into Petfinder, it's easy for the formatting get a bit inconsistent.

* `get_pet_name()` - Removes dashes, parentheses and brackets from names, and converts them to title case.
* `get_pet_description()` - Removes the inline styling that WYSIWYG editors can add to descriptions.

### Description Formatting

The `get_pet_description()` function wraps descriptions in a `<pre>` tag to preserve whitespace and formatting. Use the `.pf-description` class in your CSS file to override any default styling on this element.

If nothing else, you may want to start with:

```css
.pf-description {
	white-space: pre-wrap;
	word-break: keep-all;
}
```



## Condensor

The `pet_value_condensed()` function converts multi-word strings into concatenated strings that can be used as class names. For example:

**Input:** `Golden Retriever`
**Output:** `Golden-Retriever`

Why would you need this? If you want to build a front-end filter using JavaScript, these classes give you something to filter by. Check out [PAWS New England](http://cferdinandi.github.io/petfinder-api-for-wordpress/www.pawsnewengland.com/our-dogs/) for an example.



## Lists

Petfinder for WordPress includes functions that dynamically generate lists based on the types of pets you actually have available.

* `get_type_list()` - A list of available types or species of pets.
* `get_breed_list()` - A list of available breeds.
* `get_size_list()` - A list of available sizes.
* `get_age_list()` - A list of available ages.
* `get_gender_list()` - A list of available genders.
* `get_options_list()` - A list of special needs and options.

If today, your shelter only has small and medium sized pets, that's all that will show up on the list. If you rescued a large pet tomorrow, "Large" (or whatever you call it in the [Conversions functions](#conversions)) would automatically get added to the list of available sizes.

These functions are particularly useful if you plan on creating any front-end filtering tools. See [PAWS New England](http://cferdinandi.github.io/petfinder-api-for-wordpress/www.pawsnewengland.com/our-dogs/) for an example.

### Individual Pet Options

The `get_pet_options_list()` function generates a list of special needs, suitability with other animals for a specific pet. This function is called from the `get_all_pets()` and `get_one_pet()` functions.

You can adjust the formatting of the list output under the `$pet_options` variable:

```php
$pet_options .= '<br>' . $get_option;
```



## All Pets

The `get_all_pets()` function displays a list of all available pets.

Petfinder for WordPress defines variables for a variety of info you might want to include in this list. You can adjust what get's displayed for each pet and how it's formatted under the `$pet_list` variable.

```php
$pet_list .=    '' .

	$pet_photo_thumbnail .

	'Name: ' . $pet_name . '' .
	'Animal: ' . $pet_type . '' .
	'Size: ' . $pet_size . '' .
	'Age: ' . $pet_age . '' .
	'Gender: ' . $pet_gender . '' .

	'Options:' . $pet_options . '' .

	'Learn More: ' . $pet_more_url . '' .
	'Petfinder Profile:  ' . $pet_pf_url . '' .

	'Description:' . $pet_description . '' .

	'Photos:' . $pet_photo_all .

'';
```

### Individual Pet Profiles

Petfinder for WordPress provides an easy way for you to create an individual pet profile page with its own URL.

```php
$pet_more_url = get_permalink() . '?view=pet-details&id=' . $pet->id;
```

You can add additional information to the URL string, but don't remove `?view=pet-details` or `&id=' . $pet->id`. Petfinder for WordPress uses these to pull info on individual pets.



## Individual Pets

The `get_one_pet()` function displays a information about a specific pet.

Petfinder for WordPress defines variables for a variety of info you might want to include. You can adjust what get's displayed for the pet and how it's formatted under the `$pet_info` variable.

```php
$pet_info = '&larr; Back to All Pets' .

$pet_photo_thumbnail .

'Name: ' . $pet_name . '' .
'Animal: ' . $pet_type . '' .
'Size: ' . $pet_size . '' .
'Age: ' . $pet_age . '' .
'Gender: ' . $pet_gender . '' .

'Options:' . $pet_options . '' .

'Petfinder Profile:  ' . $pet_pf_url . '' .

'Description:' . $pet_description . '' .

'Photos:' . $pet_photo_all;
```


## Shortcode

The `display_petfinder_list()` function controls what gets displayed the the `[petfinder_list]` shortcode.

If the `view=pet-details` string is present in the URL, Petfinder for WordPress will return the `get_one_pet()` function. Otherwise, it returns a series of lists and the `get_all_pets()` function.

You can adjust what gets displayed and the error messages that are show when Petfinder returns error codes under the `$petfinder_list` variables.



## Performance Considerations

The Petfinder API can be slow, especially if you're pulling information on a large number of animals.

For better performance, try using a caching plugin like [Quick Cache](http://wordpress.org/plugins/quick-cache/) or [WP Super Cache](https://wordpress.org/plugins/wp-super-cache/). It will pre-build the page with the Petfinder API (and all other pages on your site) once an hour, significantly increasing site performance.

### Increasing Timeout Limits

If you're pulling information on a large number of animals, your server may timeout before the request is completed.

If you're seeing the "Petfinder is down for the moment" message, try typing your API URL (the `http://api.petfinder.com/shelter...` part with all of your information added in) directly into a browser tab. If the data loads, then your server may be timing out before the request can be completed.

To fix this, increase the `max_execution_time` for your server. The process for doing this varies by hosting provider. You can find general information in the PHP manual.



## How to Contribute

In lieu of a formal style guide, take care to maintain the existing coding style. Don't forget to update the version number, the changelog (in the `readme.md` file), and when applicable, the documentation.



## License

Petfinder API for WordPress is licensed under the [MIT License](http://gomakethings.com/mit/).



## Changelog

* v4.3 - February 28, 2014
	* Converted to a plugin boilerplate (vs. a theme-specific function).
* v4.2 - February 7, 2014
	* Wrapped description in `<pre>` tags to preserve whitespace and formatting.
	* Added functions to convert plain text URLs and emails to working links.
* v4.1 - November 7, 2013
	* [Added `isset()` check for `$_GET` values to prevent errors.](https://github.com/cferdinandi/petfinder-api-for-wordpress/issues/5)
* v4.0 - August 30, 2013
	* Changed several function names to make them more intuitive.
	* Pass more arguments through shortcode for greater flexibility.
	* Breaks backwards compatabitility with versions 3.x.
* v3.0 - July 29, 2013
	* `get_pet_photos()` now accepts arguments.
	* Two separate functions for all pets and individual pet profiles.
* v2.1 - July 12, 2013
	* Performance fix.
* v2.0 - June 11, 2013
	* Completely redesigned toolkit.
* v1.4 - April 1, 2013
	* Fixed display glitch for special needs categories.
	* Updated documentation.
* v1.3 - March 28, 2013
	* Replaced dropdown menu with popup modal windows.
* v1.2 - March 17, 2013
	* Added scripts to remove styling added by Petfinder.
* v1.1 - March 14, 2013
	* Updated PHP to prevent errors from showing on the site if API fails to load.
* v1.0 - March 6, 2013
	* Initial release.