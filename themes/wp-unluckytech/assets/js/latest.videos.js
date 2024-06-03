document.addEventListener('DOMContentLoaded', function () {
    const API_KEY = 'YOUR_YOUTUBE_API_KEY';
    const CHANNEL_ID = 'YOUR_CHANNEL_ID';
    const MAX_RESULTS = 5;
    const videoContainer = document.getElementById('latest-videos');

    fetch(`https://www.googleapis.com/youtube/v3/search?key=${AIzaSyAf5l9EGP5O4bVW0ZX-dmMtUdXzBXvkV9o}&channelId=${RickAstleyYT}&part=snippet,id&order=date&maxResults=${MAX_RESULTS}`)
        .then(response => response.json())
        .then(data => {
            data.items.forEach(item => {
                const videoId = item.id.videoId;
                const videoTitle = item.snippet.title;
                const videoThumbnail = item.snippet.thumbnails.high.url;
                
                const videoItem = document.createElement('div');
                videoItem.classList.add('video-item');

                const thumbnail = document.createElement('img');
                thumbnail.src = videoThumbnail;
                thumbnail.alt = videoTitle;

                const title = document.createElement('div');
                title.classList.add('video-item-title');
                title.textContent = videoTitle;

                videoItem.appendChild(thumbnail);
                videoItem.appendChild(title);
                videoContainer.appendChild(videoItem);
            });
        })
        .catch(error => console.error('Error fetching YouTube videos:', error));
});