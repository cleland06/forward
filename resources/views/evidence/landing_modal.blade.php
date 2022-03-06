<form>
    <div class="form-group">
      <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
          
        <div class="carousel-inner" id="slider">
        
          
          @foreach($evidence->images as $key => $value)
          <div class="carousel-item {{ ($key == 0)? 'active' : "" }} ">
            <img class="d-block w-100" src="{{ $value->url }}" alt="">
          </div>
        
          @endforeach
        </div>
        
      </div>
    </div>
     <div class="row">
        <div class="col">
              <label >Categoría</label>
              <h3> {{ $evidence->category->name }}</h3>
        </div>
        <div class="col">
                 <label >Fecha</label>
                <h3> {{ $evidence->created_at->format('d/m/Y') }}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col">
              <label >Usuario</label>
              <h3>{{ $evidence->user->name }}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col">
          <label >Descripción</label>
          <div id="description">
              {!! $evidence->description !!}
          </div>
        </div>
    </div>
  </form>
  <script src="{{ asset('js/app.js') }}"></script>
  <script>
      $(document).ready(function(){
          $('#carouselExampleSlidesOnly').carousel({
            interval: 2000
          });
      })
  </script>