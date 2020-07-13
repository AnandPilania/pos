import {responseBodyHandling, catchErrorHandling} from "../../utils/axios";

export default class CategoriesList {
    constructor() {
        this.init();
    }

    init() {
        this.initDataTable();
        this.initEventListeners();
    }

    initDataTable() {
        this.dataTable = jQuery('.table').dataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 20]
        });
    }

    initEventListeners() {
        $("[name^='show-toggle-']").on('change', function () {
            const id = this.name.split("show-toggle-")[1];
            axios.post(route('admin.clients.categories.toggle-active', clientId), {id})
                .then(result => result['data'])
                .then(data => {
                    responseBodyHandling(data);
                })
                .catch(error => {
                    catchErrorHandling(error);
                });
        });
    }

    openVideoDialog(videoId, productName) {
        $("#modal-block-fadein .block-title").html(productName);
        $("#modal-block-fadein iframe").attr("src", "https://www.youtube.com/embed/" + videoId);
        $("#modal-block-fadein").modal('show');
    }

    delete(id) {
        swal({
            title: 'Are you sure?',
            text: 'Are you sure to delete this category? \nAll products related to this category will be also deleted.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-danger m-1',
            cancelButtonClass: 'btn btn-secondary m-1',
            confirmButtonText: 'Yes, delete!',
            html: false,
            preConfirm: (e) => {
                return new Promise((resolve) => {
                    setTimeout(() => {
                        resolve();
                    }, 50);
                });
            }
        }).then((result) => {
            if (result.value) {

                axios.post(route('admin.clients.categories.delete', clientId), {id})
                    .then(response => response['data'])
                    .then(data => {
                        responseBodyHandling(data, true);
                    })
                    .catch(error => {
                        catchErrorHandling(error);
                    });

            } else if (result.dismiss === 'cancel') {

            }
        });
    }
}
