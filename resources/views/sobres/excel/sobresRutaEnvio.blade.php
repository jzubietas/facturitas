
<br><br>
<table>
  <tbody>
    <tr colspan="8">
        <h1 style="text-align:center;">
          ENVIOS DE FECHA  {{  \Carbon\Carbon::now()->addDays(1)->format('d-m-Y') }}
        </h1>
    </tr>
  </tbody>
</table>
<table>
    <thead>
      <tr>
        {{--<th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ITEM</th>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">FECHA</th>
        <th style="background-color: #4c5eaf; text-align: center; color: #ffff;">ASESOR</th>--}}
        <th width="100px" style="background-color: #FFFF00; text-align: center; color: #ffff;">CLIENTE</th>
        <th width="30px" style="background-color: #FFFF00; text-align: center; color: #ffff;">QTY</th>
        <th width="100px" style="background-color: #FFFF00; text-align: center; color: #ffff;">CODIGOS</th>
        <th width="250px" style="background-color: #FFFF00; text-align: center; color: #ffff;">PRODUCTO</th>
        <th width="170px" style="background-color: #FFFF00; text-align: center; color: #ffff;">DIRECCION</th>
        <th width="180px" style="background-color: #FFFF00; text-align: center; color: #ffff;">REFERENCIA</th>
        <th width="180px" style="background-color: #FFFF00; text-align: center; color: #ffff;">OBSERVACION</th>
        <th width="90px" style="background-color: #FFFF00; text-align: center; color: #ffff;">DISTRITO</th>
      </tr>
    </thead>
    <tbody>
      <?php $cont = 0; ?>
      <?php $contproducto = 0; ?>
      
      @foreach ($pedidos as $plima)
        <tr>
          {{--<td>{{ $cont + 1 }}</td>--}}
          {{--<td>ENV00{{ $plima->id }}</td>
          <td>{{ $plima->fecha }}</td>
          <td>{{ $plima->identificador }}</td>--}}
          <td>{{ $plima->celular }}-{{ $plima->nombre }}</td>

          @if ($plima->destino2== 'PROVINCIA')
            <td style="color:red;">
          @else 
            <td>
          @endif
            {{ $plima->cantidad }}
          </td>

          @if ($plima->destino2== 'PROVINCIA')
            <td style="color:red;">
          @else 
            <td>
          @endif
          
            @foreach(explode(',', $plima->codigos) as $codigo) 
              <p>{{$codigo}}</p>
            @endforeach

          </td>
          @if ($plima->destino2== 'PROVINCIA')
            <td style="color:red;">
          @else 
            <td>
          @endif
          
            <?php $contproducto=1; ?>
            <?php  $count= count(explode(',',$plima->producto)) ?>
            <?php if($count==1){ ?>
              <p>{{$contproducto}} {{$plima->producto}}</p>
            <?php }else{ ?>
              <?php $contproducto=1; ?>
              @foreach(explode(',', $plima->producto) as $product) 
              
              <p>{{$contproducto}} {{$product}}</p>
              <?php $contproducto++; ?>
            @endforeach
            <?php $contproducto=0; ?>

           <?php } ?>
            

            </td>

          @if ($plima->destino2== 'PROVINCIA')
            <td style="color:red;">
          @else 
            <td>
          @endif

            {{ $plima->direccion }}
          </td>
          <td>

            @if ($plima->destino2== 'LIMA')
                {{ $plima->referencia }}
            @endif
          
            </td>
          <td>{{ $plima->observacion }}</td>

            

            @if($plima->distribucion!='')
                @if($plima->distribucion=='NORTE')
                  <td style="background-color:#A5F1E9">
                    {{$plima->distrito}}
                @elseif($plima->distribucion=="CENTRO")
                  <td style="background-color:#F0FF42">
                    {{$plima->distrito}}
                @elseif($plima->distribucion=="SUR")
                  <td style="background-color:#B6E2A1">
                    {{$plima->distrito}}
                @else
                  <td>
                @endif
            
            @else

              <td>
            @endif
           <td></td>
        </tr>
        <?php $cont++; ?>
      @endforeach
      
    </tbody>
  </table>