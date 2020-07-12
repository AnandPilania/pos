
export default class ProductsEdit {
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
        let t = this;
        $('#image').on('change', function () {
            let imgPath = this.value;
            let ext = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            if (ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg")
                t.readURL(this);
            else
                alert("Please select image file (jpg, jpeg, png).")
        });

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

    changeProfile() {
        $('#image').click();
    }

    readURL(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.readAsDataURL(input.files[0]);
            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result);
            };
        }
    }

    removeImage(url) {
        $('#preview').attr('src', url);
    }
}
