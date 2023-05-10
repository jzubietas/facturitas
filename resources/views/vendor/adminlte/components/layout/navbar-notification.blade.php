{{-- Navbar notification --}}

<li class="{{ $makeListItemClass() }}" id="{{ $id }}">

    {{-- Link --}}
    <a @if($enableDropdownMode) href="" @endif {{ $attributes->merge($makeAnchorDefaultAttrs()) }}>

        {{-- Icon --}}
        <i class="{{ $makeIconClass() }}"></i>

        {{-- Badge --}}
        <span class="{{ $makeBadgeClass() }}">{{ $badgeLabel }}</span>

    </a>

    {{-- Dropdown Menu --}}
    @if($enableDropdownMode)

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

            {{-- Custom dropdown content provided by external source --}}
            <div class="adminlte-dropdown-content"></div>

            {{-- Dropdown divider --}}
            <div class="dropdown-divider"></div>

            {{-- Dropdown footer with link --}}
            <a href="{{ $attributes->get('href') }}" class="dropdown-item dropdown-footer">
                @isset($dropdownFooterLabel)
                    {{ $dropdownFooterLabel }}
                @else
                    <i class="fas fa-lg fa-search-plus"></i>
                @endisset
            </a>

        </div>

    @endif

</li>

{{-- If required, update the notification periodically --}}

@if (! is_null($makeUpdateUrl()) && $makeUpdatePeriod() > 0)
    @push('js')
        <script>

            $(() => {

                // Method to get new notification data from the configured url.

                function insertDotMenu(child, dotClass, value) {
                    var parent = $(child).parent();
                    var epa = parent.find(dotClass);
                    if (epa.length > 0) {
                        epa.html(value);
                    } else {
                        parent.append('<i class="' + dotClass.split('.').join(' ').trim() + '">' + value + '</i>');
                    }
                }

                function insertIconMenu(child, dotClass, value) {
                    var parent = $(child).parent();
                    var epa = parent.find(dotClass);

                    $('.warning').remove();

                    if (epa.length > 0) {
                        epa.html(value);
                    } else {
                        parent.append('<i class="' + value + '"></i>');
                    }


                }

                const mapAlertShows = new Map

                function showAlerts(data) {
                    if (data.alertas) {
                        /*PNotify.info({
                                       title: 'En construcion ... ',
                                       text: '¿Espere al lanzamiento?',
                                       hide: false,
                                       closer: false,
                                       sticker: false,
                                       modules: new Map([
                                           ...PNotify.defaultModules,
                                           [PNotifyConfirm, {confirm: true}]
                                       ])
                                   });*/
                        data.alertas.forEach(function (alerta) {
                            if (!mapAlertShows.has(alerta.id)) {
                                let type = 'notice';
                                if(alerta.tipo=='info'||alerta.tipo=='success'||alerta.tipo=='error'){//notice|info|success|error
                                    type=alerta.tipo
                                }
                                const notice = PNotify[type]({
                                    title: alerta.subject,
                                    text: alerta.message,
                                    hide: false,
                                    closer: false,
                                    sticker: false,
                                    icon:'fa fa-frown',
                                    modules: new Map([
                                        ...PNotify.defaultModules,
                                        [PNotifyConfirm, {
                                            confirm: true,
                                            buttons: [
                                                {
                                                    text: 'Aceptar',
                                                    primary: true,
                                                    promptTrigger: true,
                                                    click: function (notice, value) {
                                                        $.post('{{route('alertas.confirmar',['action'=>'aceptar'])}}',{
                                                            alerta_id:alerta.id
                                                        })
                                                            .always(function () {
                                                                notice.close();
                                                                notice.fire('pnotify:confirm', {notice, value});
                                                            })
                                                    },
                                                }
                                                /*,
                                                {
                                                    text: 'Aceptar',
                                                    primary: true,
                                                    click: function (notice) {
                                                        $.post('{{route('alertas.confirmar',['action'=>'aceptar'])}}',{
                                                            alerta_id:alerta.id
                                                        })
                                                            .always(function () {
                                                                notice.close();
                                                                notice.fire('pnotify:cancel', {notice});
                                                            })
                                                    },
                                                },*/
                                            ]
                                        }]
                                    ]),
                                });
                                notice.on('pnotify:confirm', () => {
                                    mapAlertShows.delete(alerta.id)
                                });
                                notice.on('pnotify:cancel', () => {
                                    mapAlertShows.delete(alerta.id)
                                });
                                mapAlertShows.set(alerta.id, notice)
                            }
                        })
                    }
                }

                let updateNotification = (nLink) => {
                    // Make an ajax call to the configured url. The response should be
                    // an object with the new data. The supported properties are:
                    // 'label', 'label_color', 'icon_color' and 'dropdown'.

                    $.ajax({
                        url: "{{ $makeUpdateUrl() }}"
                    })

                        .done((data) => {
                            nLink.update(data);
                            insertDotMenu("i.dot_correcciones_count", '.noti-correcciones.noti-side', data.contador_correcciones)
                            insertDotMenu("i.dot_pedidos_atender_count", '.noti-pedidos-atender.noti-side', data.contador_pedidos_atender)
                            insertDotMenu("i.dot_pedidos_atencion_count", '.noti-pedidos-atencion.noti-side', data.contador_pedidos_atencion)
                            insertDotMenu("i.dot_pedidos_atendidos_count", '.noti-pedidos-atendidos.noti-side', data.contador_pedidos_atendidos)
                            insertDotMenu("i.dot_pedidos_atendidos_operacion_count", '.noti-pedidos-atendidos.noti-side', data.contador_pedidos_atendidos_operacion)
                            insertDotMenu("i.dot_pedidos_pen_anulacion_count", '.noti-pedidos-pen-anulacion.noti-side', data.contador_pedidos_pen_anulacion)
                            insertDotMenu("i.dot_sobres_entregados_count", '.noti-sobres-entregados.noti-side', data.contador_sobres_entregados)

                            insertDotMenu("i.dot_sobres_confirmar_recepcion_count", '.dot-notify.noti-side', data.contador_sobres_confirmar_recepcion)

                            insertDotMenu("i.dot_contador_en_motorizados_count", '.dot-notify.noti-side', data.contador_en_motorizados_count)
                            insertDotMenu("i.dot_contador_en_motorizados_confirmar_count", '.dot-notify.noti-side', data.contador_en_motorizados_confirmar_count)
                            insertIconMenu("i.dot_contador_sobres_devueltos", '.dot-notify.noti-side', data.contador_sobres_devueltos)

                            insertDotMenu("i.dot_encargado_tienda_agente_count", '.dot-notify.noti-side', data.contador_encargado_tienda_agente)
                            insertDotMenu("i.btnLlamadasCont", '.dot-notify.noti-side', data.contador_contactos_registrados)
                            $("#alert-authorization").html(data.authorization_courier)

                            showAlerts(data)
                        })


                        .fail(function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR, textStatus, errorThrown);
                        });
                };

                // First load of the notification data.

                let nLink = new _AdminLTE_NavbarNotification("{{ $id }}");
                updateNotification(nLink);

                // Periodically update the notification.

                setInterval(updateNotification, {{ $makeUpdatePeriod() }}, nLink);
            })

        </script>
    @endpush
