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


    document.querySelector('.previous').addEventListener('click',carrouselPrevious);
    document.querySelector('.next').addEventListener('click',carrouselNext);

}