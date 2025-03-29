$(document).ready(function() {
    $('#submitBtn').click(function() {
        var name = $('#name_chk').val();
        var email = $('#email_chk').val();
        var password = $('#password_chk').val();
        var confirmPassword = $('#conform_password_chk').val();
        var agreeTerms = $('#form2Example3c').prop('checked');

        // Reset previous error messages
        $('.error-message').remove();

        // Validate name field
        if (name === '') {
            $('#name_error').after(' <span class="error-message">Please enter your name</span>');
            return false;
        }

        // Validate email field
        if (email === '') {
            $('#email_error').after('<span class="error-message">Please enter your email</span>');
            return false;
        } else if (!validateEmail(email)) {
            $('#email_error').after('<span class="error-message">Please enter a valid email</span>');
            return false;
        }


        // Validate password field
        if (password === '') {
            $('#password_error').after('<span class="error-message">Please enter a password</span>');
            return false;
        } else if (password.length < 8) {
            $('#password_error').after('<span class="error-message">Password must be at least 8 characters long</span>');
            return false;
        }

        // Validate confirm password field
        if (confirmPassword === '') {
            $('#conform_password_error').after('<span class="error-message">Please confirm your password</span>');
            return false;
        } else if (password !== confirmPassword) {
            $('#conform_password_error').after('<span class="error-message">Passwords do not match</span>');
            return false;
        }

        // Validate terms agreement checkbox
        if (!agreeTerms) {
            $('.form-check-label').after('<span class="error-message">Please agree to the Terms of Service</span>');
            return false;
        }

        // Email validation function
        function validateEmail(email) {
            var re = /^[a-z._%+-][a-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
            return re.test(email);
        }

        $("#RegistrationForm").submit();
    });
});