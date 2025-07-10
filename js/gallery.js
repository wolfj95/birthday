// Photo Gallery JavaScript
// 90s style photo loading and display

// Sample 1995-themed photos (placeholder URLs)
var samplePhotos = [
    {
        url: "https://picsum.photos/200/150?random=1",
        title: "Windows 95 Launch",
        description: "The revolutionary operating system that changed everything!"
    },
    {
        url: "https://picsum.photos/200/150?random=2", 
        title: "Grunge Music Scene",
        description: "The alternative rock movement that defined the 90s"
    },
    {
        url: "https://picsum.photos/200/150?random=3",
        title: "First Website",
        description: "What the early web looked like in 1995"
    },
    {
        url: "https://picsum.photos/200/150?random=4",
        title: "Nintendo Game Boy",
        description: "Portable gaming was revolutionized"
    },
    {
        url: "https://picsum.photos/200/150?random=5",
        title: "Dial-up Internet",
        description: "That beautiful sound of connection"
    },
    {
        url: "https://picsum.photos/200/150?random=6",
        title: "Toy Story",
        description: "The first fully computer-animated film"
    },
    {
        url: "https://picsum.photos/200/150?random=7",
        title: "CD-ROM Games",
        description: "The future of gaming storage"
    },
    {
        url: "https://picsum.photos/200/150?random=8",
        title: "Pagers",
        description: "Before cell phones, there were beepers"
    }
];

// 90s style photo loading function with local 1995 photos
function loadPhotos() {
    var galleryGrid = document.getElementById("gallery-grid");
    var loadingMessage = document.getElementById("loading-message");
    
    if (!galleryGrid || !loadingMessage) {
        return;
    }
    
    // Show loading message
    loadingMessage.style.display = "block";
    
    // Fetch 1995 photos from JSON file
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'js/1995-photos.json', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.photos && response.photos.length > 0) {
                        // Randomly select 20 photos from the collection
                        var randomPhotos = getRandomPhotos(response.photos, 20);
                        displayPhotos(randomPhotos);
                    } else {
                        // Fallback to sample photos
                        displayPhotos(samplePhotos);
                    }
                } catch (e) {
                    // Fallback to sample photos
                    displayPhotos(samplePhotos);
                }
            } else {
                // Fallback to sample photos
                displayPhotos(samplePhotos);
            }
        }
    };
    xhr.send();
}

// Get random photos from array
function getRandomPhotos(photos, count) {
    var shuffled = photos.slice();
    for (var i = shuffled.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = shuffled[i];
        shuffled[i] = shuffled[j];
        shuffled[j] = temp;
    }
    return shuffled.slice(0, count);
}

// Display photos with 90s-style progressive loading
function displayPhotos(photos) {
    var galleryGrid = document.getElementById("gallery-grid");
    var loadingMessage = document.getElementById("loading-message");
    
    // Simulate 90s-style slow loading
    setTimeout(function() {
        loadingMessage.style.display = "none";
        galleryGrid.style.display = "block";
        
        // Add photos one by one (90s style progressive loading)
        var photosToShow = Math.min(photos.length, 20);
        for (var i = 0; i < photosToShow; i++) {
            (function(index) {
                setTimeout(function() {
                    addPhotoToGallery(photos[index]);
                }, index * 500); // 500ms delay between each photo
            })(i);
        }
    }, 2000);
}

// Add individual photo to gallery
function addPhotoToGallery(photo) {
    var galleryGrid = document.getElementById("gallery-grid");
    
    // Create photo container
    var photoContainer = document.createElement("div");
    photoContainer.className = "photo-container";
    photoContainer.style.cssText = "margin: 10px; display: inline-block; border: 2px solid #000; background: #fff; padding: 5px; width: 160px;";
    
    // Create image element
    var img = document.createElement("img");
    img.src = photo.url;
    img.alt = photo.title;
    img.style.cssText = "width: 150px; height: auto; display: block;";
    
    // Create title
    var title = document.createElement("div");
    title.innerHTML = "<strong>" + photo.title + "</strong>";
    title.style.cssText = "font-size: 12px; text-align: center; margin-top: 5px; width: 150px; word-wrap: break-word;";
    
    // Create description
    var description = document.createElement("div");
    description.innerHTML = photo.description;
    description.style.cssText = "font-size: 10px; text-align: center; margin-top: 2px; color: #666; width: 150px; word-wrap: break-word;";
    
    // Add click event (90s style popup)
    img.onclick = function() {
        showPhotoPopup(photo);
    };
    
    // Add hover effect
    img.onmouseover = function() {
        this.style.border = "2px solid #ff0000";
        this.style.cursor = "pointer";
    };
    
    img.onmouseout = function() {
        this.style.border = "none";
    };
    
    // Assemble container
    photoContainer.appendChild(img);
    photoContainer.appendChild(title);
    photoContainer.appendChild(description);
    
    // Add to gallery
    galleryGrid.appendChild(photoContainer);
}

// 90s style photo popup
function showPhotoPopup(photo) {
    var popup = window.open("", "photoPopup", "width=400,height=300,scrollbars=yes,resizable=yes");
    
    popup.document.write("<!DOCTYPE html><html><head><title>" + photo.title + "</title>");
    popup.document.write("<style>body { font-family: 'Times New Roman', serif; background: #c0c0c0; margin: 10px; }</style>");
    popup.document.write("</head><body>");
    popup.document.write("<h2>" + photo.title + "</h2>");
    popup.document.write("<img src='" + photo.url + "' alt='" + photo.title + "' style='max-width: 100%; border: 2px solid #000;'>");
    popup.document.write("<p>" + photo.description + "</p>");
    popup.document.write("<p><button onclick='window.close()'>Close Window</button></p>");
    popup.document.write("</body></html>");
    
    popup.document.close();
}


// Initialize gallery when page loads
function initGallery() {
    loadPhotos();
    
    // Add some 90s-style status updates
    if (window.status !== undefined) {
        setTimeout(function() {
            window.status = "Loading awesome 1995 photos...";
        }, 1000);
        
        setTimeout(function() {
            window.status = "Gallery loaded! Click photos for details.";
        }, 5000);
    }
}

// Wait for page to load (90s style)
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initGallery);
} else {
    initGallery();
}