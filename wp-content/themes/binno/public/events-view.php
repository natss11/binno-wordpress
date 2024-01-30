<?php
/*
Template Name: Single Event
*/

get_header('events');

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

// Get the event ID from the query parameter
$event_id = isset($_GET['event_id']) ? ($_GET['event_id']) : 0;

// Check if a valid event ID is provided
if ($event_id > 0) {
    $events = fetch_api_data("https://www.binnostartup.site/m/api/events/$event_id");

    if ($events) {
        $event = $events[0];
?>
        <div class="container mx-auto p-8 px-72">
            <!-- Back icon with link to 'events' page -->
            <a href="<?php echo esc_url(home_url('events')); ?>" class="blue-back text-lg">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <div class="flex flex-row mb-4 mt-5">
                <div>
                    <?php wpgetapi_endpoint('binno', 'apiimages', array('debug' => false)); ?>
                    <h2 class="text-xl font-semibold mb-2"><?php echo esc_html($event['event_author']); ?></h2>
                    <p class="text-gray-600 mb-2"><?php echo esc_html($event['event_datecreated']); ?></p>
                </div>
            </div>
            <img id="event_pic" src="<?php echo esc_url($event['event_img']); ?>" alt="<?php echo ($event['event_img']); ?>" class="w-full h-full object-cover mb-2" style="background-color: #888888;">
            <h2 class="text-2xl font-semibold mt-5 mb-2"><?php echo esc_html($event['event_title']); ?></h2>
            <p class="text-gray-600 mb-5"><?php echo esc_html($event['event_description']); ?></p>
        </div>
<?php
    } else {
        echo '<p>No event found.</p>';
    }
} else {
    echo '<p>Invalid event ID.</p>';
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
<?php

get_footer();
?>