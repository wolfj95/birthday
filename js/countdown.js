// Birthday Countdown Timer
// Pure 90s JavaScript - no modern features!

function updateCountdown() {
    // Set the birthday date (March 15, 2025, 7:00 PM)
    var birthdayDate = new Date("November 10, 2025 19:00:00").getTime();
    var now = new Date().getTime();
    var distance = birthdayDate - now;
    
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Display the result
    var countdownElement = document.getElementById("countdown-timer");
    
    if (distance > 0) {
        countdownElement.innerHTML = 
            "<strong>" + days + "</strong> days, " +
            "<strong>" + hours + "</strong> hours, " +
            "<strong>" + minutes + "</strong> minutes, " +
            "<strong>" + seconds + "</strong> seconds " +
            "until the party!";
    } else {
        countdownElement.innerHTML = "<strong>🎉 IT'S PARTY TIME! 🎉</strong>";
    }
}

// 90s style function to add blinking effect
function addBlinkingEffect() {
    var blinkElements = document.getElementsByClassName("blink");
    for (var i = 0; i < blinkElements.length; i++) {
        blinkElements[i].style.visibility = 
            (blinkElements[i].style.visibility === "hidden") ? "visible" : "hidden";
    }
}

// Initialize countdown when page loads
function initCountdown() {
    updateCountdown();
    
    // Update countdown every second
    setInterval(updateCountdown, 1000);
    
    // Add blinking effect every 500ms (authentic 90s style!)
    setInterval(addBlinkingEffect, 1000);
    
    // Add some 90s-style status bar messages
    if (window.status !== undefined) {
        var statusMessages = [
            "Welcome to my birthday website!",
            "Don't forget to RSVP!",
            "Party like it's 1995!",
            "Check out the photo gallery!",
            "Post a message in the forum!",
            "Browse the cool links!"
        ];
        
        var currentMessage = 0;
        setInterval(function() {
            window.status = statusMessages[currentMessage];
            currentMessage = (currentMessage + 1) % statusMessages.length;
        }, 3000);
    }
}

// Wait for page to load (90s style)
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initCountdown);
} else {
    initCountdown();
}

// Add some fun 90s-style window effects
function add90sEffects() {
    // Change title periodically (like old websites used to do)
    var originalTitle = document.title;
    var flashingTitle = "<3 " + originalTitle + " <3";
    var titleFlash = false;
    
    setInterval(function() {
        document.title = titleFlash ? flashingTitle : originalTitle;
        titleFlash = !titleFlash;
    }, 2000);
}

// Initialize 90s effects
setTimeout(add90sEffects, 1000);