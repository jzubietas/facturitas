<!-- Modal -->
<div class="modal fade" id="modal-revertir-congelado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="exampleModalLabel">Revertir cliente congelado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formrevertircongelado" name="formrevertircongelado">
                <input type="hidden" id="congelado" name="congelado" class="form-control">
                <div class="modal-body">
                    <div class="form-group col-lg-12">
                        <p style="text-align: justify;">
                            Confirme si desea
                            <strong>DESCONGELAR</strong> el cliente: <strong
                                class="textcode">PED000</strong></p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
