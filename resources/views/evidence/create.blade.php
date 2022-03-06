<div class="row">
    <div class="col-md-8">
        <card class="card ">
            <h2 class="card-header d-flex justify-content-between align-items-center text-white bg-warning">
                Crear Evidencia
                <button onclick="getView({url:'/evidence/index',btn:$(this),msj_finished:'Atrás'})" type="button" class="btn btn-sm btn-primary">Atrás</button>
            </h2>
            <div class="card-body">
                <form onsubmit="return false;" id="frm-evidence" action="#" data-url="/evidence">
                    <div class="form-group">
                        <label for="title">Título:</label>
                        <input type="text" class="form-control" id="title" name="title" autocomplete="off">
                        <span  class="error invalid-feedback"><strong></strong></span>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Categoría:</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">Seleccione una opción</option>
                            @forelse ($categories as $k => $v)
                                <option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
                            @empty
                                <option value="">No hay categoría registrada</option>
                            @endforelse
                        </select>
                        <span  class="error invalid-feedback"><strong></strong></span>
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción:</label>
                        <textarea name="description" id="description" cols="30" rows="10" class=" summernote form-control"></textarea>
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
    $(document).ready(function(e){
        $('.summernote').summernote({
            height: 150,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
            });
            $('.btn-save').on('click',function(event){
                let $form                 = $('#frm-evidence');
                let url                   = $form.data('url');
                let description           = $('.summernote').summernote('code')
                var data = $($form).serialize();///+"&description="+description   ;
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
                            getView({url:"/evidence/edit/"+resp.result.data.evidence.id,div_objet:'content'});
                        }
                    });        
        })
    })
    
</script>