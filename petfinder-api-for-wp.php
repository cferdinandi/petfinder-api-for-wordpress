<?php

/* ======================================================================

    Petfinder API for WordPress v2.0
    A collection of functions to help you display Petfinder listings
    on your WordPress site, by Chris Ferdinandi.
    http://gomakethings.com

    Thanks Bridget Wessel's Petfinder Listings Plugin for getting me started.
    http://wordpress.org/extend/plugins/petfinder-listings/

    Free to use under the MIT License.
    http://gomakethings.com/mit/
    
 * ====================================================================== */


/* =============================================================
    YOUR SHELTER INFO
    Get your shelter info from Petfinder.
 * ============================================================= */

function get_petfinder_data() {

    // Your Account Info
    $api_key = 'xxxx'; // Change to your API key
    $shelter_id = 'xxxx'; // Change to your shelter ID
    $count = '20'; // Number of animals to return. Set to higher than total # of animals in your shelter.

    // Create the request URL
    $request_url = "http://api.petfinder.com/shelter.getPets?key=" . $api_key . "&count=" . $count . "&id=" . $shelter_id . "&status=A&output=full";

    // Request shelter data from Petfinder
    $petfinder_data = @simplexml_load_file( $request_url );

    return $petfinder_data;
    
}






/* =============================================================
    CONVERSIONS
    Functions to convert default Petfinder return values into
    human-readable and/or custom descriptions.
 * ============================================================= */

// Convert Pet Size
function get_pet_size($pet_size) {
    if ($pet_size == 'S') return 'Small';
    if ($pet_size == 'M') return 'Medium';
    if ($pet_size == 'L') return 'Large';
    if ($pet_size == 'XL') return 'Extra-Large';
    return 'Not Known';
}

// Convert Pet Age
function get_pet_age($pet_age) {
    if ($pet_age == 'Baby') return 'Baby';
    if ($pet_age == 'Young') return 'Young';
    if ($pet_age == 'Adult') return 'Adult';
    if ($pet_age == 'Senior') return 'Senior';
    return 'Not Known';
}

// Convert Pet Gender
function get_pet_gender($pet_gender) {
    if ($pet_gender == 'M') return 'Male';
    if ($pet_gender == 'F') return 'Female';
    return 'Not Known';
}

// Convert Pet Animal Type
function get_pet_type($pet_type) {
    if ($pet_type == 'Dog') return 'Dog';
    if ($pet_type == 'Cat') return 'Cat';
    if ($pet_type == 'Small&amp;Furry') return 'Small & Furry';
    if ($pet_type == 'BarnYard') return 'Barnyard';
    if ($pet_type == 'Horse') return 'Horse';
    if ($pet_type == 'Pig') return 'Pig';
    if ($pet_type == 'Rabbit') return 'Rabbit';
    if ($pet_type == 'Reptile') return 'Scales, Fins & Other';
    return 'Not Known';
}

// Convert Special Needs & Options
function get_pet_option($pet_option) {
    if ($pet_option == 'specialNeeds') return 'Special Needs';
    if ($pet_option == 'noDogs') return 'No Dogs';
    if ($pet_option == 'noCats') return 'No Cats';
    if ($pet_option == 'noKids') return 'No Kids';
    if ($pet_option == 'noClaws') return 'No Claws';
    if ($pet_option == 'hasShots') return 'Has Shots';
    if ($pet_option == 'housebroken') return 'Housebroken';
    if ($pet_option == 'altered') return 'Spayed/Neutered';
    return 'Not Known';
}





/* =============================================================
    PET PHOTO SETTINGS
    Set size and number of pet photos.
 * ============================================================= */

