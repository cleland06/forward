<div class="row">
    <div class="col-md-12">
        <card class="card ">
            
            <h2 class="card-header d-flex justify-content-between align-items-center text-white bg-warning">
                Categorías
                <button onclick="getView({url:'/categories/create',btn:$(this),msj_finished:'Nuevo'})"  type="button" class="btn btn-sm btn-primary nav-view">Nuevo</button>
            </h2>
            <div class="card-body">
                <table id="data_table_categories" class="table table-bordered datatable">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </card>
    </div>
</div>

<script>
    $(document).ready(function(){
        var table = setDataTable(
            { id:'data_table_categories',
                url:"{{ route('dataTableCategories') }}",
                columns:[
                {data: 'id', name: 'id'},
                {data: 'name', name: 'Nombre'},
                {
                    data: 'action', 
                    name: 'Acciones', 
                    orderable: false, 
                    searchable: false
                }]
            });
        
    })
</script>