document.querySelector('#contactForm').addEventListener('submit', function(event) {
    event.preventDefault();  // Prevent default form submission

    let formData = new FormData(this);  // Collect form data

    // Perform basic validation
    if (!document.querySelector('input[name="type_of_use"]:checked')) {
        alert("Please select a Type of Use");
        return;
    }
    if (!document.querySelector('#power_requirement').value) {
        alert("Please select a Power Requirement");
        return;
    }

    fetch('contact.php', {
        method: 'POST',  // Use POST method
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Message sent successfully!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});
