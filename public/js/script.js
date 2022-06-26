let productTypes = {
    'furniture': [
        {'value': 'height', 'fieldLabel': 'Height (CM)', 'placeholder': '75'},
        {'value': 'length', 'fieldLabel': 'Length (CM)', 'placeholder': '180'},
        {'value': 'width', 'fieldLabel': 'Width (CM)', 'placeholder': '30'}
    ],
    'book': [
        {'value': 'weight', 'fieldLabel': 'Weight (KG)', 'placeholder': '0.250'}
    ],
    'dvd': [
        {'value': 'size','fieldLabel': 'Size (MB)', 'placeholder': '4700'}
    ]
}

let createAttributeInput = function (formInputs) {
    for (const formInput of Object.values(formInputs)) {
        let value = formInput['value'];
        let fieldLabel = formInput['fieldLabel'];
        let placeholder = formInput['placeholder'];
        let container = document.createElement('p');

        let label = document.createElement('label');
        label.setAttribute('for', value);
        label.setAttribute('form', 'product_form');
        label.classList.add('secondary');
        label.textContent = fieldLabel;

        let input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('id', value);
        input.setAttribute('name', value);
        input.setAttribute('placeholder', placeholder);
        input.classList.add('secondary');

        container.appendChild(label);
        container.appendChild(input);

        let fieldset = document.getElementById('product-form-secondary');
        fieldset.appendChild(container);
    }
};

let removeAttributeInputs = function () {
    let container = document.getElementById('product-form-secondary');
    while (container.hasChildNodes()) {
        container.removeChild(container.lastChild);
    }
}

let select = document.getElementById('productType');
let selected = select.options[select.selectedIndex].value;
let formInputs = productTypes[selected];
createAttributeInput(formInputs);

select.addEventListener("change", function () {
    removeAttributeInputs();
    let selected = select.options[select.selectedIndex].value;
    let formInputs = productTypes[selected];
    createAttributeInput(formInputs);
});


