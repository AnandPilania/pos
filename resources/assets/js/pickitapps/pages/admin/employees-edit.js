
export default class EmployeesEdit {
    constructor() {
        this.init();
    }

    init() {
        this.initValidators();
    }

    initValidators() {
        jQuery('.js-validation').validate({
            errorClass: 'invalid-feedback animated fadeIn',
            errorElement: 'div',
            errorPlacement: (error, el) => {
                jQuery(el).addClass('is-invalid');
                jQuery(el).parents('.form-group').append(error);
            },
            highlight: (el) => {
                jQuery(el).parents('.form-group').find('.is-invalid').removeClass('is-invalid').addClass('is-invalid');
            },
            success: (el) => {
                jQuery(el).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
                jQuery(el).remove();
            },
            rules: {
                'first-name': {
                    required: true,
                },
                'last-name': {
                    required: true,
                },
                'password': {
                    minlength: 5
                }
            },
            messages: {
                'password': {
                    minlength: 'Your password must be at least 5 characters long'
                }
            }
        });
    }

}
