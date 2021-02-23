var executed = 0;
var global_data = [];
var current_song;

function queryAPI(access_token, type, time_range, index, load) {
    $.ajax({
        url: 'https://api.spotify.com/v1/me/top/' + type,
        type: 'get',
        headers: {
            'Authorization': 'Bearer ' + access_token,
        },
        data: { 
            'time_range': time_range,
        },
        success: function(response) {
            console.log(response);
            
            if (type === 'artists' && load) {
                buildFeatured(response);
                buildArtists(response);
            } else if (load) {
                buildTracks(response);
            }
            
            global_data[index] = JSON.stringify(response);
            executed++;
            
            if (executed >= 6) {
                storeData(global_data);
            }
        },
        error: function(xhr) {
            console.log(xhr);
        }
    });
}

function storeData(data) {
    $.post( "store_query.php", {data: data}).done(function() {location.reload();});
}

function buildFeatured(data) {
    $('#im1').attr('src', data.items[0].images[0].url);
    $('#im2').attr('src', data.items[1].images[0].url);
    $('#im3').attr('src', data.items[2].images[0].url);
}

function buildArtists(data) {
    data.items.forEach(function(d, i) {
        $('#artists').append(`
            <div class="artist">
                <div class="artist-img-container">
                    <img src="${d.images[0].url}" class="artist-img"/>
                </div>
                <div class="content-wrapper">
                    <div class="content">
                        <a href="${d.external_urls.spotify}"class="name" target="_blank">${d.name}</a>
                        <p>Total followers: <span class="followers">${parseInt(d.followers.total).toLocaleString()}</span></p>
                    </div>
                </div>
            </div>
        `);
    });
}

function buildTracks(data) {
    data.items.forEach(function(d, i) {
        $('#tracks').append(`
            <div class="track">
                <div class="track-img-container">
                    <img src="${d.album.images[0].url}" class="track-img"/>
                    <div class="overlay" onclick="toggleAudio(this, '${d.preview_url}')">
                        <i class="fas fa-play overlay-icon"></i>
                    </div>
                    <audio>
                        <source src="${d.preview_url}" type="audio/mp3">
                    </audio>
                </div>
                <div class="content-wrapper">
                    <div class="content">
                        <a href="${d.external_urls.spotify}" class="name" target="_blank">${d.name}</a>
                        <p>${parseArtists(d.artists)}</p>
                    </div>
                </div>
            </div>
        `);
    });
}

function parseArtists(data) {
    var str = '';
    var length = data.length - 1;
    data.forEach(function(d, i) {
        if (i === length) {
            str += `<a href="${d.external_urls.spotify}" class="track-artist" target="_blank">${d.name}</a>`;
        } else {
            str += `<a href="${d.external_urls.spotify}" class="track-artist" target="_blank">${d.name}</a><span>, </span>`;
        }
    });
    return str;
}

function buildFromStored(artist_data, track_data) {
    try {
        buildFeatured(JSON.parse(artist_data));
        buildArtists(JSON.parse(artist_data));
        buildTracks(JSON.parse(track_data));
    } catch (err) {
        startQuery();
    }
}

$(function() {
    $(window).scroll(function(){
        $('.arrow').css('opacity', 1 - $(window).scrollTop() / 100); 
    }); 
    
    toggleAudio = function(elem, url) {
        var not_playing = $(elem).find('i').hasClass('fa-play');
        $('.fa-pause').toggleClass('fa-pause fa-play');
        (current_song && current_song.pause());
        if (not_playing) {
            $(elem).find('i').toggleClass('fa-pause fa-play');
            current_song = new Audio(url);
            current_song.play();
        }
    }
    
    $('.fa-angle-down').click(function() {
        $('#list-container').get(0).scrollIntoView();
    });
});
