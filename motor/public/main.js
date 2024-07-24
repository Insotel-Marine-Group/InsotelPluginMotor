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

  console.log("Rutas:", rutas);
  console.log("Options destino:", optionsDestino);

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

function loadDatePicker(
  dateGeneral,
  dateFechas,
  dateIdioma,
  solo_una_fecha,
  tipo_viaje
) {
  var combinedConfig;

  if (solo_una_fecha) {
    combinedConfig = $.extend(
      {
        singleDatePicker: true,
      },
      dateGeneral,
      dateFechas,
      dateIdioma
    );
  } else if (tipo_viaje === "seleccionable") {
    combinedConfig = $.extend(
      {
        singleDatePicker: idavuelta(),
      },
      dateGeneral,
      dateFechas,
      dateIdioma
    );
  } else {
    combinedConfig = $.extend(
      {
        singleDatePicker: false,
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
