let ppSlideIndex = 1;
ppShowSlides(ppSlideIndex);

// Next/previous controls
function ppPlusSlides(n) {
  ppShowSlides(ppSlideIndex += n);
}

// Thumbnail image controls
function ppCurrentSlide(n) {
  ppShowSlides(ppSlideIndex = n);
}

function ppShowSlides(n) {
  let i;
  let slides = document.getElementsByClassName("pp-slide");
  let dots = document.getElementsByClassName("pp-dot");
  if (!slides || slides.length === 0) return;
  
  if (n > slides.length) {ppSlideIndex = 1}
  if (n < 1) {ppSlideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" pp-active", "");
  }
  slides[ppSlideIndex-1].style.display = "block";
  if (dots.length > 0) {
    dots[ppSlideIndex-1].className += " pp-active";
  }
}

// Auto slideshow functionality
setInterval(function() {
    ppPlusSlides(1);
}, 5000); // Change image every 5 seconds

// Theme Toggle functionality (Dark/Light mode)
document.addEventListener("DOMContentLoaded", () => {
    const themeToggle = document.getElementById("theme-toggle");
    if (themeToggle) {
        // Check for saved theme preference in cookies
        const savedTheme = getCookie("theme");
        if (savedTheme === "light") {
            document.body.classList.add("light-mode");
            themeToggle.textContent = "☀️";
        }

        themeToggle.addEventListener("click", () => {
            document.body.classList.toggle("light-mode");
            let theme = "dark";
            if (document.body.classList.contains("light-mode")) {
                theme = "light";
                themeToggle.textContent = "☀️";
            } else {
                themeToggle.textContent = "🌙";
            }
            // Save preference to cookie (expires in 30 days)
            setCookie("theme", theme, 30);
        });
    }

    // Easter Egg (Konami Code)
    let konamiCode = ["ArrowUp", "ArrowUp", "ArrowDown", "ArrowDown", "ArrowLeft", "ArrowRight", "ArrowLeft", "ArrowRight", "b", "a"];
    let konamiIndex = 0;
    document.addEventListener("keydown", (e) => {
        if (e.key === konamiCode[konamiIndex]) {
            konamiIndex++;
            if (konamiIndex === konamiCode.length) {
                alert("🎮 Cheat Code Activated: Je hebt de Easter Egg gevonden!");
                document.body.style.transform = "rotate(180deg)";
                setTimeout(() => document.body.style.transform = "rotate(0deg)", 3000);
                konamiIndex = 0;
            }
        } else {
            konamiIndex = 0;
        }
    });
});

// Helper functions for cookies
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(let i=0;i < ca.length;i++) {
        let c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
