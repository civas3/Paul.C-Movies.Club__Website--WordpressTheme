function openNav() {
    document.getElementById("mobileNav").style.width = "300px";
  }

  function closeNav() {
    document.getElementById("mobileNav").style.width = "0";
  }





  document.addEventListener('DOMContentLoaded', function() {
    let menuItem = document.getElementById('menu-item-377');
    let submenuToggle = menuItem.querySelector('a');
    let submenu = menuItem.querySelector('.sub-menu');

    if (submenu) {
        submenu.style.display = 'none'; // Initially hide the submenu
    }

    submenuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        if (submenu.style.display === 'block') {
            submenu.style.display = 'none';
            menuItem.classList.remove('submenu-open');
        } else {
            submenu.style.display = 'block';
            menuItem.classList.add('submenu-open');
        }
    });
});


 
//try to add transition!