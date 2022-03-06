async function getView({url,type,data,btn,msj_loader,msj_finished , div_objet="content"}){
    btn = (btn) ? btn : null;
    makeLoader({el:btn,status:'loader',"msj_loader":msj_loader});
    let result;
    try {
        type = (type != "")?type:"GET"
        result = await $.ajax({
            url: url,
            type: type,
            data: data,
            headers: {
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });
        makeLoader({el:btn,status:'finished',"msj_finished":msj_finished});
        $('#'+div_objet).html(result);
    } catch (error) {
        makeLoader({el:btn,status:'finished',"msj_finished":msj_finished});
        console.error(error);
    }
}

function setDataTable({id,url,columns}){
    $('#'+id).DataTable({
        processing: true,
        serverSide: true,
        ajax: url,
        columns: columns,
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
    });
}

function deleteByID({url,id,btn,div_objet,url_finished}){
    data = {'id':id}
    postAjax({url:url, data:data , btn:btn,msj_finished:"Eliminar"})
    .then((resp)=>{
        console.log(resp);
        if(resp.result.status == "Ok"){
            getView({url:url_finished,div_objet:div_objet});
        }
    })
}

//funcion asyncrona para recibir datos de una ruta con verbo GET
async function getAjax({url,type,data,btn,msj_loader,msj_finished}){
    btn = (btn) ? btn : null;
    makeLoader({el:btn,status:'loader',"msj_loader":msj_loader});
    let result;
    try {
        type = (type != "")?type:"GET"
        result = await $.ajax({
            url: url,
            type: type,
            data: data,
            headers: {
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });
        makeLoader({el:btn,status:'finished',"msj_finished":msj_finished});
        return result;
    } catch (error) {
        makeLoader({el:btn,status:'finished',"msj_finished":msj_finished});
        console.error(error);
    }
}

async function postAjax({url, data , btn , _processData = true  , _contentType = 'application/x-www-form-urlencoded; charset=UTF-8' , msj_finished , alert_success = "Si" ,msj_success = "Operacion Realizada con Éxito"  }){
    btn = (btn) ? btn : $('#btn_aceptar');
    
    makeLoader({el:btn,status:'loader' , msj_finished:msj_finished});
    
    
    let confirmacion = await confirm('¿Esta seguro de realizar la operación?').then(async(resp) => {
        
        if (resp|| resp == "ok") {
            let result;
            try {
                //alert("aquí");
                result = await $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(data){
                        
                        let theErrors = ( typeof data.result.status !== 'undefined') ? data.result.errors : data.errors;
                        console.log(theErrors);
                        //let theErrors = (data.result.status !== 'undefined') ? data.result.errors : data.errors;

                        $(":input").each(function(){
                            $(this).removeClass('is-invalid');
                        });

                        //alert(data.status);

                        
                        
                        
                        if( (typeof data.result.status !== 'undefined' && data.result.status == 'Error') || (typeof data.status !== 'undefined' && data.status != "Error")){

                            let cont = 0;
                            let messageError = 'Hubo un error en su solicitud: ';
                            for (var prop in theErrors){
                                console.log(theErrors[prop])
                                let theElementFather = 'the' + prop;
                                let thePropError = prop + 'Error';
                                let element = $('input[name="'+prop+'"] , select[name="'+prop+'"] , textarea[name="'+prop+'"]') 
                                if(element.length){
                                    cont++;
                                    setErrorInput(element,theErrors[prop]);        
                                }
                            }

                            if(cont == 0){
                                //messageError+= " "+theErrors//data.errors
                                if(typeof data.result.message !== 'undefined'){
                                    messageError+=" "+data.result.message;
                                }
                            }
                            toastr.error(messageError);
                        }
                        else if(typeof data.result.status !== 'undefined' && data.result.status == 'Warning'){
                            toastr.warning(data.result.message);
                        }
                        else if(typeof data.status !== 'undefined' && data.status == "Warning"){
                            //alert("entró aquí "+data.status)
                            toastr.warning(data.message);
                        }
                        else{
                            if(alert_success)
                            toastr.success(msj_success);
                        }
                    },
                    error: function() {
                        makeLoader({el:btn,status:'finished',msj_finished:msj_finished});
                    }
            })
            return result;
            
            } catch (error) {
                console.error(error);
                if( error.status === 422 ) {
                    var theErrors = $.parseJSON(error.responseText);
                    
                        let messageError = 'Hubo un error en su solicitud: ';
                        for (var prop in theErrors.errors){
                            
                            let theElementFather = 'the' + prop;
                            let thePropError = prop + 'Error';
                            let element = $('input[name="'+prop+'"] , select[name="'+prop+'"] , textarea[name="'+prop+'"]') 
                            if(element.length){
                                setErrorInput(element,theErrors.errors[prop]);        
                            }
                        }
                    }
                makeLoader({el:btn,status:'finished',msj_finished:msj_finished});
                toastr.error('Hubo un error al hacer su consulta');
            }
            makeLoader({el:btn,status:'finished',msj_finished:msj_finished});
            return result
        }
        makeLoader({el:btn,status:'finished',msj_finished:msj_finished});
        return false
    })
    makeLoader({el:btn,status:'finished',msj_finished:msj_finished});
    return confirmacion
}


async function confirm(texto = '¿Esta seguro de realizar la operación?') {
    try {
    const alert =  await swal.fire({
            title: 'Atención',
            text: texto,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        });
    return !!(alert.value && alert.value === true);
    } catch (e) {
        console.log('error:', e);
        return false;
    }
}

//funcion para imprimir los errores, recibe el elemento((input) y el mensaje
function setErrorInput(el,msj){
    let span = el.siblings('span')
    el.removeClass('is-invalid')
    el.removeClass('is-valid')
    el.addClass('is-invalid')
    span.show();
    span.find('strong').html(msj)
}
//funcion para borrar los errors de un input
function setOkInput(el){
    let span = el.siblings('span')
    el.removeClass('is-invalid')
    el.addClass('is-valid')
    span.hide();
    span.find('strong').html("")
}
//funcion para poner un input sin status
function setOkDefault(el){
    let span = el.siblings('span')
    el.removeClass('is-invalid')
    el.removeClass('is-valid')
    span.hide();
    span.find('strong').html("")
}
//function para limbiar los mensajes de input
function setWithOutMessageInput(el){
    let span = el.siblings('span')
    el.removeClass('is-invalid')
    el.removeClass('is-valid')
    span.hide();
    span.find('strong').html("")
}

//function de loader para el botón
function makeLoader({el,msj_loader = "",msj_finished = "Aceptar",status}){
    if(el){
        let spinner = `<div class="spinner-border spinner-border-sm " role="status">
        <span class="sr-only">`+msj_loader+`</span>
        </div>`
        if(status == "loader"){
            el.prop('disabled',true)
            el.html(spinner);
        }else if(status == "finished"){
            el.html(msj_finished);
            el.prop('disabled',false)
        }
    }
    
}

function alertError(msj = "Lo sentimos a oucrrido un problema",tittle = "Error"){
    swal({
        icon: 'error',
        title: tittle,
        text: msj,
        //footer: '<a href="">Why do I have this issue?</a>'
    })
}

function alertSuccess(msj="Operación realizada con éxito",tittle="Ok"){
    Swal(
        tittle,
        msj,
        'success'
    )
}

function alertWarning(msj = "Lo sentimos a oucrrido un problema",tittle = "Atención"){
    new swal({
        icon: 'warning',
        title: tittle,
        text: msj,
        //footer: '<a href="">Why do I have this issue?</a>'
    })
}
