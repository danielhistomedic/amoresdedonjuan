/*-------------------------------------------
[ Agregar eventos para animaciones en elementos Tab ]*/

if (document.querySelector('.tab_nuevopaciente')) {
    const tab_nuevopaciente = document.querySelector('.tab_nuevopaciente');
    tab_nuevopaciente.addEventListener('show.mdb.tab', (event) => {
        // event.target; // newly activated tab
        // event.relatedTarget; // previous active tab
        var jqEl_tab_nuevopaciente = $('.tab_nuevopaciente');
        var animation = $(jqEl_tab_nuevopaciente).attr('data-animation');
        $('.animationPaciente').addClass(animation + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
            $(jqEl_tab_nuevopaciente).removeClass(animation);
            $(jqEl_tab_nuevopaciente).removeClass('animated');
        });
    })
}


/* -------------------------------------------
[ Agregar eventos para animaciones en elementos Buttons ] */

function animationButton(eLOrigen, eLDestino) {

    var animation = $(eLOrigen).attr('data-animation');
    $(eLDestino).addClass(animation + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
        $(eLOrigen).removeClass(animation);
        $(eLOrigen).removeClass('animated');
    });

}


function animationBtnEdit(btnEditar, eLDestino) {

    var animation = $(btnEditar).attr('data-animation');
    $(eLDestino).addClass(animation + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
        $(btnEditar).removeClass(animation);
        $(btnEditar).removeClass('animated');
    });

}

function animationBtnCancelar(btnCancelar, eLDestino) {

    var animation = $(btnCancelar).attr('data-animation');
    $(eLDestino).addClass(animation + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
        $(btnCancelar).removeClass(animation);
        $(btnCancelar).removeClass('animated');
    });

}