document.addEventListener("DOMContentLoaded", function () {
    // Navbar shrink effect on scroll
    const navbar = document.querySelector(".navbar");
    window.addEventListener("scroll", function () {
        if (window.scrollY > 50) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });

    // Smooth scrolling for navigation links
    document.querySelectorAll(".nav-links a").forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const targetId = this.getAttribute("href").substring(1);
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                window.scrollTo({
                    top: targetSection.offsetTop - 80,
                    behavior: "smooth"
                });
            }
        });
    });

    // Scroll-to-top button
    const scrollTopBtn = document.querySelector(".scroll-top");
    window.addEventListener("scroll", function () {
        if (window.scrollY > 300) {
            scrollTopBtn.style.display = "block";
        } else {
            scrollTopBtn.style.display = "none";
        }
    });

    scrollTopBtn.addEventListener("click", function () {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const logosContainer = document.querySelector(".logos");

    logosContainer.addEventListener("mouseenter", () => {
        logosContainer.style.animationPlayState = "paused"; // Hover par stop
    });

    logosContainer.addEventListener("mouseleave", () => {
        logosContainer.style.animationPlayState = "running"; // Hover hatate hi resume
    });
});
document.addEventListener("DOMContentLoaded", function () {
    let sections = document.querySelectorAll("section");

    function revealSections() {
        sections.forEach((section) => {
            let sectionTop = section.getBoundingClientRect().top;
            let windowHeight = window.innerHeight;

            if (sectionTop < windowHeight - 100) {
                section.classList.add("visible");
            }
        });
    }

    window.addEventListener("scroll", revealSections);
    revealSections(); // Ensure first sections appear on load
});
document.querySelectorAll(".card").forEach(card => {
    card.addEventListener("click", () => {
        let bgImage = card.querySelector(".card-image").style.backgroundImage;
        document.querySelector(".services-container").style.backgroundImage = bgImage;
        document.querySelector(".services-container").style.backgroundSize = "cover";
        document.querySelector(".services-container").style.backgroundPosition = "center";
        document.querySelector(".services-container").style.transition = "background 0.5s ease-in-out";
    });
});
