let html5QrCode = null;

$(document).ready(function () {
    if ($('#btnToggleQR').length) {
        $('#btnToggleQR').on('click', function () {
            toggleQRScanner();
        });
    }

    if ($('#btnStopQR').length) {
        $('#btnStopQR').on('click', function () {
            stopQRScanner();
        });
    }
});

function loadQrLibrary() {
    return new Promise((resolve, reject) => {
        if (typeof Html5Qrcode !== 'undefined') {
            resolve();
            return;
        }
        let script = document.createElement('script');
        script.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

function toggleQRScanner() {
    loadQrLibrary().then(() => {
        $('#qr-reader-container').slideDown(300);
        $('#btnToggleQR').prop('disabled', true);

        html5QrCode = new Html5Qrcode("qr-reader");
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            onQrScanSuccess,
            onQrScanFailure
        ).catch(err => {
            console.error("Error al iniciar lector QR: ", err);
            mensajeAlertaModal({
                icon: 'error',
                timer: 4000,
                title: '¡Atención!',
                text: "No se pudo acceder a la cámara. Verifique que cuenta con permisos e inténtelo nuevamente.",
                textButton: 'Cerrar'
            });
            $('#qr-reader-container').slideUp(200);
            $('#btnToggleQR').prop('disabled', false);
        });
    }).catch(err => {
        console.error("No se pudo cargar la librería de QR: ", err);
        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: '¡Atención!',
            text: "Error de red al cargar el script del lector QR.",
            textButton: 'Cerrar'
        });
    });
}

function stopQRScanner() {
    if (html5QrCode && html5QrCode.isScanning) {
        html5QrCode.stop().then(() => {
            $('#qr-reader-container').slideUp(200);
            $('#btnToggleQR').prop('disabled', false);
            html5QrCode = null;
        }).catch(err => {
            console.error("Error al detener la cámara: ", err);
        });
    } else {
        $('#qr-reader-container').slideUp(200);
        $('#btnToggleQR').prop('disabled', false);
    }
}

function onQrScanSuccess(decodedText, decodedResult) {
    stopQRScanner();
    var audio = new Audio('audio_file.mp3');
    try {
        audio.play().catch(e => console.log("Audio play prevented or failed", e));
    } catch (e) { }
    location.href = decodedText;
}

function onQrScanFailure(error) {
    // Normal/constant during scanning
}


if (document.getElementById('btnRegistrarVisita')) {
    var btnRegistrarVisita = document.getElementById('btnRegistrarVisita');
    btnRegistrarVisita.onclick = function () { registrarVisita() };
}



function registrarVisita() {

    document.getElementById('btnRegistrarVisita').classList.add('disabled');
    let folio = document.getElementById('folio').value;

    if (folio == '') {
        mensajeAlertaModal({
            icon: 'error',
            timer: 2000,
            title: iconMensajeError + ' ¡Atención!',
            text: "Debe indicar Folio.",
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismissTimer == true) { document.getElementById('btnRegistrarVisita').classList.remove('disabled'); };
            if (result.dismissUser == true) { document.getElementById('btnRegistrarVisita').classList.remove('disabled'); }
        })

        return;

    }

    document.getElementById('btnRegistrarVisita').classList.remove('disabled');
    location.href = base_url + '/visitas/visitasValida/' + folio;

}