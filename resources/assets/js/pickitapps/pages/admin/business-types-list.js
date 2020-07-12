
export default class BusinessTypesList {
    constructor() {
        this.init();
    }

    init() {
        this.initDataTable();
    }

    initDataTable() {
        this.dataTable = jQuery('.table').dataTable({
            processing: true,
            // serverSide: true,
            // ajax: route('admin.business-types.list').template,
            // columns: [
            //     {data: 'id', name: 'id'},
            //     {data: 'name', name: 'name'},
            // ],
            pageLength: 10,
            lengthMenu: [5, 10, 20]
        });
    }

    delete(id) {
        swal({
            title: 'Are you sure?',
            text: 'This posit type will be also detached from clients.',
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

                axios.post(route('admin.business-types.delete'), {id})
                    .then(result => {
                        console.log(this.dataTable);
                        if (this.dataTable) {
                            //this.dataTable.fnDestroy().fnDraw(false);
                        }
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