function get_pet_photos($pet) {

    // Define Variables
    $pet_photos = '';

    // Photo Sizes
    $pet_photo_large = 'x'; // original, up to 500x500
    $pet_photo_medium = 'pn'; // up to 320x250
    $pet_photo_thumbnail_small = 't'; // scaled to 50px tall
    $pet_photo_thumbnail_medium = 'pnt'; // scaled to 60px wide
    $pet_photo_thumbnail_large = 'fpm'; // scaled to 95px wide

    // Set Photo Options
    $pet_photo_size = $pet_photo_medium; // change as desired
    $pet_photo_limit_number = true; // limit number of photos to just first photo? true = yes

    // If pet has photos
    if( count($pet->media->photos) > 0 ) {

        // For each photo, get photos that match the set size
        foreach ( $pet->media->photos->photo as $photo ) {
            foreach( $photo->attributes() as $key => $value ) {
                if ( $key == 'size' ) {
                    if ( $value == $pet_photo_size ) {

                        // If limit set on number of photos, get the first photo
                        if ( $pet_photo_limit_number == true ) {
                            $pet_photos = '<img alt="Photo of ' . $pet_name . '" src="' . $photo . '">';
                            break;
                        }

                        // Otherwise, get all of them
                        else {
                            $pet_photos .= '<img alt="Photo of ' . $pet_name . '" src="' . $photo . '">';
                        }
                        
                    }
                }
            }
        }
    }

    // If no photos have been uploaded for the pet
    else {
        $pet_photos = ''; // Add a URL for a fallback/placeholder photo
    }

    return $pet_photos;
    
}





/* =============================================================
    PET NAME CLEANUP
    Adjust formatting and remove special characters from pet names.
 * ============================================================= */

function get_pet_name($pet_name) {

    // Clean-up pet name
    $pet_name = array_shift(explode('-', $pet_name)); // Remove '-' from animal names
    $pet_name = array_shift(explode('(', $pet_name)); // Remove '(...)' from animal names
    $pet_name = array_shift(explode('[', $pet_name)); // Remove '[...]' from animal names
    $pet_name = strtolower($pet_name); // Transform names to lowercase
    $pet_name = ucwords($pet_name); // Capitalize the first letter of each name

    // Return pet name
    return $pet_name;
    
}





/* =============================================================
    PET DESCRIPTION CLEANUP
    Remove inline styling and empty tags from pet descriptions.
 * ============================================================= */

function get_pet_description($pet_description) {

    // Remove unwanted styling from pet description
    $pet_description = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $pet_description);// Remove inline styling
    $pet_description = preg_replace('/<font[^>]+>/', '', $pet_description); // Remove font tag
    $pet_description_scrub = array('<p></p>' => '', '<p> </p>' => '', '<p>&nbsp;</p>' => '', '<span></span>' => '', '<span> </span>' => '', '<span>&nbsp;</span>' => '', '<span>' => '', '</span>' => '', '<font>' => '', '</font>' => ''); // Define empty tags to remove
    $pet_description = strtr($pet_description, $pet_description_scrub); // Remove empty tags

    // Return pet description
    return $pet_description;
    
}





/* =============================================================
    PET VALUE CONDENSER
    Removes spacing and special characters from strings.
 * ============================================================= */

function pet_value_condensed($pet_value) {

    // Define characters to remove and remove them
    $condense_list = array('(' => '', ')' => '', '&' => '-', '/' => '-', '  ' => '-', ' ' => '-');
    $pet_value = strtr($pet_value, $condense_list);

    // Return condensed list
    return $pet_value;
    
}




/* =============================================================
    PET TYPE LIST
    List of available types of pets (dog, cat, horse, etc.)
 * ============================================================= */

function get_type_list($pets) {

    // Define Variables
    $types = '';
    $type_list = '';

    // Create a list of types of pets
    foreach( $pets as $pet ) {
        $types .= get_pet_type($pet->animal) . "|";
    }

    // Remove duplicates, convert into an array, and alphabetize
    $types = array_filter(array_unique(explode('|', $types)));
    asort($types);

    // For each type of pet
    foreach( $types as $type ) {

        // Create a condensed version without spaces or special characters
        $type_condensed = pet_value_condensed($type);

        // Create a list
        $type_list .= $type . ' / ' . $type_condensed . '<br>';
    }

    // Return the list
    return '<h2>Pet Types</h2>' . $type_list;
    
}





