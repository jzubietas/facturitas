$(document).ready(function() {

	$(document).on("click",".radioadelanto",function(event){
        event.preventDefault();
    });

    $(document).on("mousedown",".radioadelanto",function(event){
            event.preventDefault();
            if($(this).prop("checked") == true){
              console.log("marcado")
              $(this).prop("checked",false).val("0")//////revertir   
              console.log("cambio cheked 2")           
                  let montopagos=parseFloat($("#diferencia").val().replace(",", ""));//0
                  /*if(montopagos==0 || montopagos==null || isNaN(montopagos)){
                    console.log("no hay pagos ingresados");
                    return;
                  }*/
                  let filedata=tabla_pedidos.row($(this).closest('tr')).data();
                  let pedidosaldo=parseFloat(filedata.saldo);
                  if(pedidosaldo==null || isNaN(pedidosaldo)){
                    console.log("no hay saldo ingresado");
                    return;
                  }

                  console.log("desmarco saldo "+pedidosaldo+" monto "+montopagos);

                  /////falta aqui analizis
                  //montopagos=parseFloat(montopagos+pedidosaldo);

                  //551.20---356.00
                  console.log("guardasaldo "+guardasaldo);
                  montopagos=(guardasaldo);
                  $("#diferencia").val(montopagos);

                  $(this).closest('tr').find("td").eq(3).find(":input").val(pedidosaldo.toFixed(2));
                  $(this).closest('tr').find("td").eq(3).find(".numberdiferencia").text(pedidosaldo.toFixed(2));
                  let totalafterdifer1=$(this).closest('tr').find("td").eq(2).find(".numbersaldo").val();
                  let totalafterdifer2=$(this).closest('tr').find("td").eq(3).find(".numberdiferencia").val();
                  let totalafterdifer=parseFloat(totalafterdifer1-totalafterdifer2);
                  $(this).closest('tr').find("td").eq(3).find(".numbertotal").val(totalafterdifer);
                  //$(this).closest('tr').find(".radioadelanto").prop("disabled",false);

                  //revertir pago reviso todo otra vez
                  $('#tabla_pedidos > tbody  > tr').each(function(index,tr) {
                      console.log(index+" posicion");
                      console.log("aca valido check en total y check en saldo");
                      //var idfila=$(this).find("td").eq(0).html();//fila idpedido
                      var idfila=$(this).find("td").eq(0).find(":input").val();//fila idpedido
                      if(idfila!=filedata.id)
                      {
                        console.log("id no es el mismo que acabo de ejecutar")
                        
                        //var saldofila=$(this).find("td").eq(3).html();//saldo/551.20
                        var saldofila=parseFloat($(this).find("td").eq(2).find(":input").val());
                        console.log("saldo fila "+idfila+" es "+saldofila);//551.20
                        var radiototalfila=$(this).find("td").eq(4).find("input").prop("checked");//check radiototal
                        console.log("checktotal fila "+idfila+" es "+( (radiototalfila)? 'si':'no') );//
                        var radiosaldofila=$(this).find("td").eq(5).find("input").prop("checked");
                        console.log("checksaldo fila "+idfila+" es "+( (radiosaldofila)? 'si':'no') );
                        /*if(radiototalfila || radiosaldofila)
                        {
                          console.log("radiofila  "+index+" total o saldo esta checkedado")
                        }
                        if(!radiototalfila && !radiosaldofila){
                          console.log("radiofila "+index+" total o saldo no estan checkedado")                          
                          if(saldofila<=montopagos)
                          {
                            console.log("saldo menor igual a monto, bloqueo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",false);
                            $(this).find("td").eq(5).find("input").prop("disabled",false);
                          }else{
                            console.log("saldo mayor monto, bloqueo total,activo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",true);
                            $(this).find("td").eq(5).find("input").prop("disabled",false);
                          }
                        }*/
                      }
                      
                    });
              //fin revertir

            }else if($(this).prop("checked") == false){
              console.log("no marcado adelanto");///aca falla cuando el monto es menor que el saldo
                  let montopagos=parseFloat($("#diferencia").val().replace(",", ""));
                  if(montopagos==null || isNaN(montopagos)){
                    console.log("no hay pagos ingresados");
                    return;
                  }
                  let filedata=tabla_pedidos.row($(this).closest('tr')).data();
                  let pedidosaldo=parseFloat(filedata.saldo);
                  if(pedidosaldo==null || isNaN(pedidosaldo)){
                    console.log("no hay saldo ingresado");
                    return;
                  }
                  console.log("montopagos "+montopagos+" y pedidosaldo "+pedidosaldo);
                  //sol si saldo es mayor al monto
                  if(montopagos<pedidosaldo)
                  {
                    $(this).prop("checked",true).val("1")////aqui valida
                    console.log("cambio cheked 3")
                    guardasaldo=$("#diferencia").val();//356 guardo////27
                    let montopagosante=montopagos;
                    montopagos=(0).toFixed(2);
                    console.log("diferencia "+montopagos);
                    $("#diferencia").val(montopagos);

                    console.log("aqui cuando es adelanto y la diferencia debe ser 0 y guardarlo");
                    console.log("pedidosaldo "+pedidosaldo+" montopagos saldo "+montopagosante);
                    //restar saldo menos monto que queda general
                    let montoqueda=parseFloat(pedidosaldo-montopagosante);
                    console.log("montoqueda "+montoqueda)
                    $(this).closest('tr').find("td").eq(3).find(":input").val(montoqueda.toFixed(2));///diferencia
                    $(this).closest('tr').find("td").eq(3).find(".numberdiferencia").text(montoqueda.toFixed(2));///diferencia
                    let totalafterdifer1=$(this).closest('tr').find("td").eq(2).find(".numbersaldo").val();
                    let totalafterdifer2=$(this).closest('tr').find("td").eq(3).find(".numberdiferencia").val();
                    let totalafterdifer=parseFloat(totalafterdifer1-totalafterdifer2);
                    $(this).closest('tr').find("td").eq(3).find(".numbertotal").val(totalafterdifer);
                    //$(this).closest('tr').find(".radioatotal").prop("disabled",true);
                    //recorido defilas distintas al actual
                    $('#tabla_pedidos > tbody  > tr').each(function(index,tr) {
                      console.log(index+" posicion");
                      console.log("aca valido check en total y check en saldo");
                      //var idfila=$(this).find("td").eq(0).html();//fila idpedido
                      var idfila=$(this).find("td").eq(0).find(":input").val();//fila idpedido
                      if(idfila!=filedata.id)
                      {
                        console.log("id no es el mismo que acabo de ejecutar")
                        
                        //var saldofila=$(this).find("td").eq(3).html();//saldo
                        var saldofila=parseFloat($(this).find("td").eq(2).find(":input").val());
                        console.log("saldo fila "+idfila+" es "+saldofila);
                        var radiototalfila=$(this).find("td").eq(4).find("input").prop("checked");//check radiototal
                        console.log("checktotal fila "+idfila+" es "+( (radiototalfila)? 'si':'no') );
                        var radiosaldofila=$(this).find("td").eq(5).find("input").prop("checked");
                        console.log("checksaldo fila "+idfila+" es "+( (radiosaldofila)? 'si':'no') );
                        if(radiototalfila || radiosaldofila)
                        {
                          console.log("radiofila  "+index+" total o saldo esta checkedado")
                        }
                        if(!radiototalfila && !radiosaldofila){
                          console.log("radiofila "+index+" total o saldo no estan checkedado")                          
                          if(saldofila<=montopagos)
                          {
                            console.log("saldo menor igual a monto, bloqueo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",false);
                            $(this).find("td").eq(5).find("input").prop("disabled",false);
                          }else{
                            console.log("saldo mayor monto, bloqueo total,activo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",true);
                            $(this).find("td").eq(5).find("input").prop("disabled",false);
                          }
                        }
                      }
                      
                    });
                    
                  }
                  
              //fin validar

            }
            return;
           
          });

});