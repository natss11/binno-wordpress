<?php
get_header('guides');

/*
Template Name: Single Guide
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

$program_id = isset($_GET['program_id']) ? $_GET['program_id'] : 0;

$programs = fetch_api_data("https://www.binnostartup.site/m/api/programs/$program_id");

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
        <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    </head>

    <body>
        <div class="container mx-auto p-8 px-42">
            <!-- Back icon with link to 'events' page -->
            <a href="<?php echo esc_url(home_url('guides')); ?>" class="blue-back text-lg">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="flex container mx-auto p-8 px-42">
            <!-- Left column for chapters -->
            <div class="w-1/4 p-4">
                <h1 class="element_h1">Chapters</h1>
                <ul id="chapter-list" class="mt-5">
                    <?php
                    // Display chapters
                    if (isset($programs['program_pages']) && is_array($programs['program_pages'])) {
                        foreach ($programs['program_pages'] as $index => $page) {
                            echo "<li class='mt-10'><a href='#' onclick='loadChapter($index)' class='element_a'>{$page['program_pages_title']}</a></li>";
                        }
                    }
                    ?>
                </ul>
            </div>

            <!-- Right column for data -->
            <div class="w-3/4 p-4 flex flex flex-col gap-4" id="content-container">
                <?php
                // Display initial content
                if ($programs) {
                    echo "<h9 class='element_p'>" . (isset($programs['program_author']) ? esc_html($programs['program_author']) : '') . "</h9>";
                    echo "<h1 class='element_h1'>" . (isset($programs['program_heading']) ? esc_html($programs['program_heading']) : '') . "</h1>";
                    echo "<p class='element_p'>" . (isset($programs['program_description']) ? esc_html($programs['program_description']) : '') . "</p>";
                } else {
                    echo "Failed to fetch data.";
                }
                ?>
            </div>
        </div>

        <!-- JavaScript to handle tab switching and highlight the selected tab -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add click event listeners to all chapter links
                document.querySelectorAll('#chapter-list a').forEach(function(chapterLink) {
                    chapterLink.addEventListener('click', function(event) {
                        event.preventDefault();
                        // Remove 'active' class from all chapter links
                        document.querySelectorAll('#chapter-list a').forEach(function(link) {
                            link.classList.remove('active');
                        });
                        // Add 'active' class to the clicked chapter link
                        this.classList.add('active');
                        // Load chapter content
                        loadChapter(this.getAttribute('data-index'));
                    });
                });
            });

            // JavaScript function to load chapter content
            function loadChapter(index) {
                var chapterData = <?php echo json_encode(isset($programs['program_pages']) ? $programs['program_pages'] : array()); ?>;
                var contentContainer = document.getElementById('content-container');

                let contents = '';
                if (Array.isArray(chapterData) && chapterData.length > 0 && index < chapterData.length) {
                    chapterData[index]['elements'].forEach(element => {
                        contents += `<${element['type']} ${element['attributes']}>${element['content']}</${element['type']}>`;
                    });
                }

                contentContainer.innerHTML = contents;
            }
        </script>

        <script>
            const loadImage = async () => {
                const currentSrc = document.getElementById('guide_pic').alt
                const res = await fetch(
                    `https://www.binnostartup.site/m/api/images?filePath=guide-pics/${encodeURIComponent(currentSrc)}`
                )

                const blob = await res.blob();
                const imageUrl = URL.createObjectURL(blob);

                document.getElementById('guide_pic').src = imageUrl;

            }

            loadImage()
        </script>

    </body>

    </html>

<?php
}
get_footer();
?>