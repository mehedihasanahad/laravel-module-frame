$(document).ready(function() {

    $('.open-edit-modal').click(function(e) {
        e.preventDefault();
        var edituserId = $(this).data('id');
        $('#userIdInput').val(edituserId);
        $.ajax({
            url: '/user/edit/' + edituserId,
            method: 'GET',
            success: function(response) {
                $('#firstNameInput').val(response.first_name);
                $('#lastNameInput').val(response.last_name);
                $('#nationalIDInput').val(response.national_id);
                $('#birthDateInput').val(response.birth_date);
                // Show the edit modal
                $('#edituserDetailsModal').modal('show');
            },
            error: function(error) {
                console.log(error);
            }
        });


    });




});
