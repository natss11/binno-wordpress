<?php
/*
Template Name: Single Post
*/

get_header('posts');

function fetch_api_data($api_url)
{
    // Make the request
    $response = wp_remote_get($api_url, array('sslverify'   => false, 'sslversion' => CURL_SSLVERSION_TLSv1_2));

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

// Get the post ID from the query parameter
$post_id = isset($_GET['post_id']) ? ($_GET['post_id']) : 0;

// Check if a valid post ID is provided
if ($post_id > 0) {
    $posts = fetch_api_data("https://www.binnostartup.site/m/api/posts/$post_id");

    if ($posts) {
        $post = $posts[0];
?>
        <div class="container mx-auto p-8 px-72">
            <!-- Back icon with link to 'posts' page -->
            <a href="<?php echo esc_url(home_url('posts')); ?>" class="blue-back text-lg">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <div class="flex flex-row mb-4 mt-5">
                <div>
                    <h2 class="text-xl font-semibold mb-2"><?php echo esc_html($post['post_author']); ?></h2>
                    <p class="text-gray-600 mb-2"><?php echo esc_html($post['post_dateadded']); ?></p>
                </div>
            </div>
            <img src="<?php echo esc_url($post['post_img']); ?>" alt="<?php echo ($post['post_img']); ?>" class="w-full h-full object-cover mb-2" style="background-color: #888888;">
            <h2 class="text-2xl font-semibold mt-5 mb-2"><?php echo esc_html($post['post_heading']); ?></h2>
            <p class="text-gray-600 mb-5"><?php echo esc_html($post['post_bodytext']); ?></p>
        </div>
<?php
    } else {
        echo '<p>No post found.</p>';
    }
} else {
    echo '<p>Invalid post ID.</p>';
}

?>
<script>
    const loadImage = async () => {
        const currentSrc = document.getElementById('post_pic').alt
        const res = await fetch(
            `https://www.binnostartup.site/m/api/images?filePath=post-pics/${encodeURIComponent(currentSrc)}`
        )

        const blob = await res.blob();
        const imageUrl = URL.createObjectURL(blob);

        document.getElementById('post_pic').src = imageUrl;

    }

    loadImage()
</script>
<?php

get_footer();
?>