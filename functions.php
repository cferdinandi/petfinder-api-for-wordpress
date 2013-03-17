<?php // Don't delete this line or you'll break WordPress

// If your theme does not have a functions.php file, add this file to your theme.
// If functions.php already exists, copy-and-paste the text below into it.


// START COPYING HERE.

/* ======================================================================
 * PetFinder-API.php
 * Add PetFinder listings to the site.
 * Script by Chris Ferdinandi - http://gomakethings.com
 * Adapted from the Petfinder Listings plugin - http://wordpress.org/extend/plugins/petfinder-listings/
 * ====================================================================== */

// Create Petfinder Shelter List function
function petf_shelter_list() {

    // API Attributes
    $api_key = 'xxxx'; // Change to your API key
    $count = '20'; // Number of animals to return. Set to higher than total # of animals in your shelter.
    $shelter_id = 'xxxx'; // Change to your shelter ID
    $url = "http://api.petfinder.com/shelter.getPets?key=" . $api_key . "&count=" . $count . "&id=" . $shelter_id . "&status=A&output=full"; // API call

    // Request shelter data
    $xml = @simplexml_load_file( $url );
    // If data not available, don't display errors on page
    if ($xml === false) {}


    // If the API returns without errors
    if( $xml->header->status->code == "100"){
        $output_buffer = "";
        // If there is at least one animal
        if( count( $xml->pets->pet ) > 0 ){

            // Intro text. Filter & Sort Results.
            // Change text to suit your needs.
            // Different text shown based on whether JS (and thus filters) supported.
            $output_buffer .= "<div class='hide-no-js'><p>Use the filters to narrow your search, and click on a pet to learn more.</p></div>
                              <div class='hide-js'><p>Click on a pet to learn more.</p></div>
                              <p><button class='collapse-toggle' data-target='#sort-options'>Filter Results</button></p>
                              <div class='collapse hide-no-js' id='sort-options'>
                                  <form>
                                    <div class='row'>
                                        <div class='grid-img'>
                                            <h3>Age</h3>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.Puppy' checked>
                                                Puppies
                                            </label>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.Young' checked>
                                                Young
                                            </label>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.Adult' checked>
                                                Adult
                                            </label>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.Senior' checked>
                                                Senior
                                            </label>
                                        </div>
                                        <div class='grid-img'>
                                            <h3>Size</h3>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.Small' checked>
                                                Small
                                            </label>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.Medium' checked>
                                                Medium
                                            </label>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.Large' checked>
                                                Large
                                            </label>
                                        </div>
                                        <div class='grid-img'>
                                            <h3>Gender</h3>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.Male' checked>
                                                Male
                                            </label>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.Female' checked>
                                                Female
                                            </label>
                                        </div>
                                        <div class='grid-img'>
                                            <h3>Special Requirements</h3>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.noDogs' checked>
                                                No Other Dogs
                                            </label>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.noCats' checked>
                                                No Cats
                                            </label>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.noKids' checked>
                                                No Kids
                                            </label>
                                            <label>
                                                <input type='checkbox' class='pf-sort' data-target='.specialNeeds' checked>
                                                Special Needs
                                            </label>
                                        </div>
                                    </div>
                                    <h3>Breeds</h3>
                                    <label>
                                        <input type='checkbox' class='pf-toggle-all' data-target='.pf-breeds' checked>
                                        Select/Unselect All
                                    </label>
                                    <div class='row'>";
                                    // Get a list of breeds
                                    foreach( $xml->pets->pet as $pet ) {
                                        foreach( $pet->breeds->breed as $this_breed ) {
                                            $breed_list .= $this_breed . ",";
                                        }
                                    }
                                    
                                    // Breeds as an array
                                    $breed_list = array_filter(array_unique(explode(',', $breed_list)));
                                    asort($breed_list);

                                    // Split breed list in half
                                    $breed_count = count($breed_list);
                                    $breed_list1 = array_slice($breed_list, 0, $breed_count / 2);
                                    $breed_list2 = array_slice($breed_list, $breed_count / 2);

                                    // Display breed filters
                                    foreach( $breed_list1 as $breed ) {
                                        // Remove weird characters and spaces
                                        $breed_concat_values = array('(' => '', ')' => '', '  ' => '-', ' ' => '-');
                                        $breed_concat = strtr($breed, $breed_concat_values);
                                        $output_buffer .= "<div class='grid-3'>
                                                            <label>
                                                                <input type='checkbox' class='pf-breeds' data-target='." . $breed_concat . "' checked>" .
                                                                $breed . "
                                                            </label>
                                                          </div>";
                                    }
                                    foreach( $breed_list2 as $breed ) {
                                        // Remove weird characters and spaces
                                        $breed_concat_values = array('(' => '', ')' => '', '  ' => '-', ' ' => '-');
                                        $breed_concat = strtr($breed, $breed_concat_values);
                                        $output_buffer .= "<div class='grid-3'>
                                                            <label>
                                                                <input type='checkbox' class='pf-breeds' data-target='." . $breed_concat . "' checked>" .
                                                                $breed . "
                                                            </label>
                                                          </div>";
                                    }
            $output_buffer .=       "</div>
                                </form>
                            </div>
                            <div class='row'>";

            // Display animals available for adoption
            foreach( $xml->pets->pet as $pet ) {

                // Variables
                $pet_name = $pet->name;
                $pet_name = array_shift(explode('-', $pet_name)); // Remove '-' from animal names
                $pet_name = array_shift(explode('(', $pet_name)); // Remove '(...)' from animal names
                $pet_name = strtolower($pet_name); // Transform names to lowercase
                $pet_name = ucwords($pet_name); // Capitalize the first letter of each name
                $pet_url = "http://www.petfinder.com/petdetail/" . $pet->id; // Create a link to the animals Petfinder profile

                // Give size attribute human-readable names
                switch ($pet->size){
                    case "L":
	                    $pet_size = "Large";
	                    break;
                    case "M":
	                    $pet_size = "Medium";
	                    break;
                    case "S":
	                    $pet_size = "Small";
	                    break;
                    default:
	                    $pet_size = "Not known";
	                    break;
                }

                // Change age attribute names
                switch ($pet->age){
                    case "Baby":
	                    $pet_age = "Puppy";
	                    break;
                    case "Young":
	                    $pet_age = "Young";
	                    break;
                    case "Adult":
	                    $pet_age = "Adult";
	                    break;
                    case "Senior":
	                    $pet_age = "Senior";
	                    break;
                    default:
	                    $pet_age = "Not known";
	                    break;
                }

                // Give pet gender attribute human-readable names
                $pet_sex = (($pet->sex == "M") ? "Male" : "Female");

                // Get url for WordPress theme directory
                $theme_url = get_template_directory_uri();

                // Output to Display
                
                                    // Add classes to each animal for age, gender, size and breed.
                                    // Used for filtering
                $output_buffer .=   "<div class='grid-img pf "
                                        . $pet_age . " " . $pet_sex . " " . $pet_size;
                                        foreach( $pet->options->option as $pet_option ) {
                                            $output_buffer .= " " . $pet_option;
                                        }
                                        foreach( $pet->breeds->breed as $this_breed ) {
                                            //$breed_concat = array(' ' => '-');
                                            $breed_concat = array('(' => '', ')' => '', ' ' => '-');
                                            $breed_class = strtr($this_breed, $breed_concat);
                                            $output_buffer .= " " . $breed_class;
                                        }
                                        
                                        // Display content: link to Petfinder, modal toggle, image and name of the animal,
                                        // and modal content, including description.
                $output_buffer .=   "'>
                                        <a class='modal' data-target='#modal-" . $pet->id . "' target='_blank' href='" . $pet_url . "'>";
                                            // If a photo of the animal exists, show it.
                                            if(count($pet->media->photos) > 0){
                                                $output_buffer .= "<img class='pf-img' alt='Photo of " . $pet_name . "' src='" . $pet->media->photos->photo . "'>";
                                            }
                                            // Otherwise, use the default photo.
                                            else {
                                                $output_buffer .= "<img class='pf-img' alt='No photo available yet for " . $pet_name . "' src='" . $theme_url . "/img/nophoto.jpg'>";
                                            }

                $output_buffer .=           "<h3>" . $pet_name . "</h3>
                                        </a>
                                        <div class='modal-menu' id='modal-" . $pet->id . "'>
                                            <div class='container'>
                                                <div class='group'>
                                                    <a class='close modal-close' href='#'>×</a>
                                                </div>
                                                <div class='row'>
                                                    <div class='grid-2'>
                                                        <p>
                                                            <strong>Size:</strong> ". $pet_size . "<br>
                                                            <strong>Age:</strong> " . $pet_age ."<br>
                                                            <strong>Gender:</strong> " . $pet_sex ."
                                                        </p>
                                                        <p>
                                                        <strong>Breed(s)</strong>";
                                                        foreach( $pet->breeds->breed as $this_breed ) {
                                                            $output_buffer .= "<br>" . $this_breed;
                                                        }
                $output_buffer .=                       "</p>
                                                        <p>
                                                            <strong>Special Requirements</strong>";
                                                            $icons = "";
                                                            foreach( $pet->options->option as $option ){
                                                                switch($option){
                                                                    case "noCats":
                                                                        $icons .= "<span class='pf-icon'>Cats</span>";
                                                                        break;
                                                                    case "noDogs":
                                                                        $icons .= "<span class='pf-icon'>Dogs</span>";
                                                                        break;
                                                                    case "noKids":
                                                                        $icons .= "<span class='pf-icon'>Kids</span>";
                                                                        break;
                                                                    case "specialNeeds":
                                                                        $special .= "Special Needs";
                                                                    case "altered":
                                                                        $output_buffer .= "";
                                                                        break;
                                                                    case "hasShots":
                                                                        $output_buffer .= "";
                                                                        break;
                                                                    case "housebroken":
                                                                        $output_buffer .= "";
                                                                        break;
                                                                }
                                                            }
                                                            if($icons != ""){
                                                                $output_buffer .= "<br>No " . $icons;
                                                            }
                                                            if($special != ""){
                                                                $output_buffer .= "<br>" . $special;
                                                            }
                                                            if($icons == "" && $special == ""){
                                                                $output_buffer .= "<br>None";
                                                            }
                $output_buffer .=                       "</p>
                                                        <p>
                                                            <a target='_blank' href='" . $pet_url . "'>See more photos on PetFinder...</a>
                                                        </p>
                                                    </div>
                                                    <div class='grid-4'>
                                                        <h3>About " . $pet_name . "</h3>" .
                                                        $pet->description .
                                                        "<button class='btn modal-close'>Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>" . 
                                        $pet_size . ", " . $pet_age . ", " . $pet_sex;
                                        $icons = "";
                                        foreach( $pet->options->option as $option ){
                                            switch($option){
                                                case "noCats":
                                                    $icons .= "<span class='pf-icon'>Cats</span>";
                                                    break;
                                                case "noDogs":
                                                    $icons .= "<span class='pf-icon'>Dogs</span>";
                                                    break;
                                                case "noKids":
                                                    $icons .= "<span class='pf-icon'>Kids</span>";
                                                    break;
                                                case "specialNeeds":
                                                    $special .= "Special Needs";
                                                case "altered":
                                                    $output_buffer .= "";
                                                    break;
                                                case "hasShots":
                                                    $output_buffer .= "";
                                                    break;
                                                case "housebroken":
                                                    $output_buffer .= "";
                                                    break;
                                            }
                                        }
                                        if($icons != ""){
                                            $output_buffer .= "<br>No " . $icons;
                                        }
                                        if($special != ""){
                                            $output_buffer .= "<br>" . $special;
                                        }
                $output_buffer .=   "</div>";
            }
            $output_buffer .= "</div>";
        }

        // If no animals are available for adoption
        else{
            $output_buffer .= "<p>We don't have any dogs available for adoption at this time. Sorry! Please check back soon.</p>";
        }
    }

    // If error code is returned
    else{
        $output_buffer = "<p>Petfinder is down for the moment. Please check back shortly.</p>";
    }


    // Remove inline styling
    $output_buffer = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $output_buffer);

    // Remove font tag
    $output_buffer = preg_replace('/<font[^>]+>/', '', $output_buffer);

    // Remove empty tags
    $petlist_cleaners = array('<p></p>' => '', '<p> </p>' => '', '<p>&nbsp;</p>' => '', '<span></span>' => '', '<span> </span>' => '', '<span>&nbsp;</span>' => '', '<span>' => '', '</span>' => '', '<font>' => '', '</font>' => '');
    $output_buffer = strtr($output_buffer, $petlist_cleaners);
    

    // Display content
    return $output_buffer;
    
}

// Create shortcode for use in WordPress text editor
add_shortcode('shelter_list','petf_shelter_list');


// STOP COPYING HERE.


// Don't delete this line or you'll break WordPress ?>
