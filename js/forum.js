// Forum JavaScript
// 90s style forum functionality

// Update forum statistics
function updateForumStats() {
    var totalMessages = document.getElementById("total-messages");
    var totalVisitors = document.getElementById("total-visitors");
    var latestMessage = document.getElementById("latest-message");
    
    if (totalMessages) {
        // Simulate increasing message count
        var currentCount = parseInt(totalMessages.innerHTML) || 5;
        totalMessages.innerHTML = currentCount + Math.floor(Math.random() * 3);
    }
    
    if (totalVisitors) {
        // Simulate increasing visitor count
        var currentVisitors = parseInt(totalVisitors.innerHTML) || 42;
        totalVisitors.innerHTML = currentVisitors + Math.floor(Math.random() * 5);
    }
    
    if (latestMessage) {
        // Update latest message timestamp
        var now = new Date();
        var months = ["January", "February", "March", "April", "May", "June",
                     "July", "August", "September", "October", "November", "December"];
        var dateStr = months[now.getMonth()] + " " + now.getDate() + ", " + now.getFullYear();
        latestMessage.innerHTML = dateStr;
    }
}

// 90s style form validation
function validateForumForm() {
    var form = document.querySelector('form[action="php/forum.php"]');
    
    if (!form) return;
    
    form.onsubmit = function(e) {
        var author = document.getElementById("author").value.trim();
        var subject = document.getElementById("subject").value.trim();
        var message = document.getElementById("message").value.trim();
        
        if (!author || !subject || !message) {
            alert("Please fill in all required fields!\n\nRequired fields:\n- Your Name\n- Subject\n- Message");
            e.preventDefault();
            return false;
        }
        
        if (message.length < 10) {
            alert("Your message is too short! Please write at least 10 characters.");
            e.preventDefault();
            return false;
        }
        
        if (message.length > 1000) {
            alert("Your message is too long! Please keep it under 1000 characters.");
            e.preventDefault();
            return false;
        }
        
        // 90s style confirmation dialog
        var confirmed = confirm("Are you sure you want to post this message?\n\nYour message will be reviewed before appearing on the forum.");
        if (!confirmed) {
            e.preventDefault();
            return false;
        }
        
        return true;
    };
}

// Add character counter to message textarea
function addCharacterCounter() {
    var messageField = document.getElementById("message");
    
    if (!messageField) return;
    
    // Create counter element
    var counter = document.createElement("div");
    counter.id = "char-counter";
    counter.style.cssText = "font-size: 10px; color: #666; margin-top: 2px;";
    
    // Insert after textarea
    messageField.parentNode.insertBefore(counter, messageField.nextSibling);
    
    // Update counter function
    function updateCounter() {
        var length = messageField.value.length;
        var remaining = 1000 - length;
        
        counter.innerHTML = length + " characters (max 1000)";
        
        if (remaining < 100) {
            counter.style.color = "#ff0000";
        } else if (remaining < 200) {
            counter.style.color = "#ff8800";
        } else {
            counter.style.color = "#666666";
        }
    }
    
    // Update on keyup (90s style event handling)
    messageField.onkeyup = updateCounter;
    messageField.onchange = updateCounter;
    
    // Initial update
    updateCounter();
}

// 90s style message preview
function addMessagePreview() {
    var messageField = document.getElementById("message");
    var subjectField = document.getElementById("subject");
    var authorField = document.getElementById("author");
    
    if (!messageField || !subjectField || !authorField) return;
    
    // Create preview button
    var previewBtn = document.createElement("input");
    previewBtn.type = "button";
    previewBtn.value = "Preview Message";
    previewBtn.style.cssText = "margin-left: 10px;";
    
    // Add preview functionality
    previewBtn.onclick = function() {
        var author = authorField.value.trim() || "Anonymous";
        var subject = subjectField.value.trim() || "No Subject";
        var message = messageField.value.trim() || "No message";
        
        // Create preview window
        var previewWindow = window.open("", "messagePreview", "width=500,height=400,scrollbars=yes,resizable=yes");
        
        previewWindow.document.write("<!DOCTYPE html><html><head><title>Message Preview</title>");
        previewWindow.document.write("<style>");
        previewWindow.document.write("body { font-family: 'Times New Roman', serif; background: #c0c0c0; margin: 10px; }");
        previewWindow.document.write(".forum-post { background: #ffffcc; border: 1px solid #000; padding: 10px; margin: 10px 0; }");
        previewWindow.document.write(".author { font-weight: bold; color: #800000; }");
        previewWindow.document.write(".date { font-size: 10px; color: #666; }");
        previewWindow.document.write("</style>");
        previewWindow.document.write("</head><body>");
        previewWindow.document.write("<h2>Message Preview</h2>");
        previewWindow.document.write("<div class='forum-post'>");
        previewWindow.document.write("<div class='author'>📝 " + author + "</div>");
        previewWindow.document.write("<div class='date'>" + new Date().toLocaleString() + "</div>");
        previewWindow.document.write("<div class='subject'><strong>" + subject + "</strong></div>");
        previewWindow.document.write("<div class='content'><p>" + message.replace(/\n/g, "<br>") + "</p></div>");
        previewWindow.document.write("</div>");
        previewWindow.document.write("<p><button onclick='window.close()'>Close Preview</button></p>");
        previewWindow.document.write("</body></html>");
        
        previewWindow.document.close();
    };
    
    // Add preview button to form
    var submitBtn = document.querySelector('input[type="submit"]');
    if (submitBtn) {
        submitBtn.parentNode.insertBefore(previewBtn, submitBtn);
    }
}

// Add 90s style typing sound effects (if available)
function addTypingSounds() {
    var messageField = document.getElementById("message");
    
    if (!messageField) return;
    
    // 90s style typing feedback
    messageField.onkeydown = function(e) {
        // Change background color briefly to show typing
        this.style.backgroundColor = "#ffffcc";
        
        setTimeout(function() {
            messageField.style.backgroundColor = "#ffffff";
        }, 100);
    };
}

// Initialize forum functionality
function initForum() {
    updateForumStats();
    validateForumForm();
    addCharacterCounter();
    addMessagePreview();
    addTypingSounds();
    
    // Update stats periodically
    setInterval(updateForumStats, 30000); // Every 30 seconds
    
    // 90s style status bar messages
    if (window.status !== undefined) {
        setTimeout(function() {
            window.status = "Ready to post your message!";
        }, 1000);
    }
}

// Wait for page to load (90s style)
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initForum);
} else {
    initForum();
}