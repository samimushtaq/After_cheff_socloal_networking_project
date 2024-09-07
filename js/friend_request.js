$(document).ready(function() {
    window.respondToRequest = function(requestId, status) {
        $.post('php/respond_to_request.php', { request_id: requestId, status: status }, function(response) {
            console.log(response); // Log the response for debugging
            if (response.success) {
                location.reload();
            } else {
                alert(response.message);
            }
        }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Error: ' + textStatus + ', ' + errorThrown); // Log AJAX errors
            alert('An error occurred while processing your request.');
        });
    }
});
