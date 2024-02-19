$(document).ready(function() {
    function activateNavLinkBasedOnUrl() {
        // Get the current URL's last part. For filenames like 'dashboard.php', 'orders.php', etc.
        var currentPath = window.location.pathname.split('/').pop();

        // Flag to check if the profile link is activated
        var isProfileActive = false;

        // Iterate over each link in the navbar
        $('.navbar-nav .nav-item a').each(function() {
            var linkHref = $(this).attr('href');

            // Since hrefs are like './dashboard.php', we remove './' to match the currentPath
            var formattedLinkHref = linkHref.replace('./', '');

            // Check if the link's href matches the last part of the current URL
            if(formattedLinkHref === currentPath) {
                // Add 'active' class to the matching link
                $(this).addClass('activenav');

                // If the current page is 'profile.php', set the flag to true
                if(formattedLinkHref === 'profile.php') {
                    isProfileActive = true;
                }
            }
        });

        // If the current page is 'profile.php', add 'active' class to the Your Profile dropdown toggle
        if(isProfileActive) {
            $('.navbar-nav .nav-item .dropdown-toggle').addClass('activenav');
        }
    }

    // Call the function to activate the nav link
    activateNavLinkBasedOnUrl();
});
