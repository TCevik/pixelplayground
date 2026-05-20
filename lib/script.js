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
