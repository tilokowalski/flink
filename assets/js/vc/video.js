
$('.flink-vc.video').each(function() {

    const videoContainer = $(this);

    const video = videoContainer.find('video');

    const containerTimeline = videoContainer.find('div.timeline-wrapper');

    const buttonPlayPause = videoContainer.find('button.play-pause');  
    const buttonFullscreen = videoContainer.find('button.fullscreen');
    const buttonMuteUnmute = videoContainer.find('button.mute-unmute');
    const buttonSpeed = videoContainer.find('button.speed');

    const sliderVolume = videoContainer.find('input.volume-slider');

    const containerTimeCurrent = videoContainer.find('div.current-time');
    const containerTimeTotal = videoContainer.find('div.total-time');

    // Timeline

    let isScrubbing = false;
    let wasPaused;

    function toggleScrubbing(e) {
        const rect = containerTimeline[0].getBoundingClientRect();
        const percent = Math.min(Math.max(0, e.pageX - rect.x), rect.width) / rect.width;
        isScrubbing = (e.buttons & 1) === 1;
        if (isScrubbing) {
            wasPaused = video[0].paused;
            video[0].pause();
        } else {
            video[0].currentTime = percent * video[0].duration
            if (!wasPaused) video[0].play();
        }
        handleTimelineUpdate(e);
    }

    function handleTimelineUpdate(e) {
        const rect = containerTimeline[0].getBoundingClientRect();
        const percent = Math.min(Math.max(0, e.pageX - rect.x), rect.width) / rect.width;
        containerTimeline[0].style.setProperty('--preview-position', percent);

        if (isScrubbing) {
            e.preventDefault();
            containerTimeline[0].style.setProperty('--progress-position', percent);
        }

    }

    containerTimeline.on('mousemove', handleTimelineUpdate);
    containerTimeline.on('mousedown', toggleScrubbing);

    $(document).on('mouseup', function(e) {
        if (isScrubbing) toggleScrubbing(e)
    });

    $(document).on('mousemove', function(e) {
        if (isScrubbing) handleTimelineUpdate(e)
    });
    
    // Play / Pause

    function togglePlayPause() {
        if (video[0].attr('data-pausable') === 'false') return;
        video[0].paused ? video[0].play() : video[0].pause();
    }

    buttonPlayPause.click(togglePlayPause);
    video.click(togglePlayPause);
    
    video.on('play', function() {
        videoContainer.removeClass('paused');
    });
    
    video.on('pause', function() {
        videoContainer.addClass('paused');
    });

    // Volume

    function toggleMuteUnmute() {
        video[0].muted = !video[0].muted
    }

    buttonMuteUnmute.click(toggleMuteUnmute);

    sliderVolume.on('input', function(e) {
        video[0].volume = $(this).val();
        video.muted = $(this).val() === 0;
    });

    video.on('volumechange', function() {
        sliderVolume.val(video[0].volume);
        let volumeLevel;
        if (video[0].muted || video[0].volume === 0) {
            sliderVolume.val(0);
            volumeLevel = "muted";
        } else if (video[0].volume >= .5) {
            volumeLevel = "high";
        } else {
            volumeLevel = "low";
        }
        videoContainer.attr('data-volume-level', volumeLevel);
    });

    // Duration

    const leadingZeroFormatter = new Intl.NumberFormat(undefined, {
        minimumIntegerDigits: 2
    });

    function formatDuration(time) {
        const seconds = Math.floor(time % 60);
        const minutes = Math.floor(time / 60) % 60;
        const hours = Math.floor(time / 3600);
        if (hours === 0) {
            return minutes + ':' + leadingZeroFormatter.format(seconds);
        } else {
            return hours + ':' + leadingZeroFormatter.format(minutes) + ':' + leadingZeroFormatter.format(seconds);
        }
    }

    video.on('loadeddata', function() {
        containerTimeTotal.html(formatDuration(video[0].duration));
    });

    video.on('timeupdate', function() {
        containerTimeCurrent.html(formatDuration(video[0].currentTime));
        containerTimeline[0].style.setProperty('--progress-position', video[0].currentTime / video[0].duration);
    });

    // Speed

    function changePlaybackSpeed() {
        let newPlaybackRate = video[0].playbackRate + .5;
        if (newPlaybackRate > 2) newPlaybackRate = .5;
        video[0].playbackRate = newPlaybackRate;
        buttonSpeed.html(newPlaybackRate + 'x'); 
    }

    buttonSpeed.click(changePlaybackSpeed);

    // Fullscreen

    function toggleFullscreen() {
        document.fullscreenElement == null ? videoContainer[0].requestFullscreen() : document.exitFullscreen();
    }

    buttonFullscreen.click(toggleFullscreen);
    video.dblclick(toggleFullscreen);

    $(document).on('fullscreenchange', function() {
        videoContainer.toggleClass('fullscreen', document.fullscreenElement !== null);
    });

});
