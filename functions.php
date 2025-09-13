<?php 

/** enqueue css, js, icons scripts */
add_action('wp_enqueue_scripts', 'my_theme_enqueue_assets');
function my_theme_enqueue_assets() {
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), null, 'all');
    wp_enqueue_style('custom-css', get_template_directory_uri().'/assets/css/custom.css',);
    wp_enqueue_script('jquery');
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', array('jquery'), '1.11.3', 'all');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', array('jquery'), '6.7.2', 'all');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true);
    wp_enqueue_script('custom-js', get_template_directory_uri().'/assets/js/custom.js', array(), time(), true);
}

/** including post-types */
// include_once(get_template_directory().'/post-types/'.'Services.php');


/** theme support */
add_theme_support('post-thumbnails');


/** SVG support */
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }
add_filter('upload_mimes', 'cc_mime_types');


/** create role - user */
function add_custom_user_role() {
    add_role(
        'user', // Unique identifier (slug) for the role
        'User', // Display name of the role
        array(
            'read'           => true,  // Can read posts and pages
        )
    );
}
add_action( 'init', 'add_custom_user_role' );


// AJAX handler for registration
function custom_user_registration() {
    if ( empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) ) {
        wp_send_json_error(['message' => 'All fields are required.']);
    }

    $userdata = array(
        'first_name' => sanitize_text_field($_POST['firstName']),
        'last_name'  => sanitize_text_field($_POST['lastName']),
        'user_login' => sanitize_user($_POST['username']),
        'user_email' => sanitize_email($_POST['email']),
        'user_pass'  => $_POST['password'],
        'role'       => 'user',
    );

    $user_id = wp_insert_user($userdata);

    if ( is_wp_error($user_id) ) {
        wp_send_json_error(['message' => $user_id->get_error_message()]);
    }

    if ( ! empty($_FILES['profile_image']['name']) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $attachment_id = media_handle_upload('profile_image', 0);

        if ( is_wp_error($attachment_id) ) {
            wp_send_json_error(['message' => 'Image upload failed: ' . $attachment_id->get_error_message()]);
        } else {
            update_user_meta($user_id, 'custom_profile_image', $attachment_id);
        }
    }

    wp_send_json_success(['message' => 'Registration successful!']);
}
add_action('wp_ajax_nopriv_custom_user_registration', 'custom_user_registration');
add_action('wp_ajax_custom_user_registration', 'custom_user_registration');


function custom_user_avatar($avatar, $id_or_email, $size, $default, $alt) {
    $user = false;

    if ( is_numeric($id_or_email) ) {
        $user = get_user_by('id', $id_or_email);
    } elseif ( is_object($id_or_email) && ! empty($id_or_email->user_id) ) {
        $user = get_user_by('id', $id_or_email->user_id);
    } elseif ( is_string($id_or_email) ) {
        $user = get_user_by('email', $id_or_email);
    }

    if ( $user ) {
        $avatar_id = get_user_meta($user->ID, 'custom_profile_image', true);
        if ( $avatar_id ) {
            $avatar_img = wp_get_attachment_image_src($avatar_id, [$size, $size]);
            if ( $avatar_img ) {
                return '<img src="' . esc_url($avatar_img[0]) . '" width="' . esc_attr($size) . '" height="' . esc_attr($size) . '" alt="' . esc_attr($alt) . '" class="custom-avatar" />';
            }
        }
    }

    return $avatar;
}
add_filter('get_avatar', 'custom_user_avatar', 10, 5);


// AJAX Login handler
function custom_user_login() {
    $email    = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $user = get_user_by('email', $email);
    if ( ! $user ) {
        wp_send_json_error(['message' => 'Email does not exist.']);
    }

    if ( ! wp_check_password($password, $user->user_pass, $user->ID) ) {
        wp_send_json_error(['message' => 'Invalid password.']);
    }

    $creds = array(
        'user_login'    => $user->user_login,
        'user_password' => $password,
        'remember'      => true,
    );

    $logged_in_user = wp_signon($creds, false);

    if ( is_wp_error($logged_in_user) ) {
        wp_send_json_error(['message' => 'Login failed.']);
    } else {
        wp_send_json_success(['message' => 'Login successful!']);
    }
}
add_action('wp_ajax_nopriv_custom_user_login', 'custom_user_login');
add_action('wp_ajax_custom_user_login', 'custom_user_login');

?>