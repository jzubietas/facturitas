<!-- Modal -->
<div class="modal fade" id="modal-perdonar_currier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success">

        <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="formperdonarcurrier" name="formperdonarcurrier">

            <div class="card-body">
              <div class="form-row">


                <div class="form-group lg-12">
                  {!! Form::label('motivo', 'Ingrese el motivo para perdonar el currier(Max. 250 caracteres)') !!}
                  {!! Form::textarea('motivo',null, ['class' => 'form-control', 'rows' => '4', 'placeholder' => 'Motivo']) !!}
                </div>
                  <div class="form-group lg-12">
                      <div id="attachmentfiles" class="border border-dark rounded d-flex justify-content-center align-items-center position-relative"
                           style="height: 200px" >
                          <i class="fa fa-upload"></i>
                          <div class="result_picture position-absolute" style="display: block;top: 0;left: 0;bottom: 0;right: 0;text-align: center;">
                              <img src="" class="h-100 img-fluid" alt="">
                          </div>
                      </div>
                      <p class="text-danger text-xs mt-4">Puede copiar y pegar la imagen o hacer click en el recuadro para seleccionar un archivo</p>
                      <input type="file" name="perdonar_currier_captura" id="perdonar_currier_captura"  class="d-none form-control" placeholder="">
                  </div>
              </div>
            </div>
            <div class="card-footer text-center">
              <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> Perdonar deuda</button>
            </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
