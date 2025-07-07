<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 🔹 Create database tables on plugin activation.
function er_create_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $events_table = $wpdb->prefix . 'er_events';
    $attendees_table = $wpdb->prefix . 'er_attendees';

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    // Events table
    $sql1 = "CREATE TABLE IF NOT EXISTS $events_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        event_name varchar(255) NOT NULL,
        event_date date NOT NULL,
        location varchar(255) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Attendees table
    $sql2 = "CREATE TABLE IF NOT EXISTS $attendees_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        event_id mediumint(9) NOT NULL,
        attendee_name varchar(255) NOT NULL,
        attendee_email varchar(255) NOT NULL,
        registered_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (event_id) REFERENCES $events_table(id) ON DELETE CASCADE
    ) $charset_collate;";

    dbDelta( $sql1 );
    dbDelta( $sql2 );
}
register_activation_hook( ER_PLUGIN_PATH . 'event-registration.php', 'er_create_tables' );

// 🔹 Insert a new event.
function er_insert_event($event_name, $event_date, $location) {
    global $wpdb;
    $table = $wpdb->prefix . 'er_events';

    $wpdb->insert($table, [
        'event_name' => sanitize_text_field($event_name),
        'event_date' => sanitize_text_field($event_date),
        'location' => sanitize_text_field($location),
    ]);
}

// 🔹 Get all events.
function er_get_events() {
    global $wpdb;
    $table = $wpdb->prefix . 'er_events';

    return $wpdb->get_results("SELECT * FROM $table ORDER BY event_date ASC");
}

// 🔹 Register an attendee.
function er_register_attendee($event_id, $name, $email) {
    global $wpdb;
    $table = $wpdb->prefix . 'er_attendees';

    $wpdb->insert($table, [
        'event_id' => intval($event_id),
        'attendee_name' => sanitize_text_field($name),
        'attendee_email' => sanitize_email($email),
    ]);
}

// 🔹 Get attendees by event.
function er_get_attendees_by_event($event_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'er_attendees';

    return $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table WHERE event_id = %d ORDER BY registered_at DESC", $event_id)
    );
}
