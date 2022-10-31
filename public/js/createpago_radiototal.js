$(document).ready(function() {
	$(document).on("click",".radiototal",function(event){
        event.preventDefault();
    });

    $(document).on("mousedown",".radiototal",function(event){
    	event.preventDefault();
    	event.stopPropagation();
    	let valchecktotal=$(this).prop("checked");
    	if($(this).prop("checked") === true){
    		console.log("true");
    		return false;
    	}else{
    		console.log("true");
    		return false;
    	}
    });

    $(document).on("mousedown",".radiototasssl",function(event){
            event.preventDefault();
            
            if($(this).prop("checked") == true)
            {
              //revertir
              console.log("true");
              //$(this).prop("checked",false).val("0")

              return true;
            }else{
              //marcar
              console.log("false");

              //$(this).prop("checked",true).val("1")
              return true;
            }
            
            return true;
            if($(this).prop("checked") == true){
              console.log("marcado")
              $(this).prop("checked",false).val("0")////
              console.log("cambio cheked 4")
              //revertir
                  let montopagos=parseFloat($("#diferencia").val().replace(",", ""));
                  if(montopagos==null || isNaN(montopagos)){
                    console.log("no hay pagos ingresados");
                    return;
                  }

                  let filedata=tabla_pedidos.row($(this).closest('tr')).data();
                  console.log(filedata)
                  let pedidosaldo=parseFloat(filedata.saldo);
                  if(pedidosaldo==null || isNaN(pedidosaldo)){
                    console.log("no hay saldo ingresado");
                    return;
                  }

                  console.log("desmarco saldo "+pedidosaldo+" monto "+montopagos)

                  montopagos=parseFloat(montopagos+pedidosaldo);
                  console.log("diferencia "+montopagos);
                  $("#diferencia").val(montopagos);
                  
                  $(this).closest('tr').find("td").eq(3).find(":input").val(pedidosaldo.toFixed(2));
                  $(this).closest('tr').find("td").eq(3).find(".numberdiferencia").text(pedidosaldo.toFixed(2));
                  let totalafterdifer1=$(this).closest('tr').find("td").eq(2).find(".numbersaldo").val();
                  let totalafterdifer2=$(this).closest('tr').find("td").eq(3).find(".numberdiferencia").val();
                  let totalafterdifer=parseFloat(totalafterdifer1-totalafterdifer2);
                  $(this).closest('tr').find("td").eq(3).find(".numbertotal").val(totalafterdifer);

                  $(this).closest('tr').find(".radioadelanto").prop("disabled",false);
                  $('#tabla_pedidos > tbody  > tr').each(function(index,tr) {
                      console.log(index+" posicion");
                      console.log("aca valido check en total y check en saldo");
                      //var idfila=$(this).find("td").eq(0).html();//fila idpedido
                      var idfila=$(this).find("td").eq(0).find(":input").val();//fila idpedido
                      //if(idfila!=filedata.id)
                      {
                        console.log("id no es el mismo que acabo de ejecutar")
                        
                        //var saldofila=parseFloat($(this).find("td").eq(3).html());//saldo
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
                          console.log("saldofila = "+saldofila + " y montopagos="+montopagos);//600/100
                          console.log("radiofila "+index+" total o saldo no estan checkedado")                          
                          if(saldofila<=montopagos)
                          {
                            console.log("saldo "+saldofila+" es menor igual a monto "+montopagos+", bloqueo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",false);
                            $(this).find("td").eq(5).find("input").prop("disabled",true);
                          }else{
                            console.log("saldo "+saldofila+" es mayor a monto "+montopagos+", bloqueo total,activo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",true);
                            $(this).find("td").eq(5).find("input").prop("disabled",false);
                          }
                        }
                      }
                      
                    });
              //fin revertir

            }else if($(this).prop("checked") == false){
              console.log("no marcado");///aca falla cuando el monto es menor que el saldo
              
                  let montopagos=parseFloat($("#diferencia").val().replace(",", ""));
                  if(montopagos==null || isNaN(montopagos)){
                    console.log("no hay pagos ingresados");
                    return;
                  }

                  

                  let filedata=tabla_pedidos.row($(this).closest('tr')).data();
                  console.log(filedata)
                  let pedidosaldo=parseFloat(filedata.saldo);
                  if(pedidosaldo==null || isNaN(pedidosaldo)){
                    console.log("no hay saldo ingresado");
                    return;
                  }

                  console.log("monto es "+montopagos)
                  console.log("saldo es "+pedidosaldo)

                  if(montopagos>=pedidosaldo)
                  {
                    console.log("montopagos>=pedidosaldo")
                    ////aqui valida////////data aqui check para diferencia
                    montopagos=parseFloat(montopagos-pedidosaldo);
                    //console.log("diferencia "+montopagos);
                    $("#diferencia").val(montopagos.toFixed(2));
                    /////
                    //console.log("aqui debo cambiar el valor de input y span de columna diferencia");
                    $(this).closest('tr').find("td").eq(3).find(":input").val("0.00");
                    $(this).closest('tr').find("td").eq(3).find(".numberdiferencia").text("0.00");
                    let totalafterdifer1=$(this).closest('tr').find("td").eq(2).find(".numbersaldo").val();
                    let totalafterdifer2=$(this).closest('tr').find("td").eq(3).find(".numberdiferencia").val();
                    let totalafterdifer=parseFloat(totalafterdifer1-totalafterdifer2);
                    $(this).closest('tr').find("td").eq(3).find(".numbertotal").val(totalafterdifer);
                    //return;
                    //$(this).closest('tr').find(".numberdiferencia").text("0.00");
                    //$(this).find("td").eq(3).find(":input").val("0.00");///aqui me quede
                    //$(this).find("td").eq(3).find("span").text("0.00");///aqui me quede

                    //$(this).prop("checked",false).val("0")
                    //console.log("cambio cheked 5")

                    $(this).closest('tr').find(".radioadelanto").prop("disabled",true);
                    //prop("disabled",false)
                    $(this).closest('tr').find(".radiototal").prop("disabled",false).val("0");
                    console.log("cambio cheked 1")
                    $(this).closest('tr').find(".radiototal").prop("checked",true).val("1");
                    
                    return;

                    console.log("nuevo montogeneral "+$("#diferencia").val());
                    $('#tabla_pedidos > tbody  > tr').each(function(index,tr) {
                      console.log(index+" posicion");
                      console.log("aca valido check en total y check en saldo");
                      //var idfila=$(this).find("td").eq(0).html();//fila idpedido
                      var idfila=$(this).find("td").eq(0).find(":input").val();//fila idpedido
                      //if(idfila==filedata.id){
                        //console.log(idfila+" con "+filedata.id)
                        //$(this).prop("checked",true).val("1")
                      //}
                      if(idfila!=filedata.id)
                      {
                        console.log("id no es el mismo que acabo de ejecutar")
                        
                        //var saldofila=parseFloat($(this).find("td").eq(3).html());//saldo
                        var saldofila=parseFloat($(this).find("td").eq(2).find(":input").val());//saldo
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
                          console.log("saldofila = "+saldofila + " y montopagos="+montopagos);
                          console.log("radiofila "+index+" total o saldo no estan checkedado")                          
                          if(saldofila<=montopagos)
                          {
                            console.log("saldo menor igual a monto, bloqueo saldo");
                            $(this).find("td").eq(4).find("input").prop("disabled",false);
                            $(this).find("td").eq(5).find("input").prop("disabled",true);
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
            //return;
           
          });
});