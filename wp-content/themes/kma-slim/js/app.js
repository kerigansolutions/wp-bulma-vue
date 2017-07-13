window.Vue = require('vue');

/**
 * For Burger button in nav menu
 */
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


new Vue({
    el: '#bot',
    data: {
        siteby: 'Site by KMA.',
        copyright: 'Kerigan Marketing Associates. All rights reserved.'
    }
})