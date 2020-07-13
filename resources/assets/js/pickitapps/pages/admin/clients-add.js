import Helpers from "../../modules/helpers";
export default class ClientsAdd {
    constructor() {
        this.init();
    }

    init() {
        this.initValidators();
        Helpers.run('datepicker');
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
                'email': {
                    required: true,
                    email: true
                },
                'password': {
                    required: true,
                    minlength: 5
                },
                'phone-number': {
                    required: true
                },
                'start-date': {
                    required: true,
                    date: true
                },
                'expire-date': {
                    required: true,
                    date: true
                }
            },
            messages: {
                'password': {
                    required: 'Please provide a password',
                    minlength: 'Your password must be at least 5 characters long'
                },
                'start-date': {
                    required: 'Start Date is required'
                },
                'expire-date': {
                    required: 'Expire Date is required'
                }
            }
        });
    }

}
