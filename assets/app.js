/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

// MENU BURGER
let burger = document.querySelector('#burger');
let menu = document.querySelector('#menu');
let fermer = document.querySelector('#fermer');

burger.addEventListener('click',function(){
    menu.classList.toggle('deploye');
    
    if(menu.classList.contains('transition')){
    setTimeout(function(){
        menu.classList.toggle('transition');
    },1000);
    }
    else {
        menu.classList.toggle('transition');
    }
    });
    
    // fermer.addEventListener('click',function(){
    
    // menu.classList.remove('deploye')
    
    // });
// redimensionnement de fenêtre
window.addEventListener('resize',function(){
    
    console.log(window.innerWidth);
    if (this.window.innerWidth >= 850) {
        menu.classList.remove('deployer','transition');
    }
});

// Controle de presence de l'élément sur la page en cours
if( document.querySelector('.carrousel') ){

    var carrousel = document.querySelector('.carrousel');
    var reglette = document.querySelector('.carrousel .reglette');
    console.log(reglette);

    var figures = document.querySelectorAll('.carrousel .reglette figure');
    var nbFigures = figures.length;

    var tabOrganisation = new Array(nbFigures);

    // Initialisation
    reglette.style.width = 100 * nbFigures + '%';

    for(let i=0; i < nbFigures; i++){
        figures[i].style.order=i;
        figures[i].style.width = (100/nbFigures) + '%';
        tabOrganisation[i] = i;
    }

    // Reattribution des orders
    function attribOrder(){
        for(let i=0; i < nbFigures; i++){
            figures[i].style.order = tabOrganisation[i];
        }
    }

    // Organisation quand on recule
    function previousImage(){
        // attribuer la valeur de order du premier élément au dernier
        let element = tabOrganisation.shift();
        tabOrganisation.push(element);
        attribOrder();
    }

    // Organisation quand on avance
    function nextImage(){
        // attribuer la valeur de order du dernier élément au premier
        let element = tabOrganisation.pop();
        tabOrganisation.unshift(element);
        attribOrder();
    }
    
    function carrouselNext(){
        console.log(reglette);

        reglette.classList.add('animavance');
        setTimeout(function(){

            reglette.style.left=0;
            nextImage();
            reglette.classList.remove('animavance');

        },1000);
    }

    function carrouselPrevious(){
        previousImage();
        reglette.style.left='-100%';

        reglette.classList.add('animrecule');
        setTimeout(function(){
            reglette.classList.remove('animrecule');
            reglette.style.left=0;
        },1000);

    }
    
    let timer1 = setInterval(carrouselNext,3000);

    // mettre en place une pause quand la souris rentre sur le carrousel
    carrousel.addEventListener('mouseenter',function(){
        clearInterval(timer1);
    });

    carrousel.addEventListener('mouseleave',function(){
        clearInterval(timer1); // par sécurité on arrête un eventuel redémarrage
        timer1 =  setInterval(carrouselNext,3000);
    });

    document.querySelector('.previous').addEventListener('click',carrouselPrevious);
    document.querySelector('.next').addEventListener('click',carrouselNext);

}

function reveal() {
    var reveals = document.querySelectorAll(".reveal");
    for (var i = 0; i < reveals.length; i++) {
      var windowHeight = window.innerHeight;
      var elementTop = reveals[i].getBoundingClientRect().top;
      var elementVisible = 150;
      if (elementTop < windowHeight - elementVisible) {
        reveals[i].classList.add("activ");
      } else {
        reveals[i].classList.remove("activ");
      }
    }
  }

  window.addEventListener("scroll", reveal);

// To check the scroll position on page load
reveal();

 // Fonction pour afficher l'image agrandie
    function afficherImageAgrandie(imageURL) {
      // Créer un élément d'image pour l'image agrandie
      var imageAgrandie = document.createElement("img");
      imageAgrandie.src = imageURL;
      
      // Créer le conteneur de l'image agrandie
      var conteneurImageAgrandie = document.createElement("div");
      conteneurImageAgrandie.className = "image-agrandie";
      conteneurImageAgrandie.appendChild(imageAgrandie);
      
      // Ajouter l'image agrandie à la page
      document.body.appendChild(conteneurImageAgrandie);
      
      // Afficher l'image agrandie
      conteneurImageAgrandie.style.display = "block";
      
      // Ajouter un gestionnaire d'événement pour masquer l'image agrandie lorsque vous cliquez dessus
      conteneurImageAgrandie.addEventListener("click", function() {
        conteneurImageAgrandie.style.display = "none";
      });
    }