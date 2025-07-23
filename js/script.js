navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   profile.classList.remove('active');
}

function loader(){
   document.querySelector('.loader').style.display = 'none';
}

function fadeOut(){
   setInterval(loader, 1000);
}

window.onload = fadeOut;

document.querySelectorAll('input[type="number"]').forEach(numberInput => {
   numberInput.oninput = () =>{
      if(numberInput.value.length > numberInput.maxLength) numberInput.value = numberInput.value.slice(0, numberInput.maxLength);
   };
});

const swiper = new Swiper('.swiper', {
   loop: true,
 
   
   autoplay: {
      delay: 2500, 
      disableOnInteraction: false, 
      
   },

   
   pagination: {
     el: '.swiper-pagination',
     clickable: true
   },
 
   
   navigation: {
     nextEl: '.swiper-button-next',
     prevEl: '.swiper-button-prev',
   },
 });


 function scrollToTop() {
   window.scrollTo({ top: 0, behavior: 'smooth' });
}

window.onload = function() {
   var scrollButton = document.querySelector('.scroll-to-top');
   scrollButton.classList.add('hide'); 
};

window.onscroll = function() {
   var scrollButton = document.querySelector('.scroll-to-top');
   if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
      scrollButton.classList.remove('hide');
      scrollButton.classList.add('show');
   } else {
      scrollButton.classList.remove('show');
      scrollButton.classList.add('hide');
   }
}