/* =============================================================
    BREED LIST
    List of available breeds.
 * ============================================================= */

function get_breed_list($pets) {

    // Define Variables
    $breeds = '';
    $breed_list = '';

    // Get a list of breeds for each pet
    foreach( $pets as $pet ) {
        foreach( $pet->breeds->breed as $pet_breed ) {
            $breeds .= $pet_breed . "|";
        }
    }

    // Remove duplicates, convert into an array and alphabetize
    $breeds = array_filter(array_unique(explode('|', $breeds)));
    asort($breeds);

    // For each breed
    foreach( $breeds as $breed ) {

        // Create a condensed version without spaces or special characters
        $breed_condensed = pet_value_condensed($breed);

        // Create a list
        $breed_list .= $breed . ' / ' . $breed_condensed . '<br>';
    }

    // Return the list
    return '<h2>Pet Breeds</h2>' . $breed_list;
    
}





/* =============================================================
    SIZE LIST
    List of available size of pets.
 * ============================================================= */

function get_size_list($pets) {

    // Define Variables
    $sizes = '';
    $size_list = '';

    // Create a list of pet sizes
    foreach( $pets as $pet ) {
        $sizes .= get_pet_size($pet->size) . "|";
    }

    // Remove duplicates, convert into an array, alphabetize and reverse list order
    $sizes = array_filter(array_unique(explode('|', $sizes)));
    asort($sizes);
    $sizes = array_reverse($sizes);

    // For each size of pet
    foreach( $sizes as $size ) {

        // Create a condensed version without spaces or special characters
        $size_condensed = pet_value_condensed($size);

        // Create a list
        $size_list .= $size . ' / ' . $size_condensed . '<br>';
    }

    // Return the list
    return '<h2>Pet Sizes</h2>' . $size_list;
    
}





/* =============================================================
    AGE LIST
    List of available pet ages.
 * ============================================================= */

function get_age_list($pets) {

    // Define Variables
    $ages = '';
    $age_list = '';

    // Create a list of pet ages
    foreach( $pets as $pet ) {
        $ages .= get_pet_age($pet->age) . "|";
    }

    // Remove duplicates, convert into an array and reverse list order
    $ages = array_reverse(array_filter(array_unique(explode('|', $ages))));

    // For each pet age
    foreach( $ages as $age ) {

        // Create a condensed version without spaces or special characters
        $age_condensed = pet_value_condensed($age);

        // Create a list
        $age_list .= $age . ' / ' . $age_condensed . '<br>';
    }

    // Return the list
    return '<h2>Pet Ages</h2>' . $age_list;
    
}





/* =============================================================
    GENDER LIST
    List of available pet genders.
 * ============================================================= */

function get_gender_list($pets) {

    // Define Variables
    $genders = '';
    $gender_list = '';

    // Create a list available pet genders
    foreach( $pets as $pet ) {
        $genders .= get_pet_gender($pet->sex) . "|";
    }

    // Remove duplicates and convert into an array
    $genders = array_filter(array_unique(explode('|', $genders)));

    // For each pet gender
    foreach( $genders as $gender ) {

        // Create a condensed version without spaces or special characters
        $gender_condensed = pet_value_condensed($gender);

        // Create a list
        $gender_list .= $gender . ' / ' . $gender_condensed . '<br>';
    }

    // Return the list
    return '<h2>Pet Genders</h2>' . $gender_list;
    
}





/* =============================================================
    OPTIONS & SPECIAL NEEDS LIST
    List of all available special needs and options for pets.
 * ============================================================= */

function get_options_list($pets) {

    // Define Variables
    $options = '';
    $options_list = '';

    // Create a list of pet options and special needs
    foreach( $pets as $pet ) {
        foreach( $pet->options->option as $pet_option ) {
            $options .= get_pet_option($pet_option) . "|";
        }
    }

    // Remove duplicates, convert into an array and reverse list order
    $options = array_reverse(array_filter(array_unique(explode('|', $options))));

    // For each pet option
    foreach( $options as $option ) {

        // Create a condensed version without spaces or special characters
        $option_condensed = pet_value_condensed($option);

        // Create a list
        $option_list .= $option . ' / ' . $option_condensed . '<br>';
    }

    // Return the list
    return '<h2>Pet Options</h2>' . $option_list;
    
}






