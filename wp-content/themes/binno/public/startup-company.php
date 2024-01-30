<?php
get_header('profiles');
/*
Template Name: Startup Company
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

$companies = fetch_api_data("https://217.196.51.115/m/api/members/companies");

if (!$companies) {
    // Handle the case where the API request failed or returned invalid data
    echo "Failed to fetch companies.";
} else {
?>

    <body>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center"> <!-- Change mx-40 to mx-20 -->
            <h1 class="font-bold text-3xl md:text-6xl ml-10 mt-5 mb-5" style="color: #ff7a00;">Startups</h1>
            <p class="text-lg mb-10 mx-20" style="text-align: center;">Welcome to the exciting world of entrepreneurship in the Bicol
                Region! In this dynamic and rapidly evolving landscape, we embark on a journey to explore and
                discover the latest startups that are revolutionizing industries, solving pressing challenges,
                and shaping the future of this vibrant region. From innovative tech ventures to sustainable
                enterprises, join us as we uncover the diverse and inspiring stories of entrepreneurs
                who are making their mark on the Bicolano startup scene and beyond.
            </p>

            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10 mx-20">
                <?php

                $i = 0;
                foreach ($companies as $company) {
                    $i++;
                    // Check if the properties are set before trying to access them
                    $setting_institution = isset($company['setting_institution']) ? esc_html($company['setting_institution']) : '';
                ?>
                    <div class="bg-white rounded-lg overflow-hidden shadow-md relative">
                        <img src="<?php echo isset($company['setting_coverpic']) ? esc_html(str_replace('profile-cover-img/', '', $company['setting_coverpic'])) : ''; ?>" alt="<?php echo isset($company['setting_coverpic']) ? esc_html(str_replace('profile-cover-img/', '', $company['setting_coverpic'])) : ''; ?>" class="w-full h-32 object-cover" style="background-color: #888888;">
                        <img src="<?php echo isset($company['setting_profilepic']) ? esc_html(str_replace('profile-img/', '', $company['setting_profilepic'])) : ''; ?>" alt="<?php echo isset($company['setting_profilepic']) ? esc_html(str_replace('profile-img/', '', $company['setting_profilepic'])) : ''; ?>" class="w-32 h-32 object-cover rounded-full -mt-20 square-profile object-cover absolute left-1/2 transform -translate-x-1/2" style="background-color: #888888;">

                        <div class="flex flex-col items-center px-4 py-2"> <!-- flex container and center alignment -->
                            <h2 class="text-lg font-semibold mb-2 mt-10"><?php echo $setting_institution; ?></h2>
                        </div>

                        <div class="mt-1 mb-3 mr-3 ml-3 flex justify-end">
                            <button class="btn-see_profile w-full" onclick="redirectToProfile('<?php echo esc_url(home_url('startup-company-profile?setting_institution=' . urlencode($setting_institution) . '&member_id=' . urlencode($company['member_id']))); ?>')">See Profile</button>
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