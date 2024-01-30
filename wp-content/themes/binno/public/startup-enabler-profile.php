<?php
get_header('profiles');
/*
Template Name: Startup Enabler Profile
*/

function fetch_api_data($api_url)
{
    // Make the request
    $response = wp_remote_get($api_url, array('sslverify' => false, 'sslversion' => CURL_SSLVERSION_TLSv1_2));

    // Check for errors
    if (is_wp_error($response)) {
        return false;
    }

    // Get the response body
    $body = wp_remote_retrieve_body($response);

    // Decode JSON response
    $data = json_decode($body, true);

    // Check if the decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        // Handle JSON decoding error
        return false;
    }

    return $data;
}

$enablers = fetch_api_data("https://217.196.51.115/m/api/members/enablers");

if (!$enablers) {
    // Handle the case where the API request failed or returned invalid data
    echo "Failed to fetch enablers.";
} else {
?>

    <body class="bg-gray-100">
        <div class="container mx-auto p-8">

            <div class="flex items-center mb-5">
                <a href="<?php echo esc_url(home_url('startup-enabler')); ?>" class="blue-back text-lg">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <?php
            // Retrieve the parameters from the URL
            $setting_institution = isset($_GET['setting_institution']) ? urldecode($_GET['setting_institution']) : '';

            // Find the specific enabler by institution name
            $selected_enabler = null;
            foreach ($enablers as $enabler) {
                if (isset($enabler['setting_institution']) && $enabler['setting_institution'] === $setting_institution) {
                    $selected_enabler = $enabler;
                    break;
                }
            }

            // Display enabler details if found
            if ($selected_enabler) {
            ?>
                <div class="container mx-auto p-15 px-36">
                    <div class="bg-white rounded-lg overflow-hidden shadow-md mb-5">
                        <img src="<?php echo esc_url($selected_enabler['setting_coverpic']); ?>" alt="<?php echo esc_html(str_replace('profile-cover-img/', '', $selected_enabler['setting_coverpic'])); ?>" class="w-full h-64 object-cover" style="background-color: #888888;">
                    </div>
                    <div class="flex -mt-20 ml-20">
                        <img src="<?php echo esc_url($selected_enabler['setting_profilepic']); ?>" alt="<?php echo esc_html(str_replace('profile-img/', '', $selected_enabler['setting_profilepic'])); ?>" class="w-32 h-32 object-cover rounded-full border-4 border-white" style="background-color: #888888;">
                        <div class="px-4 py-2 mt-16 ml-2">
                            <h4 class="text-3xl font-bold mb-2"><?php echo esc_html($selected_enabler['setting_institution']); ?></h4>
                            <p class="text-m text-gray-600 mb-2"><?php echo esc_html($selected_enabler['setting_address']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Left column for chapters and company description -->
                <div class="flex container mx-auto p-15 px-36">
                    <div class="w-1/3 p-4 bg-gray-200 mt-10">
                        <h7>Company Description</h7>
                        <p class="text-sm text-gray-600 mb-2 mt-5 text-justify"><?php echo esc_html($selected_enabler['setting_bio']); ?></p>
                    </div>

                    <!-- Right column for data -->
                    <div class="w-3/4 p-4 flex flex-col gap-4">
                        <!-- Tab buttons -->
                        <div class="flex justify-end gap-10 text-xl">
                            <button class="tab-btn active" onclick="showContent('events', this)">Events</button>
                            <button class="tab-btn" onclick="showContent('blogs', this)">Blogs</button>
                        </div>

                        <!-- Events content -->
                        <div id="eventsContent" class="mt-10 ml-5">
                            <h10>Events</h10>
                            <?php
                            // Fetch events for the specific member
                            $events_url = "https://217.196.51.115/m/api/events/";
                            $member_id = $selected_enabler['member_id'];
                            $events = fetch_api_data($events_url);

                            if ($events) {
                                // Filter and display events for the specific member
                                $filtered_events = array_filter($events, function ($event) use ($member_id) {
                                    return $event['event_author'] === $member_id;
                                });

                                // Sort events by date in descending order (newest to oldest)
                                usort($filtered_events, function ($a, $b) {
                                    return strtotime($b['event_date']) - strtotime($a['event_date']);
                                });

                                foreach ($filtered_events as $event) {
                            ?>
                                    <div class="border p-4 mb-4 mt-5">
                                        <div class="flex items-center">
                                            <img src="<?php echo esc_url($selected_enabler['setting_profilepic']); ?>" alt="<?php echo esc_html(str_replace('profile-img/', '', $selected_enabler['setting_profilepic'])); ?>" class="w-16 h-16 object-cover rounded-full border-4 border-white" style="background-color: #888888;">

                                            <div class="ml-4">
                                                <h4 class="text-xl font-bold"><?php echo esc_html($selected_enabler['setting_institution']); ?></h4>
                                                <p class="text-sm text-gray-600"><?php echo esc_html($event['event_datecreated']); ?></p>
                                            </div>
                                        </div>

                                        <h2 class="text-l font-bold mt-3"><?php echo esc_html($event['event_title']); ?></h2>
                                        <img src="<?php echo esc_url($event['event_img']); ?>" alt="<?php echo esc_html($event['event_img']); ?>" class="w-full h-64 object-cover mb-2 mt-3" style="background-color: #888888;">
                                        <p class="text-sm text-gray-600 mb-2 mt-2"><?php echo esc_html($event['event_date']); ?></p>
                                        <p class="text-m text-black-800 mt-3"><?php echo esc_html($event['event_description']); ?></p>
                                    </div>
                            <?php
                                }
                            } else {
                                // Handle the case where the API request for events failed or returned invalid data
                                echo "Failed to fetch events.";
                            }
                            ?>

                            <script>
                                const loadImage = async () => {
                                    const currentSrc = document.getElementById('event_pic').alt
                                    const res = await fetch(
                                        `https://www.binnostartup.site/m/api/images?filePath=event-pics/${encodeURIComponent(currentSrc)}`
                                    )

                                    const blob = await res.blob();
                                    const imageUrl = URL.createObjectURL(blob);

                                    document.getElementById('event_pic').src = imageUrl;

                                }

                                loadImage()
                            </script>

                        </div>

                        <!-- Blogs content (hidden by default) -->
                        <div id="blogsContent" class="mt-10 ml-5" style="display: none;">
                            <h10>Blogs</h10>

                            <div id="blogsContent" class="mt-5">
                                <?php
                                // Fetch blogs for the specific member
                                $blogs_url = "https://217.196.51.115/m/api/blogs/";
                                $member_id = $selected_enabler['member_id'];
                                $blogs = fetch_api_data($blogs_url);

                                if ($blogs) {
                                    // Filter and display blogs for the specific member
                                    $filtered_blogs = array_filter($blogs, function ($blog) use ($member_id) {
                                        return $blog['blog_author'] === $member_id;
                                    });

                                    // Sort blogs by date in descending order (newest to oldest)
                                    usort($filtered_blogs, function ($a, $b) {
                                        return strtotime($b['blog_dateadded']) - strtotime($a['blog_dateadded']);
                                    });

                                    foreach ($filtered_blogs as $blog) {
                                ?>
                                        <div class="border p-4 mb-4 mt-5">
                                            <div class="flex items-center">
                                                <img src="<?php echo esc_url($selected_enabler['setting_profilepic']); ?>" alt="<?php echo esc_html(str_replace('profile-img/', '', $selected_enabler['setting_profilepic'])); ?>" class="w-16 h-16 object-cover rounded-full border-4 border-white" style="background-color: #888888;">

                                                <div class="ml-4">
                                                    <h4 class="text-xl font-bold"><?php echo esc_html($selected_enabler['setting_institution']); ?></h4>
                                                    <p class="text-sm text-gray-600"><?php echo esc_html($blog['blog_dateadded']); ?></p>
                                                </div>
                                            </div>
                                            <h2 class="text-l font-bold mt-3"><?php echo esc_html($blog['blog_title']); ?></h2>
                                            <img src="<?php echo esc_url($blog['blog_img']); ?>" alt="<?php echo esc_html($blog['blog_img']); ?>" class="w-full h-64 object-cover mb-2 mt-3" style="background-color: #888888;">
                                            <p class="text-m text-black-800 mt-3"><?php echo esc_html($blog['blog_content']); ?></p>
                                        </div>
                                <?php
                                    }
                                } else {
                                    // Handle the case where the API request for blogs failed or returned invalid data
                                    echo "Failed to fetch blogs.";
                                }
                                ?>
                            </div>

                            <script>
                                const loadImage = async () => {
                                    const currentSrc = document.getElementById('blog_pic').alt
                                    const res = await fetch(
                                        `https://www.binnostartup.site/m/api/images?filePath=blog-pics/${encodeURIComponent(currentSrc)}`
                                    )

                                    const blob = await res.blob();
                                    const imageUrl = URL.createObjectURL(blob);

                                    document.getElementById('blog_pic').src = imageUrl;

                                }

                                loadImage()
                            </script>

                        </div>
                    </div>

                    <!-- JavaScript to handle tab switching -->
                    <script>
                        function showContent(tabName, tabBtn) {
                            // Hide all content
                            document.getElementById('eventsContent').style.display = 'none';
                            document.getElementById('blogsContent').style.display = 'none';

                            // Deactivate all tabs
                            document.querySelectorAll('.tab-btn').forEach(function(tabBtn) {
                                tabBtn.classList.remove('active');
                            });

                            // Show the selected content
                            document.getElementById(tabName + 'Content').style.display = 'block';

                            // Activate the selected tab
                            tabBtn.classList.add('active');
                        }
                    </script>

                </div>

            <?php
            } else {
                // Handle the case where the selected enabler is not found
                echo "Enabler not found.";
            }
            ?>

        </div>

        <script>
            const loadImage = async () => {
                const currentSrc = document.getElementById('profile_img').alt
                const res = await fetch(
                    `https://www.binnostartup.site/m/api/images?filePath=profile-img/${encodeURIComponent(currentSrc)}`
                )

                const blob = await res.blob();
                const imageUrl = URL.createObjectURL(blob);

                document.getElementById('profile_img').src = imageUrl;

            }

            loadImage()
        </script>

    </body>

    </html>

<?php
}
get_footer();
?>