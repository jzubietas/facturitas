@extends('adminlte::page')

@section('title', 'Perfil de Usuario')

@section('content_header')
   {{-- <h1>PERFIL</h1>--}}
@stop

@section('content')

<div class="px-4 mt-4">

    <hr class="mt-0 mb-4">
    <h1 class="mt-5">PERFIL</h1>
    <div class="row">
        <div class="col-xl-4">
            <!-- Profile picture card-->
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Imagen de Perfil</div>
                <div class="card-body text-center">
                    <!-- Profile picture image-->
                    @if(file_exists('storage/users/' . $users->profile_photo_path))
                        <img class="img-account-profile rounded-circle mb-2" src="{{ 'storage/users/'.$users->profile_photo_path }}" alt="{{ $users->profile_photo_path }}" style="object-fit: contain; width: 300px; height: 300px;">
                    @else
                        <img class="img-account-profile rounded-circle mb-2" src="{{ Auth::user()->adminlte_image() }}" alt="{{ Auth::user()->adminlte_image() }}" style="object-fit: contain; width: 300px; height: 300px;">
                    @endif

                    <!-- Profile picture help block-->
                    <div class="small font-italic text-muted mb-4">JPG or PNG que no pese mas de 5 MB</div>
                    <!-- Profile picture upload button-->
                    <form id="frmNewImage">
                        <input type="file" class="form-control form-control-sm fileimagen"   accept="image/png,image/jpeg" id="imagen" name="imagen[]" />
                        <button class="btn btn-primary" type="button" id="btnCargarNuevaImagen" name="btnCargarNuevaImagen">Cargar nueva imagen</button>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">Detalles de la cuenta</div>
                <div class="card-body">
                    <form id="frmDetallesCuenta">
                        <!-- Form Group (username)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="txtCorreo">Usuario / correo</label>
                            <input class="form-control" id="txtCorreo" name="txtCorreo" type="text" placeholder="Ingrese su correo" value="{{$users->email}}" readonly>
                        </div>
                        <!-- Form Row-->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (first name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="txtNombresCompletos">Nombres Completos</label>
                                <input class="form-control" id="txtNombresCompletos" name="txtNombresCompletos" type="text" placeholder="Ingrese sus Nombres completos" value="{{$users->name}}">
                            </div>
                            <!-- Form Group (last name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="cbxPerfil">Rol</label>
                                {{--<input class="form-control" id="inputLastName" type="text" placeholder="Enter your last name" value="Luna">--}}
                                <select name="cbxPerfil" id="cbxPerfil" class="form-control" {{ ((Auth::user()->id==1)?'enabled':'disabled') }} >
                                    <option value=" ">----SELECCIONE----</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" {{(($role->name==$mirol)?'Selected':'')}}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Form Row        -->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (organization name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="txtIdentificador">Identificador</label>
                                <input class="form-control" id="txtIdentificador" name="txtIdentificador" type="text" placeholder="Ingrese su Identificador" value="{{$users->identificador}}" readonly>
                            </div>
                            <!-- Form Group (location)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="txtDireccion">Direccion</label>
                                <input class="form-control" id="txtDireccion" name="txtDireccion"  type="text" placeholder="Ingrese su direccion" value="{{$users->direccion}}">
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (phone number)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="txtCelular">Celular</label>
                                <input class="form-control" id="txtCelular" name="txtCelular" type="tel" placeholder="Ingrese su numero" value="{{ $users->celular }}">
                            </div>
                            <!-- Form Group (birthday)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="txtCumpleanios">Birthday</label>
                                <input class="form-control" id="txtCumpleanios" type="date" name="txtCumpleanios" placeholder="Ingrese Cumpleaños" value="{{ $users->birthday }}">
                            </div>
                        </div>
                        <!-- Form Group (email address)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="txtContraseniaAnterior">Contraseña Anterior</label>
                            <input class="form-control" id="txtContraseniaAnterior" name="txtContraseniaAnterior" type="password" placeholder="Ingrese contraseña" value="">
                           {{-- <div class="input-group-append">
                                <div class="input-group-text">
                                    <span id="show_password" class="fas fa-eye" role="button"></span>
                                </div>
                            </div>--}}
                            <label class="small mb-1" for="txtContraseniaNueva">Contraseña Nueva</label>
                            <input class="form-control" id="txtContraseniaNueva" name="txtContraseniaNueva"  type="password" placeholder="Ingrese contraseña" value="">
                        </div>
                        <input type="hidden" name="txtUserid" id="txtUserid" value="{{ $users->id }}">
                        <!-- Form Row-->
                        <!-- Save changes button-->
                        <button class="btn btn-primary" type="submit" id="btnActualizarDatos">Actualizar Datos</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
    $('#txtContraseniaAnterior').val('');
    $.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
    function convertFormToJSON(form) {
        const array = $(form).serializeArray(); // Encodes the set of form elements as an array of names and values.
        const json = {};
        $.each(array, function () {
            json[this.name] = this.value || "";
        });
        return json;
    }
    $("#frmDetallesCuenta").on("submit", function (e) {
        e.preventDefault();

        const form = $(e.target);
        const datosfrm = convertFormToJSON(form);
        var datasend = new FormData();
        $('#cbxPerfil').prop('disabled',false)
        var perfil=   $('#cbxPerfil').val();
        $('#cbxPerfil').prop('disabled',true)
        /*datasend.append('cbxPerfil', perfil);*/
        datasend.append('txtCelular', datosfrm.txtCelular);
        datasend.append('txtContraseniaAnterior', datosfrm.txtContraseniaAnterior);
        datasend.append('txtContraseniaNueva', datosfrm.txtContraseniaNueva);
        datasend.append('txtCorreo', datosfrm.txtCorreo);
        datasend.append('txtCumpleanios', datosfrm.txtCumpleanios);
        datasend.append('txtDireccion', datosfrm.txtDireccion);
        datasend.append('txtIdentificador', datosfrm.txtIdentificador);
        datasend.append('txtNombresCompletos', datosfrm.txtNombresCompletos);
        datasend.append('txtUserid', datosfrm.txtUserid);

        console.log(datosfrm);
        $.ajax({
           data: datasend,
           processData: false,
           contentType: false,
           type: 'POST',
           url: "{{ route('users.updateprofile') }}",
            success: function (respuesta) {
                console.log('respuesta',respuesta);
                if (respuesta.success){
                    Swal.fire(
                        'Notificacion',
                        'Datos del perfil actualizado correctamente',
                        'success'
                    )
                }else {
                    Swal.fire(
                        'Alerta',
                        'Hubo un error al intentar actualizar',
                        'error'
                    )
                }
            }
        });
    });

    $("#frmNewImage").on("submit", function (e) {
        e.preventDefault();
        const formulario = $(e.target);
        const datosImg = convertFormToJSON(formulario);
        /*var datasend = new FormData();*/
        console.log('FrmImage',datosImg);
    });
    $(document).on("click", "#btnCargarNuevaImagen", function () {
        var imagen = $('#imagen').val();
        var userid = $('#txtUserid').val();
        if (imagen==''){
            Swal.fire(
                'Alerta',
                'Debe seleccionar una imagen.',
                'error'
            );
            return false;
        }
        console.log('imagen',imagen);
        var dataimg = new FormData();
        dataimg.append('img', imagen);
        dataimg.append('userid', userid);
        let filesimg= $('[name="imagen[]');
        if (filesimg[0].files.length > 0) {
            for (let i in filesimg[0].files) {
                dataimg.append('imagen[]', filesimg[0].files[i]);
            }
        }
        $.ajax({
            data: dataimg,
            processData: false,
            contentType: false,
            type: 'POST',
            url: "{{ route('users.updateimage') }}",
            success: function (respuesta) {
                console.log('respuesta',respuesta);
                if (respuesta.success){
                    Swal.fire(
                        'Notificacion',
                        'Imagen actualizado correctamente',
                        'success'
                    )

                }else {
                    Swal.fire(
                        'Alerta',
                        'Hubo un error al intentar actualizar',
                        'error'
                    )
                }
            }
        });
    });
</script>
@endsection
