 <!-- Modal -->
 <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header bg-danger">
         <h5 class="modal-title" id="staticBackdropLabel">ALERTA DE PEDIDOS SIN PAGOS</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
       <div class="modal-body">
         <b>A CONTINUACIÓN, SE LISTAN LOS PEDIDOS PENDIENTES POR COBRAR.</b>
         <br>
         <br>
         <table id="tablaPrincipal" class="table table-striped table-sm">
           <thead>
             <tr>
               <th scope="col">ID</th>
               <th scope="col">PEDIDO</th>
               <th scope="col">CLIENTE</th>
               {{-- <th scope="col">ESTADO</th> --}}
             </tr>
           </thead>
           <tbody>

           </tbody>
         </table>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-danger" data-dismiss="modal">Entendido</button>
         <a href="{{ route('pedidos.sinpagos') }}" class="btn btn-primary">Ver Detalle</a>
       </div>
     </div>
   </div>
 </div>
