
export default class PositionsAdd {
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
                'name': {
                    required: true,
                },
                'slug': {
                    required: true,
                }
            }
        });
    }

}
