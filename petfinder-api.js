/* =============================================================
 * houdini.js v1.0.0
 * A simple collapse and expand widget.
 * Script by Chris Ferdinandi - http://cferdinandi.github.com/houdini/
 * Licensed under WTFPL - http://www.wtfpl.net/
 * ============================================================= */

$(function () {
    $('.collapse-toggle').click(function(e) { // When a link or button with the .collapse-toggle class is clicked
        e.preventDefault(); // Prevent the default action from occurring

        // Set Variables
        var dataID = $(this).attr('data-target'); // dataID is the data-target value
        var hrefID = $(this).attr('href'); // hrefID is the href value

        // Toggle the Active Class
        if (dataID)  { // If the clicked element has a data-target
            $(dataID).toggleClass('active'); // Add or remove the .active class from the element whose ID matches the data-target value
        }
        else { // Otherwise
            $(hrefID).toggleClass('active'); // Add or remove the .active class from the element whose ID matches the href value
        }
    });
});





/* =============================================================
 * modal.js v1.0.0
 * A kinda-sorta-not-really modal window thingy.
 * Script by Chris Ferdinandi - http://gomakethings.com
 * Licensed under WTFPL - http://www.wtfpl.net
 * ============================================================= */

$(function () {
    $('.modal').click(function(e) {
        e.preventDefault();
        var dataID = $(this).attr('data-target');
        $('.modal-menu').not(dataID).removeClass('active');
        $(dataID).toggleClass('active');
    });
    $('.modal-close').click(function(e) {
        e.preventDefault();
        $('.modal-menu').removeClass('active');
    });
});





/* =============================================================
 * petfinder-sort.js v1.0.0
 * Filter PetFinder results by a variety of categories.
 * Script by Chris Ferdinandi - http://gomakethings.com
 * Licensed under WTFPL - http://www.wtfpl.net
 * ============================================================= */

$(function () {
    function petfinderSort() {
        $('.pf').hide();
        $('.pf-breeds').each(function () {
            var sortBreed = $(this).attr('data-target');
            if ($(this).prop('checked')) {
                $(sortBreed).show();
            }
        });
        $('.pf-sort').each(function () {
            var sortTarget = $(this).attr('data-target');
            if ($(this).prop('checked')) { }
            else {
                $(sortTarget).hide();
            }
        });
    }
    $('.pf-toggle-all').click(function() {
        var toggleTarget = $(this).attr('data-target');
        if($(this).prop('checked')) {
            $(toggleTarget).prop('checked',true);
            petfinderSort();
        }
        else {
            $(toggleTarget).prop('checked',false);
            petfinderSort();
        }
    });
    $('.pf-sort, .pf-breeds').click(petfinderSort);
});





/* =============================================================
 * js-accessibility.js v1.0.0
 * Adds .js class to <body> for progressive enhancement.
 * Script by Chris Ferdinandi - http://cferdinandi.github.com/js-accessibility/
 * Licensed under WTFPL - http://www.wtfpl.net
 * ============================================================= */

$(function () {
    $('body').addClass('js'); // On page load, add the .js class to the <body> element.
});
