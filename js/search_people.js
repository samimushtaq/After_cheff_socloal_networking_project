$(document).ready(function() {
    $('#searchForm').submit(function(event) {
        event.preventDefault();
        const query = $('#searchQuery').val();

        $.get('php/search_people.php', { query: query }, function(response) {
            const data = JSON.parse(response);
            let output = '';

            if (data.message) {
                output = `<p>${data.message}</p>`;
            } else {
                data.forEach(user => {
                    output += `<div>
                        <img src="php/${user.profile_image}" alt="${user.name}'s profile image">
                        <p><a href="view_profile.php?user_id=${user.id}"style="color:white;">${user.name} (@${user.username})</a></p>
                        ${user.request_status ? 
                            `<button onclick="cancelRequest(${user.id})">Cancel Request</button>` : 
                            `<button onclick="sendRequest(${user.id})">Send Request</button>`
                        }
                    </div>`;
                });
            }
            $('#searchResults').html(output);
        });
    });

    window.sendRequest = function(receiverId) {
        $.post('php/search_people.php', { action: 'send', receiver_id: receiverId }, function(response) {
            alert(response.message);
            if (response.success) {
                updateRequestButton(receiverId, 'send');
            }
        }, 'json');
    }

    window.cancelRequest = function(receiverId) {
        $.post('php/search_people.php', { action: 'cancel', receiver_id: receiverId }, function(response) {
            alert(response.message);
            if (response.success) {
                updateRequestButton(receiverId, 'cancel');
            }
        }, 'json');
    }

    function updateRequestButton(userId, action) {
        const button = $(`#searchResults button[onclick*="${action}Request(${userId})"]`);
        if (action === 'send') {
            button.text('Cancel Request');
            button.attr('onclick', `cancelRequest(${userId})`);
        } else if (action === 'cancel') {
            button.text('Send Request');
            button.attr('onclick', `sendRequest(${userId})`);
        }
    }
});
