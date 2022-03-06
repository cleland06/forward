
<div class="row">
    <div class="col-md-6">
        <card class="card ">
            <h2 class="card-header d-flex justify-content-between align-items-center text-white bg-warning">
                Editar Evidencia
                <button onclick="getView({url:'/evidence/index',btn:$(this),msj_finished:'Atrás'})" type="button" class="btn btn-sm btn-primary">Atrás</button>
            </h2>
            <div class="card-body">
                <form onsubmit="return false;" id="frm-evidence" action="#" data-url="/evidence">
                    <input type="hidden" name="id" value="{{ $evidence->id }}">
                    <div class="form-group">
                        <label for="title">Título:</label>
                        <input value="{{ $evidence->title }}" type="text" class="form-control" id="title" name="title" autocomplete="off">
                        <span  class="error invalid-feedback"><strong></strong></span>
                    </div>
                    <div id="owl-demo" class="owl-carousel">
                    </div>
                    <div class="form-group">
                        <label for="category_id">Categoría:</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">Seleccione una opción</option>
                            @forelse ($categories as $k => $v)
                                <option {{ ($evidence->category_id == $v['id']) ? "selected" : "" }} value="{{ $v['id'] }}">{{ $v['name'] }}</option>
                            @empty
                                <option value="">No hay categoría registrada</option>
                            @endforelse
                        </select>
                        <span  class="error invalid-feedback"><strong></strong></span>
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción:</label>
                        <textarea name="description" id="description" cols="30" rows="10" class=" summernote form-control">{!! $evidence->title !!}</textarea>
                        <span  class="error invalid-feedback"><strong></strong></span>
                    </div>
                    
                </form>
                <div class="form-group">
                    <form action=""
                    method="POST"
                    class="dropzone"
                    id="my-awesome-dropzone">
                    <input type="hidden" name="id" value="{{ $evidence->id }}">
                    </form>
                </div>
                
            </div>
            <div class="card-footer">
                <button  data-msj_finished="Aceptar" class="btn btn-primary btn-save">Aceptar</button>
            </div>
        </card>
    </div>

    <div class="col-md-6">
        <card class="card ">
            <h2 class="card-header d-flex justify-content-between align-items-center text-white bg-warning">
                Imágenes
            </h2>
            <div class="card-body">
                <div class="carousel-inner py-4">
                    <!-- Single item -->
                    <div class="carousel-item active">
                        <div class="container">
                            <div class="row" id="carousel-row">
                                cargando...
                            <div> 
                        </div>
                    </div>   
                </div>
                
            </div> {{--  card body  --}}
        </card>
    </div>
</div>


<script type="text/javascript">
    var my_dropzone = new Dropzone("#my-awesome-dropzone", {
        url:"{{ route('evidenceImagesStore') }}",
        headers:{
            'X-CSRF-TOKEN' : "{{csrf_token()}}"
        },
        dictDefaultMessage: "Arrastre una imagen al recuadro para subirla",
        acceptedFiles: "image/*",
        maxFilesize: 2,
        //maxFiles: 4,
    });
    my_dropzone.on("queuecomplete", function (file) {
        getImages();
    });
    $(document).ready(function(){
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

            getImages();

            

    });
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

    function updateStatus (id,el){
        
        var data = {id:id,status:el.val()};
        let json_config = {
                url:'images/update/status',
                data:data,
                _processData:true,//true,
                //_contentType:""//'application/x-www-form-urlencoded; charset=UTF-8',
                
                alert_success: true
            }
            postAjax(json_config)
                    
    }
    function deleteImage (id,el){
        
        var data = {id:id,status:el.val()};
        let json_config = {
                url:'/images/destroy/',
                data:data,
                _processData:true,//true,
                //_contentType:""//'application/x-www-form-urlencoded; charset=UTF-8',
                msj_finished:"Eliminado",
                btn:el,
                alert_success: true
            }
            postAjax(json_config)
            .then((resp)=>{
                getImages();
            })
                    
    }

    function getImages(){
        getAjax({url:"/images/{{ $evidence->id }}"})
            .then((resp)=>{
                console.log(resp)
                if(resp.result.status == "Ok"){
                    $('#carousel-row').html("");


                    
                    if(resp.result.data.evidenceimages.length > 0){
                        $.each(resp.result.data.evidenceimages, function(i, el) {
                            let selected_activo = (el.status == "Activo") ? "selected":""
                            let selected_bloqueado = (el.status == "Bloqueado") ? "selected":""
                            let card = `
                            <div class="col-lg-4">
                                <div class="card">
                                  <img src="`+el.url+`" class="card-img-top" alt="Waterfall">
                                  <div class="card-body">
                                    
                                    <p class="card-text">
                                        <select onChange="updateStatus('`+el.id+`',$(this))" name="status" class="form-control">
                                            <option `+selected_activo+` value="Activo">Activo</option>
                                            <option `+selected_bloqueado+` value="Bloqueado">Bloqueado</option>
                                        </select>
                                    </p>
                                    <a href="#!" onclick="deleteImage('`+el.id+`',$(this))" class="btn btn-danger" style="">Eliminar</a>
                                  </div>
                                </div>
                              </div>
                            `
                            
                            $("#carousel-row").append(card);
                        });
                    }else{
                        $("#carousel-row").append('<h5>Aún no tenemos registro de imágenes</h5>');
                    }
                    
                    
                    
                }
            })
    }

    
     
      
</script>