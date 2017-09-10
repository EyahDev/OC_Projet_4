// jQuery : Ajout d'une class btn pour les boutons radio
$('.required').addClass('btn');


// Récupération de tous les jours fériés francais
function JoursFeries (an) {
    var JourAn = new Date(an, 0, 1);
    var FeteTravail = new Date(an, 4, 1);
    var Victoire1945 = new Date(an, 4, 8);
    var FeteNationale = new Date(an, 6, 14);
    var Assomption = new Date(an, 7, 15);
    var Toussaint = new Date(an, 10, 1);
    var Armistice = new Date(an, 10, 11);
    var Noel = new Date(an, 11, 25);

    var G = an % 19;
    var C = Math.floor(an / 100);
    var H = (C - Math.floor(C / 4) - Math.floor((8 * C + 13) / 25) + 19 * G + 15) % 30;
    var I = H - Math.floor(H / 28) * (1 - Math.floor(H / 28) * Math.floor(29 / (H + 1)) * Math.floor((21 - G) / 11));
    var J = (an * 1 + Math.floor(an / 4) + I + 2 - C + Math.floor(C / 4)) % 7;
    var L = I - J;
    var MoisPaques = 3 + Math.floor((L + 40) / 44);
    var JourPaques = L + 28 - 31 * Math.floor(MoisPaques / 4);
    var LundiPaques = new Date(an, MoisPaques - 1, JourPaques + 1);
    var Ascension = new Date(an, MoisPaques - 1, JourPaques + 39);
    var LundiPentecote = new Date(an, MoisPaques - 1, JourPaques + 50);

    return [
        JourAn,
        LundiPaques,
        FeteTravail,
        Victoire1945,
        Ascension,
        LundiPentecote,
        FeteNationale,
        Assomption,
        Toussaint,
        Armistice,
        Noel
    ];
}


// Fonctionnement du calendrier FlatPickr
$(".datepicker").flatpickr({
    locale: "fr",
    inline: true,
    minDate: 'today',
    disable: [
        function(date) {
            return (date.getDay() === 2 || date.getDay() === 0);
        },
        function(date) {
        // Récupération de l'année
        var years = date.getFullYear();

        // Récupération d'un tableau de jours fériés

        var holidays = JoursFeries(years);

        holidays.forEach(function(test) {
            console.log(date);
            console.log(test);
            console.log((test === date));
        })}]
});

// Ajout d'une information lors d'une coche sur tarif réduit
$('input:checkbox').change(
    function(){
        if ($(this).is(':checked')) {
            $(this).parent().append('<p class="infoTR" id="infoTR">Pour les étudiants, employés de musées, sans-emploi, militaires... Un justificatif vous sera demandé. </p>');
        } else {
            $(this).parent().find('#infoTR').remove();
        }
    }
);

// Ajout d'une information lors d'une coche d'un bouton radio (journée ou demi-journée)
$('input:radio[name="order_customer_first_step[duration]"]').change(
    function(){
        if ($(this).is(':checked') && this.value === "journée") {
            $("#order_customer_first_step_duration").find('#infoDuration').remove();
            $("#order_customer_first_step_duration").append('<p class="infoDuration" id="infoDuration">Entrée dans le musée à partir de 09h00.</p>');
        } else if ($(this).is(':checked') && this.value === "demi-journée") {
            $("#order_customer_first_step_duration").find('#infoDuration').remove();
            $("#order_customer_first_step_duration").append('<p class="infoDuration" id="infoDuration">Entrée dans le musée à partir de 14h00.</p>');
        }
    }
);

// Fonctionnement du tableau pour la partie récapitulative
$(document).ready(function() {

    var table = $('#table');

    // Table bordered
    $('#table-bordered').change(function() {
        var value = $( this ).val();
        table.removeClass('table-bordered').addClass(value);
    });

    // Table striped
    $('#table-striped').change(function() {
        var value = $( this ).val();
        table.removeClass('table-striped').addClass(value);
    });

    // Table hover
    $('#table-hover').change(function() {
        var value = $( this ).val();
        table.removeClass('table-hover').addClass(value);
    });

    // Table color
    $('#table-color').change(function() {
        var value = $(this).val();
        table.removeClass(/^table-mc-/).addClass(value);
    });
});

// // jQuery’s hasClass and removeClass on steroids
// // by Nikita Vasilyev
// // https://github.com/NV/jquery-regexp-classes
// (function(removeClass) {
//
//     jQuery.fn.removeClass = function( value ) {
//         if ( value && typeof value.test === "function" ) {
//             for ( var i = 0, l = this.length; i < l; i++ ) {
//                 var elem = this[i];
//                 if ( elem.nodeType === 1 && elem.className ) {
//                     var classNames = elem.className.split( /\s+/ );
//
//                     for ( var n = classNames.length; n--; ) {
//                         if ( value.test(classNames[n]) ) {
//                             classNames.splice(n, 1);
//                         }
//                     }
//                     elem.className = jQuery.trim( classNames.join(" ") );
//                 }
//             }
//         } else {
//             removeClass.call(this, value);
//         }
//         return this;
//     }
//
// })(jQuery.fn.removeClass);

