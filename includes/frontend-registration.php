<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include functions file if not already included
require_once ER_PLUGIN_PATH . 'includes/functions.php';

/**
 * Frontend Event Registration Shortcode
 */
function er_frontend_registration_form() {
    ob_start();

    // Handle form submission
    if (isset($_POST['er_frontend_register'])) {
        $event_id = intval($_POST['event_id']);
        $attendee_name = sanitize_text_field($_POST['attendee_name']);
        $attendee_email = sanitize_email($_POST['attendee_email']);

        if ($event_id && $attendee_name && $attendee_email) {
            er_register_attendee($event_id, $attendee_name, $attendee_email);
            echo '<p class="er-success">Thank you for registering!</p>';
        } else {
            echo '<p class="er-error">Please fill in all fields.</p>';
        }
    }

    // Get events
    $events = er_get_events();
    ?>
    <div class="er-registration-form">
        <h2>Register for an Event</h2>
        <form method="post">
            <p>
                <label for="event_id">Select Event</label><br>
                <select name="event_id" required>
                    <option value="">Select an event</option>
                    <?php if ($events): ?>
                        <?php foreach ($events as $event): ?>
                            <option value="<?php echo esc_attr($event->id); ?>">
                                <?php echo esc_html($event->event_name . ' (' . $event->event_date . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No events available</option>
                    <?php endif; ?>
                </select>
            </p>
            <p>
                <label for="attendee_name">Your Name</label><br>
                <input type="text" name="attendee_name" required>
            </p>
            <p>
                <label for="attendee_email">Your Email</label><br>
                <input type="email" name="attendee_email" required>
            </p>
            <p>
                <input type="submit" name="er_frontend_register" value="Register">
            </p>
        </form>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('event_registration_form', 'er_frontend_registration_form');
