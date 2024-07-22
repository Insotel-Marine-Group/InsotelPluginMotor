const obtenerRutasCoincidentes = (puertoRutaIdaSeleccionada, todasLasRutas) => {
  // Filtramos todas las rutas para encontrar aquellas que coinciden con el puerto de ruta ida seleccionado
  const rutasCoincidentes = todasLasRutas.filter(
    (ruta) => ruta.puerto_ruta_ida === puertoRutaIdaSeleccionada
  );
  // Devolvemos las rutas coincidentes
  return rutasCoincidentes;
};

const convertirPuertosAObjeto = (puertos) => {
  return puertos.reduce((acc, puerto) => {
    acc[puerto.nombre] = puerto.valor;
    return acc;
  }, {});
};

const convertirRutasAObjeto = (rutas, puertos) => {
  return rutas.reduce((acc, ruta) => {
    const puerto = puertos.find((p) => p.id === ruta.puerto_ruta_vuelta);
    acc[puerto.nombre] = puerto.valor;
    return acc;
  }, {});
};

const getTotalPasajeros = () => {
  return (
    parseInt($("#booking-form #adultos").val()) +
    parseInt($("#booking-form #ninos").val()) +
    parseInt($("#booking-form #seniors").val()) +
    parseInt($("#booking-form #bebes").val())
  );
};

function setRutasValueInSelect(idOrigen, idDestino, puertos, rutas) {
  const optionsOrigen = convertirPuertosAObjeto(puertos);
  const selectOrigen = document.querySelector(`#${idOrigen}`);
  selectOrigen.innerHTML = "";

  Object.entries(optionsOrigen).forEach(([key, value]) => {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = key;
    selectOrigen.appendChild(option);
  });

  const optionsDestino = convertirRutasAObjeto(
    obtenerRutasCoincidentes(puertos[0].id, rutas),
    puertos
  );
  const selectDestino = document.querySelector(`#${idDestino}`);
  selectDestino.innerHTML = "";

  Object.entries(optionsDestino).forEach(([key, value]) => {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = key;
    selectDestino.appendChild(option);
  });
}

function paramsByDatepicker(lang, dateActually) {
  let datas = {};

  if (lang == "EN") {
    datas = {
      minDate: dateActually,
      locale: {
        direction: "ltr",
        format: "DD/MM/YYYY",
        separator: " - ",
        applyLabel: "Apply",
        cancelLabel: "Cancel",
        fromLabel: "From",
        toLabel: "To",
        customRangeLabel: "Custom",
        daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
        monthNames: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
          "August",
          "September",
          "October",
          "November",
          "December",
        ],
        firstDay: 0,
      },
    };
  } else {
    datas = {
      minDate: dateActually,
      locale: {
        direction: "ltr",
        format: "DD/MM/YYYY",
        separator: " - ",
        applyLabel: "Aplicar",
        cancelLabel: "Cancelar",
        fromLabel: "De",
        toLabel: "A",
        customRangeLabel: "Custom",
        daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        monthNames: [
          "Enero",
          "Febrero",
          "Marzo",
          "Abril",
          "Mayo",
          "Junio",
          "Julio",
          "Agosto",
          "Septiembre",
          "Octubre",
          "Noviembre",
          "Diciembre",
        ],
        firstDay: 1,
      },
    };
  }

  return datas;
}

function idavuelta() {
  if (document.querySelector("#idavue").value == "ida") {
    return true;
  } else {
    return false;
  }
}

function loadDatePicker(dateGeneral, dateFechas, dateIdioma, tipo_calendario) {
  var combinedConfig;

  if (tipo_calendario != "") {
    let singleDatePicker = false;
    if (tipo_calendario === "single") singleDatePicker = true;

    var combinedConfig = $.extend(
      {
        singleDatePicker: singleDatePicker,
      },
      dateGeneral,
      dateFechas,
      dateIdioma
    );
  } else {
    var combinedConfig = $.extend(
      {
        singleDatePicker: idavuelta(),
      },
      dateGeneral,
      dateFechas,
      dateIdioma
    );
  }

  // Inicializa el daterangepicker con la configuración combinada
  $("#booking-form #fecha-viaje").daterangepicker(
    combinedConfig,
    function (start, end, label) {
      // callback function
    }
  );
}

/*DENTO DE ESTA FUNCIÓN ESTAN EL SUBMIT DEL FORMULARIO */
function loadEventListeners(urlMotor, idioma, opciones_ida_vuelta) {
 

  /*Cuando clicka el switch para que se vean las opciones del vehiculo*/
  $("#booking-form #switchViajo").change(function (e) {
    vehicini = "20220319";
    vehicfin = "20220930";

    $("#booking-form #divmarca").removeClass("disabled");
    $("#booking-form #marca").attr("disabled", false);

    if (!$("#booking-form #switchViajo").is(":checked")) {
      $(this).val("0");
    } else {
      $(this).val("1");
    }

    updateDate();

    ori = $("#booking-form #origen").val();
    des = $("#booking-form #destino").val();
    fechaini = $("#booking-form #fechaini").val();
    fechafin = $("#booking-form #fechafin").val();

    if (idioma == "en") {
      fechaini =
        fechaini.substr(6, 4) + fechaini.substr(0, 2) + fechaini.substr(3, 2);
      fechafin =
        fechafin.substr(6, 4) + fechafin.substr(0, 2) + fechafin.substr(3, 2);
    } else {
      fechaini =
        fechaini.substr(6, 4) + fechaini.substr(3, 2) + fechaini.substr(0, 2);
      fechafin =
        fechafin.substr(6, 4) + fechafin.substr(3, 2) + fechafin.substr(0, 2);
    }

    idavue = $("#booking-form #idavue").val();

    if (idavue == "ida") {
      if (fechaini >= vehicini && fechaini <= vehicfin) permisovehi = 1;
      else permisovehi = 0;
    } else {
      if (
        $("#booking-form #origen").val() == "ibi" ||
        $("#booking-form #origen").val() == "for"
      ) {
        if (fechaini >= vehicini && fechafin <= vehicfin) permisovehi = 1;
        else permisovehi = 0;
      } else {
        permisovehi = 1;
      }
    }

    if ($("#booking-form #switchViajo").is(":checked")) {
      $("#booking-form #capaViajo").slideDown("slow");
    } else {
      $("#booking-form #capaViajo").slideUp("slow");
    }
  });


}

function updateDate() {
  let fechas = $("#booking-form #fecha-viaje").val();
  let fechasarray = fechas.split("-");
  let fechaini = $.trim(fechasarray[0]);
  let fechafin = $.trim(fechasarray[1]);

  let diaini = fechaini.substring(0, 2);
  let mesini = fechaini.substring(3, 5);
  let anoini = fechaini.substring(6, 10);
  let diafin = fechafin.substring(0, 2);
  let mesfin = fechafin.substring(3, 5);
  let anofin = fechafin.substring(6, 10);

  $("#booking-form #fechaini").val(diaini + "-" + mesini + "-" + anoini);
  $("#booking-form #fechafin").val(diafin + "-" + mesfin + "-" + anofin);
}

function calcularPasajeros(adultos, ninos, seniors, bebes, label_pasajeros) {
  if (adultos == 0 && ninos == 0 && seniors == 0 && bebes == 0) {
    numpasajeros = "Sin pasajeros";
  } else {
    totalpasajeros = adultos + ninos + seniors + bebes;
    numpasajeros = totalpasajeros + " " + label_pasajeros;
  }

  $("#booking-form #numpasajeros").val(numpasajeros);
}
