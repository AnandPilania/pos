
export default class BusinessTypesList {
    constructor() {
        this.init();
    }

    init() {
        this.initDataTable();
    }

    initDataTable() {
        jQuery('.table').dataTable({
            stateSave: true,
            pageLength: 10,
            lengthMenu: [5, 10, 20]
        })
    }

    delete(id) {
        swal({
            title: 'Are you sure?',
            text: 'This position will be also detached from users.',
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

                axios.post(baseUrl + '/business-types/delete', {id})
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
