<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>BINNO</title>

    <?php wp_head(); ?>

    <script>
        $(document).ready(function() {
            $('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevents the click event from propagating to the document body
                $(this).next('ul').toggleClass('hidden');
            });

            // Hide dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown-toggle').length) {
                    $('.dropdown-toggle').next('ul').addClass('hidden');
                }
            });
        });
    </script>

</head>

<body class="font-sans">

    <header>
        <div>
            <nav class="h-40 px-5 py-4 md:py-0 md:px-10 flex flex-col md:flex-row justify-between items-center">
                <div class="logo mb-4 md:mb-0">
                    <a href="<?php echo home_url(); ?>">
                        <img class="w-52 h-52 md:w-64 md:h-64" src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="Logo">
                    </a>
                </div>

                <!-- Navigation menu -->
                <ul class="md:flex md:justify-end">

                    <li style="margin-right: 65px;" class="mr-4">
                        <a href="home" class="blue-underline">
                            <span>Discover</span>
                        </a>
                    </li>
                    <li style="margin-right: 65px;" class="mr-4">
                        <a href="posts" class="blue-underline">
                            <span>Posts</span>
                        </a>
                    </li>
                    <li style="margin-right: 65px;" class="mr-4">
                        <a href="events" class="blue-underline">
                            <span>Events</span>
                        </a>
                    </li>
                    <li style="margin-right: 65px;" class="mr-4">
                        <a href="blogs" class="blue-underline">
                            <span class="blue-text">Blogs</span>
                        </a>
                    </li>
                    <li style="margin-right: 65px;" class="mr-4">
                        <a href="guides" class="blue-underline">
                            <span>Guides</span>
                        </a>
                    </li>
                    <li style="margin-right: 65px;" class="mr-4 relative group">
                        <a href="#" class="blue-underline dropdown-toggle">
                            <span>Profiles</span>
                            <!-- dropdown icon -->
                            <span class="dropdown-icon">▼</span>
                        </a>
                        <ul class="mt-5 absolute hidden space-y-2 bg-white border border-gray-200 rounded-md shadow-lg custom-dropdown">
                            <li><a href="<?php echo home_url('startup-enabler'); ?>" class="orange-text block py-3 px-4">Startup Enablers</a></li>
                            <li><a href="<?php echo home_url('startup-company'); ?>" class="orange-text block py-3 px-4">Startup Companies</a></li>
                        </ul>
                    </li>

                    <li class="mr-4">
                        <a href="membership" class="btn-blue">
                            <span>Become a member</span>
                        </a>
                    </li>

                </ul>

                <!-- End of navigation menu -->

            </nav>
        </div>
    </header>

</body>

</html>