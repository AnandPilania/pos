import {responseBodyHandling, catchErrorHandling} from "../../utils/axios";

export default class EmployeesList {
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
        $("[name^='enable-toggle-']").on('change', function () {
            const id = this.name.split("enable-toggle-")[1];
            axios.post(route('admin.employees.toggle-active'), {id})
                .then(result => result['data'])
                .then(data => {
                    responseBodyHandling(data);
                })
                .catch(error => {
                    catchErrorHandling(error);
                });
        });
    }

    delete(id) {
        swal({
            title: 'Are you sure?',
            text: 'Are you sure to delete this employee ?',
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

                axios.post(route('admin.employees.delete'), {id})
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
