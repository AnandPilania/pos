
export default class CategoriesAdd {
    constructor() {
        this.init();
    }

    init() {
        this.initValidators();
        this.initEventListeners();
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
                'category-name': {
                    required: true,
                },
            }
        });
    }

    initEventListeners() {
        $("#checkbox-name-rtl").on("change", () => {
            if ($("#checkbox-name-rtl").prop("checked") === true) {
                $("[name='category-name-ar']").attr("dir", "rtl");
                $("[name='category-tags-ar']").attr("dir", "rtl");
            } else {
                $("[name='category-name-ar']").removeAttr("dir");
                $("[name='category-tags-ar']").removeAttr("dir");
            }
        });
    }

}
