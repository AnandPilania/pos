
export default class EmployeesList {
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

                axios.post(baseUrl + '/employees/delete', {id})
                    .then(result => {
                        console.log(result);
                    })
                    .catch(error=> {
                        console.log(error);
                    });

            } else if (result.dismiss === 'cancel') {

            }
        });

    }
}
