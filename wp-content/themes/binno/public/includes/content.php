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

$posts = fetch_api_data("http://217.196.51.115/m/api/posts/");
$events = fetch_api_data("http://217.196.51.115/m/api/events/");
$blogs = fetch_api_data("http://217.196.51.115/m/api/blogs/");


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
        <main class="container flex justify-center items-center">
            <div class="container mx-16">

                <!-- Display Startup Posts -->
                <h3 class="font-semibold text-3xl md:text-5xl">Startup Posts</h3>
                <div class="container p-8 px-16 flex flex-col md:flex-column">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="cardContainer">

                        <?php
                        // Sort the posts array by post date in descending order
                        usort($posts, function ($a, $b) {
                            return strtotime($b['post_dateadded']) - strtotime($a['post_dateadded']);
                        });

                        $i = 0;
                        foreach ($posts as $post) :
                            $i++;
                            $shortened_heading = substr($post['post_heading'], 0, 15);
                        ?>
                            <div class="card-container bg-white rounded-lg overflow-hidden shadow-lg h-full">
                                <img src=<?php echo esc_html(($post['post_img'])); ?> alt=<?php echo esc_html(($post['post_img'])); ?> id="dynamicImg-<?php echo $i ?>" class="w-full h-40 object-cover" style="background-color: #888888;">
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

                                // Limit the post_heading to 15 characters and append '...'
                                // const truncatedHeading = cards[i].post_heading.length > 15 ?
                                //     cards[i].post_heading.slice(0, 15) + '...' :
                                //     cards[i].post_heading;

                                card.innerHTML = `
                                    <img src="${cards[i].post_img}" alt="${cards[i].post_img}" id="dynamicImg-${i}" class="w-full h-40 object-cover" style="background-color: #888888;">
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

                            // Call updateImageSrc for each image after updating the current page
                            for (var i = 1; i <= 3; i++) {
                                var imgElement = document.getElementById("dynamicImg-" + i);
                                if (imgElement) {
                                    updateImageSrc(imgElement);
                                }
                            }
                        }

                        function prevPage() {
                            currentPage = Math.max(currentPage - 1, 0);
                            displayCards();

                            // Call updateImageSrc for each image after updating the current page
                            for (var i = 1; i <= 3; i++) {
                                var imgElement = document.getElementById("dynamicImg-" + i);
                                if (imgElement) {
                                    updateImageSrc(imgElement);
                                }
                            }
                        }

                        // Initial display
                        displayCards();

                        // Function to fetch image data from API
                        async function updateImageSrc(imgElement) {
                            const res = await fetch('http://217.196.51.115/m/api/images?filePath=post-pics/' + encodeURIComponent(imgElement.src))
                                .then(response => response.blob())
                                .then(data => {
                                    var blob = new Blob([data], {
                                        type: 'image/png'
                                    });
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

                        $i = 0;
                        foreach ($events as $event) :
                            $i++;
                        ?>
                            <div class="card-container bg-white rounded-lg overflow-hidden shadow-lg h-full">
                                <img src=<?php echo esc_html(($event['event_img'])); ?> alt=<?php echo esc_html(($event['event_img'])); ?> id="dynamicImg-<?php echo $i ?>" class="w-full h-40 object-cover" style="background-color: #888888;">
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
                        <button id="prevEventBtn" onclick="prevEventPage()" class="prev-button bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <div class="mx-1"></div>
                        <button id="nextEventBtn" onclick="nextEventPage()" class="next-button bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
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

                                // const truncatedTitle = eventCards[i].event_title.length > 20 ?
                                //     eventCards[i].event_title.slice(0, 20) + '...' :
                                //     eventCards[i].event_title;

                                card.innerHTML = `
            <img src="${eventCards[i].event_img}" alt="${eventCards[i].event_img}" id="dynamicEventImg-${i}" class="w-full h-40 object-cover" style="background-color: #888888;">
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

                            const prevEventBtn = document.getElementById('prevEventBtn');
                            const nextEventBtn = document.getElementById('nextEventBtn');

                            if (eventCards.length <= eventCardsPerPage) {
                                prevEventBtn.style.display = 'none';
                                nextEventBtn.style.display = 'none';
                            } else {
                                prevEventBtn.style.display = 'inline-block';
                                nextEventBtn.style.display = 'inline-block';
                            }
                        }

                        function nextEventPage() {
                            currentEventPage = Math.min(currentEventPage + 1, Math.ceil(eventCards.length / eventCardsPerPage) - 1);
                            displayEventCards();

                            // Call updateEventImageSrc for each image after updating the current page
                            for (var i = 1; i <= 3; i++) {
                                var imgElement = document.getElementById("dynamicEventImg-" + i);
                                if (imgElement) {
                                    updateEventImageSrc(imgElement); // Fix: Use updateEventImageSrc instead of updateImageSrc
                                }
                            }
                        }

                        function prevEventPage() {
                            currentEventPage = Math.max(currentEventPage - 1, 0);
                            displayEventCards();

                            // Call updateEventImageSrc for each image after updating the current page
                            for (var i = 1; i <= 3; i++) {
                                var imgElement = document.getElementById("dynamicEventImg-" + i);
                                if (imgElement) {
                                    updateEventImageSrc(imgElement); // Fix: Use updateEventImageSrc instead of updateImageSrc
                                }
                            }
                        }

                        // Initial display
                        displayEventCards();

                        // Function to fetch image data from API
                        async function updateImageSrc(imgSrc) {
                            const res = await fetch('http://217.196.51.115/m/api/images?filePath=event-pics/' + encodeURIComponent(imgSrc))
                                .then(response => response.blob())
                                .then(data => {
                                    var blob = new Blob([data], {
                                        type: 'image/png'
                                    });
                                    imgElement.src = URL.createObjectURL(blob);
                                })
                                .catch(error => console.error('Error fetching image data:', error));
                        }

                        // Loop through images with IDs containing "dynamicEventImg"
                        for (var i = 1; i <= 3; i++) {
                            var imgElement = document.getElementById("dynamicEventImg-" + i);
                            if (imgElement) {
                                updateEventImageSrc(imgElement);
                            }
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

                        $i = 0;
                        foreach ($blogs as $blog) :
                            $i++;
                        ?>
                            <div class="card-container bg-white rounded-lg overflow-hidden shadow-lg h-full">
                                <img src=<?php echo esc_html(($blog['blog_img'])); ?> alt=<?php echo esc_html(($blog['blog_img'])); ?> id="dynamicImg-<?php echo $i ?>" class="w-full h-40 object-cover" style="background-color: #888888;">
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
                        <button onclick="prevBlogPage()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <div class="mx-1"></div>
                        <button onclick="nextBlogPage()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
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

                                // Limiting the display of blog_title to 15 characters and adding '...'
                                // const truncatedTitle = blogCards[i].blog_title.length > 15 ?
                                //     blogCards[i].blog_title.slice(0, 15) + '...' :
                                //     blogCards[i].blog_title;

                                blogCard.innerHTML = `
                <img src="${blogCards[i].blog_img}" alt="${blogCards[i].blog_img}" id="dynamicBlogImg-${i}" class="w-full h-40 object-cover" style="background-color: #888888;">
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

                            // Call updateBlogImageSrc for each image after updating the current page
                            for (var i = 1; i <= 3; i++) {
                                var imgElement = document.getElementById("dynamicBlogImg-" + i);
                                if (imgElement) {
                                    updateBlogImageSrc(imgElement); // Fixed the function name here
                                }
                            }
                        }

                        function prevBlogPage() {
                            currentBlogPage = Math.max(currentBlogPage - 1, 0);
                            displayBlogCards();

                            // Call updateBlogImageSrc for each image after updating the current page
                            for (var i = 1; i <= 3; i++) {
                                var imgElement = document.getElementById("dynamicBlogImg-" + i);
                                if (imgElement) {
                                    updateBlogImageSrc(imgElement); // Fixed the function name here
                                }
                            }
                        }

                        // Initial display
                        displayBlogCards();

                        // Function to fetch image data from API
                        async function updateBlogImageSrc(imgSrc) {
                                imgSrc.src = `http://217.196.51.115/m/api/images?filePath=blog-pics/${imgSrc.alt}`
                                console.log(imgSrc)
                                
                        }

                        // Loop through images with IDs containing "dynamicBlogImg"
                        for (var i = 0; i <= 10; i++) {
                            var imgElement = document.getElementById("dynamicBlogImg-" + i);
                            updateBlogImageSrc(imgElement);
                            console.log(`dynamicBlogImg-${i}: `,imgElement)
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