$( "#user_id" ).change(function() {
    console.log("link asesor")
    var uid=$(this).val();
    $.ajax({
        url: "{{ route('cargar.clientedeasesorparapagos') }}?user_id=" + uid,
        method: 'GET',
        success: function(data) {
          console.log(data.html);
          $('#pcliente_id').html(data.html);
          $("#pcliente_id").selectpicker("refresh");

        }
      });
  });

  $("#formulario").submit(function(event){
    event.preventDefault();
    console.log("abrir")
   
   var fd = new FormData();

   //general
   fd.append("user_id", $("#user_id").val() );
   fd.append("cliente_id", $("#cliente_id").val() );
   fd.append("pcliente_id", $("#pcliente_id").val() );
   fd.append("saldo", $("#saldo").val() );
   fd.append("total_pago_pagar", $("#total_pago_pagar").val() );
   fd.append("total_pedido", $("#total_pedido").val() );
   fd.append("total_pedido_pagar", $("#total_pedido_pagar").val() );
   fd.append("ppedido_id", $("#ppedido_id").val() );
   fd.append("pbanco", $("#pbanco").val() );
   $('input[name="tipotransferencia[]"]').each(function(){
     fd.append("tipotransferencia[]", this.value);
   });
   fd.append("titulares", $("#titulares").val() );
   fd.append("pmonto", $("#pmonto").val() );
   fd.append("pfecha", $("#pfecha").val() );
   fd.append("diferencia", $("#diferencia").val() );

   //pagos
   $('input[name="tipomovimiento[]"]').each(function(){
     fd.append("tipomovimiento[]", this.value);
   });
   $('input[name="titular[]"]').each(function(){
     fd.append("titular[]", this.value);
   });
   $('input[name="banco[]"]').each(function(){
     fd.append("banco[]", this.value);
   });
   $('input[name="fecha[]"]').each(function(){
     fd.append("fecha[]", this.value);
   });      
   $('input[name="monto[]"]').each(function(){
     fd.append("monto[]", this.value);
   });

   //pedidos
   $('input[name="pedido_id[]"]').each(function(){
     fd.append("pedido_id[]", this.value);
   });
   $('input[name="numbersaldo[]"]').each(function(){
     fd.append("numbersaldo[]", this.value);
   });
   $('input[name="numberdiferencia[]"]').each(function(){
     fd.append("numberdiferencia[]", this.value);
   });
   $('input[name="numbertotal[]"]').each(function(){
     fd.append("numbertotal[]", this.value);
   });
   
   $('input[name="checktotal[]"]').each(function(){
     fd.append("checktotal[]", this.value);
   });
   $('input[name="checkadelanto[]"]').each(function(){
     fd.append("checkadelanto[]", this.value);
   });

   //adjuntos
   let files=$('input[name="imagen"]');
   console.log("files "+files.length)
   if(files.length == 0)
   {
     Swal.fire(
         'Error',
         'Debe ingresar el detalle del pedido',
         'warning'
       )
       return false;
    }else{
     //
     console.log(files.length);//this.files.length
     var totalfilescarga = $('input[name="imagen"]').files.length;
     console.log("totalfilescarga "+totalfilescarga);
     
     if(files.length!=totalfilescarga)
     {
       Swal.fire(
         'Error',
         'Debe ingresar los adjuntos del pago',
         'warning'
       )
       return false;
     }else{
       for (let i = 0; i < files.length; i++) {
         fd.append('imagen['+i+']', files[i]);
       }
     }

     console.log(fd);

   }

   ///final de validacion registro pago



  });
  ////submitpagos


  $(document).on("click",".radiototal",function(event){
    event.preventDefault();
  });

  $(document).on("click",".radioadelanto",function(event){
    event.preventDefault();
  });

  $(document).on("mousedown",".radiototal",function(event){
    event.preventDefault();
    if($(this).prop("checked") == true){
      console.log("marcado")
      $(this).prop("checked",false).val("0")////
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
      
      //validar si sumar depende el saldo y monto
          let montopagos=parseFloat($("#diferencia").val().replace(",", ""));
          if(montopagos==null || isNaN(montopagos)){
            console.log("no hay pagos ingresados");
            return;
          }

          console.log("monto es "+montopagos)

          let filedata=tabla_pedidos.row($(this).closest('tr')).data();
          console.log(filedata)
          let pedidosaldo=parseFloat(filedata.saldo);
          if(pedidosaldo==null || isNaN(pedidosaldo)){
            console.log("no hay saldo ingresado");
            return;
          }

          console.log("saldo es "+pedidosaldo)

          if(montopagos>=pedidosaldo)
          {
            $(this).prop("checked",true).val("1")////aqui valida////////data aqui check para diferencia
            montopagos=parseFloat(montopagos-pedidosaldo);
            console.log("diferencia "+montopagos);
            $("#diferencia").val(montopagos.toFixed(2));
            /////
            console.log("aqui debo cambiar el valor de input y span de columna diferencia");
            $(this).closest('tr').find("td").eq(3).find(":input").val("0.00");
            $(this).closest('tr').find("td").eq(3).find(".numberdiferencia").text("0.00");
            let totalafterdifer1=$(this).closest('tr').find("td").eq(2).find(".numbersaldo").val();
            let totalafterdifer2=$(this).closest('tr').find("td").eq(3).find(".numberdiferencia").val();
            let totalafterdifer=parseFloat(totalafterdifer1-totalafterdifer2);
            $(this).closest('tr').find("td").eq(3).find(".numbertotal").val(totalafterdifer);
            //$(this).closest('tr').find(".numberdiferencia").text("0.00");
            //$(this).find("td").eq(3).find(":input").val("0.00");///aqui me quede
            //$(this).find("td").eq(3).find("span").text("0.00");///aqui me quede

            $(this).closest('tr').find(".radioadelanto").prop("disabled",true);
            console.log("nuevo montogeneral "+$("#diferencia").val());
            $('#tabla_pedidos > tbody  > tr').each(function(index,tr) {
              console.log(index+" posicion");
              console.log("aca valido check en total y check en saldo");
              //var idfila=$(this).find("td").eq(0).html();//fila idpedido
              var idfila=$(this).find("td").eq(0).find(":input").val();//fila idpedido
              //if(idfila!=filedata.id)
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
    return;
   
  });

  $(document).on("mousedown",".radioadelanto",function(event){
    event.preventDefault();
    if($(this).prop("checked") == true){
      console.log("marcado")
      $(this).prop("checked",false).val("0")//////revertir              
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
          //sol si saldo es mayor al monto
          if(montopagos<pedidosaldo)
          {
            $(this).prop("checked",true).val("1")////aqui valida
            guardasaldo=$("#diferencia").val();//356 guardo
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

  $(document).on("change",'#diferencia',function(e){
    console.log("logica de diferencia");
    console.log($(this).val());
    console.log("actualizar tabla de pedidos a pagar")
  });

  $(document).on("click",'#add_pedido',function(){
    agregarPedido();

  });

  $(document).on("keyup",'input.number',function(event){
    if(event.which >= 37 && event.which <= 40){
      event.preventDefault();
    }
    $(this).val(function(index, value) {
      return value
        .replace(/\D/g, "")
        .replace(/([0-9])([0-9]{2})$/, '$1.$2')  
        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",")
      ;
    });

  });