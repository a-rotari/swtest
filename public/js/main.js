const deleteBtn = document.querySelector('#delete-product-btn');
// let values = [];
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

/*
let checkboxes = document.querySelectorAll('input[type=checkbox][class=delete-checkbox]');
checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        if (this.checked) {
            if (deleteBtn.disabled) {
                deleteBtn.removeAttribute('disabled');
            }
            values.push(this.value);
        } else {
            let index = values.indexOf(this.value);
            if (index != -1) {
                values.splice(index, 1);
            }
            if (!values.length) {
                deleteBtn.setAttribute('disabled', 'disabled');
            }
        }
    });
});
*/
