document.getElementById("contactForm").addEventListener("submit", function(event) {
    const email = document.getElementById("email").value;
    const phone = document.getElementById("phone").value;
    const phonePattern = /^[0-9]{10}$/;

    if (!email.includes("@") || !phone.match(phonePattern)) {
        alert("Please provide a valid email and 10-digit phone number.");
        event.preventDefault();
    }
});

document.getElementById("appointmentForm").addEventListener("submit", function(event) {
    const date = document.getElementById("date").value;
    if (!date) {
        alert("Please select a date.");
        event.preventDefault();
    }
});
