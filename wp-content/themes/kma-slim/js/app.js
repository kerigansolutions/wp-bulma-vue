/**
 * For Burger button in nav menu
 */
require('vue');

document.getElementById("TopNavBurger").addEventListener ("click", toggleNav);
function toggleNav() {
    var nav = document.getElementById("TopNavMenu");
    var className = nav.getAttribute("class");
    if(className == "navbar-menu") {
        nav.className = "navbar-menu is-active";
    } else {
        nav.className = "navbar-menu";
    }
}