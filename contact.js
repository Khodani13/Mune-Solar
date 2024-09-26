$(function () {
    $("#contactForm input, #contactForm textarea, #contactForm select").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {
            // Optional: Add error handling logic here
        },
        submitSuccess: function ($form, event) {
            event.preventDefault(); // Prevent default submit behavior
            
            // Collect form data
            var name = $("input#name").val();
            var email = $("input#email").val();
            var phone = $("input#phone").val(); 
            var subject = $("input#subject").val();
            var message = $("textarea#message").val();
            //var powerRequirement = $("select#power_requirement").val(); // Get power requirement value
            //var typeOfUse = $("input[name='type_of_use']:checked").val() || 'Not specified'; // Get the selected radio button value
            var type_of_use = $('input[name="type_of_use"]:checked').val(); // For radio buttons
            var power_requirement = $('#power_requirement').val(); // For dropdown
            
            var $this = $("#sendMessageButton");
            $this.prop("disabled", true); // Disable submit button until AJAX call is complete to prevent duplicate messages

            $.ajax({
                url: "contact.php",
                type: "POST",
                data: {
                    name: name,
                    email: email,
                    phone: phone,
                    subject: subject,
                    message: message,
                    power_requirement: powerRequirement, // Include power requirement
                    type_of_use: typeOfUse // Include type of use
                    
                },
                cache: false,
                success: function (response) {
                    // Show success message
                    $('#success').html("<div class='alert alert-success'>");
                    $('#success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#success > .alert-success')
                        .append("<strong>Your message has been sent. </strong>");
                    $('#success > .alert-success')
                        .append('</div>');

                    // Clear all fields
                    $('#contactForm').trigger("reset");
                },
                error: function (xhr, status, error) {
                    // Show error message with specific error returned by PHP
                    $('#success').html("<div class='alert alert-danger'>");
                    $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#success > .alert-danger').append($("<strong>").text("Sorry, there was an error: " + xhr.responseJSON.error + ". Please try again later!"));
                    $('#success > .alert-danger').append('</div>');

                    // Clear all fields
                    $('#contactForm').trigger("reset");
                },
                complete: function () {
                    setTimeout(function () {
                        $this.prop("disabled", false); // Re-enable submit button when AJAX call is complete
                    }, 1000);
                }
            });
        },
        filter: function () {
            return $(this).is(":visible");
        },
    });

    // Clear success/error messages on focus
    $('#contactForm input, #contactForm textarea').focus(function () {
        $('#success').html('');
    });
    document.getElementById('contactForm').addEventListener('submit', function(event) {
        var typeOfUseChecked = document.querySelector('input[name="type_of_use"]:checked');
        var powerRequirement = document.getElementById('power_requirement').value;
    
        if (!typeOfUseChecked) {
            event.preventDefault(); // Prevent form submission
            alert('Please select a type of use.');
            // You can also display an error message in the 'help-block' div
        }
    
        if (!powerRequirement) {
            event.preventDefault(); // Prevent form submission
            alert('Please select a power requirement.');
            // Again, display an error message in the 'help-block'
        }
    });
    
});
