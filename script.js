document.addEventListener("DOMContentLoaded", function() {
    let links = document.querySelectorAll("nav a.nav-link");
    let homeContent = document.getElementById("home");
    let galleryContent = document.querySelector(".gallery");
    let galleryTitle = document.querySelector(".gallery-title");
    let blogContent = document.querySelector(".blog");
    let contactContent = document.querySelector(".contact");

    links.forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault();
            links.forEach(l => l.classList.remove("active"));
            this.classList.add("active");

            homeContent.style.display = "none";
            galleryContent.style.display = "none";
            galleryTitle.style.display = "none";
            blogContent.style.display = "none";
            contactContent.style.display = "none";

            if (this.getAttribute("href") === "index.html") {
                homeContent.style.display = "flex";
            } else if (this.getAttribute("href") === "gallery.html") {
                galleryContent.style.display = "flex";
                galleryTitle.style.display = "block";
            } else if (this.getAttribute("href") === "blog.html") {
                blogContent.style.display = "block";
            } else if (this.getAttribute("href") === "contact.html") {
                contactContent.style.display = "block";
            }
        });
    });
});
