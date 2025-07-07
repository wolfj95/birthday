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

// 90s style photo loading function
function loadPhotos() {
    var galleryGrid = document.getElementById("gallery-grid");
    var loadingMessage = document.getElementById("loading-message");
    
    if (!galleryGrid || !loadingMessage) {
        return;
    }
    
    // Show loading message
    loadingMessage.style.display = "block";
    
    // Simulate 90s-style slow loading
    setTimeout(function() {
        loadingMessage.style.display = "none";
        galleryGrid.style.display = "block";
        
        // Add photos one by one (90s style progressive loading)
        for (var i = 0; i < samplePhotos.length; i++) {
            (function(index) {
                setTimeout(function() {
                    addPhotoToGallery(samplePhotos[index]);
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
    photoContainer.style.cssText = "margin: 10px; display: inline-block; border: 2px solid #000; background: #fff; padding: 5px;";
    
    // Create image element
    var img = document.createElement("img");
    img.src = photo.url;
    img.alt = photo.title;
    img.style.cssText = "width: 150px; height: 112px; display: block;";
    
    // Create title
    var title = document.createElement("div");
    title.innerHTML = "<strong>" + photo.title + "</strong>";
    title.style.cssText = "font-size: 12px; text-align: center; margin-top: 5px;";
    
    // Create description
    var description = document.createElement("div");
    description.innerHTML = photo.description;
    description.style.cssText = "font-size: 10px; text-align: center; margin-top: 2px; color: #666;";
    
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

// Alternative Flickr API integration (commented out for now)
/*
function loadFlickrPhotos() {
    // This would integrate with Flickr API to load 1995 photos
    // For now, we use placeholder images
    var flickrUrl = "https://api.flickr.com/services/rest/";
    var apiKey = "YOUR_FLICKR_API_KEY"; // You would need to get this
    var searchTerm = "1995 vintage retro";
    
    // Example API call (requires API key)
    var requestUrl = flickrUrl + "?method=flickr.photos.search&api_key=" + apiKey + 
                    "&tags=" + searchTerm + "&format=json&nojsoncallback=1";
    
    // This would use XMLHttpRequest in real implementation
    console.log("Flickr API integration would go here");
}
*/

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