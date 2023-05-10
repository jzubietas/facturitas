  <!-- Modal -->
  <div class="modal right fade" id="modal-imagen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title" id="exampleModalLabel">Imagen</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <div class="col">
              item
              <input type="text" class="form-control modalimagen_item" readonly>
            </div>
            <div class="col">
              pago
              <input type="text" class="form-control modalimagen_banco" readonly>
            </div>
            <div class="col">
              banco
              <input type="text" class="form-control modalimagen_monto" readonly>
            </div>
            <div class="col">
              monto
              <input type="text" class="form-control modalimagen_titular" readonly>
            </div>
            <div class="col">
              titular
              <input type="text" class="form-control modalimagen_fecha" readonly>
            </div>
            <div class="col">
              fecha
              <input type="text" class="form-control" readonly>
            </div>

          </div>
          <div class="form-row">
            <div class="form-group col-lg-12" style="text-align: center">
              <img src="" alt="" height="500px" width="500px" class="img-thumbnail">
              {{--<img src="{{ asset('storage/pagos/' . $detallePago->imagen) }}" alt="{{ $detallePago->imagen }}" height="500px" width="500px" class="img-thumbnail">--}}
            </div>
          </div>
        </div>        
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
