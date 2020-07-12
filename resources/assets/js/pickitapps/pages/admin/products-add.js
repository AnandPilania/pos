
export default class ProductsAdd {
    constructor() {
        this.init();
    }

    init() {
        this.initValidators();
        this.initEventListeners();
        this.initImageUploadComponent();
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
                'product-name': {
                    required: true,
                },
                'product-price': {
                    required: true,
                    number: true
                }
            }
        });
    }

    initEventListeners() {
        $("#checkbox-name-rtl").on("change", () => {
            if ($("#checkbox-name-rtl").prop("checked") === true) {
                $("[name='product-name-ar']").attr("dir", "rtl");
                $("[name='product-description-ar']").attr("dir", "rtl");
            } else {
                $("[name='product-name-ar']").removeAttr("dir");
                $("[name='product-description-ar']").removeAttr("dir");
            }
        });
    }

    initImageUploadComponent() {
        $('.imageupload').imageupload();
    }
}
