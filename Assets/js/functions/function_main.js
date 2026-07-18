/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {


    /*-------------------------------------------
     [ Agregar evento a fecha de nacimiento ]*/

    if (document.getElementById("inputFechaNacimientoPaciente")) {
        var input_fecha = document.getElementById("inputFechaNacimientoPaciente");
        input_fecha.addEventListener("focusout", function(e) { fntCalcularEdad(this) });
    }


    /*-------------------------------------------
    [ Agregar evento a calendario de fecha de nacimiento ]*/

    $('#inputFechaNacimientoPaciente').on('pick.datepicker', function(e) {
        // e.preventDefault(); // Prevent to pick the date
        fntCalcularEdadFromDatPicker(e.date)
    });

});



/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    /*-------------------------------------------
    [ Funciones Init ]*/



}, false)


/*==================================================================
[ Funciones Nuevo Registro de Paciente ]*/

function fntCalcularEdad(e) {

    fecha_nacimiento = e.value;
    var format_fecha = Formato_Fecha_yyyymmdd(fecha_nacimiento);
    var fecha = new Date(format_fecha);
    var edad = fntMed_CalcularEdad(fecha);

    document.getElementById('inputEdadNuevoPaciente').innerText = edad;

}

function fntCalcularEdadFromDatPicker(fecha_seleccionada) {

    var edad = fntMed_CalcularEdad(fecha_seleccionada);

    document.getElementById('inputEdadNuevoPaciente').innerText = edad;

}