document.addEventListener('DOMContentLoaded', () => {
    // Define the mappings of menu items to classes
    const menuItemsClasses = {
        'menu-item-208': 'country-flag gb',
        'menu-item-209': 'country-flag fr',
        'menu-item-210': 'country-flag sp',
        'menu-item-372': 'country-flag gb',
        'menu-item-373': 'country-flag fr',
        'menu-item-374': 'country-flag sp'
    };

   

    // Loop through each menu item and add the corresponding span before the text
    Object.keys(menuItemsClasses).forEach(menuItemId => {
        const menuItem = document.querySelector(`#${menuItemId}`);

        if (menuItem) {
            const anchor = menuItem.querySelector('a');
            const span = document.createElement('span');
            span.className = menuItemsClasses[menuItemId];
            anchor.insertBefore(span, anchor.firstChild); // Insert span before the first child of anchor
        }
    });
});


