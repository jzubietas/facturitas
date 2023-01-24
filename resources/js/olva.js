async function get_olva_tracking(tracking, tracking_year) {
    this.information = {
        general: {
            id_envio: "",
            emision: "",
            remito: "",
            remitente: "",
            doc_externo: "",
            consignado: "",
            contenido: "",
            peso: "",
            cantidad: "",
            nombre_estado_tracking: "",
            nombre_estado: "",
            flg_devolucion: null
        },
        details: [],
        realtime: {},
        dates: {
            registrado: "",
            entregado: "",
            origen: "",
            destino: ""
        }
    }
    this.pimcoreApikey = 'a82e5d192fae9bbfee43a964024498e87dfecb884b67c7e95865a3bb07b607dd';
    this.baseURL = 'https://reports.olvaexpress.pe';

    const e = await fetch(`${this.baseURL}/webservice/rest/getTrackingInformation?tracking=${tracking}&emision=${tracking_year}&apikey=${this.pimcoreApikey}&details=1`, {
        method: "GET"
    }).then((response) => response.json())
        .catch(t => {
            console.error("Error al obtener tracking del CDN:", t)
        }).finally(() => {
        });

    this.information.general = e.data.general
    this.information.details = e.data.details
    this.information.realtime = e.data.realtime
    this.information.dates.destino = this.information.general.destino;
    this.information.dates.destino = this.information.general.destino;
    console.log(this.information)
    var contenido = this.information.general.contenido.replace("SYSTEM", "CAJA");
    this.information.general.contenido = contenido,
        fetch(`${this.baseURL}/webservice/rest/images?type=1&id=${this.information.general.id_envio}&apikey=${this.pimcoreApikey}`, {
            method: "GET"
        }).then(t => t.json()).catch(t => {
            console.error("Error al obtener imagenes del CDN:", t)
        }).finally(() => {
            this.loading = !1
        });
    var destino = this.information.general.destino
    var details = this.information.details.filter(function (t, e) {
        return (
            "RECIBIDO DEL CLIENTE" == t.estado_tracking
            || "CONFIRMACION PAGO OLVACOMPRAS" == t.estado_tracking) && (
                t.obs = "En almac\xe9n " + t.nombre_sede,
            t.img = "/assets/img/almacen.png")
    });
    details.length && (
        this.information.dates.registrado = details[0].fecha_creacion,
        this.information.dates.origen = details[0].nombre_sede);
    var details2 = this.information.details.filter(function (t, e) {
        return "RECEPCIONADO" == t.estado_tracking && (t.obs = "Tu env\xedo ha sido recepcionado por nuestro colaborador de recojo.",
            t.img = "/assets/img/recepcionado.png",
            !0)
    });
    details2.length && (
        this.information.dates.registrado = details2[0].fecha_creacion,
        this.information.dates.origen = details2[0].nombre_sede);
    var details3 = this.information.details.filter(function (t, e) {
        return ("REGISTRADO" == t.estado_tracking || "TRACKING IMPRESO" == t.estado_tracking) && (t.obs = "Tu env\xedo ha sido registrado en nuestro sistema.",
            t.img = "/assets/img/registrado.png",
            !0)
    });
    if (details3.length && (
        this.information.dates.registrado = details3[0].fecha_creacion,
        this.information.dates.origen = details3[0].nombre_sede),
    "REGISTRADO" == this.information.general.nombre_estado_tracking && !details3.length) {
        var a = this.information.general.nombre_oficina.split("-");
        this.clases = "registhabis",
            this.information.dates.registrado = this.information.general.fecha_envio,
            this.information.dates.origen = a[0],
        this.information.details.length || (this.information.details = [{
            fecha_creacion: this.information.general.fecha_envio,
            nombre_sede: a[0],
            estado_tracking: "REGISTRADO",
            obs: "Tu env\xedo se encuentra en nuestro almac\xe9n."
        }])
    }
    var origen = this.information.dates.origen;
    this.information.details.filter(function (t, e) {
        return ("EN VALIJA" == t.estado_tracking || "PRE VALIJA" == t.estado_tracking || "PRE DESPACHO" == t.estado_tracking) && (t.estado_tracking = t.nombre_sede == destino || t.nombre_sede == origen ? "EN ALMACEN" : "",
            t.obs = "En almac\xe9n " + t.nombre_sede + ".",
            t.img = "/assets/img/almacen.png",
            !0)
    }).length && (
        0 == details3.length ? (this.information.dates.registrado = "",
            this.information.dates.origen = "") : (this.information.dates.registrado = details3[0].fecha_creacion,
            this.information.dates.origen = details3[0].nombre_sede));

    var details4 = this.information.details.filter(function (t, e) {
        return "CONFIRMACION RECOJO" == t.estado_tracking ? (t.obs = "Tu env\xedo ha sido recepcionado por nuestro colaborador de recojo.",
            t.img = "/assets/img/recepcionado.png",
            t.estado_tracking = "RECEPCIONADO",
            !0) : "RECEPCION TIENDA" == t.estado_tracking && (t.obs = "Recepcionado en tienda.",
            t.img = "/assets/img/recepcionado.png",
            t.estado_tracking = "RECEPCIONADO",
            !0)
    });
    details4.length && (
        this.information.dates.registrado = details4[0].fecha_creacion,
        this.information.dates.origen = details4[0].nombre_sede),
    this.information.details.filter(function (t, e) {
        if ("REZAGADO" == t.estado_tracking) {
            var n = t.obs.split("/");
            return t.obs = "Tu env\xedo ha sido retenido (" + n[0] + "). Comun\xedcate con nuestro call center 01-7140909"
        }
        return !1
    }),
    this.information.details.filter(function (t, e) {
        return "EN PROCESO DE VERIFICACION POR MESA DE PARTES" == t.estado_tracking && (t.estado_tracking = "MESA DE PARTES",
            t.obs = "En proceso de verificacion por la entidad",
            !0)
    })

    this.information.details.filter(function (t, e) {
        return "DESPACHADO" == t.estado_tracking && (t.estado_tracking = "EN CAMINO",
            t.obs = "Tu env\xedo ha sido despachado a " + destino + ".",
            t.img = "/assets/img/camino.png",
            !0)
    });
    var details5 = this.information.details.filter(function (t, e) {
        if ("EN RUTA" == t.estado_tracking || "ASIGNADO" == t.estado_tracking) {
            var n = 0;
            if ("ASIGNADO" == t.estado_tracking) {
                var s = t.obs.split(" - ");
                if ("Codigo operador: OCA" == s[0])
                    return t.estado_tracking = "NO MUESTRA",
                    t.nombre_sede == destino;
                var i = s[1].split(": ")
                    , o = i[1].split("/");
                new Date(o[2], o[1] - 1, o[0]) <= new Date && (t.fecha_creacion = i[1]),
                "2648" === (s[3] ? s[3].split(": ") : [])[1] && (n = 1)
            }
            return t.estado_tracking = t.nombre_sede == destino ? "EN CAMINO" : "",
                t.obs = 0 == n ? "En camino a tu direcci\xf3n." : "Tu env\xedo pronto llegar\xe1 a la tienda/agente seleccionada.",
                t.img = "/assets/img/ruta.png",
                !0
        }
        return !1
    });

    this.information.details.filter(function (t, e) {
        return ("RECEPCION DESPACHO" == t.estado_tracking || "TRANSITO" == t.estado_tracking || "CONFIRMACION DE LLEGADA A SEDE" == t.estado_tracking) && (t.obs = "Tu env\xedo se encuentra en " + t.nombre_sede + ".",
            t.obs += t.nombre_sede == destino ? "Te mandaremos un msj cuando este listo para su entrega." : " El viaje est\xe1 por completarse.",
            t.estado_tracking = t.nombre_sede == destino ? "EN PROVINCIA" : "EN ESCALA",
            t.img = "/assets/img/escala.png",
            !0)
    });
    var details6 = this.information.details.filter(function (t, e) {
        return "CONFIRMACION EN TIENDA" == t.estado_tracking && (t.estado_tracking = "EN TIENDA/AGENTE",
            t.obs = "Tu env\xedo ya se encuentra en tienda/agente",
            t.img = "/assets/img/recibido.png",
            !0)
    });
    details6.length && (this.classCasa = !0,
        this.classTienda = !1);
    var details7 = this.information.details.filter(function (t, e) {
        if ("MOTIVADO" == t.estado_tracking || "AUSENTE" == t.estado_tracking) {
            var n = t.obs.split(" - ");
            return t.obs = "Tu env\xedo no fue entregado por el " + (n[3] ? n[3].toLowerCase() : n[3]),
                t.estado_tracking = "NO ENTREGADO",
                t.img = "/assets/img/warning2.png",
                !0
        }
        return !1
    });
    if (details7.length)
        if (details6.length) {
            var p = details6[0].fecha_creacion.split("/")
                , f = details7[0].fecha_creacion.split("/");
            new Date(p[2], p[1], p[0]) > new Date(f[2], f[1], f[0]) ? (this.classCasa = !0,
                this.classTienda = !1,
                this.information.dates.entregado = "-")
        } else
                this.information.dates.entregado = "-";
    this.information.details.filter(function (t, e) {
        return "DEVUELTO" == t.estado_tracking && (t.obs = "El env\xedo regresa al punto de partida",
            !0)
    }).length && (this.clases = "entregaexitosa devolucion",
        this.information.dates.entregado = "-");
    var g = this.information.details.filter(function (t, e) {
        return "ANULACI\xd3N DE TRACKING" == t.estado_tracking
    });
    ("ANULACI\xd3N DE TRACKING" == this.information.general.nombre_estado_tracking || g.length) && (
        a = this.information.general.nombre_oficina.split("-"),
        this.information.details = [{
            fecha_creacion: this.information.general.fecha_envio,
            nombre_sede: a[0],
            estado_tracking: "SERVICIO CANCELADO",
            obs: "Tu env\xedo ya no existe en nuestro sistema."
        }],
        this.information.dates.registrado = this.information.general.fecha_envio,
        this.information.dates.origen = a[0]);
    var m = this.information.details.filter(function (t, e) {
        return "ANULACI\xd3N DE COMPROBANTE PAGO" == t.estado_tracking
    });
    ("ANULACI\xd3N DE COMPROBANTE PAGO" == this.information.general.nombre_estado_tracking || m.length) && (
        a = this.information.general.nombre_oficina.split("-"),
        this.information.details = [{
            fecha_creacion: this.information.general.fecha_envio,
            nombre_sede: a[0],
            estado_tracking: "COMPROBANTE ANULADO",
            obs: "Tu env\xedo ya no existe en nuestro sistema a solicitud del que env\xeda."
        }],
        this.information.dates.registrado = this.information.general.fecha_envio,
        this.information.dates.origen = a[0]);
    var y = this.information.details.filter(function (t, e) {
        return "ENTREGADO" == t.estado_tracking && (t.obs = "Tu env\xedo ha sido entregado, \xa1Gracias por confiar en nosotros!",
            t.img = "/assets/img/entregado.png",
            !0)
    });
    y.length && (
        this.information.dates.entregado = y[0].fecha_creacion),
    this.classCasa || this.classTienda || (this.classTienda = !0,
        this.classCasa = !1);
    var _ = !1;
    this.information.details.forEach(function (t) {
        "ASIGNADO A DEVOLUCION" == t.estado_tracking && (_ = !0)
    }),
        this.information.details = this.information.details.filter(function (t, e) {
            if ("ANULACI\xd3N DE TRACKING" == t.estado_tracking || "ANULACI\xd3N DE COMPROBANTE PAGO" == t.estado_tracking || "RECIBIDO DEL CLIENTE" == t.estado_tracking || "RECEPCIONADO" == t.estado_tracking || "REGISTRADO" == t.estado_tracking || "ENTREGADO" == t.estado_tracking || "EN PROCESO DE VERIFICACION POR MESA DE PARTES" == t.estado_tracking || "MESA DE PARTES" == t.estado_tracking || "ASIGNADO" == t.estado_tracking || "EN TIENDA/AGENTE" == t.estado_tracking || "EN CAMINO" == t.estado_tracking || "RECEPCION DESPACHO" == t.estado_tracking || "REZAGADO" == t.estado_tracking || "DEVUELTO" == t.estado_tracking || "MOTIVADO" == t.estado_tracking || "AUSENTE" == t.estado_tracking || "CONFIRMACION DE LLEGADA A SEDE" == t.estado_tracking || "SERVICIO CANCELADO" == t.estado_tracking || "COMPROBANTE ANULADO" == t.estado_tracking || "EN PROVINCIA" == t.estado_tracking || "EN RUTA" == t.estado_tracking || "EN ALMACEN" == t.estado_tracking || "EN ESCALA" == t.estado_tracking || "NO ENTREGADO" == t.estado_tracking || "SINIESTRADO" == t.estado_tracking) {
                var n = t.fecha_creacion.split("-");
                return n.length > 2 && (t.fecha_creacion = n[2] + "/" + n[1] + "/" + n[0]),
                "ENTREGADO" == t.estado_tracking && _ && (t.estado_tracking = "ENTREGADO POR DEVOLUCION",
                    t.obs = "Tu env\xedo ha sido devuelto, \xa1Gracias por confiar en nosotros!"),
                    !0
            }
            return !1
        })
    console.log("RESPUEStA tRANSFORMADA::", this.information.details);
    for (var v = [], b = 0, w = this.information.details.length; b < w; b++) {
        var S = this.information.details[b];
        v[S.estado_tracking + " - " + S.fecha_creacion] || (v[S.estado_tracking + " - " + S.fecha_creacion] = S)
    }
    b = 0;
    var C = [];
    for (S in v)
        C[b++] = v[S];
    this.information.details = C
    if (null != this.information.general.flg_devolucion && 1 == this.information.general.flg_devolucion) {
        const t = this.information.dates.origen;
        this.information.dates.origen = this.information.general.destino
        this.information.general.destino = t
    }
    return this.information;
}
