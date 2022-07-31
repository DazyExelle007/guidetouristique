/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './scss/app.scss';

// start the Stimulus application
import  $ from 'jquery';
import './bootstrap';



const filter_btns = document.querySelectorAll('.filter-btn');
const footer_input = document.querySelector(".footer-input");
const hamburger_menu = document.querySelector(".hamburger-menu");
const navbar = document.querySelector("header nav");
const links = document.querySelectorAll(".nav-links a");

footer_input.addEventListener('focus', () => {
 // alert('bonjours');
  footer_input.classList.add('focus');
});

filter_btns.forEach(btn => btn.addEventListener('click', ()=>{
  filter_btns.forEach(button => button.classList.remove('active'));
  btn.classList.add('active');
  let filterValue = btn.dataset.filter;
  $(".grid").isotope(
    {
      filter:filterValue
    }
  )
}))


function closeMenu(){
navbar.classList.remove("open");
document.body.classList.remove("stop-scrolling")
}

hamburger_menu.addEventListener("click", () =>{
 if(!navbar.classList.contains("open")){
   navbar.classList.add("open");
   document.body.classList.add("stop-scrolling")
 }else{
  closeMenu();
 }
});


links.forEach((link)=>link.addEventListener("click", ()=>closeMenu()))


$('.grid').isotope({
    itemSelector: '.grid-item',
    layoutMode: 'fitRows',
    transitionDuration: "0.6s"
  });




  $(document).ready(function(){
    $('.group-guide').slick({
      dots: false,
      infinite: true,
      speed: 300,
      slidesToShow: 3,
      slidesToScroll: 1,
      prevArrow: $('.prevGuide'),
      nextArrow: $('.nextGuide'),
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: true,
            dots: false
          }
        },
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 580,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });
  
  });