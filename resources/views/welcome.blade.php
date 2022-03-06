<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-warning">
      <a class="navbar-brand" href="#">Evidencias</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-item nav-link active" href="/login">Login <span class="sr-only"></span></a>
          
        </div>
      </div>
    </nav>
    <p></p>
    <div class="container">
      <div class="row" id="evidence">
        
      </div>
    </div>
    

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel" id="title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div id="modal-body" class="modal-body">
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> 
    <script src="{{ asset('js/functions.js') }}"></script>
    <script >
      $(document).ready(function(){
        getAjax({url:"/evidence/landing"})
          .then((resp)=>{
            console.log(resp)
            if(resp.result.status == "Ok"){
                $('#evidence').html("");
                if(resp.result.data.evidence.length > 0){
                    $.each(resp.result.data.evidence, function(i, el) {
                        
                        let card = `
                        <div class="col">
                          <div class="card text-black bg-dark" style="width: 18rem;">
                            <img class="card-img-top" src="`+el.images[0].url+`" alt="Card image cap">
                              <div class="card-body bg-light">
                              <h5 class="card-title">`+el.title+`</h5>
                              <p class="card-text"></p>
                                <button onClick="showEvidence('`+el.category_id+`')" 
                                
                                type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Ver</button>
                            </div>
                          </div>
                        </div>
                        `
                        
                        $("#evidence").append(card);
                    });
                }else{
                    $("#evidence").append('<h5>Aún no tenemos registro de imágenes</h5>');
                }
                
                
                
            }
        })
      })
      function showEvidence(id){
        $('#modal-body').html("");
        getAjax({url:"/evidence/show/"+id})
        .then((resp)=>{

          //muestro la modal
          $('#modal-body').html(resp);
          
        })
      }
    </script>
  </body>
</html>
