<?php
get_header('profiles');
/*
Template Name: Startup Enabler
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
    $data = json_decode($body);

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

    <body>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
            <h1 class="font-bold text-3xl md:text-6xl ml-10 mt-5 mb-5" style="color: #ff7a00;">Startup Enablers</h1>
            <p class="text-lg mb-10 mx-20" style="text-align: center;">Welcome to an exciting glimpse into
                the vibrant and dynamic world of startup enablers in the Bicol Region! Nestled in the Philippines,
                this picturesque region is not only known for its natural beauty but also for
                fostering an innovative and thriving entrepreneurial ecosystem. Join us as we explore
                the key players, initiatives, and resources that have transformed Bicol into a hotbed
                for startups, empowering the region's creative minds to turn their ideas into reality
                and shape the future of business.
            </p>

            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10 mx-20">
                <?php

                $i = 0;
                foreach ($enablers as $enabler) {
                    $i++;
                    $setting_institution = isset($enabler->setting_institution) ? esc_html($enabler->setting_institution) : '';
                    $setting_coverpic = isset($enabler->setting_coverpic) ? esc_html(str_replace('profile-cover-img/', '', $enabler->setting_coverpic)) : '';
                    $setting_profilepic = isset($enabler->setting_profilepic) ? esc_html(str_replace('profile-img/', '', $enabler->setting_profilepic)) : '';
                ?>
                    <div class="bg-white rounded-lg overflow-hidden shadow-md relative">
                        <img src="<?php echo $setting_coverpic; ?>" alt="<?php echo $setting_coverpic; ?>" class="w-full h-32 object-cover" style="background-color: #888888;">
                        <img src="<?php echo $setting_profilepic; ?>" alt="<?php echo $setting_profilepic; ?>" class="w-32 h-32 object-cover rounded-full -mt-20 square-profile object-cover absolute left-1/2 transform -translate-x-1/2" style="background-color: #888888;">
                        <div class="flex flex-col items-center px-4 py-2">
                            <h2 class="text-lg font-semibold mb-2 mt-10"><?php echo $setting_institution; ?></h2>
                        </div>

                        <div class="mt-1 mb-3 mr-3 ml-3 flex justify-end">
                            <button class="btn-see_profile w-full" onclick="redirectToProfile('<?php echo esc_url(home_url('startup-enabler-profile?setting_institution=' . urlencode($setting_institution) . '&member_id=' . urlencode($enabler->member_id))); ?>')">See Profile</button>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>

            <script>
                function redirectToProfile(profileUrl) {
                    window.location.href = profileUrl;
                }
            </script>

        </div>

        <script>
            // Function to update image src from API
            const updateImageSrc = async (imgElement) => {
                // Get the current src value
                var currentSrc = imgElement.alt;

                // Fetch image data from API
                const res = await fetch('https://www.binnostartup.site/m/api/images?filePath=profile-img/' + encodeURIComponent(currentSrc))
                    .then(response => response.blob())
                    .then(data => {
                        // Create a blob from the response data
                        var blob = new Blob([data], {
                            type: 'image/png'
                        }); // Adjust type if needed

                        console.log(blob)
                        // Set the new src value using a blob URL
                        imgElement.src = URL.createObjectURL(blob);
                    })
                    .catch(error => console.error('Error fetching image data:', error));
            }

            // Loop through images with IDs containing "dynamicImg"
            for (var i = 1; i <= 3; i++) {
                var imgElement = document.getElementById("dynamicImg-" + i);
                if (imgElement) {
                    // Update each image's src from the API
                    updateImageSrc(imgElement);
                }
            }
        </script>

    </body>

    </html>

<?php
}
get_footer();
?>