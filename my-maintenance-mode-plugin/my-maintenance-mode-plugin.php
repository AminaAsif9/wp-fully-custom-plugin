<?php
/** 
*Plugin Name: my maintenance mode plugin
*Plugin URL: http://wordpress.org
*Description: This is a maintenance mode/ Under construction plugin for WordPress.
*Author: Amina
*Version: 1.0.0
*/

// Enqueue scripts and styles
function smm_enqueue_scripts() {
    wp_enqueue_script('smm-customize-preview', plugins_url('/customizer.js', __FILE__), array('customize-preview'), null, true);
    wp_enqueue_style('smm-styles', plugins_url('/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'smm_enqueue_scripts');

// Add Customizer settings
function smm_customize_register($wp_customize) {
    // Add section for maintenance mode settings
    $wp_customize->add_section('smm_settings_section', array(
        'title'    => __('Maintenance Mode Settings', 'smm'),
        'priority' => 30,
    ));

    // Add setting for mobile background image
    $wp_customize->add_setting('smm_mobile_bg_image');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'smm_mobile_bg_image_control', array(
        'label'    => __('Add Mobile Background Image', 'smm'),
        'section'  => 'smm_settings_section',
        'settings' => 'smm_mobile_bg_image',
    )));

    // Add setting for YouTube video background
    $wp_customize->add_setting('smm_youtube_bg_video', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('smm_youtube_bg_video_control', array(
        'label'    => __('YouTube Video Background URL', 'smm'),
        'section'  => 'smm_settings_section',
        'settings' => 'smm_youtube_bg_video',
        'type'     => 'url',
    ));

    // Add setting for enabling frontend login
    // Add setting for enabling frontend login
    $wp_customize->add_setting('smm_enable_login', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('smm_enable_login_control', array(
        'label'    => __('Enable Frontend Login', 'smm'),
        'section'  => 'smm_settings_section',
        'settings' => 'smm_enable_login',
        'type'     => 'checkbox',
    ));

    // Add color settings for login sidebar background and text
    $wp_customize->add_setting('smm_login_bg_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'smm_login_bg_color_control', array(
        'label'    => __('Login Form Background Color', 'smm'),
        'section'  => 'smm_settings_section',
        'settings' => 'smm_login_bg_color',
    )));

    $wp_customize->add_setting('smm_login_text_color', array(
        'default' => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'smm_login_text_color_control', array(
        'label'    => __('Login Form Text Color', 'smm'),
        'section'  => 'smm_settings_section',
        'settings' => 'smm_login_text_color',
    )));
     
}
add_action('customize_register', 'smm_customize_register');

// Function to display maintenance mode page
function smm_maintenance_mode() {
    if (!is_admin() && !defined('DOING_AJAX')) {
        header('HTTP/1.1 503 Service Unavailable');
        header('Status: 503 Service Unavailable');
        header('Retry-After: 3600');

        $mobile_bg_image = get_theme_mod('smm_mobile_bg_image');
        $youtube_bg_video = get_theme_mod('smm_youtube_bg_video');
        $enable_login = get_theme_mod('smm_enable_login');
        $login_bg_color = get_theme_mod('smm_login_bg_color', '#ffffff');
        $login_text_color = get_theme_mod('smm_login_text_color', '#000000');

        $default_bg_image = plugins_url('assets/img/coming-soon.png', __FILE__);

        echo '<div id="smm-maintenance-mode" style="position: relative; width: 100%; height: 100vh; overflow: hidden; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; font-family: Arial, sans-serif; color: white;">';

        if ($youtube_bg_video) {
            echo '<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: -1;" src="https://www.youtube.com/embed/' . esc_attr(extract_youtube_id($youtube_bg_video)) . '?autoplay=1&mute=1&loop=1&playlist=' . esc_attr(extract_youtube_id($youtube_bg_video)) . '&controls=0&showinfo=0&autohide=1&modestbranding=1" frameborder="0" allow="autoplay; loop; muted" allowfullscreen></iframe>';
        } elseif ($mobile_bg_image) {
            echo '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; background-image: url(' . esc_url($mobile_bg_image) . '); background-size: cover; background-position: center;"></div>';
        } else {
            echo '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; background-image: url(' . esc_url($default_bg_image) . '); background-size: cover; background-position: center;"></div>';
        }

        echo '<h1 style="font-size: 46px; font-weight: bold; margin: 0;">Under Construction</h1>';
        echo '<h2 style="font-size: 22px; margin-top: 10px;">We will be back soon.</h2>';

        // Login Sidebar
        if ($enable_login) {
            echo '<style>
            #login-sidebar {
                position: fixed;
                top: 0;
                right: -220px; /* Positioning starts off the screen */
                width: 200px; /* Set sidebar width to 200px */
                height: 100vh;
                background-color: ' . esc_attr($login_bg_color) . ';
                color: ' . esc_attr($login_text_color) . ';
                padding: 20px;
                box-shadow: -5px 0 15px rgba(0, 0, 0, 0.5);
                z-index: 1000;
                transition: right 0.3s ease;
            }

            #toggle-login {
                position: fixed;
                top: 30%;
                right: 10px; /* Position just outside the sidebar */
                transform: translateY(-50%); /* Center vertically */
                cursor: pointer;
                z-index: 1001;
                width: 40px; /* Keep the size consistent */
                height: 40px; /* Keep the size consistent */
                transition: right 0.3s ease;
            }

            #login-sidebar label {
                font-weight: bold;
            }

            #login-sidebar input {
                 width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                 border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
                }

            #login-sidebar button {
                padding: 10px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                width: 100%; /* Full width */
                background-color: ' . esc_attr($login_text_color) . ';
                color: ' . esc_attr($login_bg_color) . ';
            }
                span.psw {
                float: right;
                font-size: 14px;
                 cursor: pointer;
                }

            @media (max-width: 600px) {
                #login-sidebar {
                    width: 65%; /* Adjust width for small screens */
                }
            }
            </style>';

            // Sidebar Toggle Button HTML
            echo '<img id="toggle-login" src="http://localhost/wp-hackathon/wordpress/wp-content/uploads/2024/11/lock.png" onclick="toggleSidebar()">';

            // Sidebar and Toggle Button Script
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var sidebar = document.getElementById("login-sidebar");
                    sidebar.style.right = "-240px"; // Initialize sidebar position

                    var toggleButton = document.getElementById("toggle-login");
                    toggleButton.style.right = "10px"; // Initialize button position
                });

                function toggleSidebar() {
                    var sidebar = document.getElementById("login-sidebar");
                    var toggleButton = document.getElementById("toggle-login");

                    if (sidebar.style.right === "-240px") {
                        sidebar.style.right = "0";
                        toggleButton.style.right = "240px"; // Adjust to keep the button visible
                        toggleButton.src = "http://localhost/wp-hackathon/wordpress/wp-content/uploads/2024/11/unlock.png";
                    } else {
                        sidebar.style.right = "-240px";
                        toggleButton.style.right = "10px"; // Move back to outside the sidebar
                        toggleButton.src = "http://localhost/wp-hackathon/wordpress/wp-content/uploads/2024/11/lock.png";
                    }
                }
            </script>';

            // Sidebar HTML Content
            echo '<div id="login-sidebar">
                <h2>Login Form</h2>
                <form method="post" action="' . esc_url(wp_login_url()) . '">
                    <label for="username">Username</label>
                    <input type="text" name="log" id="username" placeholder="Enter Username">
                    <label for="password">Password</label>
                    <input type="password" name="pwd" id="password" placeholder="Enter Password">
                    <button type="submit" style="font-weight:bold;">Login</button>
                <label style="font-weight:normal; display: flex; align-items: center;    font-size: 14px;">
                <input type="checkbox" checked="checked" name="remember" style=" margin: 13px -88px;"> 
                Remember me </label>
                <span class="psw">Forgot password?</span>
                
                    </form>
            </div>';
            
            
        }

        exit;
    }
    
}









add_action('template_redirect', 'smm_maintenance_mode');

// Helper function to extract YouTube video ID from URL
function extract_youtube_id($url) {
    parse_str(parse_url($url, PHP_URL_QUERY), $query);
    return $query['v'] ?? '';
}
?>
