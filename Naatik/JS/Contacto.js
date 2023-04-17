window.onload = function () {
  window.scrollTo(0, 0);
}

window.sr = ScrollReveal();
  sr.reveal('.navbar', {
    duration: 1500,
    origin: 'bottom'
  });
  sr.reveal('.scroll-sub', {
    duration: 2000,
    origin: 'bottom'
  });
  sr.reveal('.scroll-contacto', {
    duration: 1500,
    origin: 'bottom',
    distance: '4rem'
  });
  sr.reveal('.scroll-estancias', {
    duration: 1500,
    origin: 'bottom'
  });
  sr.reveal('.scroll-footer', {
    duration: 1500,
    origin: 'bottom',
    distance: '4rem'
  });


const accordionItemHeaders = document.querySelectorAll(".accordion-item-header");

accordionItemHeaders.forEach(accordionItemHeader => {
  accordionItemHeader.addEventListener("click", event => {

    accordionItemHeader.classList.toggle("active");
    const accordionItemBody = accordionItemHeader.nextElementSibling;
    if(accordionItemHeader.classList.contains("active")) {
      accordionItemBody.style.maxHeight = accordionItemBody.scrollHeight + "px";
    }
    else {
      accordionItemBody.style.maxHeight = 0;
    }

  });
});

// When the user scrolls the page, execute myFunction
window.onscroll = function() {myFunction()};

// Get the navbar
var navbar = document.getElementById("navbar");

// Get the offset position of the navbar
var sticky = navbar.offsetTop;

// Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}
