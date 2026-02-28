const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
const navbar = document.getElementById('navbar');
const form = document.getElementById('profileForm');
const logoutBtn = document.getElementById('logoutBtn');

/* Mobile Nav */
mobileNavToggle.addEventListener('click', () => {
    const visibility = navbar.getAttribute('data-visible');

    if (visibility === "false") {
        navbar.setAttribute('data-visible', "true");
        mobileNavToggle.setAttribute('aria-expanded', "true");
    } else {
        navbar.setAttribute('data-visible', "false");
        mobileNavToggle.setAttribute('aria-expanded', "false");
    }
});

/* Form Validation */
form.addEventListener('submit', function(e) {
    e.preventDefault();

    const phone = document.getElementById("Pnumber").value;

    const phonePattern = /^\+?[0-9]{7,15}$/;

    if (!phonePattern.test(phone)) {
        alert("Please enter a valid phone number (7–15 digits).");
        return;
    }

    alert("Profile updated successfully!");
});

/* Logout */
logoutBtn.addEventListener('click', () => {
    if (confirm("Are you sure you want to sign out?")) {
        window.location.href = "../frontend/Log-in.html";
    }
});