/* =============================================================
    PET INFORMATION
    Get and display information on each pet.
 * ============================================================= */

function get_pet_info($pets) {

    $pet_info = '';

    foreach( $pets as $pet ) {

        // Define Variables
        $pet_name = get_pet_name($pet->name);
        $pet_type = get_pet_type($pet->animal);
        $pet_size = get_pet_size($pet->size);
        $pet_age = get_pet_age($pet->age);
        $pet_gender = get_pet_gender($pet->sex);
        $pet_url = 'http://www.petfinder.com/petdetail/' . $pet->id;
        $pet_photos = get_pet_photos($pet);
        $pet_description = get_pet_description($pet->description);

        // Get list of breed(s)
        // Condensed list can be used as classes for filtering with JavaScript.
        // Approach can be used with other values, too.
        $pet_breeds = '';
        foreach( $pet->breeds->breed as $breed ) {
            $pet_breeds .= '<br>' . $breed; // Regular list
            $pet_breeds_condensed .= ' ' . pet_value_condensed($breed); // Condensed list
        }

        // Get list of all pet options
        // Like Breeds, features a condensed list, too.
        $pet_options = '';
        foreach( $pet->options->option as $option ) {
            $pet_options .= '<br>' . get_pet_option($option); // Regular list
            $pet_options_condensed .= ' ' . pet_value_condensed($option); // Condensed list
        }
        
        // Compile pet info
        $pet_info .=    '<h3>' . $pet_name . '</h3>' .
                        '<strong>Type:</strong> ' . $pet_type . '<br>' .
                        '<strong>Breed(s):</strong> ' . $pet_breeds . '<br>' .
                        '<strong>Condensed Breed(s):</strong> ' . $pet_breeds_condensed . '<br>' .
                        '<strong>Size:</strong> ' . $pet_size . '<br>' .
                        '<strong>Age:</strong> ' . $pet_age . '<br>' .
                        '<strong>Gender:</strong> ' . $pet_gender . '<br>' .
                        '<strong>Options:</strong> ' . $pet_options . '<br>' .
                        '<strong>URL:</strong> <a href="' . $pet_url . '">' . $pet_url . '</a><br>' .
                        '<strong>Photos:</strong> ' . $pet_photos . '<br>' .
                        '<strong>Description:</strong> ' . $pet_description;

    }

    // Return pet info
    return '<h2>Pet Info</h2>' . $pet_info;

}





/* =============================================================
    DISPLAY PETFINDER LISTINGS
    Compile lists and pet info, and display via a shortcode.
 * ============================================================= */

function get_petfinder_list() {

    // Access Petfinder Data
    $petfinder_data = get_petfinder_data();
    $petfinder_list = '';

    // If the API returns without errors
    if( $petfinder_data->header->status->code == "100" ) {
    
        // If there is at least one animal
        if( count( $petfinder_data->pets->pet ) > 0 ) {

            // Set $pets variable
            $pets = $petfinder_data->pets->pet;

            // Compile information that you want to include
            $petfinder_list =   get_type_list($pets) . '<br>' .
                                get_breed_list($pets) . '<br>' .
                                get_size_list($pets) . '<br>' .
                                get_age_list($pets) . '<br>' .
                                get_gender_list($pets) . '<br>' .
                                get_options_list($pets) . '<br>' .
                                get_pet_info($pets);

        }

        // If no animals are available for adoption
        else {
            $petfinder_list = '<p>We don\'t have any pets available for adoption at this time. Sorry! Please check back soon.</p>';
        }
    }

    // If error code is returned
    else {
        $petfinder_list = '<p>Petfinder is down for the moment. Please check back shortly.</p>';
    }

    return $petfinder_list;
    
}
add_shortcode('petfinder_list','get_petfinder_list');

?>
