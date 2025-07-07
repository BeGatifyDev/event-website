<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include functions file if not already included
require_once ER_PLUGIN_PATH . 'includes/functions.php';

/**
 * Add Event Registration menu to admin dashboard
 */
function er_add_admin_menu() {
    add_menu_page(
        'Event Registration',           
        'Event Registration',           
        'manage_options',               
        'event-registration',           
        'er_admin_page_content',        
        'dashicons-calendar-alt',       
        6                               
    );
}
add_action('admin_menu', 'er_add_admin_menu');

/**
 * Render the admin page content
 */
function er_admin_page_content() {
    ?>
    <div class="wrap">
        <h1>Event Registration Dashboard</h1>

        <?php
        // Handle event creation form submission
        if (isset($_POST['er_create_event'])) {
            $event_name = sanitize_text_field($_POST['event_name']);
            $event_date = sanitize_text_field($_POST['event_date']);
            $location = sanitize_text_field($_POST['location']);
            er_insert_event($event_name, $event_date, $location);
            echo '<div class="updated"><p>Event created successfully!</p></div>';
        }

        // Handle attendee registration form submission
        if (isset($_POST['er_register_attendee'])) {
            $event_id = intval($_POST['event_id']);
            $attendee_name = sanitize_text_field($_POST['attendee_name']);
            $attendee_email = sanitize_email($_POST['attendee_email']);
            er_register_attendee($event_id, $attendee_name, $attendee_email);
            echo '<div class="updated"><p>Attendee registered successfully!</p></div>';
        }
        ?>

        <!-- ✅ Create Event Form -->
        <h2>Create New Event</h2>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="event_name">Event Name</label></th>
                    <td><input type="text" name="event_name" required></td>
                </tr>
                <tr>
                    <th><label for="event_date">Event Date</label></th>
                    <td><input type="date" name="event_date" required></td>
                </tr>
                <tr>
                    <th><label for="location">Location</label></th>
                    <td><input type="text" name="location" required></td>
                </tr>
            </table>
            <p><input type="submit" name="er_create_event" class="button button-primary" value="Create Event"></p>
        </form>

        <!-- ✅ List Events -->
        <h2>All Events</h2>
        <table class="widefat">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Attendees</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $events = er_get_events();
                if ($events) {
                    foreach ($events as $event) {
                        $attendees = er_get_attendees_by_event($event->id);
                        ?>
                        <tr>
                            <td><?php echo esc_html($event->id); ?></td>
                            <td><?php echo esc_html($event->event_name); ?></td>
                            <td><?php echo esc_html($event->event_date); ?></td>
                            <td><?php echo esc_html($event->location); ?></td>
                            <td><?php echo count($attendees); ?></td>
                        </tr>

                        <!-- ✅ List Attendees per Event -->
                        <?php if ($attendees): ?>
                        <tr>
                            <td colspan="5">
                                <strong>Attendees:</strong>
                                <ul>
                                    <?php foreach ($attendees as $a): ?>
                                        <li><?php echo esc_html($a->attendee_name); ?> (<?php echo esc_html($a->attendee_email); ?>)</li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="5">No events created yet.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- ✅ Register Attendee -->
        <h2>Register Attendee</h2>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="event_id">Select Event</label></th>
                    <td>
                        <select name="event_id" required>
                            <option value="">Select an event</option>
                            <?php foreach ($events as $event): ?>
                                <option value="<?php echo esc_attr($event->id); ?>"><?php echo esc_html($event->event_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="attendee_name">Attendee Name</label></th>
                    <td><input type="text" name="attendee_name" required></td>
                </tr>
                <tr>
                    <th><label for="attendee_email">Attendee Email</label></th>
                    <td><input type="email" name="attendee_email" required></td>
                </tr>
            </table>
            <p><input type="submit" name="er_register_attendee" class="button button-primary" value="Register Attendee"></p>
        </form>

    </div>
    <?php
}