@endif

{{-- Register Javascript utility class for this component --}}

@once
    @push('js')
        <script>

            class _AdminLTE_NavbarNotification {

                /**
                 * Constructor.
                 *
                 * target: The id of the target notification link.
                 */
                constructor(target) {
                    this.target = target;
                }

                /**
                 * Update the notification link.
                 *
                 * data: An object with the new data.
                 */
                update(data) {
                    // Check if target and data exists.

                    let t = $(`li#${this.target}`);

                    if (t.length <= 0 || !data) {
                        return;
                    }

                    let badge = t.find(".navbar-badge");
                    let icon = t.find(".nav-link > i");
                    let dropdown = t.find(".adminlte-dropdown-content");

                    // Update the badge label.
                    if (data.label && data.label > 0) {
                        badge.html(data.label);
                    } else {
                        badge.empty();
                    }
                    // Update the badge color.

                    if (data.label_color) {
                        badge.removeClass((idx, classes) => {
                            return (classes.match(/(^|\s)badge-\S+/g) || []).join(' ');
                        }).addClass(`badge-${data.label_color} badge-pill`);
                    }

                    // Update the icon color.

                    if (data.icon_color) {
                        icon.removeClass((idx, classes) => {
                            return (classes.match(/(^|\s)text-\S+/g) || []).join(' ');
                        }).addClass(`text-${data.icon_color}`);
                    }

                    // Update the dropdown content.

                    if (data.dropdown && dropdown.length > 0) {
                        dropdown.html(data.dropdown);
                    }
                }
            }

        </script>
    @endpush
@endonce
