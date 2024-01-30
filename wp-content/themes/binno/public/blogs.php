<?php
get_header('blogs');

/*
Template Name: Blogs
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

$blogs = fetch_api_data("https://www.binnostartup.site/m/api/blogs/");

if (!$blogs) {
    // Handle the case where the API request failed or returned invalid data
    echo "Failed to fetch blogs.";
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
                <h3 class="font-semibold text-3xl md:text-5xl">Blogs</h3>

                <div class="container mx-auto p-8 px-16 flex flex-col md:flex-column">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php
                        // Sort the blogs array by the blog_dateadded field in descending order
                        usort($blogs, function ($a, $b) {
                            return strtotime($b['blog_dateadded']) - strtotime($a['blog_dateadded']);
                        });

                        $i = 0;
                        foreach ($blogs as $blog) :
                            $i++;
                        ?>
                            <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                                <img src=<?php echo esc_url($blog['blog_img']); ?> alt=<?php echo ($blog['blog_img']); ?> id="dynamicImg-<?php echo $i ?>" class="w-full h-40 object-cover" style="background-color: #888888;">
                                <div class="p-4">
                                    <div class="flex items-center mb-2">
                                        <div>
                                            <h2 class="text-2xl font-semibold"><?php echo esc_html($blog['blog_title']); ?></h2>
                                            <p class="text-gray-600 text-sm"><?php echo esc_html($blog['blog_dateadded']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1 mb-3 mr-3 flex justify-end">
                                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('blogs-view'))) . '?blog_id=' . $blog['blog_id']; ?>" class="btn-seemore">See Blog</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
                const res = await fetch('https://www.binnostartup.site/m/api/images?filePath=blog-pics/' + encodeURIComponent(currentSrc))
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