jQuery(document).ready(function($) {
    const toggleButton = $('#login-toggle');
    const loginForm = $('#frontend-login-sidebar');

    // Ensure the sidebar starts hidden
    loginForm.css('right', '-350px'); // Start off-screen
    loginForm.hide(); // Hide initially

    toggleButton.on('click', function() {
        if (!loginForm.hasClass('visible')) {
            // Show the form with slide-in effect
            loginForm.addClass('visible'); 
            loginForm.show(); // Ensure it's displayed
            loginForm.animate({ right: '0' }, 300); // Slide in
            toggleButton.css('background-image', 'url(http://front-end-login-sidebar.local/wp-content/uploads/2024/11/download.jpeg)'); // Change to unlock image
            toggleButton.css("right", "300px"); // Adjust position of the toggle button
        } else {
            // Hide the form with slide-out effect
            loginForm.removeClass('visible'); 
            loginForm.animate({ right: '-350px' }, 300, function() {
                loginForm.hide(); // Hide after animation is done
            }); // Slide out
            toggleButton.css('background-image', 'url(http://front-end-login-sidebar.local/wp-content/uploads/2024/11/download.png)'); // Change to lock image
            toggleButton.css("right", "0px"); // Reset position of the toggle button
        }
    });
});
