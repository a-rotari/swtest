/**
 * This scripts is for the main page of Test Assignment.
 * It facilitates deletion of products when 'Mass Delete' button is clicked.
 */

const deleteBtn = document.querySelector('#delete-product-btn');
deleteBtn.addEventListener('click', (event) => {
    let checkboxes = document.querySelectorAll('input[type=checkbox][class=delete-checkbox]:checked');
    let values = [];
    checkboxes.forEach(function (checkbox) {
        values.push(checkbox.value);
    });
    if (values.length) {
        let params = values.join('/');
        let newLocation = window.location.href + 'delete-products/' + params;
        location.href = newLocation;
    }
});
