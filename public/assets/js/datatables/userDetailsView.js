$(document).ready(function () {

    $('.open-modal').click(function (e) {
        e.preventDefault();

        var userId = $(this).data('id');

        $.ajax({
            url: '/user/view/' + userId,
            method: 'GET',
            success: function (response) {
                $('#modalFirstName').text(response.first_name ? response.first_name : 'N/A');
                $('#modalLastName').text(response.last_name ? response.last_name : 'N/A');
                $('#modalEmail').text(response.email ? response.email : 'N/A');
                $('#modalUserGroupID').text(response.user_group_id ? response.user_group_id : 'N/A');
                $('#modalIsApproved').text(response.is_approved ? response.is_approved : 'N/A');
                $('#modalStatus').text(response.status == 1 ? 'Active' : 'Inactive');
                $('#modalPhoto').text(response.photo ? response.photo : 'N/A');
                $('#modalNationalID').text(response.national_id ? response.national_id : 'N/A');
                $('#modalBirthDate').text(response.birth_date ? response.birth_date : 'N/A');
                $('#modalPresentAddress').text(response.present_address ? response.present_address : 'N/A');
                $('#modalPermanentAddress').text(response.permanent_address ? response.permanent_address : 'N/A');
                $('#modalGender').text(response.gender ? response.gender : 'N/A');
                $('#modalMobile').text(response.mobile ? response.mobile : 'N/A');
                $('#viewUserDetailsModal').modal('show');
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});
