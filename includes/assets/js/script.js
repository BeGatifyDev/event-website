// assets/js/script.js

/**
 * Event Registration Plugin - Main JS
 * Author: Oluwafemi Oluwatobi Best
 * Description: Handles interactivity for forms, dynamic UI actions, and plugin admin enhancements.
 */

(function($) {
    "use strict";

    $(document).ready(function() {
        // ✅ Example: Confirmation before deleting an event
        $('.er-delete-button').on('click', function(e) {
            if (!confirm("Are you sure you want to delete this event?")) {
                e.preventDefault();
            }
        });

        // ✅ Example: Highlight active navigation if added later
        $('.er-admin-menu a').each(function() {
            if (this.href === window.location.href) {
                $(this).addClass('active');
            }
        });

        // ✅ Example: Future functionality placeholder
        // $('.some-class').on('click', function() {
        //     // Perform an action
        // });

        // ✅ Console greeting for developer check
        console.log("Event Registration Plugin JS Loaded ✅");
    });

})(jQuery);
