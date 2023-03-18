<!-- FormularioEventos -->
<div class="modal fade" id="FormularioEventos" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="Codigo">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Título del evento:</label>
                        <input type="text" id="Titulo" class="form-control" placeholder="">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Fecha de inicio:</label>

                        <div class="input-group" data-autoclose="true">
                            <input type="date" id="FechaInicio" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group col-md-6" id="TituloHoraInicio">
                        <label>Hora de inicio:</label>

                        <div class="input-group clockpicker" data-autoclose="true">
                            <input type="text" id="HoraInicio" value="" class="form-control" autocomplete="off" />
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Fecha de fin:</label>

                        <div class="input-group" data-autoclose="true">
                            <input type="date" id="FechaFin" value="" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group col-md-6" id="TituloHoraFin">
                        <label>Hora de fin:</label>

                        <div class="input-group clockpicker" data-autoclose="true">
                            <input type="text" id="HoraFin" value="" class="form-control" autocomplete="off" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Descripción:</label>
                    <textarea id="Descripcion" rows="3" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>Color de fondo:</label>
                    <input type="color" value="#3788D8" id="ColorFondo" class="form-control" style="height:36px;">
                </div>
                <div class="form-group">
                    <label>Color de texto:</label>
                    <input type="color" value="#ffffff" id="ColorTexto" class="form-control" style="height:36px;">
                </div>

            </div>
            <div class="modal-footer">

                <button type="button" id="BotonAgregar" class="btn btn-success">Agregar</button>
                <button type="button" id="BotonModificar" class="btn btn-success">Modificar</button>
                <button type="button" id="BotonBorrar" class="btn btn-success">Borrar</button>
                <button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>

            </div>
        </div>
    </div>
</div>
