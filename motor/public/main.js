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

function loadDatePicker(dateGeneral, dateFechas, dateIdioma) {
  setTimeout(() => {
    var combinedConfig = $.extend(
      {
        singleDatePicker: idavuelta(),
      },
      dateGeneral,
      dateFechas,
      dateIdioma
    );

    // Inicializa el daterangepicker con la configuración combinada
    $("#booking-form #fecha-viaje").daterangepicker(
      combinedConfig,
      function (start, end, label) {
        // callback function
      }
    );
  }, 2000);
}

/*DENTO DE ESTA FUNCIÓN ESTAN EL SUBMIT DEL FORMULARIO */
function loadEventListeners(
  urlMotor,
  idioma,
  dateGeneral,
  dateFechas,
  dateIdioma
) {
  /*Cuando cambiar entre ida o ida y vuelta*/
  $("#booking-form #idavue").change(function () {
    loadDatePicker(dateGeneral, dateFechas, dateIdioma);
  });

  /*Cuando cambia el origen*/
  $("#booking-form #origen").change(function () {
    let newOptions;
    ori = $("#booking-form #origen").val();
    if (ori == "ibi") {
      // Mallorca
      newOptions = {
        Formentera: "for",
      };
      $("#booking-form").attr(
        "action",
        `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`
      );
    } else if (ori == "for") {
      // Menorca
      newOptions = {
        Ibiza: "ibi",
      };

      $("#booking-form").attr(
        "action",
        `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`
      );
    } else {
      newOptions = {
        Ibiza: "ibi",
        Formentera: "for",
      };

      $("#booking-form").attr(
        "action",
        `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`
      );
    }

    let $el = $("#booking-form #destino");
    $el.empty(); // remove old options
    $.each(newOptions, function (key, value) {
      $el.append($("<option></option>").attr("value", value).text(key));
    });

    loadDatePicker(dateGeneral, dateFechas, dateIdioma);
  });

  /*Cuando cambia el destino*/
  $("#booking-form #destino").change(function () {
    loadDatePicker(dateGeneral, dateFechas, dateIdioma);
  });

  /*Cuando hace focus en el codigo promocional*/
  $("#booking-form #promo").focus(function () {
    return false;
  });

  /*Cuando clicka en el div de los pasajeros para que se despliegue*/
  $("#booking-form #divpersonas").click(function (e) {
    $("#booking-form #divocupacion").slideDown();
    e.stopPropagation();
  });

  $("#booking-form #divocupacion").click(function (e) {
    e.stopPropagation();
  });

  /*Cuando clicka en el boton de aceptar en el dropdown de pasajeros para que se cierre*/
  $("#booking-form #aceptarocu").click(function () {
    $("#booking-form #divocupacion").slideUp();
  });

  /*Cuando cambia el vehiculo para que pueda seleccionar las marcas o no*/
  $("#booking-form #vehiculo").change(function () {
    let vehiculo = $("#booking-form #vehiculo").val();
    if (
      vehiculo == "turismo" ||
      vehiculo == "remolque" ||
      vehiculo == "electrico" ||
      vehiculo == "hibrido"
    ) {
      $("#booking-form #divmarca").removeClass("disabled");
      $("#booking-form #marca").attr("disabled", false);
    } else if (vehiculo == "otros") {
      $("#aviso-vehiculosnoinc").fadeIn(600);
    } else if (vehiculo == "furgoneta") {
      $("#aviso-furgonetas").fadeIn(600);
      $("#booking-form #divmarca").removeClass("disabled");
      $("#booking-form #marca").attr("disabled", false);
    } else {
      $("#booking-form #divmarca").addClass("disabled");
      $("#booking-form #marca").attr("disabled", true);
      $("#booking-form #divmodelo").addClass("disabled");
      $("#booking-form #modelo").attr("disabled", true);
    }

    if (
      vehiculo == "" ||
      vehiculo == "bicicleta" ||
      vehiculo == "motocicleta" ||
      vehiculo == "ciclomotor"
    ) {
      $("#booking-form #marca").val("");
      $("#booking-form #modelo").val("");
    }
  });

  /*Cuando cambia la marca*/
  $("#booking-form #marca").change(function () {
    $("#booking-form #divmodelo").removeClass("disabled");
    $("#booking-form #modelo").attr("disabled", false);

    $("#booking-form #modelo").empty();

    let marca = $("#booking-form #marca").val();
    $.ajax({
      type: "POST",
      url:
        "/wp-content/plugins/motor/public/modelos.php?marca=" + marca,
      success: function (response) {
        $("#booking-form #modelo").html(response).fadeIn();
      },
    });
  });

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

  /*Cuando clicka en reservar esto seria el metodo SUBMIT*/
  $("#booking-form #botonbuscar").click(function (ev) {
    console.log("Has clickado el boton de buscar reserva");
    fechas = $("#booking-form #fecha-viaje").val();
    fechasarray = fechas.split("-");
    fechaini = $.trim(fechasarray[0]);
    fechafin = $.trim(fechasarray[1]);

    diaini = fechaini.substring(0, 2);
    mesini = fechaini.substring(3, 5);
    anoini = fechaini.substring(6, 10);
    diafin = fechafin.substring(0, 2);
    mesfin = fechafin.substring(3, 5);
    anofin = fechafin.substring(6, 10);

    $("#booking-form #fechaini").val(diaini + "-" + mesini + "-" + anoini);
    $("#booking-form #fechafin").val(diafin + "-" + mesfin + "-" + anofin);

    $("#booking-form").attr(
      "action",
      `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`
    );

    if (!$("#booking-form #switchFamilia").is(":checked")) {
      $("#booking-form #familia").val("");
    }
    if ($("#booking-form #switchMascotas").is(":checked")) {
      $("#booking-form #mascotas").val("1");
    }
    if ($("#booking-form #switchViajo").is(":checked")) {
      $("#booking-form").attr(
        "action",
        `${urlMotor}/${idioma}/Home/IndexDesdePuntoCom`
      );
    } else {
      $("#booking-form #vehiculo").val("");
    }

    $("#booking-form").submit();
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


