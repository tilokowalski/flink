
$('.flink-vc.video').each(function() {

    const videoContainer = $(this);

    const video = videoContainer.find('video');
    const buttonPlayPause = videoContainer.find('button.play-pause');  

    $(document).keydown(function (e) {
        switch (e.key.toLowerCase()) {
            case " ": if (!buttonPlayPause.is(':focus')) togglePlayPause(); break;
        }
    });
    
    // Play / Pause

    function togglePlayPause() {
        video[0].paused ? video[0].play() : video[0].pause();
    }

    buttonPlayPause.click(() => togglePlayPause());
    video.click(() => togglePlayPause());
    
    video.on('play', function() {
        videoContainer.removeClass('paused');
    });
    
    video.on('pause', function() {
        videoContainer.addClass('paused');
    });

});
