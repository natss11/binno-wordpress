<?php
get_header('guides');

/*
Template Name: Guides
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

$programs = fetch_api_data("https://www.binnostartup.site/m/api/programs/");

if (!$programs) {
    // Handle the case where the API request failed or returned invalid data
    echo "Failed to fetch guides";
} else {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    </head>

    <body>
        <main class="container mx-16 flex justify-center items-center">
            <div class="container mx-16">
                <h3 class="font-semibold text-3xl md:text-5xl">Guides</h3>

                <div class="container mx-auto p-8 px-16 flex flex-col md:flex-column">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php
                        //program_dateadded attribute
                        usort($programs, function ($a, $b) {
                            //program_dateadded is in Y-m-d H:i:s format
                            $dateA = strtotime(isset($a['program_dateadded']) ? $a['program_dateadded'] : 0);
                            $dateB = strtotime(isset($b['program_dateadded']) ? $b['program_dateadded'] : 0);

                            // Sort in descending order (newest to oldest)
                            return $dateB - $dateA;
                        });

                        $i = 0;
                        foreach ($programs as $program) {
                            $i++;
                        ?>
                            <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <img src=<?php echo esc_html(($program['program_img'])); ?> alt=<?php echo esc_html(($program['program_img'])); ?> id="dynamicImg-<?php echo $i ?>" class="w-full h-40 object-cover" style="background-color: #888888;">
                                <div class="p-4 object-cover ml-5">
                                    <h2 class="text-2xl font-semibold"><?php echo esc_html(isset($program['program_heading']) ? $program['program_heading'] : ''); ?></h2>
                                    <p class="text-gray-600 text-sm"><?php echo esc_html(isset($program['program_dateadded']) ? $program['program_dateadded'] : ''); ?></p>
                                </div>
                                <div class="mt-1 mb-3 mr-3 flex justify-end">
                                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('guides-view'))) . '?program_id=' . (isset($program['program_id']) ? $program['program_id'] : ''); ?>" class="btn-seemore">See Program</a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </main>

        <script>
            // Function to update image src from API
            const updateImageSrc = async (imgElement) => {
                // Get the current src value
                var currentSrc = imgElement.alt;

                // Fetch image data from API
                const res = await fetch('https://www.binnostartup.site/m/api/images?filePath=guide-pics/' + encodeURIComponent(currentSrc))
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