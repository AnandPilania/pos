import {catchErrorHandling, responseBodyHandling} from "../../utils/axios";

export default class PositionsList {
    constructor() {
        this.init();
    }

    init() {
        this.initDataTable();
    }

    initDataTable() {
        jQuery('.table').dataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 20]
        })
    }

    deletePosition(id) {
        swal({
            title: 'Are you sure?',
            text: 'This position will be also detached from employees.',
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

                axios.post(route('admin.positions.delete'), {id})
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
