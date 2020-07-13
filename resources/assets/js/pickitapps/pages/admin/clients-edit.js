import Helpers from "../../modules/helpers";

export default class ClientsEdit {
    constructor() {
        this.init();
    }

    init() {
        this.initValidators();
        this.initEventListeners();
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

    initEventListeners() {
        $("#save-button").on("click", () => {
            $("#modal-confirm").modal('show');
        });
    }

    onEditButtonClicked() {
        $("#edit-button").hide();
        $("#save-button").show();
        $("#cancel-button").show();
        $("[name='start-date']").removeAttr("disabled");
        $("[name='expire-date']").removeAttr("disabled");
        $("[name='discount']").removeAttr("disabled");
        $("[name='subscription']").removeAttr("disabled");
    }

    onCancelButtonClicked(startDate, expireDate, discount, subscriptionId) {
        $("#edit-button").show();
        $("#save-button").hide();
        $("#cancel-button").hide();
        $("[name='start-date']").val(startDate).attr("disabled", "disabled");
        $("[name='expire-date']").val(expireDate).attr("disabled", "disabled");
        $("[name='discount']").val(discount).attr("disabled", "disabled");
        $("[name='subscription']").val(subscriptionId).attr("disabled", "disabled");
    }

    resuscitateCustomer(id, add_flag) {
        axios.post(route('admin.clients.resuscitate'), {
            'id': id,
            'start-date': $("[name='start-date']").val(),
            'expire-date': $("[name='expire-date']").val(),
            'discount': $("[name='discount']").val(),
            'subscription': $("[name='subscription']").val(),
            'add_flag': add_flag
        })
            .then(response => response['data'])
            .then(data => {
                if (data.message.length === 0) {
                    if (add_flag === 1) {
                        toastr.success('You have successfully resuscitated this customer!');
                    } else {
                        toastr.success('You have successfully edit current invoice!');
                    }
                    $("#edit-button").show();
                    $("#save-button").hide();
                    $("#cancel-button").hide();
                    $("[name='start-date']").attr("disabled", "disabled");
                    $("[name='expire-date']").attr("disabled", "disabled");
                    $("[name='discount']").attr("disabled", "disabled");
                    $("[name='subscription']").attr("disabled", "disabled");
                    $("#modal-confirm").modal('hide');
                } else {
                    toastr.info(data.message);
                }
            })
            .catch(error => {

            });
    }
}
