
function likeMovie(imdbID) {
    jQuery.ajax({
        url: movieClubAjax.ajax_url,
        type: 'post',
        data: {
            action: 'like_movie',
            imdb_id: imdbID
        },
        success: function(response) {
            if (response.success) {
                jQuery('#thumbsUp_' + imdbID).text(response.data.likes);
            }
        },
        error: function() {
            alert('An error occurred. Please try again.');
        }
    });
}

function dislikeMovie(imdbID) {
    jQuery.ajax({
        url: movieClubAjax.ajax_url,
        type: 'post',
        data: {
            action: 'dislike_movie',
            imdb_id: imdbID
        },
        success: function(response) {
            if (response.success) {
                jQuery('#thumbsDown_' + imdbID).text(response.data.dislikes);
            }
        },
        error: function() {
            alert('An error occurred. Please try again.');
        }
    });
}