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
function loadEventListeners(urlMotor, idioma) {
  /*Cuando cambia la marca*/
  // $("#booking-form #marca").change(function () {
  //   $("#booking-form #divmodelo").removeClass("disabled");
  //   $("#booking-form #modelo").attr("disabled", false);

  //   $("#booking-form #modelo").empty();

  //   let marca = $("#booking-form #marca").val();
  //   $.ajax({
  //     type: "POST",
  //     url: "/wp-content/plugins/motor/public/modelos.php?marca=" + marca,
  //     success: function (response) {
  //       $("#booking-form #modelo").html(response).fadeIn();
  //     },
  //   });
  // });
  if (document.querySelector("#booking-form #marca") != undefined) {
    document
      .querySelector("#booking-form #marca")
      .addEventListener("change", function () {
        const marcaSelect = document.querySelector("#booking-form #marca");
        const divModelo = document.querySelector("#booking-form #divmodelo");
        const modeloSelect = document.querySelector("#booking-form #modelo");

        // Habilita el contenedor y el select de modelo
        divModelo.classList.remove("disabled");
        modeloSelect.disabled = false;

        // Limpia el contenido del select de modelo
        modeloSelect.innerHTML = "";

        // Obtén el valor seleccionado en el select de marca
        const marca = marcaSelect.value;
        // Construye la URL para la solicitud nuevo-test/wordpress-6.2.1-es_ES
        const url = `/wp-content/plugins/motor/public/modelos.php?marca=${marca}`;

        // Realiza una solicitud POST usando fetch
        fetch(url, {
          method: "POST",
        })
          .then((response) => response.text()) // Convierte la respuesta a texto
          .then((data) => {
            // Actualiza el select de modelo con la respuesta y muestra el select
            modeloSelect.innerHTML = data;
            modeloSelect.style.display = "block";
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      });
  }

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

  // Selecciona el botón de buscar
  document
    .querySelector("#booking-form #botonbuscar")
    .addEventListener("click", function (ev) {
      // Imprime un mensaje en la consola
      console.log("Has clickado el boton de buscar reserva");

      // Obtén el valor del campo de fecha
      const fechas = document.querySelector("#booking-form #fecha-viaje").value;
      console.log(fechas);
      if (fechas.includes("-")) {
        const fechasArray = fechas.split("-");
        const fechaini = fechasArray[0].trim();
        const fechafin = fechasArray[1].trim();

        // Extrae el día, mes y año de las fechas
        const diaini = fechaini.substring(0, 2);
        const mesini = fechaini.substring(3, 5);
        const anoini = fechaini.substring(6, 10);
        const diafin = fechafin.substring(0, 2);
        const mesfin = fechafin.substring(3, 5);
        const anofin = fechafin.substring(6, 10);

        // Asigna las fechas formateadas a los campos de entrada
        document.querySelector(
          "#booking-form #fechaini"
        ).value = `${diaini}-${mesini}-${anoini}`;
        document.querySelector(
          "#booking-form #fechafin"
        ).value = `${diafin}-${mesfin}-${anofin}`;
      } else {
        const diaini = fechas.substring(0, 2);
        const mesini = fechas.substring(3, 5);
        const anoini = fechas.substring(6, 10);

        document.querySelector(
          "#booking-form #fechaini"
        ).value = `${diaini}-${mesini}-${anoini}`;

        document.querySelector(
          "#booking-form #fechafin"
        ).value = `${diaini}-${mesini}-${anoini}`;
      }

      // Establece la acción del formulario
      document.querySelector(
        "#booking-form"
      ).action = `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`;

      // Verifica el estado de los switches y actualiza los valores del formulario
      if (document.querySelector("#booking-form #switchFamilia") != undefined) {
        if (!document.querySelector("#booking-form #switchFamilia").checked) {
          document.querySelector("#booking-form #familia").value = "";
        }
      }

      if (document.querySelector("#booking-form #switchViajo") != undefined) {
        if (document.querySelector("#booking-form #switchViajo").checked) {
          document.querySelector(
            "#booking-form"
          ).action = `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`;
        } else {
          document.querySelector("#booking-form #vehiculo").value = "";
        }
      }

      // Envía el formulario
      document.querySelector("#booking-form").submit();
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
