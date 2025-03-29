class CommonFunction {
    static validForm(FormId) {
        const formElement = document.querySelector(`#${FormId}`);
        const elements = document.querySelectorAll(`#${FormId} .validation`);
        Array.prototype.forEach.call(elements, (item) => {
            item.parentElement.querySelector("label").classList.add('required-star');
        });

        ValidForm(formElement, {
            errorPlacement: 'after',
        });

    }
    static validFormV2(FormId) {
        const formElement = document.querySelector(`#${FormId}`);
        const elements = document.querySelectorAll(`#${FormId} .validation`);
        Array.prototype.forEach.call(elements, (item) => {
            item.parentElement.parentElement.querySelector("label").classList.add('required-star');
        });

        ValidForm(formElement, {
            errorPlacement: 'after',
        });

    }
}

