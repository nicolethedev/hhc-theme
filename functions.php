<?php

// Enqueue stylesheet
function enqueue_styles() {
    wp_enqueue_style('hhc', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'enqueue_styles');

// Add custom login logo
function my_login_logo() { ?>
  <style type="text/css">

      body.login {
          background-color: #342D2C; 
      } 

      #login h1 a {
          background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/site-login-logo.png');
          background-size: contain;
          background-repeat: no-repeat;
          width: 250px;   
          height: 250px;   
          display: block;
          margin: 0 auto 30px auto;
      }
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );


// Change the login logo URL to the home page
function my_login_logo_url() {
  return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
  return 'Headhunters Canada';
}
add_filter( 'login_headertext', 'my_login_logo_url_title' );

// Removing all dashboard widgets, except for the custom one.
function wporg_remove_all_dashboard_metaboxes() {
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'health_check_status', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('otter_form_submissions', 'dashboard', 'normal');
}
add_action( 'wp_dashboard_setup', 'wporg_remove_all_dashboard_metaboxes' );

// Display a welcom message at the top of the dashboard
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

function my_custom_dashboard_widgets() {
    wp_add_dashboard_widget(
        'custom_welcome_widget',
        'Welcome to Headhunters Canada',
        'custom_dashboard_welcome'
    );

    // Move your widget to the top
    global $wp_meta_boxes;
    $dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
    $my_widget = array('custom_welcome_widget' => $dashboard['custom_welcome_widget']);
    unset($dashboard['custom_welcome_widget']);
    $wp_meta_boxes['dashboard']['normal']['core'] = $my_widget + $dashboard;
}

function custom_dashboard_welcome() {
    echo '<p>Welcome to your dashboard! If you have any questions, please feel free to reach out! - Nicole</p>';
}

// Remove admin menu links for non-Administrator accounts
function fwd_remove_admin_links() {
	if ( !current_user_can( 'manage_options' ) ) {
		remove_menu_page( 'edit.php' );           // Remove Posts link
    		remove_menu_page( 'edit-comments.php' );  // Remove Comments link
	}
}
add_action( 'admin_menu', 'fwd_remove_admin_links' );

