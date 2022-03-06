<div class="row">
    <div class="col-md-6">
        <card class="card ">
            <h2 class="card-header d-flex justify-content-between align-items-center text-white bg-warning">
                Crear Categoría
                <button onclick="getView({url:'/categories/index',btn:$(this),msj_finished:'Atrás'})" type="button" class="btn btn-sm btn-primary">Atrás</button>
            </h2>
            <div class="card-body">
                <form onsubmit="return false;" id="frm-categories" action="#" data-url="/categories">
                    <div class="form-group">
                        <label for="name">Nombre:</label>
                        <input type="text" class="form-control" id="name" name="name" autocomplete="off">
                        <span  class="error invalid-feedback"><strong></strong></span>
                    </div>
                    
                </form>
                
            </div>
            <div class="card-footer">
                <button  data-msj_finished="Aceptar" class="btn btn-primary btn-save">Aceptar</button>
            </div>
        </card>
    </div>
</div>
<script>
    $('.btn-save').on('click',function(event){
            let $form                 = $('#frm-categories');
            let url                   = $form.data('url');
            var data = $($form).serialize()   ;
            let json_config = {
                    url:url,
                    data:data,
                    btn:$(this),
                    _processData:true,//true,
                    //_contentType:""//'application/x-www-form-urlencoded; charset=UTF-8',
                    msj_finished:$(this).data('msj_finished'),
                    alert_success: true
                }
                postAjax(json_config)
                .then(resp=>{
                    console.log(resp);
                    if(resp.result.status == "Ok"){
                        getView({url:"/categories/index",div_objet:'content'});
                    }
                });        
    })
</script>