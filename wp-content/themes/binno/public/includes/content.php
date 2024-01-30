<?php

get_header();

/*
Template Name: Discover
*/

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

$posts = fetch_api_data("https://217.196.51.115/m/api/posts/");
$events = fetch_api_data("https://217.196.51.115/m/api/events/");
$blogs = fetch_api_data("https://217.196.51.115/m/api/blogs/");


if (!$posts || !$events || !$blogs) {
    // Handle the case where the API request failed or returned invalid data
    echo "Failed to fetch data.";
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
        <title>BINNO</title>
    </head>

    <body>
        <main class="container mx-16 flex justify-center items-center">
            <div class="container mx-16">

                <!-- Display Startup Posts -->
                <h3 class="font-semibold text-3xl md:text-5xl">Startup Posts</h3>
                <div class="container mx-auto p-8 px-16 flex flex-col md:flex-column">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="cardContainer">

                        <?php
                        // Sort the posts array by post date in descending order
                        usort($posts, function ($a, $b) {
                            return strtotime($b['post_dateadded']) - strtotime($a['post_dateadded']);
                        });

                        foreach ($posts as $post) :
                        ?>
                            <div class="card-container bg-white rounded-lg overflow-hidden shadow-lg h-full">
                                <img src="<?php echo esc_url($post['post_img']); ?>" alt="<?php echo esc_html($post['post_img']); ?>" class="w-full h-40 object-cover" style="background-color: #888888;">
                                <div class="p-4">
                                    <div class="flex items-center mb-2">
                                        <div>
                                            <h2 class="text-2xl font-semibold"><?php echo esc_html($post['post_heading']); ?></h2>
                                            <p class="text-gray-600 text-sm"><?php echo esc_html($post['post_dateadded']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button onclick="prevPage()" class="prev-button bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <div class="mx-1"></div>
                        <button onclick="nextPage()" class="next-button bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>

                    <script>
                        const cardsPerPage = 3;
                        let currentPage = 0;
                        const cards = <?php echo json_encode($posts); ?>;

                        function displayCards() {
                            const cardContainer = document.getElementById('cardContainer');
                            const prevButton = document.querySelector('.prev-button');
                            const nextButton = document.querySelector('.next-button');

                            // Clear card container
                            cardContainer.innerHTML = '';

                            const startIndex = currentPage * cardsPerPage;
                            const endIndex = startIndex + cardsPerPage;

                            for (let i = startIndex; i < endIndex && i < cards.length; i++) {
                                const card = document.createElement('div');
                                card.className = 'card-container bg-white rounded-lg overflow-hidden shadow-lg h-full';
                                card.innerHTML = `
                    <img src="${cards[i].post_img}" alt="${cards[i].post_img}" class="w-full h-40 object-cover" style="background-color: #888888;">
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <div>
                                <h2 class="text-2xl font-semibold">${cards[i].post_heading}</h2>
                                <p class="text-gray-600 text-sm">${cards[i].post_dateadded}</p>
                            </div>
                        </div>
                    </div>`;
                                cardContainer.appendChild(card);
                            }
                            // Hide/Show buttons based on the number of posts
                            if (cards.length <= cardsPerPage) {
                                prevButton.style.display = 'none';
                                nextButton.style.display = 'none';
                            } else {
                                prevButton.style.display = 'block';
                                nextButton.style.display = 'block';
                            }
                        }

                        function nextPage() {
                            currentPage = Math.min(currentPage + 1, Math.ceil(cards.length / cardsPerPage) - 1);
                            displayCards();
                        }

                        function prevPage() {
                            currentPage = Math.max(currentPage - 1, 0);
                            displayCards();
                        }

                        // Initial display
                        displayCards();

                        // Function to fetch image data from API
                        async function updateImageSrc(imgSrc) {
                            const res = await fetch('https://217.196.51.115/m/api/images?filePath=post-pics/' + encodeURIComponent(imgSrc))
                                .then(response => response.blob())
                                .then(data => {
                                    // Create a blob from the response data
                                    var blob = new Blob([data], {
                                        type: 'image/png'
                                    }); // Adjust type if needed

                                    // Return the blob URL
                                    return URL.createObjectURL(blob);
                                })
                                .catch(error => {
                                    console.error('Error fetching image data:', error);
                                    return ''; // Return an empty string in case of an error
                                });

                            return res;
                        }
                    </script>
                </div>

                <!-- Display Events -->
                <h3 class="font-semibold text-3xl md:text-5xl">Events</h3>
                <div class="container mx-auto p-8 px-16 flex flex-col md:flex-column">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="eventCardContainer">

                        <?php
                        // Sort events array by date in descending order
                        usort($events, function ($a, $b) {
                            return strtotime($b['event_datecreated']) - strtotime($a['event_datecreated']);
                        });

                        foreach ($events as $event) :
                        ?>
                            <div class="card-container bg-white rounded-lg overflow-hidden shadow-lg h-full">
                                <img src="<?php echo esc_url($event['event_img']); ?>" alt="<?php echo esc_html($event['event_img']); ?>" class="w-full h-40 object-cover" style="background-color: #888888;">
                                <div class="p-4">
                                    <div class="flex items-center mb-2">
                                        <div>
                                            <h2 class="text-2xl font-semibold"><?php echo esc_html($event['event_title']); ?></h2>
                                            <p class="text-gray-600 text-sm"><?php echo esc_html($event['event_datecreated']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button id="prevBtn" onclick="prevEventPage()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <div class="mx-1"></div>
                        <button id="nextBtn" onclick="nextEventPage()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>

                    <script>
                        const eventCardsPerPage = 3;
                        let currentEventPage = 0;
                        const eventCards = <?php echo json_encode($events); ?>;

                        function displayEventCards() {
                            const eventCardContainer = document.getElementById('eventCardContainer');
                            eventCardContainer.innerHTML = '';

                            const startIndex = currentEventPage * eventCardsPerPage;
                            const endIndex = startIndex + eventCardsPerPage;

                            for (let i = startIndex; i < endIndex && i < eventCards.length; i++) {
                                const card = document.createElement('div');
                                card.className = 'card-container bg-white rounded-lg overflow-hidden shadow-lg h-full';
                                card.innerHTML = `
                    <img src="${eventCards[i].event_img}" alt="${eventCards[i].event_img}" class="w-full h-40 object-cover" style="background-color: #888888;">
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <div>
                                <h2 class="text-2xl font-semibold">${eventCards[i].event_title}</h2>
                                <p class="text-gray-600 text-sm">${eventCards[i].event_datecreated}</p>
                            </div>
                        </div>
                    </div>`;
                                eventCardContainer.appendChild(card);
                            }

                            // Show/hide next and previous buttons based on the number of events
                            const prevBtn = document.getElementById('prevBtn');
                            const nextBtn = document.getElementById('nextBtn');

                            if (eventCards.length <= eventCardsPerPage) {
                                // Hide buttons if the number of events is 3 or below
                                prevBtn.style.display = 'none';
                                nextBtn.style.display = 'none';
                            } else {
                                // Show buttons if the number of events is 4 or above
                                prevBtn.style.display = 'inline-block';
                                nextBtn.style.display = 'inline-block';
                            }
                        }

                        function nextEventPage() {
                            currentEventPage = Math.min(currentEventPage + 1, Math.ceil(eventCards.length / eventCardsPerPage) - 1);
                            displayEventCards();
                        }

                        function prevEventPage() {
                            currentEventPage = Math.max(currentEventPage - 1, 0);
                            displayEventCards();
                        }

                        // Initial display
                        displayEventCards();

                        // Function to fetch image data from API
                        async function updateImageSrc(imgSrc) {
                            const res = await fetch('https://217.196.51.115/m/api/images?filePath=event-pics/' + encodeURIComponent(imgSrc))
                                .then(response => response.blob())
                                .then(data => {
                                    // Create a blob from the response data
                                    var blob = new Blob([data], {
                                        type: 'image/png'
                                    }); // Adjust type if needed

                                    // Return the blob URL
                                    return URL.createObjectURL(blob);
                                })
                                .catch(error => {
                                    console.error('Error fetching image data:', error);
                                    return ''; // Return an empty string in case of an error
                                });

                            return res;
                        }
                    </script>
                </div>

                <!-- Display Blogs -->
                <h3 class="font-semibold text-3xl md:text-5xl">Blogs</h3>
                <div class="container mx-auto p-8 px-16 flex flex-col md:flex-column">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="blogCardContainer">

                        <?php
                        // Sort blogs by 'blog_dateadded' in descending order
                        usort($blogs, function ($a, $b) {
                            return strtotime($b['blog_dateadded']) - strtotime($a['blog_dateadded']);
                        });

                        foreach ($blogs as $blog) : ?>
                            <div class="card-container bg-white rounded-lg overflow-hidden shadow-lg h-full">
                                <img src="<?php echo esc_url($blog['blog_img']); ?>" alt="<?php echo esc_html($blog['blog_img']); ?>" class="w-full h-40 object-cover" style="background-color: #888888;">
                                <div class="p-4">
                                    <div class="flex items-center mb-2">
                                        <div>
                                            <h2 class="text-2xl font-semibold"><?php echo esc_html($blog['blog_title']); ?></h2>
                                            <p class="text-gray-600 text-sm"><?php echo esc_html($blog['blog_dateadded']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-4 flex justify-end" id="blogNavButtons">
                        <button id="prevButton" onclick="prevBlogPage()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <div class="mx-1"></div>
                        <button id="nextButton" onclick="nextBlogPage()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>


                    <script>
                        const blogCardsPerPage = 3;
                        let currentBlogPage = 0;
                        const blogCards = <?php echo json_encode($blogs); ?>;

                        function displayBlogCards() {
                            const blogCardContainer = document.getElementById('blogCardContainer');
                            blogCardContainer.innerHTML = '';

                            const startBlogIndex = currentBlogPage * blogCardsPerPage;
                            const endBlogIndex = startBlogIndex + blogCardsPerPage;

                            for (let i = startBlogIndex; i < endBlogIndex && i < blogCards.length; i++) {
                                const blogCard = document.createElement('div');
                                blogCard.className = 'card-container bg-white rounded-lg overflow-hidden shadow-lg h-full';
                                blogCard.innerHTML = `
                    <img src="${blogCards[i].blog_img}" alt="${blogCards[i].blog_img}" class="w-full h-40 object-cover" style="background-color: #888888;">
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <div>
                                <h2 class="text-2xl font-semibold">${blogCards[i].blog_title}</h2>
                                <p class="text-gray-600 text-sm">${blogCards[i].blog_dateadded}</p>
                            </div>
                        </div>
                    </div>`;
                                blogCardContainer.appendChild(blogCard);
                            }

                            // Display or hide navigation buttons based on the number of blogs
                            const blogNavButtons = document.getElementById('blogNavButtons');
                            blogNavButtons.innerHTML = '';

                            if (blogCards.length > blogCardsPerPage) {
                                const prevButton = document.createElement('button');
                                prevButton.className = 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mx-2';
                                prevButton.innerHTML = '<i class="fas fa-arrow-left"></i> Previous';
                                prevButton.onclick = prevBlogPage;
                                blogNavButtons.appendChild(prevButton);

                                const nextButton = document.createElement('button');
                                nextButton.className = 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600';
                                nextButton.innerHTML = 'Next <i class="fas fa-arrow-right"></i>';
                                nextButton.onclick = nextBlogPage;
                                blogNavButtons.appendChild(nextButton);
                            }
                        }

                        function nextBlogPage() {
                            currentBlogPage = Math.min(currentBlogPage + 1, Math.ceil(blogCards.length / blogCardsPerPage) - 1);
                            displayBlogCards();
                        }

                        function prevBlogPage() {
                            currentBlogPage = Math.max(currentBlogPage - 1, 0);
                            displayBlogCards();
                        }

                        // Initial display
                        displayBlogCards();

                        // Function to fetch image data from API
                        async function updateImageSrc(imgSrc) {
                            const res = await fetch('https://217.196.51.115/m/api/images?filePath=blog-pics/' + encodeURIComponent(imgSrc))
                                .then(response => response.blob())
                                .then(data => {
                                    // Create a blob from the response data
                                    var blob = new Blob([data], {
                                        type: 'image/png'
                                    }); // Adjust type if needed

                                    // Return the blob URL
                                    return URL.createObjectURL(blob);
                                })
                                .catch(error => {
                                    console.error('Error fetching image data:', error);
                                    return ''; // Return an empty string in case of an error
                                });

                            return res;
                        }
                    </script>
                </div>

            </div>
        </main>

    </body>

    </html>

<?php
}
?>