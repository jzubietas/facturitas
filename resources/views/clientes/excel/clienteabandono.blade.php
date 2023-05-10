
<table>
  <thead>
    <tr>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ITEM</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ID</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ASESOR ASIGNADO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">NOMBRE</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">DNI</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">CELULAR</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">PROVINCIA</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">DISTRITO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">DIRECCION</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">REFERENCIA</th>
      <th style="background-color: #528d37; text-align: center; color: white;">% FISICO SIN BANCA</th>
      <th style="background-color: #528d37; text-align: center; color: white;">% FISICO BANCARIZADO</th>
      <th style="background-color: #528d37; text-align: center; color: white;">% ELECTRONICO SIN BANCA</th>
      <th style="background-color: #528d37; text-align: center; color: white;">% ELECTRONICO BANCARIZADO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">DEPOSITO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">FECHA DEL ÚLTIMO PEDIDO</th>
      <th style="background-color: #4c5eaf; text-align: center; color: white;">ESTADO PEDIDOS</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">ENE-{{ $anioa }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">FEB-{{ $anioa }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">MAR-{{ $anioa }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">ABR-{{ $anioa }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">MAY-{{ $anioa }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">JUN-{{ $anioa }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">JUL-{{ $anioa }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">AGO-{{ $anioa }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">SET-{{ $anioa }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">OCT-{{ $anioa }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">NOV-{{ $anioa }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">DIC-{{ $anioa }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">ENE-{{ $aniop }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">FEB-{{ $aniop }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">MAR-{{ $aniop }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">ABR-{{ $aniop }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">MAY-{{ $aniop }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">JUN-{{ $aniop }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">JUL-{{ $aniop }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">AGO-{{ $aniop }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">SET-{{ $aniop }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">OCT-{{ $aniop }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">NOV-{{ $aniop }}</th>
      <th style="background-color: #ebfa67; text-align: center; color: black;">DIC-{{ $aniop }}</th>
    </tr>
  </thead>
  <tbody>
    <?php $cont = 0; ?>

    @foreach ($cliente_list as $dato)
      <tr>
        <td>{{ $cont + 1 }}</td>
        <td>CLI00{{ $dato['id'] }}</td>
        <td>{{ $dato['asesor'] }}</td>
        <td>{{ $dato['nombre'] }}</td>
        <td>{{ $dato['dni'] }}</td>
        <td>{{ $dato['celular'] }}</td>
        <td>{{ $dato['icelular'] }}</td>
        <td>{{ $dato['provincia'] }}</td>
        <td>{{ $dato['distrito'] }}</td>
        <td>{{ $dato['direccion'] }}</td>
        <td>{{ $dato['referencia'] }}</td>

        <?php if($dato['porcentajefsb']!=null){ ?>
            <td>{{ $dato['porcentajefsb']['porcentaje'] }}</td>
        <?php }else{ ?>
            <td></td>
        <?php } ?>

        <?php if($dato['porcentajefb']!=null){ ?>
            <td>{{ $dato['porcentajefb']['porcentaje'] }}</td>
        <?php }else{ ?>
            <td></td>
        <?php } ?>

        <?php if($dato['porcentajeesb']!=null){ ?>
            <td>{{ $dato['porcentajeesb']['porcentaje'] }}</td>
        <?php }else{ ?>
            <td></td>
        <?php } ?>

        <?php if($dato['porcentajeeb']!=null){ ?>
            <td>{{ $dato['porcentajeeb']['porcentaje'] }}</td>
        <?php }else{ ?>
            <td></td>
        <?php } ?>

        <td>{{ $dato['deposito'] }}</td>
        <td>{{ $dato['fecha'] }}</td>
        @if($dato['estadopedido'] == 'RECURRENTE')
          <td style="background: #afdfb2">RECURRENTE</td>
        @else
          <td style="background: #e73d3d">ABANDONO</td>
        @endif
        <td>
            {{ $dato['eneroa'] }}
        </td>
        <td>
            {{ $dato['febreroa'] }}
        </td>
        <td>
            {{ $dato['marzoa'] }}
        </td>
        <td>
            {{ $dato['abrila'] }}
        </td>
        <td>
            {{ $dato['mayoa'] }}
        </td>
        <td>
            {{ $dato['junioa'] }}
        </td>
        <td>
            {{ $dato['julioa'] }}
        </td>
        <td>
            {{ $dato['agostoa'] }}
        </td>
        <td>
            {{ $dato['setiembrea'] }}
        </td>
        <td>
            {{ $dato['octubrea'] }}
        </td>
        <td>
            {{ $dato['noviembrea'] }}
        </td>
        <td>
            {{ $dato['diciembrea'] }}
        </td>
        <td>
            {{ $dato['enerop'] }}
        </td>
        <td>
            {{ $dato['febrerop'] }}
        </td>
        <td>
            {{ $dato['marzop'] }}
        </td>
        <td>
            {{ $dato['abrilp'] }}
        </td>
        <td>
            {{ $dato['mayop'] }}
        </td>
        <td>
            {{ $dato['juniop'] }}
        </td>
        <td>
            {{ $dato['juliop'] }}
        </td>
        <td>
            {{ $dato['agostop'] }}
        </td>
        <td>
            {{ $dato['setiembrep'] }}
        </td>
        <td>
            {{ $dato['octubrep'] }}
        </td>
        <td>
            {{ $dato['noviembrep'] }}
        </td>
        <td>
            {{ $dato['diciembrep'] }}
        </td>
      </tr>
      <?php $cont++; ?>
    @endforeach
  </tbody>
</table>
