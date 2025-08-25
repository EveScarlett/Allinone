let canvasfoto = document.querySelector("#image-tag");

function calculoEdad2(elemento) {
    var value = elemento.value;
    var aux = elemento.id.split('-');

    var fecha_formato = value.split('-');

    var formatoymd = fecha_formato[0] + '-' + fecha_formato[1] + '-' + fecha_formato[2];
    var hoy = new Date();
    var cumpleanos = new Date(formatoymd);
    var edad = hoy.getFullYear() - cumpleanos.getFullYear();
    var m = hoy.getMonth() - cumpleanos.getMonth();

    if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
        edad--;
    }

    document.getElementById(aux[0] + '-edad').value = edad;
}

function calculaAntiguedad(valor) {
    console.log('Estoy calculando la antiguedad: ' + valor);

    dt1 = new Date();
    dt2 = new Date(valor);

    let final = '';

    var diff_year_month_day = difference(dt1, dt2);
    console.log('Original: ' + diff_year_month_day);

    const myArray = diff_year_month_day.split("-");
    console.log(myArray);
    if (myArray[0] != 0) {
        final += myArray[0] + ' años ';
    }
    if (myArray[1] != 0) {
        final += myArray[1] + ' meses ';
    }
    if (myArray[2] != 0) {
        final += myArray[2] + ' dias ';
    }
    console.log('Antiguedad: ' + final);
    $("#trabajadores-antiguedad").val(final);


}


function difference(dt1, dt2) {

    var time = (dt2.getTime() - dt1.getTime()) / 1000;
    var year = Math.abs(Math.round((time / (60 * 60 * 24)) / 365.25));
    var month = Math.abs(Math.round(time / (60 * 60 * 24 * 7 * 4)));
    var days = Math.abs(Math.round(time / (3600 * 24)));
    return year + "-" + month + "-" + days;

}


function converToMayus(elemento) {

    var charPos = doGetCaretPosition(elemento); //Obtiene la posición actual del curosr
    elemento.value = elemento.value.toUpperCase();
    setCaretPosition(elemento, charPos); //Setea el cursor en la posicion actual
}

/*
 ** Returns the caret (cursor) position of the specified text field (oField).
 ** Return value range is 0-oField.value.length.
 */
function doGetCaretPosition(oField) {

    // Initialize
    var iCaretPos = 0;

    // IE Support
    if (document.selection) {

        // Set focus on the element
        oField.focus();

        // To get cursor position, get empty selection range
        var oSel = document.selection.createRange();

        // Move selection start to 0 position
        oSel.moveStart('character', -oField.value.length);

        // The caret position is selection length
        iCaretPos = oSel.text.length;
    }

    // Firefox support
    else if (oField.selectionStart || oField.selectionStart == '0')
        iCaretPos = oField.selectionDirection == 'backward' ? oField.selectionStart : oField.selectionEnd;

    // Return results
    return iCaretPos;
}

function setCaretPosition(elem, caretPos) {
    if (elem !== null) {
        if (elem.createTextRange) {
            var range = elem.createTextRange();
            range.move('character', caretPos);
            range.select();
        } else {
            if (elem.selectionStart) {
                elem.focus();
                elem.setSelectionRange(caretPos, caretPos);
            } else
                elem.focus();
        }
    }
}


function nuevoEstudio(id, valor, categoria, showcategoria) {
    console.log('id:' + id + ' | valor: ' + valor);

    if (valor == 0) {
        console.log('Es uno nuevo');
        document.getElementById(id).value = '';
        document.getElementById(id).style.display = 'block';
        $("#" + id).attr("aria-required", "true");

    } else {
        console.log('No es nuevo');
        document.getElementById(id).style.display = 'none';
        document.getElementById(id).ariarequired = false;

        $('#' + categoria).val('').trigger('change');
        $('#' + showcategoria).val('').trigger('change');

        var base = 'index.php?r=poes%2Ftraercategoria';

        if (valor != '' && valor != null) {
            $.ajax({
                url: base,
                type: 'post',
                data: {
                    id: valor,
                    _csrf: yii.getCsrfToken()
                },
                success: function(data) {
                    if (data) {

                        data = $.parseJSON(data);
                        console.log(data['categoria']);

                        $('#' + categoria).val(data['categoria']).trigger('change');
                        $('#' + showcategoria).val(data['categoria']).trigger('change');
                    }
                }
            });
        } else {

        }
    }
}

function nuevoEstudio2(id, valor, estudio_id) {
    console.log('id:' + id + ' | valor: ' + valor);

    if (valor == -1) {
        console.log('Es uno nuevo');
        document.getElementById(id).value = '';
        document.getElementById(id).style.display = 'block';
        $("#" + id).attr("aria-required", "true");

    } else {
        console.log('No es nuevo');
        try {
            document.getElementById(id).style.display = 'none';
            document.getElementById(id).ariarequired = false;
        } catch (error) {

        }

        $('#' + estudio_id).empty();

        var base = 'index.php?r=poes%2Ftraerestudios';

        if (valor != '' && valor != null) {
            $.ajax({
                url: base,
                type: 'post',
                data: {
                    id: valor,
                    _csrf: yii.getCsrfToken()
                },
                success: function(data) {
                    if (data) {

                        data = $.parseJSON(data);
                        console.log(data);

                        $('#' + estudio_id).append('<option value="">Estudio--</option>');
                        $.each(data['estudios'], function(index, estudio) {
                            $('#' + estudio_id).append(
                                '<option value="' + estudio
                                .id +
                                '">' + estudio.nombre + '</option>');
                        });
                    }
                }
            });
        } else {

        }
    }
}

function nuevoParametro(id, valor) {
    console.log('id:' + id + ' | valor: ' + valor);

    if (valor == 0) {
        console.log('Es uno nuevo');
        document.getElementById(id).value = '';
        document.getElementById(id).style.display = 'block';
        $("#" + id).attr("aria-required", "true");

    } else {
        console.log('No es nuevo');
        document.getElementById(id).style.display = 'none';
        document.getElementById(id).ariarequired = false;
    }
}


function radioLists() {

    var familiarempresa = $('input[name="Hccohc[familiar_empresa]"]:checked').val();

    /* ANTECEDENTES HEREDO FAMILIARES*/
    var diabetes = $('input[name="Hccohc[diabetess]"]:checked').val();
    var hiper = $('input[name="Hccohc[hipertension]"]:checked').val();
    var cancer = $('input[name="Hccohc[cancer]"]:checked').val();
    var nefropatias = $('input[name="Hccohc[nefropatias]"]:checked').val();
    var cardiopatias = $('input[name="Hccohc[cardiopatias]"]:checked').val();
    var reumaticas = $('input[name="Hccohc[reuma]"]:checked').val();
    var hepaticos = $('input[name="Hccohc[hepa]"]:checked').val();
    var tuberculosis = $('input[name="Hccohc[tuber]"]:checked').val();
    var psiquiatricos = $('input[name="Hccohc[psi]"]:checked').val();

    /*ANTECEDENTES PERSONALES NO PATOLOGICOS */
    var tabaquismo = $('input[name="Hccohc[tabaquismo]"]:checked').val();
    var alcoholismo = $('input[name="Hccohc[alcoholismo]"]:checked').val();
    var cocina = $('input[name="Hccohc[cocina]"]:checked').val();
    var audifonos = $('input[name="Hccohc[audifonos]"]:checked').val();
    var drogadiccion = $('input[name="Hccohc[droga]"]:checked').val();
    var vacunacion = $('input[name="Hccohc[vacunacion_cov]"]:checked').val();
    var covidreciente = $('input[name="Hccohc[covidreciente]"]:checked').val();

    console.log('Familiar Empresa: ' + familiarempresa);

    /*ANTECEDENTES PERSONALES  PATOLOGICOS */
    var alergias = $('input[name="Hccohc[alergias]"]:checked').val();
    var asma = $('input[name="Hccohc[asma]"]:checked').val();
    var cardiopatias_pat = $('input[name="Hccohc[cardio]"]:checked').val();
    var cirugias = $('input[name="Hccohc[cirugias]"]:checked').val();
    var convulsiones = $('input[name="Hccohc[convulsiones]"]:checked').val();
    var diabetes_pat = $('input[name="Hccohc[diabetes]"]:checked').val();
    var hipertension_pat = $('input[name="Hccohc[hiper]"]:checked').val();
    var lumbalgias = $('input[name="Hccohc[lumbalgias]"]:checked').val();
    var nefropatias_pat = $('input[name="Hccohc[nefro]"]:checked').val();
    var poliomelitis = $('input[name="Hccohc[polio]"]:checked').val();
    var sarampion = $('input[name="Hccohc[saram]"]:checked').val();
    var pulmonares = $('input[name="Hccohc[pulmo]"]:checked').val();
    var hematicos = $('input[name="Hccohc[hematicos]"]:checked').val();
    var traumatismos = $('input[name="Hccohc[trauma]"]:checked').val();
    var usoMedicamentos = $('input[name="Hccohc[medicamentos]"]:checked').val();
    var protesis = $('input[name="Hccohc[protesis]"]:checked').val();
    var transfusiones = $('input[name="Hccohc[trans]"]:checked').val();
    var enfocular = $('input[name="Hccohc[enf_ocular]"]:checked').val();
    var enfauditiva = $('input[name="Hccohc[enf_auditiva]"]:checked').val();
    var fractura = $('input[name="Hccohc[fractura]"]:checked').val();
    var amputacion = $('input[name="Hccohc[amputacion]"]:checked').val();
    var hernias = $('input[name="Hccohc[hernias]"]:checked').val();
    var enfsanguinea = $('input[name="Hccohc[enfsanguinea]"]:checked').val();
    var tumorescancer = $('input[name="Hccohc[tumorescancer]"]:checked').val();
    var enfpsico = $('input[name="Hccohc[enfpsico]"]:checked').val();


    /*ANTECEDENTES LABORALES */
    var experlaboral = $('input[name="Hccohc[antecedentes_lab]"]:checked').val();
    console.log('------------------------------------Experiencia Laboral: ' + experlaboral);
    if (experlaboral == 1) {
        $("#exp_lab1").css("display", "block");
    } else if (experlaboral == 0) {
        $("#exp_lab1").css("display", "none");
    }


    var antecedenteslabs = $('input[name="Hccohc[antecedentes_sino]"]:checked').val();
    console.log('EXPERIENCIA LABORAL: ' + antecedenteslabs);
    if (antecedenteslabs == 1) {
        $("#antecedentes_laborales").css("display", "block");
    } else {
        $("#antecedentes_laborales").css("display", "none");
    }


    //console.log('EXPERIENCIA LABORAL************************************************');
    /*EXPERIENCIA LABORAL*/
    /*  
     */

    //console.log('FAMILIAR EN EMPRESA************************************************');
    /* DIABETES*/
    if (familiarempresa === 'SI') {
        //console.log('DIABETES');
        $("#familiar1").css("display", "block");
        $("#familiar2").css("display", "block");
        document.getElementById("hccohc-id_familiar").required = true;
        document.getElementById("hccohc-id_area").required = true;
    } else if (familiarempresa === 'NO') {
        $("#familiar1").css("display", "none");
        $("#familiar2").css("display", "none");
        $('#hccohc-id_familiar').val('').trigger('change');
        $('#hccohc-id_area').val('').trigger('change');
        document.getElementById("hccohc-id_familiar").required = false;
        document.getElementById("hccohc-id_area").required = false;
    }


    //console.log('ANTECEDENTES HEREDO FAMILIARES************************************************');
    /* DIABETES*/
    if (diabetes === 'SI') {
        //console.log('DIABETES');
        $("#diabetesstxt").css("display", "block");
        document.getElementById("hccohc-diabetesstxt").required = true;
    } else if (diabetes === 'NO') {
        $("#diabetesstxt").css("display", "none");
        $('#hccohc-diabetesstxt').val('').trigger('change');
        document.getElementById("hccohc-diabetesstxt").required = false;
    }

    /* HIPERTENSIÓN*/
    if (hiper === 'SI') {
        //console.log('HIPERTENSION');
        $("#hipertensiontxt").css("display", "block");
        document.getElementById("hccohc-hipertensiontxt").required = true;
    } else if (hiper === 'NO') {
        $("#hipertensiontxt").css("display", "none");
        $('#hccohc-hipertensiontxt').val('').trigger('change');
        document.getElementById("hccohc-hipertensiontxt").required = false;
    }

    /* CANCER*/
    if (cancer === 'SI') {
        //console.log('CANCER');
        $("#cancertxt").css("display", "block");
        document.getElementById("hccohc-cancertxt").required = true;
    } else if (cancer === 'NO') {
        $("#cancertxt").css("display", "none");
        $('#hccohc-cancertxt').val('').trigger('change');
        document.getElementById("hccohc-cancertxt").required = false;
    }

    //NEFROPATIAS
    if (nefropatias === 'SI') {
        console.log('NEFROPATIAS >.>');
        $("#nefropatiastxt").css("display", "block");
        document.getElementById("hccohc-nefropatiastxt").required = true;
    } else if (nefropatias === 'NO') {
        $("#nefropatiastxt").css("display", "none");
        $('#hccohc-nefropatiastxt').val('').trigger('change');
        document.getElementById("hccohc-nefropatiastxt").required = false;
    }

    /* CARDIOPATIAS FAMILIARES*/
    if (cardiopatias === 'SI') {
        //console.log('CARDIOPATIAS');
        $("#cardiopatiastxt").css("display", "block");
        document.getElementById("hccohc-cardiopatiastxt").required = true;
    } else if (cardiopatias === 'NO') {
        $("#cardiopatiastxt").css("display", "none");
        $('#hccohc-cardiopatiastxt').val('').trigger('change');
        document.getElementById("hccohc-cardiopatiastxt").required = false;
    }

    /* ENFERMEDADES REUMATICAS FAMILIARES*/
    if (reumaticas === 'SI') {
        console.log('ENFERMEDADES REUMATICAS');
        $("#reumatxt").css("display", "block");
        document.getElementById("hccohc-reumatxt").required = true;
    } else if (reumaticas === 'NO') {
        $("#reumatxt").css("display", "none");
        $('#hccohc-reumatxt').val('').trigger('change');
        document.getElementById("hccohc-reumatxt").required = false;
    }

    /* ENFERMEDADES HEPATICAS FAMILIARES*/
    if (hepaticos === 'SI') {
        console.log('ENFERMEDADES HEPATICAS');
        $("#hepatxt").css("display", "block");
        document.getElementById("hccohc-hepatxt").required = true;
    } else if (hepaticos === 'NO') {
        $("#hepatxt").css("display", "none");
        $('#hccohc-hepatxt').val('').trigger('change');
        document.getElementById("hccohc-hepatxt").required = false;
    }

    /* TUBERCULOSIS EN ALGUN FAMILIAR*/
    if (tuberculosis === 'SI') {
        //console.log('TUBERCULOSIS');
        $("#tubertxt").css("display", "block");
        document.getElementById("hccohc-tubertxt").required = true;
    } else if (tuberculosis === 'NO') {
        $("#tubertxt").css("display", "none");
        $('#hccohc-tubertxt').val('').trigger('change');
        document.getElementById("hccohc-tubertxt").required = false;
    }

    /* ANTECEDENTES PSICOLOGICOS EN ALGUN FAMILIAR*/
    if (psiquiatricos === 'SI') {
        //console.log('ANTECEDENTES PSICOLOGICOS');
        $("#psitxt").css("display", "block");
        document.getElementById("hccohc-psitxt").required = true;
    } else if (psiquiatricos === 'NO') {
        $("#psitxt").css("display", "none");
        $('#hccohc-psitxt').val('').trigger('change');
        document.getElementById("hccohc-psitxt").required = false;
    }






    //console.log('ANTECEDENTES PERSONALES NO PATOLÓGICOS************************************************');
    /* TABAQUISMO*/
    $("#alerttaba").css("display", "none");
    if (tabaquismo === 'SI') {

        $("#tabaquismodata").css("display", "block");
        document.getElementById("tabaqdesde").readOnly = false;
        document.getElementById("hccohc-tabfrec").readOnly = false;
        document.getElementById("hccohc-tabcantidad").readOnly = false;
        document.getElementById("tabaqdesde").required = true;
        document.getElementById("hccohc-tabfrec").required = true;
        document.getElementById("hccohc-tabcantidad").required = true;


        if (document.getElementById("tabaqdesde").value !== '' && document.getElementById("tabaqdesde").value === 'NO') {
            document.getElementById("tabaqdesde").value = '';
        }
        if (document.getElementById("hccohc-tabfrec").value !== '' && document.getElementById("hccohc-tabfrec").value === 'NO') {
            document.getElementById("hccohc-tabfrec").value = '';
        }
        if (document.getElementById("hccohc-tabcantidad").value !== '' && document.getElementById("hccohc-tabcantidad").value === 'NO') {
            document.getElementById("hccohc-tabcantidad").value = '';
        }

    } else if (tabaquismo === 'NO') {
        $("#tabaquismodata").css("display", "none");

        document.getElementById("tabaqdesde").value = '';
        $('#hccohc-tabfrec').val('').trigger('change');
        $('#hccohc-tabcantidad').val('').trigger('change');

        document.getElementById("tabaqdesde").readOnly = true;
        document.getElementById("hccohc-tabfrec").readOnly = true;
        document.getElementById("hccohc-tabcantidad").readOnly = true;
        document.getElementById("tabaqdesde").required = false;
        document.getElementById("hccohc-tabfrec").required = false;
        document.getElementById("hccohc-tabcantidad").required = false;
    }



    /* ALCOHOLISMO*/
    if (alcoholismo === 'SI') {
        $("#alcoholismodata").css("display", "block");
        document.getElementById("alcodesde").readOnly = false;
        document.getElementById("hccohc-alcofrec").readOnly = false;
        document.getElementById("hccohc-alcocantidad").readOnly = false;
        document.getElementById("alcodesde").required = true;
        document.getElementById("hccohc-alcofrec").required = true;
        document.getElementById("hccohc-alcocantidad").required = true;

        if (document.getElementById("alcodesde").value !== '' && document.getElementById("alcodesde").value === 'NO') {
            document.getElementById("alcodesde").value = '';
        }
        if (document.getElementById("hccohc-alcofrec").value !== '' && document.getElementById("hccohc-alcofrec").value === 'NO') {
            document.getElementById("hccohc-alcofrec").value = '';
        }
        if (document.getElementById("hccohc-tabcantidad").value !== '' && document.getElementById("hccohc-tabcantidad").value === 'NO') {
            document.getElementById("hccohc-tabcantidad").value = '';
        }
    } else if (alcoholismo === 'NO') {
        $("#alcoholismodata").css("display", "none");

        document.getElementById("alcodesde").value = '';
        $('#hccohc-alcofrec').val('').trigger('change');
        $('#hccohc-alcocantidad').val('').trigger('change');

        document.getElementById("alcodesde").readOnly = true;
        document.getElementById("hccohc-alcofrec").readOnly = true;
        document.getElementById("hccohc-alcocantidad").readOnly = true;
        document.getElementById("alcodesde").required = false;
        document.getElementById("hccohc-alcofrec").required = false;
        document.getElementById("hccohc-alcocantidad").required = false;
    }



    /* COCINA CON LEÑA*/
    if (cocina === 'SI') {
        /*  $("#cocinadata").css("display", "block");
         document.getElementById("cocinadesde").readOnly = false;
         document.getElementById("cocinadesde").required = true;

         if (document.getElementById("cocinadesde").value !== '' && document.getElementById("cocinadesde").value === 'NO') {
             document.getElementById("cocinadesde").value = '';
         } */
        $("#cocinadata").css("display", "none");

        document.getElementById("cocinadesde").value = null;

        document.getElementById("cocinadesde").readOnly = true;
        document.getElementById("cocinadesde").required = false;
    } else if (cocina === 'NO') {
        $("#cocinadata").css("display", "none");

        document.getElementById("cocinadesde").value = null;

        document.getElementById("cocinadesde").readOnly = true;
        document.getElementById("cocinadesde").required = false;
    }



    /* USO DE AUDIFONOS*/
    if (audifonos === 'SI') {
        $("#audifonosdata").css("display", "block");

        document.getElementById("hccohc-audiodesde").readOnly = false;
        document.getElementById("hccohc-audiocuando").readOnly = false;
        document.getElementById("hccohc-audiodesde").required = true;
        document.getElementById("hccohc-audiocuando").required = true;
        if (document.getElementById("hccohc-audiodesde").value !== '' && document.getElementById("hccohc-audiodesde").value === 'NO') {
            document.getElementById("hccohc-audiodesde").value = '';
        }
        if (document.getElementById("hccohc-audiocuando").value !== '' && document.getElementById("hccohc-audiocuando").value === 'NO') {
            document.getElementById("hccohc-audiocuando").value = '';
        }
    } else if (audifonos === 'NO') {
        $("#audifonosdata").css("display", "none");

        $('#hccohc-audiocuando').val('').trigger('change');
        $('#hccohc-audiodesde').val('').trigger('change');

        document.getElementById("hccohc-audiocuando").readOnly = true;
        document.getElementById("hccohc-audiodesde").readOnly = true;
        document.getElementById("hccohc-audiocuando").required = false;
        document.getElementById("hccohc-audiodesde").required = false;
    }


    /* USO DE DROGAS*/
    if (drogadiccion === 'SI') {
        console.log('DROGADICCION');
        $("#drogadata").css("display", "block");

        document.getElementById("hccohc-drogatxt").readOnly = false;
        document.getElementById("duracion_droga").readOnly = false;
        document.getElementById("hccohc-fecha_droga").readOnly = false;
        document.getElementById("hccohc-drogatxt").required = false;
        document.getElementById("duracion_droga").required = true;
        document.getElementById("hccohc-fecha_droga").required = true;
        if (document.getElementById("hccohc-drogatxt").value !== '' && document.getElementById("hccohc-drogatxt").value === 'NO') {
            document.getElementById("hccohc-drogatxt").value = '';
        }
        if (document.getElementById("duracion_droga").value !== '' && document.getElementById("duracion_droga").value === 'NO') {
            document.getElementById("duracion_droga").value = '';
        }
        if (document.getElementById("hccohc-fecha_droga").value !== '' && document.getElementById("hccohc-fecha_droga").value === 'NO') {
            document.getElementById("hccohc-fecha_droga").value = '';
        }
    } else if (drogadiccion === 'NO') {
        $("#drogadata").css("display", "none");

        $('#duracion_droga').val('').trigger('change');
        $('#hccohc-drogatxt').val('').trigger('change');
        $('#hccohc-fecha_droga').val('').trigger('change');

        document.getElementById("hccohc-drogatxt").readOnly = true;
        document.getElementById("duracion_droga").readOnly = true;
        document.getElementById("hccohc-fecha_droga").readOnly = true;
        document.getElementById("hccohc-drogatxt").required = false;
        document.getElementById("duracion_droga").required = false;
        document.getElementById("hccohc-fecha_droga").required = false;
    }


    /* VACUNACION*/
    if (vacunacion === 'SI') {
        $("#vacunaciondata").css("display", "block");

        /* document.getElementById("hccohc-nombre_vacunacion").readOnly = false;
        document.getElementById("hccohc-nombre_vacunacion").required = true;

        document.getElementById("dosis_vacunacion").disabled = false;
        document.getElementById("dosis_vacunacion").required = true;

        document.getElementById("fecha_vacunacion").readOnly = false;
        document.getElementById("fecha_vacunacion").required = true; */

        if (document.getElementById("hccohc-nombre_vacunacion").value !== '' && document.getElementById("hccohc-nombre_vacunacion").value === 'NO') {
            document.getElementById("hccohc-nombre_vacunacion").value = '';
        }
        if (document.getElementById("dosis_vacunacion").value !== '' && document.getElementById("dosis_vacunacion").value === 'NO') {
            document.getElementById("dosis_vacunacion").value = '';
        }
        if (document.getElementById("fecha_vacunacion").value !== '' && document.getElementById("fecha_vacunacion").value === 'NO') {
            document.getElementById("fecha_vacunacion").value = '';
        }
    } else if (vacunacion === 'NO') {
        $("#vacunaciondata").css("display", "none");

        $('#hccohc-nombre_vacunacion').val('').trigger('change');
        /* document.getElementById("hccohc-nombre_vacunacion").readOnly = true;
        document.getElementById("hccohc-nombre_vacunacion").required = false; */

        /*   document.getElementById("dosis_vacunacion").disabled = true;
          document.getElementById("dosis_vacunacion").required = false; */
        document.getElementById("dosis_vacunacion").value = '';

        /* document.getElementById("fecha_vacunacion").readOnly = true;
        document.getElementById("fecha_vacunacion").required = false; */
        document.getElementById("fecha_vacunacion").value = '';
    }

    /* RECIENTEMENTE COVID*/
    if (covidreciente === 'SI') {
        $("#covidrecientedata").css("display", "block");
        //document.getElementById("hccohc-covidreciente_fecha").required = true;
        document.getElementById("hccohc-covidreciente_secuelas").required = true;
        document.getElementById("hccohc-covidreciente_vacunacion").required = true;
    } else if (covidreciente === 'NO') {
        $("#covidrecientedata").css("display", "none");
        //$('#hccohc-covidreciente_fecha').val('');
        $('#hccohc-covidreciente_secuelas').val('');
        $('#hccohc-covidreciente_vacunacion').val('');
        //document.getElementById("hccohc-covidreciente_fecha").required = false;
        document.getElementById("hccohc-covidreciente_secuelas").required = false;
        document.getElementById("hccohc-covidreciente_vacunacion").required = false;
    }



    /* ALERGIAS*/
    if (alergias === 'SI') {
        $("#alergiasdata").css("display", "block");
        document.getElementById("alergiastxt").required = true;
    } else if (alergias === 'NO') {
        $("#alergiasdata").css("display", "none");
        document.getElementById("alergiastxt").required = false;
    }
    /* ASMA*/
    if (asma === 'SI') {
        console.log('Asma si');
        $("#asmadata").css("display", "block");

        if (document.getElementById("asma").value !== '' && document.getElementById("asma").value === 'NO') {
            document.getElementById("asma").value = '';
        }
        if (document.getElementById("asmaanio").value !== '' && document.getElementById("asmaanio").value === 'NO') {
            document.getElementById("asmaanio").value = '';
        }
    } else if (asma === 'NO') {
        console.log('Asma NO');
        $("#asmadata").css("display", "none");
        document.getElementById("asma").value = '';
        document.getElementById("asmaanio").value = '';

    }


    /* CARDIOPATIAS PATOLOGICAS*/
    if (cardiopatias_pat === 'SI') {
        $("#cardiodata").css("display", "block");
        document.getElementById("cardiotxt").required = true;
    } else if (cardiopatias_pat === 'NO') {
        $("#cardiodata").css("display", "none");
        document.getElementById("cardiotxt").required = false;
    }


    /* CIRUGIAS*/
    if (cirugias === 'SI') {
        $("#cirugiasdata").css("display", "block");
        document.getElementById("cirugiastxt").required = true;
    } else if (cirugias === 'NO') {
        $("#cirugiasdata").css("display", "none");
        document.getElementById("cirugiastxt").required = false;
    }


    /* CONVULSIONES*/
    if (convulsiones === 'SI') {
        $("#convulsionesdata").css("display", "block");
        document.getElementById("convulsionestxt").required = true;
    } else if (convulsiones === 'NO') {
        $("#convulsionesdata").css("display", "none");
        document.getElementById("convulsionestxt").required = false;
    }


    /* DIABETES PATOLOGICA*/
    if (diabetes_pat === 'SI') {
        $("#diabetesdata").css("display", "block");
        document.getElementById("diabetestxt").required = true;
    } else if (diabetes_pat === 'NO') {
        $("#diabetesdata").css("display", "none");
        document.getElementById("diabetestxt").required = false;
    }

    /* HIPERTENSION PATOLOGICA*/
    if (hipertension_pat === 'SI') {
        $("#hiperdata").css("display", "block");
        document.getElementById("hipertxt").required = true;
    } else if (hipertension_pat === 'NO') {
        $("#hiperdata").css("display", "none");
        document.getElementById("hipertxt").required = false;
    }


    /* LUMBALGIAS*/
    if (lumbalgias === 'SI') {
        $("#lumbalgiasdata").css("display", "block");
        document.getElementById("lumbalgiastxt").required = true;
    } else if (lumbalgias === 'NO') {
        $("#lumbalgiasdata").css("display", "none");
        document.getElementById("lumbalgiastxt").required = false;
    }

    /* NEFROPATIAS PATOLOGICAS*/
    if (nefropatias_pat === 'SI') {
        $("#nefrodata").css("display", "block");
        document.getElementById("nefrotxt").required = true;
    } else if (nefropatias_pat === 'NO') {
        $("#nefrodata").css("display", "none");
        document.getElementById("nefrotxt").required = false;
    }

    /* POLIOMELITIS*/
    if (poliomelitis === 'SI') {
        $("#poliodata").css("display", "block");

        document.getElementById("poliomelitis_anio").readOnly = false;
        document.getElementById("poliomelitis_anio").required = true;
        if (document.getElementById("poliomelitis_anio").value !== '' && document.getElementById("poliomelitis_anio").value === 'NO') {
            document.getElementById("poliomelitis_anio").value = '';
        }
    } else if (poliomelitis === 'NO') {
        $("#poliodata").css("display", "none");

        document.getElementById("poliomelitis_anio").readOnly = true;
        document.getElementById("poliomelitis_anio").required = false;
        document.getElementById("poliomelitis_anio").value = '';
    }

    /* SARAMPION*/
    if (sarampion === 'SI') {
        $("#saramdata").css("display", "block");

        document.getElementById("sarampion_anio").readOnly = false;
        document.getElementById("sarampion_anio").required = true;
        if (document.getElementById("sarampion_anio").value !== '' && document.getElementById("sarampion_anio").value === 'NO') {
            document.getElementById("sarampion_anio").value = '';
        }
    } else if (sarampion === 'NO') {
        $("#saramdata").css("display", "none");

        document.getElementById("sarampion_anio").readOnly = true;
        document.getElementById("sarampion_anio").required = false;
        document.getElementById("sarampion_anio").value = '';
    }

    /* TRASTORNOS PULMORANES*/
    if (pulmonares === 'SI') {
        console.log('ENFERMEDADES PULMONARES');
        $("#pulmodata").css("display", "block");
        document.getElementById("pulmotxt").required = true;
    } else if (pulmonares === 'NO') {
        $("#pulmodata").css("display", "none");
        document.getElementById("pulmotxt").required = false;
    }

    /* TRASTORNOS HEMATICOS*/
    /* if (hematicos === 'SI') {
        $("#hematicosdata").css("display", "block");
        document.getElementById("hematicostxt").required = true;
    } else if (hematicos === 'NO') {
        $("#hematicosdata").css("display", "none");
        $('#hematicostxt').multipleInput('clear');
        document.getElementById("hematicostxt").required = false;
    } */

    /* TRAUMATISMOS*/
    if (traumatismos === 'SI') {
        $("#traumadata").css("display", "block");
        document.getElementById("traumatxt").required = true;
    } else if (traumatismos === 'NO') {
        $("#traumadata").css("display", "none");
        document.getElementById("traumatxt").required = false;
    }

    /* MEDICAMENTOS*/
    if (usoMedicamentos === 'SI') {
        $("#medicamentosdata").css("display", "block");
        document.getElementById("medicamentostxt").required = true;
    } else if (usoMedicamentos === 'NO') {
        $("#medicamentosdata").css("display", "none");
        document.getElementById("medicamentostxt").required = false;
    }

    /* PROTESIS*/
    if (protesis === 'SI') {
        $("#protesisdata").css("display", "block");
        document.getElementById("protesistxt").required = true;
    } else if (protesis === 'NO') {
        $("#protesisdata").css("display", "none");
        document.getElementById("protesistxt").required = false;
    }

    /* TRANSFUSIONES*/
    if (transfusiones === 'SI') {
        $("#transdata").css("display", "block");
        document.getElementById("transtxt").required = true;
    } else if (transfusiones === 'NO') {
        $("#transdata").css("display", "none");
        document.getElementById("transtxt").required = false;
    }

    /* ENFERMEDAD OCULAR*/
    if (enfocular === 'SI') {
        $("#enf_oculardata").css("display", "block");
        document.getElementById("enf_ocular_txt").required = true;
    } else if (enfocular === 'NO') {
        $("#enf_oculardata").css("display", "none");
        document.getElementById("enf_ocular_txt").required = false;
    }

    /* ENFERMEDAD AUDITIVA*/
    if (enfauditiva === 'SI') {
        $("#enf_auditivadata").css("display", "block");
        document.getElementById("enf_auditiva_txt").required = true;
    } else if (enfauditiva === 'NO') {
        $("#enf_auditivadata").css("display", "none");
        document.getElementById("enf_auditiva_txt").required = false;
    }

    /* FRACTURA*/
    if (fractura === 'SI') {
        $("#fracturadata").css("display", "block");
        document.getElementById("fractura_txt").required = true;
    } else if (fractura === 'NO') {
        $("#fracturadata").css("display", "none");
        document.getElementById("fractura_txt").required = false;
    }

    /* AMPUTACION*/
    if (amputacion === 'SI') {
        $("#amputaciondata").css("display", "block");
        document.getElementById("amputacion_txt").required = true;
    } else if (amputacion === 'NO') {
        $("#amputaciondata").css("display", "none");
        document.getElementById("amputacion_txt").required = false;
    }

    /* HERNIAS*/
    if (hernias === 'SI') {
        $("#herniasdata").css("display", "block");
        document.getElementById("hernias_txt").required = true;
    } else if (hernias === 'NO') {
        $("#herniasdata").css("display", "none");
        document.getElementById("hernias_txt").required = false;
    }

    /* ENFERMEDADES SANGUINEAS*/
    if (enfsanguinea === 'SI') {
        $("#enfsanguineadata").css("display", "block");
        document.getElementById("enfsanguinea_txt").required = true;
    } else if (enfsanguinea === 'NO') {
        $("#enfsanguineadata").css("display", "none");
        document.getElementById("enfsanguinea_txt").required = false;
    }

    /* TUMORES CANCER*/
    if (tumorescancer === 'SI') {
        $("#tumorescancerdata").css("display", "block");
        document.getElementById("tumorescancer_txt").required = true;
    } else if (tumorescancer === 'NO') {
        $("#tumorescancerdata").css("display", "none");
        document.getElementById("tumorescancer_txt").required = false;
    }

    /* ENFERMEDADES PSICOLÓGICAS*/
    if (enfpsico === 'SI') {
        $("#enfpsicodata").css("display", "block");
        document.getElementById("enfpsico_txt").required = true;
    } else if (enfpsico === 'NO') {
        $("#enfpsicodata").css("display", "none");
        document.getElementById("enfpsico_txt").required = false;
    }
}


$('#hccohc-id_empresa').on('change', function(e) {
    e.preventDefault();

    var id_cert = e.target.value;
    console.log('Cambiando la empresa: ' + id_cert);

    $('#hccohc-id_trabajador').empty();
    $('#hccohc-area').empty();
    $('#hccohc-puesto').empty();
    $('#hccohc-id_familiar').empty();
    $('#hccohc-id_area').empty();
    var base = 'index.php?r=hccohc%2Finfoempresa';

    if (id_cert != '' && id_cert != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id: id_cert,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data);

                    var folio = 'HC' + data['empresa']['abreviacion'] + data['fecha'];

                    $('#hccohc-folio').val(folio);

                    $('#hccohc-id_trabajador').append('<option value="">Selecciona</option>');
                    $('#hccohc-id_familiar').append('<option value="">Selecciona</option>');

                    $.each(data['hctrabajadores'], function(index, trabajador) {
                        $('#hccohc-id_trabajador').append(
                            '<option value="' + trabajador
                            .id +
                            '">' + trabajador.nombre + ' ' + trabajador.apellidos + '</option>');
                        $('#hccohc-id_familiar').append(
                            '<option value="' + trabajador
                            .id +
                            '">' + trabajador.nombre + ' ' + trabajador.apellidos + '</option>');
                    });


                    $('#hccohc-area').append('<option value="">Selecciona</option>');
                    $('#hccohc-id_area').append('<option value="">Selecciona</option>');

                    $.each(data['areas'], function(index, data) {
                        $('#hccohc-area').append(
                            '<option value="' + data
                            .id +
                            '">' + data.area + '</option>');
                        $('#hccohc-id_area').append(
                            '<option value="' + data
                            .id +
                            '">' + data.area + '</option>');
                    });


                    $('#hccohc-puesto').append('<option value="">Selecciona</option>');
                    $('#hccohc-puesto').append('<option value="0">NUEVO PUESTO</option>');
                    $.each(data['puestos'], function(index, data) {
                        $('#hccohc-puesto').append(
                            '<option value="' + data
                            .id +
                            '">' + data.nombre + '</option>');
                    });

                }
            }
        });
    } else {

    }
});


$('#hccohc-id_trabajador').on('change', function(e) {
    e.preventDefault();

    $('#hccohc-envia_form').val(0);

    $("#formOHC").submit();

    /* var id_trab = e.target.value;
    console.log('Cambiando el trabajador: ' + id_trab);

    $('#hccohc-nombre,#hccohc-apellidos,#hccohc-fecha_nacimiento,#hccohc-edad').empty();
    $('#hccohc-area,#hccohc-puesto,#hccohc-nivel_lectura,#hccohc-nivel_escritura,#hccohc-estado_civil,#hccohc-grupo,#hccohc-rh').val('');
    var base = 'index.php?r=hccohc%2Finfotrabajador';

    if (id_trab != '' && id_trab != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id: id_trab,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {
                    data = $.parseJSON(data);
                    console.log(data);

                    if (data['trabajador']) {
                        $("#hccohc-nombre").val(data['trabajador'].nombre);
                        $("#hccohc-apellidos").val(data['trabajador'].apellidos);
                        $("#hccohc-fecha_nacimiento").val(data['trabajador'].fecha_nacimiento);
                        $("#hccohc-edad").val(Edad(data['trabajador'].fecha_nacimiento));
                        $("#hccohc-sexo").val(data['trabajador'].sexo).trigger("change");
                        $("#hccohc-num_trabajador").val(data['trabajador'].num_trabajador);
                        $("#hccohc-nivel_lectura").val(data['trabajador'].nivel_lectura).trigger("change");
                        $("#hccohc-nivel_escritura").val(data['trabajador'].nivel_escritura).trigger("change");
                        $("#hccohc-estado_civil").val(data['trabajador'].estado_civil).trigger("change");
                        $("#hccohc-grupo").val(data['trabajador'].grupo).trigger("change");
                        $("#hccohc-rh").val(data['trabajador'].rh).trigger("change");
                        $("#hccohc-area").val(data['trabajador'].id_area).trigger("change");
                        $("#hccohc-puesto").val(data['trabajador'].id_puesto).trigger("change");
                    }
                }
            }
        });
    } else {

    } */


});


function Edad(value) {
    var formatoymd = value;
    var hoy = new Date();
    var cumpleanos = new Date(formatoymd);
    var edad = hoy.getFullYear() - cumpleanos.getFullYear();

    var m = hoy.getMonth() - cumpleanos.getMonth();

    if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
        edad--;
    }

    return edad;
}


function cambiaEmpresagetcountry(valor, modelo) {
    console.log('Cambiando la empresa: ' + valor + ' | modelo: ' + modelo + ' | obtener sus paises');

    $('#' + modelo + '-id_pais').empty();
    $('#' + modelo + '-id_linea').empty();

    var base = 'index.php?r=empresas%2Fgetpaises';

    if (valor != '' && valor != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id: valor,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data);

                    $('#' + modelo + '-id_pais').append('<option value="">Selecciona</option>');
                    $.each(data['paises'], function(index, data) {
                        $('#' + modelo + '-id_pais').append(
                            '<option value="' + data
                            .id +
                            '">' + data.pais + '</option>');
                    });

                    if (data['paises']) {

                    }


                }
            }
        });
    }
}

function cambiaCountry(valor, modelo) {
    var empresa = $('#' + modelo + '-id_empresa').val();
    console.log('Cambiando pais : ' + valor + ' | empresa: ' + empresa + ' | modelo: ' + modelo + ' | obtener sus lineas');

    $('#' + modelo + '-id_linea').empty();

    var base = 'index.php?r=empresas%2Fgetlineas';

    if (valor != '' && valor != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id_pais: valor,
                id_empresa: empresa,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data);

                    $('#' + modelo + '-id_linea').append('<option value="">Selecciona</option>');
                    $.each(data['lineas'], function(index, data) {
                        $('#' + modelo + '-id_linea').append(
                            '<option value="' + data
                            .id +
                            '">' + data.linea + '</option>');
                    });

                }
            }
        });
    }
}


function cambiaEmpresa(valor, modelo) {
    console.log('Cambiando la empresa: ' + valor + ' | modelo: ' + modelo);

    var show_nivel1 = 'none';
    var show_nivel2 = 'none';
    var show_nivel3 = 'none';
    var show_nivel4 = 'none';

    $('#' + modelo + '-id_trabajador').empty();
    $('#' + modelo + '-puesto').empty();
    $('#' + modelo + '-area').empty();
    $('#' + modelo + '-id_puesto').empty();
    $('#' + modelo + '-id_area').empty();
    $('#' + modelo + '-id_consultorio').empty();
    $('#' + modelo + '-id_consultorio2').empty();
    $('#' + modelo + '-empresa').val('');
    $('#' + modelo + '-turno').empty();
    $('#' + modelo + '-id_pais').empty();

    if (valor == "0") {
        $("#empresa").css("display", "block");
    } else {
        $("#empresa").css("display", "none");
    }

    if (modelo == 'trabajadores' || modelo == 'puestostrabajo') {
        $('#' + modelo + '-dato_extra1').empty();
        $('#' + modelo + '-dato_extra2').empty();

        $('#' + modelo + '-id_nivel1').empty();
        $('#' + modelo + '-id_nivel2').empty();
        $('#' + modelo + '-id_nivel3').empty();
        $('#' + modelo + '-id_nivel4').empty();

        $('#' + modelo + '-nombre_empresa').empty();
    }

    if (modelo == 'usuarios') {
        $('#' + modelo + '-paises_select').empty();
        $('#' + modelo + '-ubicaciones_select').empty();
    }

    var base = 'index.php?r=hccohc%2Finfoempresa';

    if (valor != '' && valor != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id: valor,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data);

                    if (modelo == 'cargasmasivas') {
                        console.log('ES CARGA MASIVA');
                        for (let i = 1; i < 40; i++) {
                            $('#cargasmasivas-extra' + i).empty();

                            $('#cargasmasivas-extra' + i).append('<option value="">SELECCIONA--</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="36">' + data['label_nivel1'] + '</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="37">' + data['label_nivel2'] + '</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="38">' + data['label_nivel3'] + '</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="39">' + data['label_nivel4'] + '</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="1">N° Trabajador</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="2">N° IMSS</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="3">Célular</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="4">Contacto Emergencia</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="5">Dirección</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="6">Colonia</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="7">Ciudad</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="8">Municipio</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="9">Estado</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="10">--</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="11">CP.</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="12">Fecha Contratacion</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="13">Estado Civil</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="14">Nivel Lectura</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="15">Nivel Escritura</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="16">Escolaridad</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="17">Ruta</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="18">Parada</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="19">Área</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="20">Puesto de Trabajo</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="21">Teamleader</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="22">Fecha Inicio</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="23">Curp</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="24">Rfc</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="25">Correo Electrónico</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="28">Extra 3</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="29">Extra 4</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="30">Extra 5</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="31">Extra 6</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="32">Extra 7</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="33">Extra 8</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="34">Extra 9</option>');
                            $('#cargasmasivas-extra' + i).append('<option value="35">Extra 10</option>');

                        }
                    }

                    $('#' + modelo + '-folio').val(data['' + modelo + '']);
                    try {
                        $('#' + modelo + '-empresa').val(data['empresa']['comercial']);
                    } catch (error) {

                    }

                    $('#' + modelo + '-id_pais').append('<option value="">SELECCIONA--</option>');
                    $.each(data['paises'], function(index, data) {
                        console.log(index + ':' + data + '| modelo: ' + modelo);
                        $('#' + modelo + '-id_pais').append(
                            '<option value="' + index +
                            '">' + data + '</option>');
                    });


                    $('#' + modelo + '-id_trabajador').append('<option value="">Selecciona</option>');
                    $.each(data['trabajadores'], function(index, trabajador) {
                        $('#' + modelo + '-id_trabajador').append(
                            '<option value="' + trabajador
                            .id +
                            '">' + trabajador.nombre + ' ' + trabajador.apellidos + '</option>');
                    });

                    $('#' + modelo + '-id_consultorio').append('<option value="">Selecciona</option>');
                    $.each(data['consultorios'], function(index, consultorio) {
                        $('#' + modelo + '-id_consultorio').append(
                            '<option value="' + consultorio
                            .id +
                            '">' + consultorio.consultorio + '</option>');
                    });

                    $('#' + modelo + '-area').append('<option value="">Selecciona</option>');
                    $.each(data['areas'], function(index, area) {
                        $('#' + modelo + '-area').append(
                            '<option value="' + area
                            .id +
                            '">' + area.area + '</option>');
                    });

                    $('#' + modelo + '-id_area').append('<option value="">Selecciona</option>');
                    $.each(data['areas'], function(index, area) {
                        $('#' + modelo + '-id_area').append(
                            '<option value="' + area
                            .id +
                            '">' + area.area + '</option>');
                    });

                    $('#' + modelo + '-puesto').append('<option value="">Selecciona</option>');
                    $.each(data['puestos'], function(index, puesto) {
                        console.log('cambia puesto: ' + index);
                        $('#' + modelo + '-puesto').append(
                            '<option value="' + puesto
                            .id +
                            '">' + puesto.nombre + '</option>');
                    });

                    $('#' + modelo + '-id_puesto').append('<option value="">Selecciona</option>');
                    $.each(data['puestos'], function(index, puesto) {
                        $('#' + modelo + '-id_puesto').append(
                            '<option value="' + puesto
                            .id +
                            '">' + puesto.nombre + '</option>');
                    });


                    $('#' + modelo + '-id_ubicacion').append('<option value="">Selecciona</option>');
                    $.each(data['ubicaciones'], function(index, ubicacion) {
                        $('#' + modelo + '-id_ubicacion').append(
                            '<option value="' + ubicacion
                            .id +
                            '">' + ubicacion.ubicacion + '</option>');
                    });

                    $('#' + modelo + '-turno').append('<option value="">Selecciona</option>');
                    $.each(data['turnos'], function(index, turno) {
                        $('#' + modelo + '-turno').append(
                            '<option value="' + turno
                            .id +
                            '">' + turno.turno + '</option>');
                    });


                    if (modelo == 'trabajadores' || modelo == 'puestostrabajo') {
                        $('#' + modelo + '-dato_extra1').append('<option value="">Selecciona</option>');
                        $.each(data['ubicaciones'], function(index, ubicacion) {
                            $('#' + modelo + '-dato_extra1').append(
                                '<option value="' + ubicacion
                                .id +
                                '">' + ubicacion.ubicacion + '</option>');
                        });

                        $('#' + modelo + '-dato_extra2').append('<option value="">Selecciona</option>');
                        $.each(data['paises'], function(index, pais) {
                            $('#' + modelo + '-dato_extra2').append(
                                '<option value="' + pais
                                .id +
                                '">' + pais.pais + '</option>');
                        });



                        $('#' + modelo + '-id_nivel1').append('<option value="">Selecciona</option>');
                        $.each(data['niveles1'], function(id, nivel) {
                            $('#' + modelo + '-id_nivel1').append(
                                '<option value="' + id +
                                '">' + nivel + '</option>');
                        });

                        $('#' + modelo + '-nombre_empresa').val(data['empresa']['comercial']);
                        $('#nombre_empresa').html(data['empresa']['comercial']);
                    }


                    if (modelo = 'usuarios') {
                        $('#' + modelo + '-ubicaciones_select').append('<option value="">Selecciona</option>');
                        $.each(data['ubicaciones'], function(index, ubicacion) {
                            $('#' + modelo + '-ubicaciones_select').append(
                                '<option value="' + ubicacion
                                .id +
                                '">' + ubicacion.ubicacion + '</option>');
                        });

                        $('#' + modelo + '-paises_select').append('<option value="">Selecciona</option>');
                        $.each(data['paises'], function(index, pais) {
                            $('#' + modelo + '-paises_select').append(
                                '<option value="' + pais
                                .id +
                                '">' + pais.pais + '</option>');
                        });
                    }


                    if (data['cantidad_niveles'] >= 1) {
                        show_nivel1 = 'block';
                    }
                    if (data['cantidad_niveles'] >= 2) {
                        show_nivel2 = 'block';
                    }
                    if (data['cantidad_niveles'] >= 3) {
                        show_nivel3 = 'block';
                    }
                    if (data['cantidad_niveles'] >= 4) {
                        show_nivel4 = 'block';
                    }

                    $("#show_nivel1").css("display", show_nivel1);
                    $("#show_nivel2").css("display", show_nivel2);
                    $("#show_nivel3").css("display", show_nivel3);
                    $("#show_nivel4").css("display", show_nivel4);

                    $('#label_nivel1').html(data['label_nivel1']);
                    $('#label_nivel2').html(data['label_nivel2']);
                    $('#label_nivel3').html(data['label_nivel3']);
                    $('#label_nivel4').html(data['label_nivel4']);


                    if (modelo = 'puestostrabajo') {
                        var iconheart = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-heart" viewBox="0 0 16 16"><path d="M10.058.501a.501.501 0 0 0-.5-.501h-2.98c-.276 0-.5.225-.5.501A.499.499 0 0 1 5.582 1a.497.497 0 0 0-.497.497V2a.5.5 0 0 0 .5.5h4.968a.5.5 0 0 0 .5-.5v-.503A.497.497 0 0 0 10.555 1a.499.499 0 0 1-.497-.499Z"/><path d="M3.605 2a.5.5 0 0 0-.5.5v12a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-12a.5.5 0 0 0-.5-.5h-.5a.5.5 0 0 1 0-1h.5a1.5 1.5 0 0 1 1.5 1.5v12a1.5 1.5 0 0 1-1.5 1.5h-9a1.5 1.5 0 0 1-1.5-1.5v-12a1.5 1.5 0 0 1 1.5-1.5h.5a.5.5 0 0 1 0 1h-.5Z"/><path d="M8.068 6.482c1.656-1.673 5.795 1.254 0 5.018-5.795-3.764-1.656-6.69 0-5.018Z"/></svg>';
                        var iconsquare = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file" viewBox="0 0 16 16"><path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg>';
                        var arr_iconos = [iconheart, iconsquare];
                        var arr_colors = ['color10', 'color5'];
                        var arr_status = ['<span class="badge bgcolor6 text-light font11"><span class="color11 mx-2" style=""><i class="bi bi-x"></i></span>Baja</span>', '<span class="badge bgcolor3 text-light font11"><span class="color7 mx-2" style=""><i class="bi bi-check"></i></span>Activo</span>'];
                        var arr_tipos = ['Médico', 'Otro'];
                        var tabla = '<table class="table table-dark table-hover table-sm text-little"><thead class="table-dark font600"><tr><td class="text-center">#</td><td class="text-center">Tipo</td><td class="text-center">Estudio</td><td class="text-center">Periodicidad</td><td class="text-center">Requerido desde Día</td><td class="text-center">Status</td></tr></thead><tbody>';

                        var fecha_apartir = '--';


                        $.each(data['requisitos'], function(index, requisito) {
                            try {
                                if (requisito.fecha_apartir != null && requisito.fecha_apartir != '') {
                                    fecha_apartir = requisito.fecha_apartir;
                                } else {
                                    fecha_apartir = '--';
                                }
                            } catch (error) {}
                            tabla += '<tr><td class="font500 text-center text-uppercase">' + (index + 1) + '</td><td class="font500 text-center text-uppercase ' + arr_colors[requisito.id_tipo] + '"> <span class="mx-2">' + arr_iconos[requisito.id_tipo] + '</span>' + arr_tipos[requisito.id_tipo] + '</td><td class="font600">' + requisito.id_estudio + '</td><td class="font500 color6 text-center">' + requisito.id_periodicidad + '</td><td class="font500 color6 text-center">' + fecha_apartir + '</td><td class="font500 text-center">' + arr_status[requisito.id_status] + '</td></tr>';
                        });

                        tabla += '</tbody></table>';
                        $('#requisitosobligatorios').html(tabla);
                    }


                }
            }
        });
    } else {

    }
}

function cambiaTrabajador(valor, modelo) {
    console.log('Cambiando el trabajador: ' + valor + ' | modelo: ' + modelo);
    var base = 'index.php?r=hccohc%2Finfotrabajador';

    if (valor != '' && valor != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id: valor,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data);

                    $('#' + modelo + '-fecha_nacimiento').val(data['trabajador']['fecha_nacimiento']);
                    $('#' + modelo + '-edad').val(data['trabajador']['edad']);
                    $('#' + modelo + '-nombre').val(data['trabajador']['nombre']);
                    $('#' + modelo + '-apellidos').val(data['trabajador']['apellidos']);
                    $('#' + modelo + '-sexo').val(data['trabajador']['sexo']).trigger('change');
                    $('#' + modelo + '-num_imss').val(data['trabajador']['num_imss']);
                    $('#' + modelo + '-num_trabajador').val(data['trabajador']['num_trabajador']);
                    $('#' + modelo + '-area').val(data['trabajador']['id_area']).trigger('change');
                    $('#' + modelo + '-id_area').val(data['trabajador']['id_area']).trigger('change');
                    $('#' + modelo + '-puesto').val(data['trabajador']['id_puesto']).trigger('change');
                    $('#' + modelo + '-id_puesto').val(data['trabajador']['id_puesto']).trigger('change');
                    $('#' + modelo + '-fecha_nacimiento').val(data['trabajador']['fecha_nacimiento']);


                    $('#' + modelo + '-id_nivel1').val(data['trabajador']['id_nivel1']).trigger('change');
                    $('#' + modelo + '-id_nivel2').val(data['trabajador']['id_nivel2']).trigger('change');
                    $('#' + modelo + '-id_nivel3').val(data['trabajador']['id_nivel3']).trigger('change');
                    $('#' + modelo + '-id_nivel4').val(data['trabajador']['id_nivel4']).trigger('change');


                    try {
                        if (data['poe'] != null) {
                            $("#poeprevio").css("display", "block");
                            $('#poe_anio_anterior').html('<label>' + data['poe']['anio'] + '</label>');
                            $('#poe_puesto_anterior').html('<label>' + data['poe']['id_puesto'] + '</label>');
                            $('#poe_area_anterior').html('<label>' + data['poe']['id_area'] + '</label>');
                            $('#poe_numimss_anterior').html('<label>' + data['poe']['num_imss'] + '</label>');
                        } else {
                            $("#poeprevio").css("display", "none");
                        }
                    } catch (error) {
                        console.log(error);
                    }




                    var tabla = '<table class="table"><thead class="table-dark"><tr><th>Categoria</th><th>Estudio</th><th>Fecha</th><th>Evidencia</th><th>Diagnóstico</th><th>Evolución</th><th>Comentarios</th></tr></thead><tbody>';

                    $.each(data['estudios'], function(index, estudio) {
                        var pdf = '';
                        if (estudio.evidencia != null) {
                            pdf = '<a href = "' + estudio.evidencia + '" target = "_blank"><span style="font-size:30px;" class="color1"><span class="" style="font-size:30px"><i class="bi bi-file-earmark-pdf"></i></span></span></a>';
                        }
                        tabla += '<tr><td>' + estudio.id_tipo + '</td><td>' + estudio.id_estudio + '</td><td>' + estudio.fecha + '</td><td>' + pdf + '</td><td>' + estudio.condicion + '</td><td>' + estudio.evolucion + '</td><td>' + estudio.comentario + '</td></tr>';
                    });

                    tabla += '</tbody></tbody></table>';
                    $('#poe_estudios_anterior').html(tabla);



                    $.each(data['alergias'], function(index, alergia) {
                        $('#alergias').append('<span class="badge bgtransparent1 text-dark font12 m-1">' + alergia.alergia + '</span>');
                    });

                    $.each(data['riesgos'], function(index, riesgo) {
                        $('#riesgos').append('<span class="badge bgtransparent1 text-dark font12 m-1">' + riesgo.riesgo + '</span>');
                    });

                    $('#historia_clinica').html('');
                    if (data['hc'] != null && data['hc'] != '' && data['hc'] != ' ') {
                        console.log('Hay HC');
                        $('#historia_clinica').html('<a href="/index.php?r=hccohc%2Fpdf&id=' + data['hc']['id'] + '" target="_blank"><span class="color1" style="font-size:25px"><i class="bi bi-file-earmark-text-fill"></i></span></a>');
                        $('#' + modelo + '-id_hccohc').val(data['hc']['id']);
                    } else {
                        $('#historia_clinica').html('<label class="color11">SIN HC</label>');
                        $('#' + modelo + '-id_hccohc').val('');

                        if (modelo != 'poes') {
                            Swal.fire({ icon: "error", title: "Requiere Realizar Historia Clínica", html: "<span style=\'\'>Debe realizar primero la historia clínica del paciente para continuar</span>" });
                        }

                    }

                    $('#programas_salud').html('');
                    if (data['programas'] != null && data['programas'] != '' && data['programas'] != ' ') {
                        console.log('Hay Programas');
                        var programas = '';

                        $.each(data['programas'], function(index, programa) {
                            programas += programa['id_programa'] + ', ';
                        });
                        $('#programas_salud').html(programas);
                    } else {
                        $('#programas_salud').html('<label class="color11">SIN PROGRAMAS DE SALUD</label>');
                    }



                }
            }
        });
    } else {

    }
}


function cambiaPuesto(valor, modelo) {
    console.log('Cambiando el puesto: ' + valor + ' | modelo: ' + modelo);

    $('#' + modelo + '-envia_puesto').val(1);
    $("#formOHC").submit();

}



function calculoIMC() {
    var altura = document.getElementById('talla').value;
    var peso = document.getElementById('peso').value;
    var cuadrado = parseFloat((altura * altura));
    if (cuadrado !== 0) {
        var calculo = (peso / cuadrado);
    }
    var calculorec = parseFloat(calculo).toFixed(2);
    document.getElementById('imcHcc').value = calculorec;

    if (calculorec < 16) {
        document.getElementById('imccat').value = ("Infrapeso:Delgadez Extrema ");
    } else if (calculorec >= 16 && calculorec < 18.5) {
        document.getElementById('imccat').value = ("Infrapeso:Delgadez Aceptable ");
    } else if (calculorec >= 18.5 && calculorec < 25) {
        document.getElementById('imccat').value = ("Peso Normal ");
    } else if (calculorec >= 25 && calculorec < 30) {
        document.getElementById('imccat').value = ("Sobrepeso ");
    } else if (calculorec >= 30 && calculorec < 35) {
        document.getElementById('imccat').value = ("Obeso: Tipo I ");
    } else if (calculorec >= 35 && calculorec < 40) {
        document.getElementById('imccat').value = ("Obeso: Tipo II ");
    } else if (calculorec >= 40) {
        document.getElementById('imccat').value = ("Obeso: Tipo III ");
    }
}


function cambiaTipoconsulta(valor) {
    console.log('Cambia tipo de consulta: ' + valor);

    $("#bloque_accidente").css("display", "none");
    $("#bloque_incapacidad").css("display", "none");
    $("#bloque_riesgo").css("display", "none");
    $(".bloque_programas").css("display", "none");

    $("#diabetes").css("display", "none");
    $("#hipertension").css("display", "none");
    $("#maternidad").css("display", "none");
    $("#lactancia").css("display", "none");
    $("#hiperdiabetes").css("display", "none");

    /* ENFERMEDADES PSICOLÓGICAS*/
    if (valor === '1') {
        $("#bloque_accidente").css("display", "block");
    } else if (valor === '4') {
        $("#bloque_incapacidad").css("display", "block");
    } else if (valor === '6') {
        $("#bloque_riesgo").css("display", "block");
    } else if (valor === '7') {
        $(".bloque_programas").css("display", "block");
    }
}

function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    console.log();
}

$('#upload').change(function() {
    var input = this;
    console.log('Estoy cambiando la foto');
    var url = $(this).val();
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();

    if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#img').attr('src', '/assets/no_preview.png');
    }
});




$("#guardarbutton").on("click", function(e) {
    e.preventDefault();

    var model_ = $('#model_').val();
    console.log('model_: ' + model_);

    if (model_ == 'consultas') {
        var foto = $("#" + model_ + "-txt_base64_foto").val();
        console.log('FOTO------------------------------------------------');
        console.log(foto);
    }

    $('#trabajadores-envia_form').val('1');
    $('#movimientos-envia_form').val('1');
    $('#insumos-envia_form').val('1');
    $('#consultas-envia_form').val('1');
    $('#ordenespoes-envia_form').val('1');
    $('#usuarios-envia_form').val('1');
    $('#mantenimientos-envia_form').val('1');
    $('#poes-envia_form').val('1').trigger('change');
    //$('#cargasmasivas-envia_form').val('1').trigger('change');

    $('#firmas-envia_form').val('1');

    try {
        console.log('intentando enviar');
        const canvas = document.getElementById("signature-pad").querySelector("canvas");
        const dataURL = canvas.toDataURL();

        $("#consultas-firma").val(dataURL);
        $("#hccohc-firma").val(dataURL);
        $("#poes-firma").val(dataURL);
        $("#mantenimientos-firma1").val(dataURL);

    } catch (error) {

    }

    if (model_ == 'poes') {
        $("#form_poes").submit();
    } else {
        $("#formOHC").submit();
    }

    console.log('envio');
});


function calculaVencimiento(fecha_vencimiento_id, fecha_documento_id, fecha, periodicidad) {
    console.log('ID FECHA VENCIMIENTO:' + fecha_vencimiento_id);
    console.log('ID FECHA DOCUMENTO:' + fecha_documento_id);
    console.log('FECHA:' + fecha);
    console.log('PERIODICIDAD:' + periodicidad);

    var fecha_vencimiento = new Date(fecha);
    var fecha_v = null;
    //['0'=>'INDEFINIDO','1'=>'15 DÍAS','2'=>'1 MES','3'=>'2 MESES','4'=>'3 MESES','5'=>'4 MESES','6'=>'5 MESES','7'=>'6 MESES','8'=>'7 MESES','9'=>'8 MESES','10'=>'9 MESES','11'=>'10 MESES','12'=>'11 MESES','13'=>'1 AÑO','14'=>'1 AÑO 3 MESES','15'=>'1 AÑO 6 MESES','16'=>'1 AÑO 9 MESES','17'=>'2 AÑOS']
    if (periodicidad != '' && fecha != '') {
        if (periodicidad == 0) { //INDEFINIDO
            $('#' + fecha_vencimiento_id).val('INDEFINIDO');
        } else if (periodicidad == 1) { //'15 DÍAS'
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 16);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 2) { //'1 MES'
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 1);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 3) { //'2 MESES'
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 2);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 4) { //'3 MESES'
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 3);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 5) { //'4 MESES'
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 4);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 6) { //'5 MESES'
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 5);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 7) { //'6 MESES'
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 6);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 8) { //'7 MESES'
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 7);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 9) { //'8 MESES'
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 8);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 10) { //'9 MESES'
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 9);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 11) { //'10 MESES'
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 10);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 12) { //'11 MESES'
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 11);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 13) { //'1 AÑO'
            fecha_vencimiento.setFullYear(fecha_vencimiento.getFullYear() + 1);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 14) { //'1 AÑO 3 MESES'
            fecha_vencimiento.setFullYear(fecha_vencimiento.getFullYear() + 1);
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 3);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 15) { //'1 AÑO 6 MESES'
            fecha_vencimiento.setFullYear(fecha_vencimiento.getFullYear() + 1);
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 6);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 16) { //'1 AÑO 9 MESES'
            fecha_vencimiento.setFullYear(fecha_vencimiento.getFullYear() + 1);
            fecha_vencimiento.setMonth(fecha_vencimiento.getMonth() + 9);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        } else if (periodicidad == 17) { //'2 AÑOS'
            fecha_vencimiento.setFullYear(fecha_vencimiento.getFullYear() + 2);
            fecha_vencimiento.setDate(fecha_vencimiento.getDate() + 1);
            fecha_v = fecha_vencimiento.toLocaleDateString();
            var myArray = fecha_v.split("/");
            var yyyy = myArray["2"];
            var mm = myArray["1"];
            var dd = myArray["0"];

            if (mm < 10) {
                mm = '0' + mm;
            }
            if (dd < 10) {
                dd = '0' + dd;
            }
            var fecha_vencimiento = yyyy + "-" + mm + "-" + dd;
            console.log('Vencimiento: ' + fecha_vencimiento);
            $('#' + fecha_vencimiento_id).val(fecha_vencimiento);
        }
    }
}

function cambiaTipo(id, valor, modelo) {
    console.log('id:' + id + ' | valor: ' + valor);
    $('#' + id).empty();
    var base = 'index.php?r=requerimientoempresa%2Ftipo';

    if (valor != '' && valor != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id: valor,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data);

                    $('#' + id).append('<option value="">SELECCIONE...</option>');
                    $.each(data['estudios'], function(index, estudio) {
                        $('#' + id).append(
                            '<option value="' + estudio
                            .id +
                            '">' + estudio.estudio + '</option>');
                    });

                    $('#' + id).append(
                        '<option value="0">OTRO</option>');

                }
            }
        });
    } else {

    }
}

function cambiaOtro(valor, modelo, atributo) {
    console.log('Cambiando el valor: ' + valor + ' | modelo: ' + modelo);
    if (valor === '0') {
        $("#otra_" + atributo).css("display", "block");
        $("#" + modelo + "-otra_" + atributo).val('');
    } else {
        $("#otra_" + atributo).css("display", "none");
        $("#" + modelo + "-otra_" + atributo).val('');

        if (modelo != 'insumos') {
            //Checar si hay espacio disponible
            var base = 'index.php?r=usuarios%2Fchecardisponibles';
            var empresa = $('#usuarios-id_empresa').val();
            if (valor != '' && valor != null) {
                $.ajax({
                    url: base,
                    type: 'post',
                    data: {
                        id_rol: valor,
                        id_empresa: empresa,
                        _csrf: yii.getCsrfToken()
                    },
                    success: function(data) {
                        if (data) {

                            data = $.parseJSON(data);
                            console.log(data);

                            if (data['message'] != null) {
                                console.log('ESTOY AQUI');
                                $("#usuarios-rol").val('').trigger('change');
                                Swal.fire({ icon: "error", title: "Error", html: data['message'] });
                                $("#usuarios-id_empresa").trigger("focus");
                            } else if (data['disponible'] == false) {
                                $("#usuarios-rol").val('').trigger('change');
                                Swal.fire({ icon: "error", title: "Se ha alcanzado el número máximo de usuarios " + data['rol'], html: "<span style=\'\'>Solicite aumentar el número de usuarios, o dar de baja alguno resistrado</span>" });
                            } else {
                                console.log('CHECAR LO DE LOS MEDICOS');
                                if (valor == 2) {
                                    $("#bloque_firmas").css("display", "block");
                                } else {
                                    $("#bloque_firmas").css("display", "none");
                                }

                                $("#formOHC").submit();
                            }
                        }
                    }
                });
            } else {

            }

        }

    }
}


/* $('#movimientos-id_empresa').on('change', function(e) {
    e.preventDefault();

    var id_cert = e.target.value;
    $('#movimientos-id_consultorio').empty();
    $('#movimientos-id_consultorio2').empty();
    console.log('Cambiando la empresa: ' + id_cert);

    $('#hccohc-id_trabajador').empty();
    var base = 'index.php?r=movimientos%2Fcreateclave';

    if (id_cert != '' && id_cert != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id: id_cert,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data);

                    $('#movimientos-folio').val(data['folio']);

                    $('#movimientos-id_consultorio').append('<option value="">Selecciona</option>');
                    $('#movimientos-id_consultorio2').append('<option value="">Selecciona</option>');
                    $.each(data['consultorios'], function(index, consultorio) {
                        $('#movimientos-id_consultorio').append(
                            '<option value="' + consultorio
                            .id +
                            '">' + consultorio.consultorio + '</option>');

                        $('#movimientos-id_consultorio2').append(
                            '<option value="' + consultorio
                            .id +
                            '">' + consultorio.consultorio + '</option>');
                    });

                }
            }
        });
    } else {

    }
}); */

function cambiaEmpresa2(valor, modelo) {
    console.log('Cambiando la empresa: ' + valor + ' | modelo: ' + modelo);
    $('#' + modelo + '-envia_empresa').val(1);

    if (modelo == 'poes') {
        $("#form_poes").submit();
        console.log('enviando --------');
    } else {
        $("#formOHC").submit();
        console.log('enviando --------');
    }

}


function cambiaConsultorio(valor, modelo) {
    console.log('Cambiando el consultorio: ' + valor + ' | modelo: ' + modelo);

    /* if (modelo == 'movimientos') {
        let e_s = $('#' + modelo + '-e_s').val();
        console.log('Son movimientos');

        if (valor != null && valor != '') {
            $("#movimiento_entrada").css("display", "none");
            $("#movimiento_salida").css("display", "none");
            if (e_s == 1) {
                $("#movimiento_entrada").css("display", "block");
            } else if (e_s == 2) {
                $("#movimiento_salida").css("display", "block");
            }
        }
    } */
    $('#movimientos-envia_form').val(0);
    $('#' + modelo + '-envia_consultorio').val(1);
    $("#formOHC").submit();
}

function cambiaTrabajadorepp(valor, modelo) {
    console.log('Cambiando el trabajador: ' + valor + ' | modelo: ' + modelo);
    $('#movimientos-envia_form').val(0);
    $('#' + modelo + '-envia_trabajador').val(1);
    $("#formOHC").submit();
}

function cambiaTipomov(valor, modelo, atributo) {
    console.log('Cambiando el valor: ' + valor + ' | modelo: ' + modelo);
    if (valor === '2') {
        $("#consultorio2").css("display", "block");
        $("#" + modelo + "-" + atributo).val('').trigger('change');
    } else {
        $("#consultorio2").css("display", "none");
        $("#" + modelo + "-" + atributo).val('').trigger('change');
    }
}

function cambiaES(valor, modelo) {
    console.log('Cambiando el valor: ' + valor + ' | modelo: ' + modelo);
    $("#movimiento_entrada").css("display", "none");
    $("#movimiento_salida").css("display", "none");

    let consultorio = $('#' + modelo + '-id_consultorio').val();
    console.log('El consultorio es: ' + consultorio);

    $('#' + modelo + '-tipo').empty();
    if (valor === '1') {

        if (consultorio == null || consultorio == '' || consultorio == ' ') {} else {
            $("#movimiento_entrada").css("display", "block");
        }

        //['1'=>'Ingreso','2'=>'Traspaso','4'=>'Ajustes','5'=>'Inventario Inicial']
        $('#' + modelo + '-tipo').append('<option value="1">Ingreso</option>');
        $('#' + modelo + '-tipo').append('<option value="3">Ajustes</option>');
        $('#' + modelo + '-tipo').append('<option value="4">Inventario Inicial</option>');
    } else {
        //['5'=>'Traspaso','6'=>'Utilizado']
        if (consultorio == null || consultorio == '' || consultorio == ' ') {} else {
            $("#movimiento_salida").css("display", "block");
        }

        $("#consultorio2").css("display", "block");
        $('#' + modelo + '-tipo').append('<option value="5">Traspaso</option>');
        $('#' + modelo + '-tipo').append('<option value="7">Caducidad</option>');
        $('#' + modelo + '-tipo').append('<option value="8">Devolución</option>');
        $('#' + modelo + '-tipo').append('<option value="9">Entrega EPP</option>');
    }
}



function nuevoInsumo(id_presentacion, id_unidad, valor, modelo) {
    console.log('id presentacion:' + id_presentacion + ' | id_unidad: ' + id_unidad + ' | valor: ' + valor + ' | modelo: ' + modelo);
    $('#' + id_presentacion).empty();
    $('#' + id_unidad).empty();
    var base = 'index.php?r=movimientos%2Finfoinsumo';

    if (valor != '' && valor != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id: valor,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data);

                    $('#' + id_presentacion).val(data['insumo']['id_presentacion']);
                    $('#' + id_unidad).val(data['insumo']['unidades_individuales']);
                }
            }
        });
    } else {

    }
}


function nuevoMedicamento(id_stock, id_unidad, cantidad_id, fecha_id, valor, modelo) {
    console.log('id stock:' + id_stock + ' | id_unidad: ' + id_unidad + ' | valor: ' + valor + ' | fecha: ' + fecha_id + ' | modelo: ' + modelo);
    $('#' + id_stock).empty();
    $('#' + cantidad_id).empty();
    $('#' + id_unidad).empty();
    var base = 'index.php?r=almacen%2Finfoinsumo';

    if (valor != '' && valor != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id: valor,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data);
                    $('#' + id_stock).val(data['almacen']['stock_unidad']);
                    $('#' + id_unidad).val(data['insumo']['unidades_individuales']);
                    $('#' + fecha_id).val(data['almacen']['fecha_caducidad']);

                }
            }
        });
    } else {

    }
}

function cambiaTotal(cantidad, cantidad_unidad, stockunidades_id) {

    if (cantidad_unidad == "" || cantidad_unidad == " " || cantidad_unidad == null) {
        if (cantidad > 0) {
            $('#' + stockunidades_id).val(cantidad);
        }
    } else {
        if (cantidad > 0) {
            var total = cantidad * cantidad_unidad;
            $('#' + stockunidades_id).val(total);
        }
    }
}

function cambiaSignos(valor, atributo) {
    console.log('------------------------------------------');
    console.log('valor:' + valor + ' | atributo: ' + atributo);

    if (atributo == 1) {
        console.log('Oximetría valor: ' + valor);
        if (valor < 85) {
            $('#consultas-oximetria_diagnostico').val('2').trigger('change');
        } else {
            $('#consultas-oximetria_diagnostico').val('1').trigger('change');
        }
    } else if (atributo == 2) {
        console.log('TA Sistólica valor: ' + valor);
        if (valor < 80) {
            console.log('Hipotensión');
            $('#consultas-tasis_diagnostico').val('2').trigger('change');
        } else if (valor > 140) {
            console.log('Hipertensión');
            $('#consultas-tasis_diagnostico').val('3').trigger('change');
        } else {
            console.log('Normal');
            $('#consultas-tasis_diagnostico').val('1').trigger('change');
        }
    } else if (atributo == 3) {
        console.log('TA Diastólica valor: ' + valor);
        if (valor < 60) {
            console.log('Hipotensión');
            $('#consultas-tadis_diagnostico').val('2').trigger('change');
        } else if (valor > 89) {
            console.log('Hipertensión');
            $('#consultas-tadis_diagnostico').val('3').trigger('change');
        } else {
            console.log('Normal');
            $('#consultas-tadis_diagnostico').val('1').trigger('change');
        }
    } else if (atributo == 4) {
        console.log('FR valor: ' + valor);
        if (valor < 15) {
            $('#consultas-fr_diagnostico').val('2').trigger('change');
        } else if (valor > 20) {
            $('#consultas-fr_diagnostico').val('3').trigger('change');
        } else {
            $('#consultas-fr_diagnostico').val('1').trigger('change');
        }
    }

    let tasis = $('#consultas-tasis_diagnostico').val();
    let tadis = $('#consultas-tadis_diagnostico').val();
    let taresult = '';

    if (tasis == 2 && tadis == 2) {
        taresult = 2;
    } else if ((tasis == 1 && tadis == 2) || (tasis == 1 && tadis == 1)) {
        taresult = 1;
    } else if ((tasis == 3 && tadis == 3) || (tasis == 3 && tadis == 1) || (tasis == 1 && tadis == 3)) {
        taresult = 3;
    }

    $('#consultas-ta_diagnostico').val(taresult).trigger('change');

    console.log('TA | sistolica: ' + tasis + ' diastolica: ' + tadis + ' Resultado ta: ' + taresult);
}


function cambiaPrograma(valor) {
    let progs = $('#consultas-id_programa').val();
    progs = String(progs);
    console.log('Cambia programa de salud: ' + progs);

    $("#diabetes").css("display", "none");
    $("#hipertension").css("display", "none");
    $("#maternidad").css("display", "none");
    $("#lactancia").css("display", "none");
    $("#hiperdiabetes").css("display", "none");

    try {
        programas = progs.split(',');
        console.log(programas);

        if (programas.includes("1")) {
            console.log('DIABETES');
            $("#diabetes").css("display", "block");
        } else {
            $("#diabetes").css("display", "none");
        }
        if (programas.includes("2")) {
            console.log('HIPERTENSIÓN');
            $("#hipertension").css("display", "block");
        } else {
            $("#hipertension").css("display", "none");
        }
        if (programas.includes("3")) {
            console.log('MATERNIDAD');
            $("#maternidad").css("display", "block");
        } else {
            $("#maternidad").css("display", "none");
        }
        if (programas.includes("4")) {
            console.log('LACTANCIA');
            $("#lactancia").css("display", "block");
        } else {
            $("#lactancia").css("display", "none");
        }
        if (programas.includes("5")) {
            console.log('HIPERTENSIÓN Y DIABETES');
            $("#hiperdiabetes").css("display", "block");
        } else {
            $("#hiperdiabetes").css("display", "none");
        }
    } catch (error) {

    }

}


function cambiaPermisos(nombreatributo) {
    atributo = $("input:checkbox[name='Usuarios[" + nombreatributo + "]']:checked").val();
    let atributos = [];

    let marcar = false;
    console.log(nombreatributo + ': ' + atributo);
    if (atributo == '1') {
        console.log('Esta checked');
        marcar = true;
    } else {
        console.log('No esta checked');
        marcar = false;
    }

    if (nombreatributo == 'permisos_all') {
        console.log('Marcaremos toda la columna: ' + nombreatributo);
        atributos = [
            'almacen_exportar',
            'almacen_listado',

            'antropometricos_crear',
            'antropometricos_exportar',
            'antropometricos_listado',
            'antropometricos_ver',

            'cargapoes_crear',
            'cargapoes_eliminar',
            'cargapoes_exportar',
            'cargapoes_listado',
            'cargapoes_ver',

            'cargatrabajadores_crear',
            'cargatrabajadores_eliminar',
            'cargatrabajadores_exportar',
            'cargatrabajadores_listado',
            'cargatrabajadores_ver',

            'categoriaestudio_actualizar',
            'categoriaestudio_crear',
            'categoriaestudio_exportar',
            'categoriaestudio_listado',
            'categoriaestudio_ver',

            'configuracion_actualizar',
            'configuracion_exportar',
            'configuracion_listado',
            'configuracion_ver',

            'consultas_actualizar',
            'consultas_crear',
            'consultas_exportar',
            'consultas_listado',
            'consultas_ver',

            'diagnosticoscie_actualizar',
            'diagnosticoscie_crear',
            'diagnosticoscie_exportar',
            'diagnosticoscie_listado',
            'diagnosticoscie_ver',

            'diagrama_actualizar',
            'diagrama_listado',
            'diagrama_ver',

            'empresas_actualizar',
            'empresas_crear',
            'empresas_exportar',
            'empresas_listado',
            'empresas_ver',

            'entradas_actualizar',
            'entradas_crear',
            'entradas_exportar',
            'entradas_listado',
            'entradas_ver',

            'epp_actualizar',
            'epp_crear',
            'epp_exportar',
            'epp_listado',
            'epp_ver',

            'eppsbitacora_actualizar',
            'eppsbitacora_crear',
            'eppsbitacora_exportar',
            'eppsbitacora_listado',
            'eppsbitacora_ver',

            'eppsstock_exportar',
            'eppsstock_listado',

            'eppsstockmin_actualizar',
            'eppsstockmin_crear',
            'eppsstockmin_exportar',
            'eppsstockmin_ver',
            'eppsstockmin_listado',

            'estudios_actualizar',
            'estudios_crear',
            'estudios_exportar',
            'estudios_listado',
            'estudios_ver',

            'firmas_actualizar',
            'firmas_crear',
            'firmas_exportar',
            'firmas_listado',
            'firmas_ver',

            'historial_exportar',
            'historial_listado',

            'historias_actualizar',
            'historias_cerrarexpediente',
            'historias_corregir',
            'historias_crear',
            'historias_delete',
            'historias_exportar',
            'historias_listado',
            'historias_ver',

            'medicamentos_actualizar',
            'medicamentos_crear',
            'medicamentos_exportar',
            'medicamentos_listado',
            'medicamentos_ver',

            'medicamentosbitacora_actualizar',
            'medicamentosbitacora_crear',
            'medicamentosbitacora_exportar',
            'medicamentosbitacora_listado',
            'medicamentosbitacora_ver',

            'medicamentosstock_exportar',
            'medicamentosstock_listado',

            'medicamentosstockmin_actualizar',
            'medicamentosstockmin_crear',
            'medicamentosstockmin_exportar',
            'medicamentosstockmin_listado',
            'medicamentosstockmin_ver',

            'nordicos_crear',
            'nordicos_exportar',
            'nordicos_listado',
            'nordicos_ver',

            'ordenpoe_actualizar',
            'ordenpoe_crear',
            'ordenpoe_exportar',
            'ordenpoe_listado',
            'ordenpoe_ver',

            'papelera_listado',

            'poes_actualizar',
            'poes_exportar',
            'poes_crear',
            'poes_listado',
            'poes_ver',
            'poes_entrega',

            'programasalud_actualizar',
            'programasalud_crear',
            'poes_documento',
            'programasalud_exportar',
            'programasalud_hc',
            'programasalud_listado',
            'programasalud_ver',

            'puestos_actualizar',
            'puestos_crear',
            'puestos_exportar',
            'puestos_listado',
            'puestos_ver',

            'requerimientos_actualizar',
            'requerimientos_crear',
            'requerimientos_exportar',
            'requerimientos_listado',
            'requerimientos_ver',

            'salidas_actualizar',
            'salidas_crear',
            'salidas_exportar',
            'salidas_listado',
            'salidas_ver',

            'trabajadores_actualizar',
            'trabajadores_crear',
            'trabajadores_delete',
            'trabajadores_expediente',
            'trabajadores_exportar',
            'trabajadores_listado',
            'trabajadores_ver',

            'turnos_actualizar',
            'turnos_listado',
            'turnos_ver',

            'usuarios_actualizar',
            'usuarios_crear',
            'usuarios_exportar',
            'usuarios_listado',
            'usuarios_ver',

            'consentimientos_actualizar',
            'consentimientos_crear',
            'consentimientos_exportar',
            'consentimientos_listado',
            'consentimientos_ver',

            'vacantes_actualizar',
            'vacantes_asignar',
            'vacantes_crear',
            'vacantes_exportar',
            'vacantes_listado',
            'vacantes_ver',

            'incapacidades_actualizar',
            'incapacidades_crear',
            'incapacidades_exportar',
            'incapacidades_listado',
            'incapacidades_ver',

            'roles_actualizar',
            'roles_crear',
            'roles_exportar',
            'roles_listado',
            'roles_ver'
        ];
    } else if (nombreatributo == 'columna_listado') {
        console.log('Marcaremos toda la columna: ' + nombreatributo);
        atributos = [
            "empresas_listado", "turnos_listado", "firmas_listado", "consentimientos_listado", "trabajadores_listado", "historial_listado", "puestos_listado", "vacantes_listado", "requerimientos_listado", "incapacidades_listado", "cargatrabajadores_listado", "consultas_listado", "historias_listado", "diagnosticoscie_listado", "nordicos_listado", "antropometricos_listado", "medicamentos_listado", "medicamentosstockmin_listado", "medicamentosstock_listado", "medicamentosbitacora_listado", "epp_listado", "eppsstockmin_listado", "eppsstock_listado", "eppsbitacora_listado", "poes_listado", "categoriaestudio_listado", "estudios_listado", "ordenpoe_listado", "cargapoes_listado", "usuarios_listado", "configuracion_listado", "roles_listado", "programasalud_listado", "papelera_listado", "diagrama_listado"
        ];
    } else if (nombreatributo == 'columna_exportar') {
        console.log('Marcaremos toda la columna: ' + nombreatributo);
        atributos = [
            "empresas_exportar", "turnos_exportar", "trabajadores_exportar", "historial_exportar", "puestos_exportar", "requerimientos_exportar", "cargatrabajadores_exportar", "consultas_exportar", "historias_exportar", "diagnosticoscie_exportar", "nordicos_exportar", "antropometricos_exportar", "medicamentos_exportar", "medicamentosstockmin_exportar", "medicamentosstock_exportar", "medicamentosbitacora_exportar", "epp_exportar", "eppsstockmin_exportar", "eppsstock_exportar", "eppsbitacora_exportar", "poes_exportar", "categoriaestudio_exportar", "estudios_exportar", "ordenpoe_exportar", "cargapoes_exportar", "usuarios_exportar", "configuracion_exportar", "firmas_exportar", "consentimientos_exportar", "vacantes_exportar", "incapacidades_exportar", "roles_exportar", "programasalud_exportar"
        ];
    } else if (nombreatributo == 'columna_crear') {
        console.log('Marcaremos toda la columna: ' + nombreatributo);
        atributos = [
            "empresas_crear", "turnos_crear", "trabajadores_crear", "historial_crear", "puestos_crear", "requerimientos_crear", "cargatrabajadores_crear", "consultas_crear", "historias_crear", "diagnosticoscie_crear", "nordicos_crear", "antropometricos_crear", "medicamentos_crear", "medicamentosstockmin_crear", "medicamentosstock_crear", "medicamentosbitacora_crear", "epp_crear", "eppsstockmin_crear", "eppsbitacora_crear", "poes_crear", "categoriaestudio_crear", "estudios_crear", "ordenpoe_crear", "cargapoes_crear", "usuarios_crear", "configuracion_crear", "firmas_crear", "consentimientos_crear", "vacantes_crear", "incapacidades_crear", "roles_crear", "programasalud_crear"
        ];
    } else if (nombreatributo == 'columna_ver') {
        console.log('Marcaremos toda la columna: ' + nombreatributo);
        atributos = [
            "empresas_ver", "turnos_ver", "trabajadores_ver", "historial_ver", "puestos_ver", "requerimientos_ver", "cargatrabajadores_ver", "consultas_ver", "historias_ver", "diagnosticoscie_ver", "nordicos_ver", "antropometricos_ver", "medicamentos_ver", "medicamentosstockmin_ver", "medicamentosstock_ver", "medicamentosbitacora_ver", "epp_ver", "eppsstockmin_ver", "eppsbitacora_ver", "poes_ver", "categoriaestudio_ver", "estudios_ver", "ordenpoe_ver", "cargapoes_ver", "usuarios_ver", "configuracion_ver", "firmas_ver", "consentimientos_ver", "vacantes_ver", "incapacidades_ver", "roles_ver", "programasalud_ver", "diagrama_ver"
        ];
    } else if (nombreatributo == 'columna_actualizar') {
        console.log('Marcaremos toda la columna: ' + nombreatributo);
        atributos = [
            "empresas_actualizar", "turnos_actualizar", "trabajadores_actualizar", "historial_actualizar", "puestos_actualizar", "requerimientos_actualizar", "cargatrabajadores_actualizar", "consultas_actualizar", "historias_actualizar", "diagnosticoscie_actualizar", "nordicos_actualizar", "antropometricos_actualizar", "medicamentos_actualizar", "medicamentosstockmin_actualizar", "medicamentosstock_actualizar", "medicamentosbitacora_actualizar", "epp_actualizar", "eppsstockmin_actualizar", "eppsbitacora_actualizar", "poes_actualizar", "categoriaestudio_actualizar", "estudios_actualizar", "ordenpoe_actualizar", "cargapoes_actualizar", "usuarios_actualizar", "configuracion_actualizar", "firmas_actualizar", "consentimientos_actualizar", "vacantes_actualizar", "incapacidades_actualizar", "roles_actualizar", "programasalud_actualizar", "historias_corregir", "diagrama_actualizar"
        ];
    } else if (nombreatributo == 'columna_expediente') {
        console.log('Marcaremos toda la columna: ' + nombreatributo);
        atributos = [
            "trabajadores_expediente", "programasalud_hc"
        ];
    } else if (nombreatributo == 'columna_cerrarexpediente') {
        console.log('Marcaremos toda la columna: ' + nombreatributo);
        atributos = [
            "historias_cerrarexpediente"
        ];
    } else if (nombreatributo == 'columna_eliminar') {
        console.log('Marcaremos toda la columna: ' + nombreatributo);
        atributos = [
            "cargatrabajadores_eliminar", "cargapoes_eliminar", "historias_delete", "trabajadores_delete"
        ];
    } else if (nombreatributo == 'columna_entrega') {
        console.log('Marcaremos toda la columna: ' + nombreatributo);
        atributos = [
            "poes_entrega"
        ];
    } else if (nombreatributo == 'columna_documento') {
        console.log('Marcaremos toda la columna: ' + nombreatributo);
        atributos = [
            "poes_documento"
        ];
    } else if (nombreatributo == 'linea_1') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "empresas_listado", "empresas_exportar", "empresas_crear", "empresas_ver", "empresas_actualizar"
        ];
    } else if (nombreatributo == 'linea_2') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "turnos_listado", "turnos_ver", "turnos_actualizar"
        ];
    } else if (nombreatributo == 'linea_3') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "firmas_listado", "firmas_exportar", "firmas_crear", "firmas_ver", "firmas_actualizar"
        ];
    } else if (nombreatributo == 'linea_4') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "consentimientos_listado", "consentimientos_exportar", "consentimientos_crear", "consentimientos_ver", "consentimientos_actualizar"
        ];
    } else if (nombreatributo == 'linea_5') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "trabajadores_listado", "trabajadores_exportar", "trabajadores_crear", "trabajadores_delete", "trabajadores_ver", "trabajadores_actualizar", "trabajadores_expediente"
        ];
    } else if (nombreatributo == 'linea_6') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "historial_listado", "historial_exportar"
        ];
    } else if (nombreatributo == 'linea_7') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "puestos_listado", "puestos_exportar", "puestos_crear", "puestos_ver", "puestos_actualizar"
        ];
    } else if (nombreatributo == 'linea_8') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "vacantes_listado", "vacantes_exportar", "vacantes_crear", "vacantes_ver", "vacantes_actualizar"
        ];
    } else if (nombreatributo == 'linea_9') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "requerimientos_listado", "requerimientos_exportar", "requerimientos_crear", "requerimientos_ver", "requerimientos_actualizar"
        ];
    } else if (nombreatributo == 'linea_10') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "incapacidades_listado", "incapacidades_exportar", "incapacidades_crear", "incapacidades_ver", "incapacidades_actualizar"
        ];
    } else if (nombreatributo == 'linea_11') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "cargatrabajadores_listado", "cargatrabajadores_exportar", "cargatrabajadores_crear", "cargatrabajadores_ver", "cargatrabajadores_eliminar"
        ];
    } else if (nombreatributo == 'linea_12') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "consultas_listado", "consultas_exportar", "consultas_crear", "consultas_ver", "consultas_actualizar"
        ];
    } else if (nombreatributo == 'linea_13') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "historias_listado", "historias_exportar", "historias_crear", "historias_delete", "historias_ver", "historias_actualizar", "historias_cerrarexpediente"
        ];
    } else if (nombreatributo == 'linea_14') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "diagnosticoscie_listado", "diagnosticoscie_exportar", "diagnosticoscie_crear", "diagnosticoscie_ver", "diagnosticoscie_actualizar"
        ];
    } else if (nombreatributo == 'linea_15') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "nordicos_listado", "nordicos_exportar", "nordicos_crear", "nordicos_ver"
        ];
    } else if (nombreatributo == 'linea_16') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "antropometricos_listado", "antropometricos_exportar", "antropometricos_crear", "antropometricos_ver"
        ];
    } else if (nombreatributo == 'linea_17') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "medicamentosstock_listado", "medicamentosstock_exportar"
        ];
    } else if (nombreatributo == 'linea_18') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "medicamentosbitacora_listado", "medicamentosbitacora_exportar", "medicamentosbitacora_crear", "medicamentosbitacora_ver", "medicamentosbitacora_actualizar"
        ];
    } else if (nombreatributo == 'linea_19') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "medicamentos_listado", "medicamentos_exportar", "medicamentos_crear", "medicamentos_ver", "medicamentos_actualizar"
        ];
    } else if (nombreatributo == 'linea_20') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "medicamentosstockmin_listado", "medicamentosstockmin_exportar", "medicamentosstockmin_crear", "medicamentosstockmin_ver", "medicamentosstockmin_actualizar"
        ];
    } else if (nombreatributo == 'linea_21') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "eppsstock_listado", "eppsstock_exportar"
        ];
    } else if (nombreatributo == 'linea_22') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "eppsbitacora_listado", "eppsbitacora_exportar", "eppsbitacora_crear", "eppsbitacora_ver", "eppsbitacora_actualizar"
        ];
    } else if (nombreatributo == 'linea_23') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "epp_listado", "epp_exportar", "epp_crear", "epp_ver", "epp_actualizar"
        ];
    } else if (nombreatributo == 'linea_24') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "eppsstockmin_listado", "eppsstockmin_exportar", "eppsstockmin_crear", "eppsstockmin_ver", "eppsstockmin_actualizar"
        ];
    } else if (nombreatributo == 'linea_25') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "poes_listado", "poes_exportar", 'poes_documento', "poes_crear", "poes_ver", "poes_actualizar", 'poes_entrega',
        ];
    } else if (nombreatributo == 'linea_26') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "estudios_listado", "estudios_exportar", "estudios_crear", "estudios_ver", "estudios_actualizar"
        ];
    } else if (nombreatributo == 'linea_27') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "ordenpoe_listado", "ordenpoe_exportar", "ordenpoe_crear", "ordenpoe_ver", "ordenpoe_actualizar"
        ];
    } else if (nombreatributo == 'linea_28') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "cargapoes_listado", "cargapoes_exportar", "cargapoes_crear", "cargapoes_ver", "cargapoes_eliminar"
        ];
    } else if (nombreatributo == 'linea_29') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "usuarios_listado", "usuarios_exportar", "usuarios_crear", "usuarios_ver", "usuarios_actualizar"
        ];
    } else if (nombreatributo == 'linea_30') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "configuracion_listado", "configuracion_exportar", "configuracion_ver", "configuracion_actualizar"
        ];
    } else if (nombreatributo == 'linea_31') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "categoriaestudio_listado", "categoriaestudio_exportar", "categoriaestudio_crear", "categoriaestudio_ver", "categoriaestudio_actualizar"
        ];
    } else if (nombreatributo == 'linea_32') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "roles_listado", "roles_exportar", "roles_crear", "roles_ver", "roles_actualizar"
        ];
    } else if (nombreatributo == 'linea_33') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "programasalud_listado", "programasalud_exportar", "programasalud_crear", "programasalud_ver", "programasalud_actualizar",
        ];
    } else if (nombreatributo == 'linea_34') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "programasalud_hc"
        ];
    } else if (nombreatributo == 'linea_35') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "historias_corregir"
        ];
    } else if (nombreatributo == 'linea_36') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "diagrama_ver", "diagrama_listado", "diagrama_actualizar"
        ];
    } else if (nombreatributo == 'linea_37') {
        console.log('Marcaremos toda el renglon: ' + nombreatributo);
        atributos = [
            "papelera_listado"
        ];
    }

    atributos.forEach((item, index, array) => {
        console.log('Attributo: ' + item);
        $("input:checkbox[name='Usuarios[" + item + "]']").prop("checked", marcar);
    });
}

function cambiaSolicitante(valor, modelo) {
    console.log('Cambiando el solicitante: ' + valor + ' | modelo: ' + modelo);
    $('#consultas-tipo_padecimiento').empty();
    $('#consultas-tipo').empty();

    $('#consultas-tipo_padecimiento').append('<option value="">Selecciona</option>');
    $('#consultas-tipo').append('<option value="">Selecciona</option>');
    if (valor == 2 || valor == 3) {
        $('#consultas-tipo_padecimiento').append('<option value="3">GENERAL</option>');

        //['3'=>'CLÍNICA','1'=>'ACCIDENTE','7'=>'PROGRAMAS DE SALUD','4'=>'INCAPACIDAD','6'=>'TRABAJOS DE RIESGO','2'=>'ANTIDOPING','5'=>'PSICOLÓGICA','8'=>'COVID-19']
        $('#consultas-tipo').append('<option value="1">ACCIDENTE</option>');
        $('#consultas-tipo').append('<option value="3">CLÍNICA</option>');
        $('#consultas-tipo').append('<option value="6">TRABAJOS DE RIESGO</option>');
        $('#consultas-tipo').append('<option value="2">ANTIDOPING</option>');
        $('#consultas-tipo').append('<option value="5">PREOCUPANTE</option>');
        $('#consultas-tipo').append('<option value="8">COVID-19</option>');

        $(".apartado_nombre").css("display", "block");
        $(".datos_trabajador").css("display", "none");
    } else {
        $('#consultas-tipo_padecimiento').append('<option value="1">LABORAL</option>');
        $('#consultas-tipo_padecimiento').append('<option value="2">NO LABORAL</option>');

        $('#consultas-tipo').append('<option value="1">ACCIDENTE</option>');
        $('#consultas-tipo').append('<option value="3">CLÍNICA</option>');
        $('#consultas-tipo').append('<option value="7">PROGRAMAS DE SALUD</option>');
        $('#consultas-tipo').append('<option value="4">INCAPACIDAD</option>');
        $('#consultas-tipo').append('<option value="6">TRABAJOS DE RIESGO</option>');
        $('#consultas-tipo').append('<option value="2">ANTIDOPING</option>');
        $('#consultas-tipo').append('<option value="5">PREOCUPANTE</option>');
        $('#consultas-tipo').append('<option value="8">COVID-19</option>');
        /*   $('#consultas-tipo').append('<option value="9">NUTRICIÓN</option>');
          $('#consultas-tipo').append('<option value="10">PSICOLÓGICA</option>'); */
        $('#consultas-tipo').append('<option value="11">ALCOHOLEMIA</option>');

        $('#' + modelo + '-nombre').val('');
        $('#' + modelo + '-apellidos').val('');
        $(".apartado_nombre").css("display", "none");
        $(".datos_trabajador").css("display", "block");
    }
}



/* Funciones CUESTIONARIO --------------------------------------------------- */
function getWorkers(e) {
    $('#trabajadores-id_puesto').empty();
    $('#trabajadores-id_area').empty();
    $('#trabajadores-id_area').empty();

    $('#cuestionario-id_pais').empty();
    $('#cuestionario-id_linea').empty();
    $('#cuestionario-id_ubicacion').empty();


    $('#cuestionario-id_nivel1').empty();
    $('#cuestionario-id_nivel2').empty();
    $('#cuestionario-id_nivel3').empty();
    $('#cuestionario-id_nivel4').empty();

    $('#trabajadores-id_area').empty();
    $('#cuestionario-nombre_empresa').empty();

    $.ajax({
        type: 'POST',
        url: './index.php?r=cuestionario%2Fget-workers',
        data: { empresa: e.value },
        success: function(data) {
            let select_pacientes = $("#e_paciente");

            if (data) {
                console.log('ENTRA ********************************************');
                data1 = $.parseJSON(data);
                data2 = JSON.parse(data);
                console.log(data1, data2);

                $('#cuestionario-id_pais').append('<option value="">SELECCIONA--</option>');
                $.each(data1['paises'], function(index, data) {
                    $('#cuestionario-id_pais').append(
                        '<option value="' + index +
                        '">' + data + '</option>');
                });

                select_pacientes.empty().trigger('change').append(new Option("", ""));

                $.each(data1['trabajadores_'], function(index, value) {
                    select_pacientes.append(new Option(index, value));
                });

                $('#trabajadores-id_area').append('<option value="">Selecciona</option>');
                $.each(data1['areas'], function(index, area) {
                    $('#trabajadores-id_area').append(
                        '<option value="' + area
                        .id +
                        '">' + area.area + '</option>');
                });

                $('#trabajadores-id_puesto').append('<option value="">Selecciona</option>');
                $.each(data1['puestos'], function(index, puesto) {
                    $('#trabajadores-id_puesto').append(
                        '<option value="' + puesto
                        .id +
                        '">' + puesto.nombre + '</option>');
                });


                $('#cuestionario-id_nivel1').append('<option value="">Selecciona</option>');
                $.each(data2['nivel1'], function(id, nivel) {
                    console.log('id: ' + id + ' | nivel: ' + nivel);
                    $('#cuestionario-id_nivel1').append(
                        '<option value="' + id +
                        '">' + nivel + '</option>');
                });

                $('#cuestionario-id_nivel2').append('<option value="">Selecciona</option>');
                $.each(data2['nivel2'], function(id, nivel) {
                    $('#cuestionario-id_nivel2').append(
                        '<option value="' + id +
                        '">' + nivel + '</option>');
                });

                $('#cuestionario-id_nivel3').append('<option value="">Selecciona</option>');
                $.each(data2['nivel3'], function(id, nivel) {
                    $('#cuestionario-id_nivel3').append(
                        '<option value="' + id +
                        '">' + nivel + '</option>');
                });

                $('#cuestionario-id_nivel4').append('<option value="">Selecciona</option>');
                $.each(data2['nivel4'], function(id, nivel) {
                    $('#cuestionario-id_nivel4').append(
                        '<option value="' + id +
                        '">' + nivel + '</option>');
                });

                $('#trabajadores-id_area').append('<option value="">Selecciona</option>');
                $.each(data2['areas_trabajador'], function(id, area) {
                    $('#trabajadores-id_area').append(
                        '<option value="' + id +
                        '">' + area + '</option>');
                });


                $('#show_nivel1').css('display', data2['show_nivel1']);
                $('#show_nivel2').css('display', data2['show_nivel2']);
                $('#show_nivel3').css('display', data2['show_nivel3']);
                $('#show_nivel4').css('display', data2['show_nivel4']);

                $('#label_nivel1').html(data2['label_nivel1']);
                $('#label_nivel2').html(data2['label_nivel2']);
                $('#label_nivel3').html(data2['label_nivel3']);
                $('#label_nivel4').html(data2['label_nivel4']);

                $('#cuestionario-name_empresa').val(data2['nombre_empresa']);
                //$('#nombre_empresa').html(data2['nombre_empresa']);


            } else {
                select_pacientes.empty().trigger('change');
            }
        }
    });
}

function loadWorkerData(select, id_tipo_cuestionario) {
    let id_trabajador = select.value;

    $.ajax({
        type: 'POST',
        url: './index.php?r=cuestionario%2Fverificar-cuestionario-trabajador',
        data: {
            id_trabajador: id_trabajador,
            id_tipo_cuestionario: id_tipo_cuestionario
        },
        success: function(data) {

            if (data) {
                let input_nombre = $("#trabajadores-nombre");
                let input_apellidos = $("#trabajadores-apellidos");
                let input_numtrabajador = $("#trabajadores-num_trabajador");
                let input_fechaNacimineto = $("#trabajadores-fecha_nacimiento");
                let input_edad = $("#trabajadores-edad");
                let input_sexo = $("#trabajadores-sexo");
                let input_empresa = $("#trabajadores-empresa");
                let input_puesto = $("#trabajadores-id_puesto");
                let input_area = $("#trabajadores-id_area");
                let input_peso = $("#inp_peso");
                let input_talla = $("#inp_talla");
                let input_imc = $("#inp_imc");

                let input_nivel1 = $("#cuestionario-id_nivel1");
                let input_nivel2 = $("#cuestionario-id_nivel2");
                let input_nivel3 = $("#cuestionario-id_nivel3");
                let input_nivel4 = $("#cuestionario-id_nivel4");

                if (id_trabajador != 0) {
                    $.ajax({
                        type: 'POST',
                        url: './index.php?r=cuestionario%2Fload-worker-data',
                        data: { id_trabajador: id_trabajador },
                        success: function(data) {
                            let empleado = JSON.parse(data);
                            console.log('Estoy aqui en los datos del trabajador');
                            console.log(empleado);

                            input_nombre.val(empleado["nombre"]);
                            input_apellidos.val(empleado["apellidos"]);
                            input_numtrabajador.val(empleado["num_trabajador"]);
                            input_fechaNacimineto.val(empleado["fecha_nacimiento"]);
                            getAge(input_fechaNacimineto);
                            input_sexo.val(empleado["sexo"]);
                            input_empresa.val(empleado["empresa"]);

                            $('#trabajadores-id_puesto').val(empleado["id_puesto"]).trigger('change');
                            $('#trabajadores-id_area').val(empleado["id_area"]).trigger('change');
                            /* input_puesto.val(empleado["id_puesto"]);
                            input_area.val(empleado["id_area"]); */
                            input_peso.val(empleado["peso"]);
                            input_talla.val(empleado["talla"]);
                            input_imc.val(empleado["imc"]);

                            input_nivel1.val(empleado["id_nivel1"]).trigger('change');
                            input_nivel2.val(empleado["id_nivel2"]).trigger('change');
                            input_nivel3.val(empleado["id_nivel3"]).trigger('change');
                            input_nivel4.val(empleado["id_nivel4"]).trigger('change');
                        }
                    });
                } else {
                    console.log('es 0');
                    input_nombre.val(null);
                    input_apellidos.val(null);
                    input_fechaNacimineto.val(null);
                    input_edad.val(null);
                    input_sexo.val(null);
                    input_empresa.val(null);
                    input_puesto.val(null);
                    input_area.val(null);
                    input_peso.val(null);
                    input_talla.val(null);
                    input_imc.val(null);
                }
            } else {
                Swal.fire('Ya se realizo el cuestionario!', 'El cuestionario ya fue aplicado a este trabajador, no es posible volverlo a aplicar.', 'info');

                // reinicializa el plugin
                var sel = $("#e_paciente");
                sel.val("");
                settings = sel.attr('data-krajee-select2');
                settings = window[settings];
                sel.select2(settings);
            }
        }
    });
}

function cargarFirma(id_medico) {
    let element = id_medico.value;

    $.ajax({
        type: 'POST',
        url: './index.php?r=cuestionario%2Ffirma',
        data: 'id_=' + element,
        success: function(data) {
            let ruta_firma = JSON.parse(data);
            let img_e = document.getElementById("frm_med");

            img_e.src = ruta_firma;
        },
    });
}

function getAge(element) {
    let dateBirthday = new Date(element.val());
    let input_age = $("#trabajadores-edad");
    let now = new Date();

    let diffYears = now.getFullYear() - dateBirthday.getFullYear();

    if (dateBirthday.getMonth() > now.getMonth()) {
        diffYears--;
    } else if (dateBirthday.getMonth() == now.getMonth()) {
        if ((now.getDate() + 1) > now.getDate()) {
            diffYears--;
        }
    }

    input_age.val(diffYears);
}


window.onload = function() {

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    let formulario = $("#cuestionario-id_form").val();

    if (formulario == 1) {
        console.log("Inicio de renderizado, formulario: " + formulario);
        for (let i = 1; i < 12; i++) {
            let className = ".field-p-1_a-" + i;
            let chkList = document.querySelector(className);

            if (chkList) {
                chkList.style = "display:none";
            }

            //Estado formularios renderizados filtro 1
            for (let ar = 1; ar < 12; ar++) { //areas
                let name = "DetalleCuestionario[_preguntas][p0][" + ar + "]";
                let val = $("input[type=radio][name='" + name + "']:checked").val();

                for (let pr = 1; pr < 12; pr++) {
                    let id_elemento = "p-" + pr + "_a-" + ar;
                    let disp;

                    elementos = document.getElementById(id_elemento);

                    if (elementos) {
                        //otros controles
                        if (pr == 2 || pr == 11) {
                            let input_text = document.getElementById(id_elemento);

                            if (val == "No") {
                                //console.log("input:", input_text, );
                                input_text.removeAttribute("required");
                            } else if (val == "Si") {
                                input_text.setAttribute("required", true);
                            }
                        } else {
                            let name_radios = "DetalleCuestionario[_preguntas][p" + pr + "][" + ar + "]";
                            let element_radios = document.getElementsByName(name_radios);

                            if (val == "Si") {
                                $("input[type=radio][name='" + name_radios + "']").attr("required", true);
                            } else if (val == "No") {
                                $("input[type=radio][name='" + name_radios + "']").attr("required", false);
                            }
                        }
                        //fin otros

                        if (val == "No") {
                            disp = "none";
                        } else if (val == "Si") {
                            disp = "block";
                        }

                        bloque = elementos.parentNode;
                        bloque.style.display = disp;
                    }
                }
            }

            //Estado formularios renderizados filtro 2
            for (let ar2 = 5; ar2 < 12; ar2++) {
                let name = "DetalleCuestionario[_preguntas][p4][" + ar2 + "]";
                let val = $("input[type=radio][name='" + name + "']:checked").val();

                for (let pr2 = 5; pr2 < 12; pr2++) {
                    let id_elemento = "p-" + pr2 + "_a-" + ar2;
                    let disp;
                    let bloque;

                    elementos = document.getElementById(id_elemento);

                    if (elementos) {
                        //otros controles
                        if (pr2 == 11) {
                            let input_text = document.getElementById(id_elemento);
                            //console.log(input_text, id_elemento);

                            if (val == "No") {
                                input_text.removeAttribute("required");
                            } else if (val == "Si") {
                                input_text.setAttribute("required", true);
                            }
                        } else {
                            let name_radios = "DetalleCuestionario[_preguntas][p" + pr2 + "][" + ar2 + "]";
                            let element_radios = document.getElementsByName(name_radios);

                            if (val == "Si") {
                                $("input[type=radio][name='" + name_radios + "']").attr("required", true);
                            } else if (val == "No") {
                                $("input[type=radio][name='" + name_radios + "']").attr("required", false);
                            }
                        }
                        //fin otros

                        if (val == "No") {
                            disp = "none";
                        } else if (val == "Si") {
                            disp = "block";
                        }

                        bloque = elementos.parentNode;
                        bloque.style.display = disp;
                    }
                }
            }
        }

        let input_dateBirthday = $("#listadotrabajadores-fecha_nacimiento");
        if (input_dateBirthday) {
            getAge(input_dateBirthday);
        }

        let select_empleado = document.getElementById("pacientes-no_empleado");

        // console.log(select_empleado);

        if (select_empleado) {

            let val = select_empleado.value;
            console.log("valor:" + select_empleado.value);
            if (val === "0") {

                let aux_no_control = document.getElementById("content_no_empleado");
                console.log(aux_no_control);
                aux_no_control.style.display = "block";
            }
        }
    }
}

function filtro_uno(_this) {
    // console.log(_this.id);
    let id_areas = _this.id.split("a-")[1];
    let name = "DetalleCuestionario[_preguntas][p0][" + id_areas + "]";
    let val = $("input[type=radio][name='" + name + "']:checked").val();

    //requerir o no los radios de la pregunta 1
    let name_radio = "DetalleCuestionario[_preguntas][p1][" + id_areas + "]";
    let element_radio = document.getElementsByName(name_radio);
    // console.log(element_radio, $("input[type=radio][name='"+name_radio+"']"));

    $("input[type=radio][name='" + name_radio + "']").attr("title", "Opción");

    // if (val == "Si") {
    //     $("input[type=radio][name='"+name_radio+"']").attr("required", true);
    // } else if (val == "No"){
    //     $("input[type=radio][name='"+name_radio+"']").attr("required", false);
    // }

    for (let pr = 1; pr < 12; pr++) {
        let id_elemento = "p-" + pr + "_a-" + id_areas;
        let disp;
        let bloque;

        elementos = document.getElementById(id_elemento);

        //otros controles
        if (pr == 2 || pr == 11) {
            let input_text = document.getElementById(id_elemento);

            if (val == "No") {
                //console.log("input:", input_text, );
                input_text.removeAttribute("required");
            } else if (val == "Si") {
                input_text.setAttribute("required", true);
            }
        } else {
            let name_radios = "DetalleCuestionario[_preguntas][p" + pr + "][" + id_areas + "]";
            let element_radios = document.getElementsByName(name_radios);

            if (val == "Si") {
                $("input[type=radio][name='" + name_radios + "']").attr("required", true);
            } else if (val == "No") {
                $("input[type=radio][name='" + name_radios + "']").attr("required", false);
            }
        }
        //fin otros

        if (val == "No") {
            disp = "none";
        } else if (val == "Si") {
            disp = "block";
        }

        bloque = elementos.parentNode;
        bloque.style.display = disp;
    }
}

function filtro_dos(_this) {
    let id_areas = _this.id.split("a-")[1];
    let name = "DetalleCuestionario[_preguntas][p4][" + id_areas + "]";
    let val = $("input[type=radio][name='" + name + "']:checked").val();

    for (let pr = 5; pr < 12; pr++) {
        let id_elemento = "p-" + pr + "_a-" + id_areas;
        let disp;
        let bloque;

        elementos = document.getElementById(id_elemento);

        //otros controles
        if (pr == 11) {
            let input_text = document.getElementById(id_elemento);
            // console.log(input_text, id_elemento);

            if (val == "No") {
                input_text.removeAttribute("required");
            } else if (val == "Si") {
                input_text.setAttribute("required", true);
            }
        } else {
            let name_radios = "DetalleCuestionario[_preguntas][p" + pr + "][" + id_areas + "]";
            let element_radios = document.getElementsByName(name_radios);

            if (val == "Si") {
                $("input[type=radio][name='" + name_radios + "']").attr("required", true);
            } else if (val == "No") {
                $("input[type=radio][name='" + name_radios + "']").attr("required", false);
            }
        }
        //fin otros

        if (val == "No") {
            disp = "none";
        } else if (val == "Si") {
            disp = "block";
        }

        bloque = elementos.parentNode;
        bloque.style.display = disp;
    }
}


function imc() {
    console.log('CALCULANDO IMC');
    let inp_imc = $("#inp_imc");
    let peso = $("#inp_peso").val();
    let talla = $("#inp_talla").val();

    if ((peso != null && peso > 0) && (talla != null && talla > 0)) {
        let altura = (talla / 100) ** 2;
        let imc = peso / altura;
        console.log(talla, altura, peso, imc);
        inp_imc.val(imc.toFixed(2));
    } else {
        inp_imc.val(0);
    }
}

function valideKey(evt) {
    let code = (evt.which) ? evt.which : evt.keyCode;

    if (code == 8) {
        return true;
    } else if (code >= 48 && code <= 57) {
        return true;
    } else if (code == 46) {
        return true;
    } else {
        return false;
    }
}


function cambiaInsumo(valor) {
    console.log('Cambiando el insumo: ' + valor);
    $('#insumostockmin-stock_unidad').empty();
    $('#insumostockmin-cantidad_caja').empty();

    var stock = $('#insumostockmin-stock').val();
    var cantidad_individual = 1;

    var base = 'index.php?r=insumostockmin%2Finfoinsumo';

    if (valor != '' && valor != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id: valor,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data["insumo"]["unidades_individuales"]);

                    var unidadescaja = 1;
                    if (data["insumo"]["unidades_individuales"] == null) {
                        $('#insumostockmin-cantidad_caja').val(1);
                    } else {
                        $('#insumostockmin-cantidad_caja').val(data["insumo"]["unidades_individuales"]);
                        unidadescaja = data["insumo"]["unidades_individuales"];
                    }

                    if (stock != '' && stock != ' ' && stock != null) {
                        cantidad_individual = stock * unidadescaja;
                    } else {
                        $('#insumostockmin-stock').val(1);
                        cantidad_individual = unidadescaja;
                    }
                    $('#insumostockmin-stock_unidad').val(cantidad_individual);
                }
            }
        });
    } else {

    }
}

function cambiaStockmin(valor) {
    console.log('Cambiando el insumo: ' + valor);
    $('#insumostockmin-stock_unidad').empty();

    var cantidad_caja = $('#insumostockmin-cantidad_caja').val();
    var stock = $('#insumostockmin-stock').val();
    var cantidad_individual = 1;

    if (stock != '' && stock != ' ' && stock != null && stock != 0) {} else {
        stock = 1;
        $('#insumostockmin-stock').val(1);
    }

    if (cantidad_caja != '' && cantidad_caja != ' ' && cantidad_caja != null && cantidad_caja != 0) {
        cantidad_individual = stock * cantidad_caja;
    } else {
        $('#insumostockmin-cantidad_caja').val(1);
        cantidad_individual = cantidad_caja;
    }

    console.log('stock: ' + stock + ' | cantidad_caja' + cantidad_caja + ' | cantidad individual: ' + cantidad_individual);
    $('#insumostockmin-stock_unidad').val(cantidad_individual);
}


$("#limpiarsearch").on("click", function(e) {
    e.preventDefault();
    console.log('estoy limpiando el search owo');

    $('#cuestionariosearch-rango1desde').val('');
    $('#cuestionariosearch-rango1hasta').val('');
    $('#cuestionariosearch-rango2desde').val('');
    $('#cuestionariosearch-rango2hasta').val('');
    $('#cuestionariosearch-rango3desde').val('');
    $('#cuestionariosearch-rango3hasta').val('');
    $('#cuestionariosearch-rango5desde').val('');
    $('#cuestionariosearch-rango5hasta').val('');

    $('#cuestionariosearch-filtro1').val('').trigger('change');
    $('#cuestionariosearch-filtro2').val('').trigger('change');
    $('#cuestionariosearch-filtro3').val('').trigger('change');
    $('#cuestionariosearch-rango4').val('').trigger('change');

    $('#poessearch-categoria').val('').trigger('change');
    $('#poessearch-entrega').val('').trigger('change');
    $('#poessearch-documento').val('').trigger('change');

    $('#buscarsearch').trigger('click');
});


function todosTrabajadores1(nombreatributo) {
    atributo = $("input:checkbox[name='Usuarios[" + nombreatributo + "]']:checked").val();
    let atributos = [];

    let marcar = false;
    console.log(nombreatributo + ': ' + atributo);
    if (atributo == '1') {
        console.log('Esta checked');
        marcar = true;
    } else {
        console.log('No esta checked');
        marcar = false;
    }

    atributos = [
        "usuarios_listado", "usuarios_exportar", "usuarios_crear", "usuarios_ver", "usuarios_actualizar"
    ];

    atributos.forEach((item, index, array) => {
        console.log('Attributo: ' + item);
        $("input:checkbox[name='Ordenespoes[" + item + "]']").prop("checked", marcar);
    });
}

$('button[name="ver_tutorial"]').click(function(e) {
    e.preventDefault();
    //var embarque_id = $(this).closest('a').attr('id');
    $('#modal-titulo').html('Carga Masiva Listado de Trabajadores');
    $('#modal-tutorial').modal('show').find('#body-tutorial').load($(this).attr('value'));

});

$('button[name="ver_excel"]').click(function(e) {
    e.preventDefault();
    //var embarque_id = $(this).closest('a').attr('id');
    $('#modal-titulo').html('Archivo Excel');
    $('#modal-tutorial').modal('show').find('#body-tutorial').load($(this).attr('value'));

});


function cambiaStatusentrega() {
    atributo = $("input:checkbox[name='Poes[cerrar_entrega]']:checked").val();

    let marcar = false;
    console.log(atributo);
    if (atributo == '1') {
        console.log('Esta checked');
        marcar = true;
    } else {
        console.log('No esta checked');
        marcar = false;
    }

    document.getElementById('enviarform').style.display = 'none';
    if (marcar) {
        document.getElementById('enviarform').style.display = 'block';
    }

}


function vencimientoIncapacidad() {

    var fecha_inicio = $('#consultas-incapacidad_fechainicio').val();
    var dias = $('#consultas-incapacidad_dias').val();

    $('#consultas-incapacidad_fechafin').val('');

    console.log('Fecha Inicio: ' + fecha_inicio + ' | Días: ' + dias);

    var base = 'index.php?r=consultas%2Fcalculafecha';
    //valor != '' && valor != null
    if (true) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                fecha_inicio: fecha_inicio,
                dias: dias,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data);

                    $('#consultas-incapacidad_fechafin').val(data['fechafin']);
                }
            }
        });
    } else {

    }
}


function cambiaStatus(valor, modelo) {
    console.log('Cambiando el status: ' + valor + ' | modelo: ' + modelo);

    $("#bloquestatus").css("display", "none");

    if (valor == "2") {
        $("#bloquestatus").css("display", "flex");
    }
}

$(".agregaformato").on("click", function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    var previa = $('#textoprevio').html();
    var consentimiento = $('#configconsentimientos-texto_consentimiento').val();
    var titulo = $('#configconsentimientos-titulo').val();
    var parrafo = $('#configconsentimientos-parrafo').val();
    var atributo = '';
    if (id == 0) { //Salto de línea
        atributo = '<br>';
    } else if (id == 1) { //Titulo
        atributo = '<h1 class="title1 text-center">' + titulo + '</h1><br>';
    } else if (id == 2) { //Párrafo
        //atributo = '<p class="text-justify">' + parrafo + '</p>';
        atributo = parrafo;
    } else if (id == 3) { //Fecha
        atributo = '<span class="font600 mx-1 color3">{fecha}</span>';
    } else if (id == 4) { //Hora
        atributo = '<span class="font600 mx-1 color3">{hora}</span>';
    } else if (id == 5) { //Nombre Comercial
        atributo = '<span class="font600 mx-1 color3">{comercial}</span>';
    } else if (id == 6) { //Razón Social
        atributo = '<span class="font600 mx-1 color3">{razon}</span>';
    } else if (id == 7) { //RFC
        atributo = '<span class="font600 mx-1 color3">{rfc}</span>';
    } else if (id == 8) { //Teléfono
        atributo = '<span class="font600 mx-1 color3">{telefono}</span>';
    } else if (id == 9) { //Correo
        atributo = '<span class="font600 mx-1 color3">{correo}</span>';
    } else if (id == 10) { //Nombre Completo
        atributo = '<span class="font600 mx-1 color3">{nombre_completo}</span>';
    } else if (id == 11) { //Área
        atributo = '<span class="font600 mx-1 color3">{area}</span>';
    } else if (id == 12) { //Puesto
        atributo = '<span class="font600 mx-1 color3">{puesto}</span>';
    } else if (id == 13) { //Fecha de Nacimiento
        atributo = '<span class="font600 mx-1 color3">{fecha_nacimiento}</span>';
    } else if (id == 14) { //Edad
        atributo = '<span class="font600 mx-1 color3">{edad}</span>';
    } else if (id == 15) { //Sexo
        atributo = '<span class="font600 mx-1 color3">{sexo}</span>';
    } else if (id == 'clean') { //Limpiar
        previa = '';
    }

    $('#textoprevio').html(previa + atributo);
    $('#configconsentimientos-titulo').val('');
    $('#configconsentimientos-parrafo').val('');

    $('#configconsentimientos-texto_consentimiento').val($('#textoprevio').html());

    console.log('estoy picando botones: ' + id);
    //$('#trabajadores-envia_form').val('1');

});


function cambiaStatususuario(valor) {
    var rol = $('#usuarios-rol').val();
    var empresa = $('#usuarios-id_empresa').val();

    console.log('Rol: ' + rol + ' | status: ' + valor);
    if (valor === '1') {

        //Checar si hay espacio disponible
        var base = 'index.php?r=usuarios%2Fchecarstatus';
        if (valor != '' && valor != null) {
            $.ajax({
                url: base,
                type: 'post',
                data: {
                    id_rol: rol,
                    id_empresa: empresa,
                    _csrf: yii.getCsrfToken()
                },
                success: function(data) {
                    if (data) {

                        data = $.parseJSON(data);
                        console.log(data);
                        if (data['disponible'] == false) {

                            Swal.fire({ icon: "error", title: "No se puede activar este usuario", html: "<span style=\'\'>Ha alcanzado el límite de usuarios " + data['rol'] + " activos. <br>Solicite aumentar el número de usuarios, o dar de baja alguno resistrado</span>" });
                            $("#usuarios-status").val('2').trigger('change');
                        }

                    }
                }
            });
        } else {

        }
    }
}


function cambiaCuerpo(valor, modelo) {
    console.log('Cambiando el valor: ' + valor + ' | modelo: ' + modelo);
    $('#insumos-talla').empty();
    //['1'=>'Cabeza','2'=>'Cuerpo','3'=>'Camisa','4'=>'Pantalón','5'=>'Manos-Guantes','6'=>'Calzado']

    $('#insumos-talla').append('<option value="">Selecciona</option>');
    if (valor === '1' || valor === '5') {
        //['100'=>'XXS','101'=>'S','102'=>'M','103'=>'L','104'=>'XL','105'=>'XXL']
        $('#insumos-talla').append('<option value="100">XXS</option>');
        $('#insumos-talla').append('<option value="101">S</option>');
        $('#insumos-talla').append('<option value="102">M</option>');
        $('#insumos-talla').append('<option value="103">L</option>');
        $('#insumos-talla').append('<option value="104">XL</option>');
        $('#insumos-talla').append('<option value="105">XXL</option>');
    } else if (valor === '2' || valor === '3' || valor === '4') {
        //['1'=>'MEX 20 | US 0 | EUR 30 | INTER XXS','2'=>'MEX 22 | US 2 | EUR 30 | INTER XXS','3'=>'MEX 26 | US 6 | EUR 32 | INTER S','4'=>'MEX 28 | US 8 | EUR 34 | INTER M','5'=>'MEX 30 | US 10 | EUR 34 | INTER M','6'=>'MEX 32 | US 12 | EUR 36 | INTER L','7'=>'MEX 36 | US 14 | EUR 36 | INTER L','8'=>'MEX 38 | US 16 | EUR 38 | INTER XL','9'=>'MEX 40 | US 18 | EUR 38 | INTER XL','10'=>'MEX 42 | US 18 | EUR 40 | INTER XXL','11'=>'MEX 44 | US 20 | EUR 40 | INTER XXL',]
        $('#insumos-talla').append('<option value="1">MEX 20 | US 0 | EUR 30 | INTER XXS</option>');
        $('#insumos-talla').append('<option value="2">MEX 22 | US 2 | EUR 30 | INTER XXS</option>');
        $('#insumos-talla').append('<option value="3">MEX 26 | US 6 | EUR 32 | INTER S</option>');
        $('#insumos-talla').append('<option value="4">MEX 28 | US 8 | EUR 34 | INTER M</option>');
        $('#insumos-talla').append('<option value="5">MEX 30 | US 10 | EUR 34 | INTER M</option>');
        $('#insumos-talla').append('<option value="6">MEX 32 | US 12 | EUR 36 | INTER L</option>');
        $('#insumos-talla').append('<option value="7">MEX 36 | US 14 | EUR 36 | INTER L</option>');
        $('#insumos-talla').append('<option value="8">MEX 38 | US 16 | EUR 38 | INTER XL</option>');
        $('#insumos-talla').append('<option value="9">MEX 40 | US 18 | EUR 38 | INTER XL</option>');
        $('#insumos-talla').append('<option value="10">MEX 42 | US 18 | EUR 40 | INTER XXL</option>');
        $('#insumos-talla').append('<option value="11">MEX 44 | US 20 | EUR 40 | INTER XXL</option>');
    } else if (valor === '6') {
        //['200'=>'2','201'=>'2.5','202'=>'3','203'=>'3.5','204'=>'4','205'=>'4.5','206'=>'5','207'=>'5.5','208'=>'6','209'=>'6.5','210'=>'7','211'=>'7.5','212'=>'8','213'=>'8.5','214'=>'9','215'=>'9.5','216'=>'10','217'=>'10.5','218'=>'11','219'=>'11.5','220'=>'12','221'=>'12.5','222'=>'13']
        $('#insumos-talla').append('<option value="200">2</option>');
        $('#insumos-talla').append('<option value="201">2.5</option>');
        $('#insumos-talla').append('<option value="202">3</option>');
        $('#insumos-talla').append('<option value="203">3.5</option>');
        $('#insumos-talla').append('<option value="204">4</option>');
        $('#insumos-talla').append('<option value="205">4.5</option>');
        $('#insumos-talla').append('<option value="206">5</option>');
        $('#insumos-talla').append('<option value="207">5.5</option>');
        $('#insumos-talla').append('<option value="208">6</option>');
        $('#insumos-talla').append('<option value="209">6.5</option>');
        $('#insumos-talla').append('<option value="210">7</option>');
        $('#insumos-talla').append('<option value="211">7.5</option>');
        $('#insumos-talla').append('<option value="212">8</option>');
        $('#insumos-talla').append('<option value="213">8.5</option>');
        $('#insumos-talla').append('<option value="214">9</option>');
        $('#insumos-talla').append('<option value="215">9.5</option>');
        $('#insumos-talla').append('<option value="216">10</option>');
        $('#insumos-talla').append('<option value="217">10.5</option>');
        $('#insumos-talla').append('<option value="218">11</option>');
        $('#insumos-talla').append('<option value="219">11.5</option>');
        $('#insumos-talla').append('<option value="220">12</option>');
        $('#insumos-talla').append('<option value="221">12.5</option>');
        $('#insumos-talla').append('<option value="222">13</option>');
    }
}


function cambiaEppitem(valor, id) {

    var talla = id.replace("trabajadores-aux_epps-", "trabajadores-aux_tallas-");
    var talla2 = id.replace("trabajadores-aux_epps-", "trabajadores-aux_tallas2-");
    console.log('Epp: ' + valor + ' | id select: ' + id + ' | id talla: ' + talla);

    $('#' + talla).empty();
    $('#' + talla2).empty();
    if (valor) {

        //Checar si hay espacio disponible
        var base = 'index.php?r=insumos%2Fchecarinsumo';
        if (valor != '' && valor != null) {
            $.ajax({
                url: base,
                type: 'post',
                data: {
                    epp: valor,
                    _csrf: yii.getCsrfToken()
                },
                success: function(data) {
                    if (data) {

                        data = $.parseJSON(data);
                        console.log(data);

                        $('#' + talla).append('<option value="">SELECCIONE...</option>');
                        $('#' + talla2).append('<option value="">SELECCIONE...</option>');

                        $.each(data['medidas'], function(index, item) {
                            $('#' + talla).append(
                                '<option value="' + index +
                                '">' + item + '</option>');

                            $('#' + talla2).append(
                                '<option value="' + index +
                                '">' + item + '</option>');
                        });

                        $('#' + talla).val(data['epp']['talla']).trigger('change');
                        $('#' + talla2).val(data['epp']['talla']).trigger('change');

                    }
                }
            });
        } else {

        }
    }
}

function cambiaCarga(valor, modelo) {
    console.log('Modelo: ' + modelo + ' | valor: ' + valor);
    $('#g1').addClass("bgnocumple");
    $('#g2').addClass("bgnocumple");
    $('#g3').addClass("bgnocumple");
    $('#g4').addClass("bgnocumple");
    $('#g5').addClass("bgnocumple");
    $('#g6').addClass("bgnocumple");
    $('#g7').addClass("bgnocumple");
    $('#e1').addClass("bgnocumple");
    $('#e2').addClass("bgnocumple");
    $('#e3').addClass("bgnocumple");
    $('#e4').addClass("bgnocumple");
    $('#e5').addClass("bgnocumple");
    $('#e6').addClass("bgnocumple");
    $('#e7').addClass("bgnocumple");
    $('#c1').addClass("bgnocumple");
    $('#c2').addClass("bgnocumple");
    $('#c3').addClass("bgnocumple");
    $('#c4').addClass("bgnocumple");
    $('#c5').addClass("bgnocumple");
    $('#c6').addClass("bgnocumple");
    $('#c7').addClass("bgnocumple");

    if (valor === '1') {
        $('#g1').addClass("bgcumple").removeClass("bgnocumple");
        $('#g2').addClass("bgcumple").removeClass("bgnocumple");
        $('#g3').addClass("bgcumple").removeClass("bgnocumple");
        $('#g4').addClass("bgcumple").removeClass("bgnocumple");
        $('#g5').addClass("bgcumple").removeClass("bgnocumple");
        $('#g6').addClass("bgcumple").removeClass("bgnocumple");
        $('#g7').addClass("bgcumple").removeClass("bgnocumple");
        $('#e1').addClass("bgcumple").removeClass("bgnocumple");
        $('#e2').addClass("bgcumple").removeClass("bgnocumple");
        $('#e3').addClass("bgcumple").removeClass("bgnocumple");
        $('#e4').addClass("bgcumple").removeClass("bgnocumple");
        $('#e5').addClass("bgcumple").removeClass("bgnocumple");
        $('#e6').addClass("bgcumple").removeClass("bgnocumple");
        $('#e7').addClass("bgcumple").removeClass("bgnocumple");
        $('#c1').addClass("bgcumple").removeClass("bgnocumple");
        $('#c2').addClass("bgcumple").removeClass("bgnocumple");
        $('#c3').addClass("bgcumple").removeClass("bgnocumple");
        $('#c4').addClass("bgcumple").removeClass("bgnocumple");
        $('#c5').addClass("bgcumple").removeClass("bgnocumple");
        $('#c6').addClass("bgcumple").removeClass("bgnocumple");
        $('#c7').addClass("bgcumple").removeClass("bgnocumple");
        $('#' + modelo + '-sexo').val(2).trigger('change');
        $('#' + modelo + '-edaddesde').val(1).trigger('change');
    } else if (valor === '2') {
        $('#g3').addClass("bgcumple").removeClass("bgnocumple");
        $('#g4').addClass("bgcumple").removeClass("bgnocumple");
        $('#g5').addClass("bgcumple").removeClass("bgnocumple");
        $('#g6').addClass("bgcumple").removeClass("bgnocumple");
        $('#g7').addClass("bgcumple").removeClass("bgnocumple");
        $('#e3').addClass("bgcumple").removeClass("bgnocumple");
        $('#e4').addClass("bgcumple").removeClass("bgnocumple");
        $('#e5').addClass("bgcumple").removeClass("bgnocumple");
        $('#e6').addClass("bgcumple").removeClass("bgnocumple");
        $('#e7').addClass("bgcumple").removeClass("bgnocumple");
        $('#c3').addClass("bgcumple").removeClass("bgnocumple");
        $('#c4').addClass("bgcumple").removeClass("bgnocumple");
        $('#c5').addClass("bgcumple").removeClass("bgnocumple");
        $('#c6').addClass("bgcumple").removeClass("bgnocumple");
        $('#c7').addClass("bgcumple").removeClass("bgnocumple");
        $('#' + modelo + '-sexo').val(3).trigger('change');
        $('#' + modelo + '-edaddesde').val(2).trigger('change');
    } else if (valor === '3') {
        $('#g4').addClass("bgcumple").removeClass("bgnocumple");
        $('#g5').addClass("bgcumple").removeClass("bgnocumple");
        $('#g6').addClass("bgcumple").removeClass("bgnocumple");
        $('#g7').addClass("bgcumple").removeClass("bgnocumple");
        $('#e4').addClass("bgcumple").removeClass("bgnocumple");
        $('#e5').addClass("bgcumple").removeClass("bgnocumple");
        $('#e6').addClass("bgcumple").removeClass("bgnocumple");
        $('#e7').addClass("bgcumple").removeClass("bgnocumple");
        $('#c4').addClass("bgcumple").removeClass("bgnocumple");
        $('#c5').addClass("bgcumple").removeClass("bgnocumple");
        $('#c6').addClass("bgcumple").removeClass("bgnocumple");
        $('#c7').addClass("bgcumple").removeClass("bgnocumple");
        $('#' + modelo + '-sexo').val(5).trigger('change');
        $('#' + modelo + '-edaddesde').val(4).trigger('change');
    } else if (valor === '4') {
        $('#g4').addClass("bgcumple").removeClass("bgnocumple");
        $('#g5').addClass("bgcumple").removeClass("bgnocumple");
        $('#g7').addClass("bgcumple").removeClass("bgnocumple");
        $('#e4').addClass("bgcumple").removeClass("bgnocumple");
        $('#e5').addClass("bgcumple").removeClass("bgnocumple");
        $('#e7').addClass("bgcumple").removeClass("bgnocumple");
        $('#c4').addClass("bgcumple").removeClass("bgnocumple");
        $('#c5').addClass("bgcumple").removeClass("bgnocumple");
        $('#c7').addClass("bgcumple").removeClass("bgnocumple");
        $('#' + modelo + '-sexo').val(6).trigger('change');
        $('#' + modelo + '-edaddesde').val(5).trigger('change');
    } else if (valor === '5') {
        $('#g5').addClass("bgcumple").removeClass("bgnocumple");
        $('#e5').addClass("bgcumple").removeClass("bgnocumple");
        $('#c5').addClass("bgcumple").removeClass("bgnocumple");
        $('#' + modelo + '-sexo').val(6).trigger('change');
        $('#' + modelo + '-edaddesde').val(5).trigger('change');
    }
}

function cambiaCargapuesto(valor) {
    var carga = null;
    console.log('La carga del puesto es: ' + valor);
    if (valor > 20 && valor <= 25) {
        carga = 5;
        console.log('carga de 25 | id_carga: ' + carga);
    } else if (valor > 15 && valor <= 20) {
        carga = 4;
        console.log('carga de 20 | id_carga: ' + carga);
    } else if (valor > 10 && valor <= 15) {
        carga = 3;
        console.log('carga de 15 | id_carga: ' + carga);
    } else if (valor > 7 && valor <= 10) {
        carga = 2;
        console.log('carga de 10 | id_carga: ' + carga);
    } else if (valor > 0 && valor <= 7) {
        carga = 1;
        console.log('carga de 7 | id_carga: ' + carga);
    } else {
        console.log('No encaja en ninguno');
    }

    $('#puestostrabajo-cargamaxima').val(carga).trigger('change');
}


$("#guardarEstudios").on("click", function(e) {
    e.preventDefault();

    var todos_estudios = $('.elemento').map(function() {
        return this.innerHTML;
    }).get();

    var formData = new FormData($('form[id="formOHC"]')[0]);

    console.log(formData);
    formData.append('listadoestudios', JSON.stringify(todos_estudios));
    var url = $('form[id="formOHC"]').attr('action');

    $.ajax({
        type: 'POST',
        url: url,
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.redirect) {
                window.location.href = response.redirect;
            } else {
                swal({
                    title: '¡Error al ordenar!',
                    text: 'No se completó el guardado, verifique los datos ingresados',
                    icon: 'error',
                });
            }
        }
    });

    //$("#formOHC").submit();
});


$('button[name="card"]').click(function(e) {
    e.preventDefault();
    var ot_id = $(this).closest('button').attr('id');
    $('#modal-card').modal('show').find('#body-card').load($(this).attr('value'));
});

function cambiaTalla1(valor, talla) {
    $("#bloque_cabezaotro").css("display", "none");

    if (valor == 0) {
        console.log('Parte del cuerpo: ' + talla + ' | Valor: ' + valor);
        $("#bloque_cabezaotro").css("display", "block");
    }
}

function cambiaTalla2(valor, talla) {
    $("#bloque_superiorotro").css("display", "none");

    if (valor == 0) {
        console.log('Parte del cuerpo: ' + talla + ' | Valor: ' + valor);
        $("#bloque_superiorotro").css("display", "block");
    }
}

function cambiaTalla3(valor, talla) {
    $("#bloque_inferiorotro").css("display", "none");

    if (valor == 0) {
        console.log('Parte del cuerpo: ' + talla + ' | Valor: ' + valor);
        $("#bloque_inferiorotro").css("display", "block");
    }
}

function cambiaTalla4(valor, talla) {
    $("#bloque_manosotro").css("display", "none");

    if (valor == 0) {
        console.log('Parte del cuerpo: ' + talla + ' | Valor: ' + valor);
        $("#bloque_manosotro").css("display", "block");
    }
}

function cambiaTalla5(valor, talla) {
    $("#bloque_piesotro").css("display", "none");

    if (valor == 0) {
        console.log('Parte del cuerpo: ' + talla + ' | Valor: ' + valor);
        $("#bloque_piesotro").css("display", "block");
    }
}

function cambiaEmpresapuesto(valor) {
    console.log('Valor: ' + valor);
    $('#vacantes-id_pais').empty();

    if (valor != null && valor != '' && valor != ' ') {

        $('#vacantes-id_puesto').val('').trigger('change');

        var base = 'index.php?r=vacantes%2Ftraerpuestos';

        if (valor != '' && valor != null) {
            $.ajax({
                url: base,
                type: 'post',
                data: {
                    id: valor,
                    _csrf: yii.getCsrfToken()
                },
                success: function(data) {
                    if (data) {

                        data = $.parseJSON(data);
                        console.log(data);

                        $('#vacantes-id_puesto').append('<option value="">Selecciona</option>');

                        $.each(data['puestos'], function(index, item) {
                            $('#vacantes-id_puesto').append(
                                '<option value="' + index +
                                '">' + item + '</option>');
                        });


                        $('#vacantes-id_pais').append('<option value="">SELECCIONA--</option>');
                        $.each(data['paises'], function(index, data) {
                            $('#vacantes-id_pais').append(
                                '<option value="' + index +
                                '">' + data + '</option>');
                        });
                    }
                }
            });
        } else {

        }
    }
}

function cambiaCheck(tipo, id, nombre) {
    valor = $('#' + id).is(':checked');
    console.log('Cambia el check: ' + valor + ' | id: ' + id + ' | nombre: ' + nombre);

    if (valor == true) {
        $("#txt_" + nombre).css("display", "block");
    } else {
        $("#txt_" + nombre).css("display", "none");
        $("#hccohc-txt_" + nombre).val('');
    }
}

$('#enviarForm').click(function(e) {
    e.preventDefault();
    console.log('Estoy enviando al servidor');
    //$('#cargasmasivas-envia_form').val(1);
    $("#formSMO").submit();
});


function cambiaSexo(sexo) {
    console.log('Cambio de sexo: ' + sexo);
    if (sexo == 2 || sexo == 3) {
        $("#antecedentesgineco").css("display", "block");
    } else {
        $("#antecedentesgineco").css("display", "none");
    }
}


function verQr(id) {
    console.log('Id trabajador qr: ' + id);
    var base = "index.php?r=trabajadores%2Floadqr&id=" + id;

    $('#modal-qr').modal('show').find('#body-qr').load(base);
}

function verQrmaquina(id) {
    console.log('Id trabajador qr: ' + id);
    var base = "index.php?r=maquinaria%2Floadqr&id=" + id;

    $('#modal-qr').modal('show').find('#body-qr').load(base);
}

$('#enviarFormvalue').click(function(e) {
    e.preventDefault();
    console.log('Estoy enviando al servidor, envia_form = 1');
    $('#maquinaria-envia_form').val(1);
    $("#formSMO").submit();
});


// $('#btnInicia').click(function(e) {
//     e.preventDefault();
//     console.log('Estoy enviando al servidor, envia_form = 1');
// });


function cambiaStatusmaquina(status) {
    console.log('Nuevo Status: ' + status);
    $('#maquinaria-status_trabajo').val(status);
    $("#formSMO").submit();
}


function cambiaEmpresamaquina(valor) {
    console.log('Cambiando empresa: ' + valor);
    var base = 'index.php?r=mantenimientos%2Finfoempresa';
    $('#mantenimientos-id_maquina').empty();
    $('#mantenimientos-id_pais').empty();
    $('#mantenimientos-id_linea').empty();
    $('#mantenimientos-id_ubicacion').empty();

    if (valor != '' && valor != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id: valor,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data);

                    $('#mantenimientos-id_maquina').append('<option value="">Selecciona</option>');
                    $.each(data['maquinas'], function(index, maquina) {
                        $('#mantenimientos-id_maquina').append(
                            '<option value="' + maquina
                            .id +
                            '">' + maquina.maquina + ' ' + maquina.clave + '</option>');
                    });

                    $('#mantenimientos-id_pais').append('<option value="">SELECCIONA--</option>');
                    $.each(data['paises'], function(index, data) {
                        $('#mantenimientos-id_pais').append(
                            '<option value="' + index +
                            '">' + data + '</option>');
                    });

                }
            }
        });
    } else {

    }
}


function cambiaMaquina(valor) {
    console.log('Cambiando la maquina: ' + valor);
    var base = 'index.php?r=mantenimientos%2Finfomaquina';

    if (valor != '' && valor != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id: valor,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {

                    data = $.parseJSON(data);
                    console.log(data);

                    $('#mantenimientos-nombre').val(data['maquina']['maquina'] + ' ' + data['maquina']['clave']);
                    $('#mantenimientos-marca').val(data['maquina']['marca']);
                    $('#mantenimientos-modelo').val(data['maquina']['modelo']);

                }
            }
        });
    } else {

    }
}

function cambiaPuestoHc(valor) {
    console.log('Cambiando puesto: ' + valor);
    var base = 'index.php?r=hccohc%2Finfopuesto';

    $("#bloquenuevo_puesto").css("display", "none");

    if (valor == 0) {
        console.log('nuevo puesto, dejar editar alli mismo');
        $("#bloquenuevo_puesto").css("display", "block");
    } else {
        $('#hccohc-aux_nuevopuesto').val('');
        if (valor != '' && valor != null) {
            $.ajax({
                url: base,
                type: 'post',
                data: {
                    id: valor,
                    _csrf: yii.getCsrfToken()
                },
                success: function(data) {
                    if (data) {

                        data = $.parseJSON(data);
                        console.log(data);

                        $('#datapuesto').html(data['puesto']['nombre']);

                    }
                }
            });
        }
    }

}


function cambiaNuevopuesto(puesto) {
    $('#datapuesto').html(puesto);
}


$('#enviarHC').click(function(e) {
    e.preventDefault();
    console.log('Estoy enviando al servidor');

    const canvas = document.getElementById("signature-pad").querySelector("canvas");
    const dataURL = canvas.toDataURL();

    $("#hccohc-firma").val(dataURL);
    $('#hccohc-envia_form').val(1);

    $("#formOHC").submit();
});


function nuevaVacuna(id, valor, id_origen) {
    console.log('id:' + id + ' | valor: ' + valor);

    if (valor == 0) {
        console.log('Es uno nuevo');
        document.getElementById(id).value = '';
        document.getElementById(id).style.display = 'block';
        $("#" + id).attr("aria-required", "true");

    } else {
        console.log('No es nuevo');
        document.getElementById(id).style.display = 'none';
        document.getElementById(id).ariarequired = false;

        $('#' + id).val('').trigger('change');

    }
}


$('.show_inspeccion').on('change', function(e) {
    e.preventDefault();
    console.log('cambian las lineas');
    var ret_valores = '';
    $('#hccohc-show_inspeccion').val(null);

    var values = $('.show_inspeccion:checkbox:checked').map(function() {
        return this.value;
    }).get();

    $.each(values, function(index, val) {
        ret_valores += val;
    });

    $('#hccohc-show_inspeccion').val(ret_valores);
    console.log(ret_valores);
});

$('.show_cabeza').on('change', function(e) {
    e.preventDefault();
    console.log('cambian las lineas');
    var ret_valores = '';
    $('#hccohc-show_cabeza').val(null);

    var values = $('.show_cabeza:checkbox:checked').map(function() {
        return this.value;
    }).get();

    $.each(values, function(index, val) {
        ret_valores += val;
    });

    $('#hccohc-show_cabeza').val(ret_valores);
    console.log(ret_valores);
});

$('.show_oidos').on('change', function(e) {
    e.preventDefault();
    console.log('cambian las lineas');
    var ret_valores = '';
    $('#hccohc-show_oidos').val(null);

    var values = $('.show_oidos:checkbox:checked').map(function() {
        return this.value;
    }).get();

    $.each(values, function(index, val) {
        ret_valores += val;
    });

    $('#hccohc-show_oidos').val(ret_valores);
    console.log(ret_valores);
});

$('.show_ojos').on('change', function(e) {
    e.preventDefault();
    console.log('cambian las lineas');
    var ret_valores = '';
    $('#hccohc-show_ojos').val(null);

    var values = $('.show_ojos:checkbox:checked').map(function() {
        return this.value;
    }).get();

    $.each(values, function(index, val) {
        ret_valores += val;
    });

    $('#hccohc-show_ojos').val(ret_valores);
    console.log(ret_valores);
});

$('.show_boca').on('change', function(e) {
    e.preventDefault();
    console.log('cambian las lineas');
    var ret_valores = '';
    $('#hccohc-show_boca').val(null);

    var values = $('.show_boca:checkbox:checked').map(function() {
        return this.value;
    }).get();

    $.each(values, function(index, val) {
        ret_valores += val;
    });

    $('#hccohc-show_boca').val(ret_valores);
    console.log(ret_valores);
});

$('.show_cuello').on('change', function(e) {
    e.preventDefault();
    console.log('cambian las lineas');
    var ret_valores = '';
    $('#hccohc-show_cuello').val(null);

    var values = $('.show_cuello:checkbox:checked').map(function() {
        return this.value;
    }).get();

    $.each(values, function(index, val) {
        ret_valores += val;
    });

    $('#hccohc-show_cuello').val(ret_valores);
    console.log(ret_valores);
});

$('.show_torax').on('change', function(e) {
    e.preventDefault();
    console.log('cambian las lineas');
    var ret_valores = '';
    $('#hccohc-show_torax').val(null);

    var values = $('.show_torax:checkbox:checked').map(function() {
        return this.value;
    }).get();

    $.each(values, function(index, val) {
        ret_valores += val;
    });

    $('#hccohc-show_torax').val(ret_valores);
    console.log(ret_valores);
});

$('.show_abdomen').on('change', function(e) {
    e.preventDefault();
    console.log('cambian las lineas');
    var ret_valores = '';
    $('#hccohc-show_abdomen').val(null);

    var values = $('.show_abdomen:checkbox:checked').map(function() {
        return this.value;
    }).get();

    $.each(values, function(index, val) {
        ret_valores += val;
    });

    $('#hccohc-show_abdomen').val(ret_valores);
    console.log(ret_valores);
});

$('.show_superior').on('change', function(e) {
    e.preventDefault();
    console.log('cambian las lineas');
    var ret_valores = '';
    $('#hccohc-show_superior').val(null);

    var values = $('.show_superior:checkbox:checked').map(function() {
        return this.value;
    }).get();

    $.each(values, function(index, val) {
        ret_valores += val;
    });

    $('#hccohc-show_superior').val(ret_valores);
    console.log(ret_valores);
});


$('.show_inferior').on('change', function(e) {
    e.preventDefault();
    console.log('cambian las lineas');
    var ret_valores = '';
    $('#hccohc-show_inferior').val(null);

    var values = $('.show_inferior:checkbox:checked').map(function() {
        return this.value;
    }).get();

    $.each(values, function(index, val) {
        ret_valores += val;
    });

    $('#hccohc-show_inferior').val(ret_valores);
    console.log(ret_valores);
});


$('.show_columna').on('change', function(e) {
    e.preventDefault();
    console.log('cambian las lineas');
    var ret_valores = '';
    $('#hccohc-show_columna').val(null);

    var values = $('.show_columna:checkbox:checked').map(function() {
        return this.value;
    }).get();

    $.each(values, function(index, val) {
        ret_valores += val;
    });

    $('#hccohc-show_columna').val(ret_valores);
    console.log(ret_valores);
});

$('.show_txtneurologicos').on('change', function(e) {
    e.preventDefault();
    console.log('cambian las lineas');
    var ret_valores = '';
    $('#hccohc-show_txtneurologicos').val(null);

    var values = $('.show_txtneurologicos:checkbox:checked').map(function() {
        return this.value;
    }).get();

    $.each(values, function(index, val) {
        ret_valores += val;
    });

    $('#hccohc-show_txtneurologicos').val(ret_valores);
    console.log(ret_valores);
});



function limitaTiempo(model) {
    atributo = $("input:checkbox[name='Usuarios[tiempo_limitado]']:checked").val();

    let marcar = false;
    //console.log(model + ': ' + atributo);
    if (atributo == '1') {
        console.log('Esta checked');
        $(".seccion_tiempo").css("display", "block");
    } else {
        console.log('No esta checked');
        $(".seccion_tiempo").css("display", "none");
        $('#usuarios-tiempo_dias').val(null).trigger('change');
        $('#usuarios-tiempo_horas').val(null).trigger('change');
        $('#usuarios-tiempo_minutos').val(null).trigger('change');

    }
}


$('#corregirHC').click(function(e) {
    e.preventDefault();
    console.log('Estoy enviando a corregir');

    $('#hccohc-envia_form').val(1);

    $("#formCorregir").submit();
});


function deleteAntecedentelab(bloque, numero, numero2) {
    console.log('ocultar: ' + bloque);
    $("#" + bloque).css("display", "none");

    $('#hccohc-laboral' + numero + '_actividad').val(null);
    $('#hccohc-laboral' + numero + '_tiempoexposicion').empty();
    $('#hccohc-laboral' + numero + '_epp').val(null);
    $('#hccohc-laboral' + numero + '_ipp').val(null);
    $('#hccohc-laboral' + numero + '_desde').val(null);
    $('#hccohc-laboral' + numero + '_hasta').val(null);
    $('#hccohc-laboral' + numero + '_exposicion').empty();

    $("#add_" + numero2).css("display", "inline");
}

function addAntecedentelab(bloque, numero) {
    console.log('mostrar: ' + bloque);
    $("#" + bloque).css("display", "flex");

    $("#add_" + numero).css("display", "none");
}

$('#btn_hcactual').click(function(e) {
    e.preventDefault();
    console.log('MOSTRAR HC ACTUAL');
    $("#actual_hc").css("display", "block");
    $("#anterior_hc").css("display", "none");
});

$('#btn_hcanterior').click(function(e) {
    e.preventDefault();
    console.log('MOSTRAR HC ANTERIOR');
    $("#anterior_hc").css("display", "block");
    $("#actual_hc").css("display", "none");
});


function seleccionaEmpresas(valor, model) {
    console.log('valor: ' + valor + ' | model: ' + model);
    $('#' + model + '-envia_form').val(0);

    $("#formOHC").submit();
}


function enviaServer(valor, model) {
    console.log('valor: ' + valor + ' | model: ' + model);
    $('#' + model + '-envia_form').val(0);

    $("#formOHC").submit();
}

$('#enviarbutton').click(function(e) {
    e.preventDefault();
    console.log('ENVIA AL SERVIDOR');
    $('#firmas-envia_form').val(1).trigger('change');

    $("#formOHC").submit();
});



function changeempresa(valor, modelo) {
    var id_empresa = $('#' + modelo + '-id_empresa').val();
    console.log('valor:' + valor + ' | modelo: ' + modelo + ' | id_empresa: ' + id_empresa);

    $('#' + modelo + '-id_pais').empty();
    $('#' + modelo + '-id_linea').empty();
    $('#' + modelo + '-id_ubicacion').empty();

    var base = 'index.php?r=empresas%2Flistpaises';

    if (valor != '' && valor != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id_empresa: valor,
                id_empresa: id_empresa,
                modelo: modelo,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {
                    data = $.parseJSON(data);
                    console.log(data);

                    $('#' + modelo + '-id_pais').append('<option value="">SELECCIONA--</option>');
                    $.each(data['paises'], function(index, data) {
                        $('#' + modelo + '-id_pais').append(
                            '<option value="' + index +
                            '">' + data + '</option>');
                    });
                }
            }
        });
    }
}


function changepais(id_pais, modelo) {
    if (modelo == 'cuestionario') {
        var id_empresa = $('#e_empresa').val();
    } else {
        var id_empresa = $('#' + modelo + '-id_empresa').val();
    }

    console.log('id_pais:' + id_pais + ' | modelo: ' + modelo + ' | id_empresa: ' + id_empresa);

    var base = 'index.php?r=empresas%2Flistlineas';

    $('#' + modelo + '-id_linea').empty();
    $('#' + modelo + '-id_ubicacion').empty();

    if (id_pais != '' && id_pais != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id_pais: id_pais,
                id_empresa: id_empresa,
                modelo: modelo,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {
                    data = $.parseJSON(data);
                    console.log(data);

                    $('#' + modelo + '-id_linea').append('<option value="">SELECCIONA--</option>');
                    $.each(data['lineas'], function(index, data) {
                        $('#' + modelo + '-id_linea').append(
                            '<option value="' + index +
                            '">' + data + '</option>');
                    });
                }
            }
        });
    }
}


function changelinea(id_linea, modelo) {
    if (modelo == 'cuestionario') {
        var id_empresa = $('#e_empresa').val();
    } else {
        var id_empresa = $('#' + modelo + '-id_empresa').val();
    }

    console.log('id_linea:' + id_linea + ' | modelo: ' + modelo + ' | id_empresa: ' + id_empresa);

    var base = 'index.php?r=empresas%2Flistubicaciones';

    $('#' + modelo + '-id_ubicacion').empty();
    $('#' + modelo + '-id_ubicacionl').empty();

    if (id_linea != '' && id_linea != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id_linea: id_linea,
                id_empresa: id_empresa,
                modelo: modelo,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {
                    data = $.parseJSON(data);
                    console.log(data);

                    $('#' + modelo + '-id_ubicacion').append('<option value="">SELECCIONA--</option>');
                    $.each(data['ubicaciones'], function(index, data) {
                        $('#' + modelo + '-id_ubicacion').append(
                            '<option value="' + index +
                            '">' + data + '</option>');
                    });

                    $('#' + modelo + '-id_ubicacionl').append('<option value="">SELECCIONA--</option>');
                    $.each(data['ubicaciones'], function(index, data) {
                        $('#' + modelo + '-id_ubicacionl').append(
                            '<option value="' + index +
                            '">' + data + '</option>');
                    });
                }
            }
        });
    }
}


function changeCantidad(valor) {
    console.log('valor: ' + valor);

    $("#showetiqueta1").css("display", "none");
    $("#showetiqueta2").css("display", "none");
    $("#showetiqueta3").css("display", "none");
    $("#showetiqueta4").css("display", "none");

    if (valor >= 1) {
        $("#showetiqueta1").css("display", "block");
        $('#empresas-nivel1').val(1);
    }
    if (valor >= 2) {
        $("#showetiqueta2").css("display", "block");
        $('#empresas-nivel2').val(1);
    }
    if (valor >= 3) {
        $("#showetiqueta3").css("display", "block");
        $('#empresas-nivel3').val(1);
    }
    if (valor >= 4) {
        $("#showetiqueta4").css("display", "block");
        $('#empresas-nivel4').val(1);
    }
}


function agregaNivel1(id_empresa, label, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | label: ' + label + ' | ruta: ' + ruta);

    $('#titulo').html('Agregar Nuevos ' + label);
    $('#modal-diagrama').modal('show').find('#body-diagrama').load(ruta);
}

function agregaNivel2(id_empresa, id_nivel1, label, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | id_nivel1: ' + id_nivel1 + ' | label: ' + label + ' | ruta: ' + ruta);

    $('#titulo').html('Agregar Nuevos ' + label);
    $('#modal-diagrama').modal('show').find('#body-diagrama').load(ruta);
}

function agregaNivel3(id_empresa, id_nivel1, id_nivel2, label, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | id_nivel1: ' + id_nivel1 + ' | id_nivel2: ' + id_nivel2 + ' | label: ' + label + ' | ruta: ' + ruta);

    $('#titulo').html('Agregar Nuevos ' + label);
    $('#modal-diagrama').modal('show').find('#body-diagrama').load(ruta);
}

function agregaNivel4(id_empresa, id_nivel1, id_nivel2, id_nivel3, label, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | id_nivel1: ' + id_nivel1 + ' | id_nivel2: ' + id_nivel2 + ' | id_nivel3: ' + id_nivel3 + ' | label: ' + label + ' | ruta: ' + ruta);

    $('#titulo').html('Agregar Nuevos ' + label);
    $('#modal-diagrama').modal('show').find('#body-diagrama').load(ruta);
}


function editarContenido(id_empresa, id_nivel, nivel, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | id_nivel: ' + id_nivel + ' | nivel: ' + nivel + ' | ruta: ' + ruta);

    $('#modal-contenido').modal('show').find('#body-contenido').load(ruta);
}


function editaNivel1(id_empresa, label, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | label: ' + label + ' | ruta: ' + ruta);

    $('#titulo').html('Editar ' + label);
    $('#modal-diagrama').modal('show').find('#body-diagrama').load(ruta);
}

function editaNivel2(id_empresa, id_nivel2, label, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | id_nivel2: ' + id_nivel2 + ' | label: ' + label + ' | ruta: ' + ruta);

    $('#titulo').html('Editar ' + label);
    $('#modal-diagrama').modal('show').find('#body-diagrama').load(ruta);
}

function editaNivel3(id_empresa, id_nivel3, label, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | id_nivel3: ' + id_nivel3 + ' | label: ' + label + ' | ruta: ' + ruta);

    $('#titulo').html('Editar ' + label);
    $('#modal-diagrama').modal('show').find('#body-diagrama').load(ruta);
}

function editaNivel4(id_empresa, id_nivel4, label, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | id_nivel4: ' + id_nivel4 + ' | label: ' + label + ' | ruta: ' + ruta);

    $('#titulo').html('Editar ' + label);
    $('#modal-diagrama').modal('show').find('#body-diagrama').load(ruta);
}



function editaKpinivel1(id_empresa, label, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | label: ' + label + ' | ruta: ' + ruta);

    $('#titulo').html('KPIs ' + label);
    $('#modal-kpiedit').modal('show').find('#body-kpiedit').load(ruta);
}

function editaKpinivel2(id_empresa, id_nivel2, label, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | id_nivel2: ' + id_nivel2 + ' | label: ' + label + ' | ruta: ' + ruta);

    $('#titulo').html('KPIs ' + label);
    $('#modal-kpiedit').modal('show').find('#body-kpiedit').load(ruta);
}

function editaKpinivel3(id_empresa, id_nivel3, label, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | id_nivel3: ' + id_nivel3 + ' | label: ' + label + ' | ruta: ' + ruta);

    $('#titulo').html('KPIs ' + label);
    $('#modal-kpiedit').modal('show').find('#body-kpiedit').load(ruta);
}

function editaKpinivel4(id_empresa, id_nivel4, label, ruta) {
    console.log('id_empresa: ' + id_empresa + ' | id_nivel4: ' + id_nivel4 + ' | label: ' + label + ' | ruta: ' + ruta);

    $('#titulo').html('KPIs ' + label);
    $('#modal-kpiedit').modal('show').find('#body-kpiedit').load(ruta);
}



function changeNivel1(id_nivel, modelo) {
    if (modelo == 'cuestionario') {
        var id_empresa = $('#e_empresa').val();
    } else {
        var id_empresa = $('#' + modelo + '-id_empresa').val();
    }

    console.log('NIVEL 1 | id_nivel:' + id_nivel + ' | modelo: ' + modelo);

    var base = 'index.php?r=hccohc%2Finfonivel1';

    $('#' + modelo + '-id_nivel2').empty();
    $('#' + modelo + '-id_nivel3').empty();
    $('#' + modelo + '-id_nivel4').empty();

    if (id_nivel != '' && id_nivel != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id_nivel: id_nivel,
                id_empresa: id_empresa,
                modelo: modelo,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {
                    data = $.parseJSON(data);
                    console.log(data);

                    $('#' + modelo + '-id_nivel2').append('<option value="">SELECCIONA--</option>');
                    $.each(data['niveles2'], function(index, data) {
                        $('#' + modelo + '-id_nivel2').append(
                            '<option value="' + index +
                            '">' + data + '</option>');
                    });


                    if (data['cambia_area'] == true) {
                        $('#' + modelo + '-id_area').empty();

                        $('#' + modelo + '-id_area').append('<option value="">SELECCIONA--</option>');
                        $.each(data['areas'], function(index, data) {
                            $('#' + modelo + '-id_area').append(
                                '<option value="' + index +
                                '">' + data + '</option>');
                        });
                    }
                }
            }
        });
    }
}


function changeNivel2(id_nivel, modelo) {
    if (modelo == 'cuestionario') {
        var id_empresa = $('#e_empresa').val();
    } else {
        var id_empresa = $('#' + modelo + '-id_empresa').val();
    }

    console.log('NIVEL 2 | id_nivel:' + id_nivel + ' | modelo: ' + modelo);

    var base = 'index.php?r=hccohc%2Finfonivel2';

    $('#' + modelo + '-id_nivel3').empty();
    $('#' + modelo + '-id_nivel4').empty();

    if (id_nivel != '' && id_nivel != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id_nivel: id_nivel,
                id_empresa: id_empresa,
                modelo: modelo,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {
                    data = $.parseJSON(data);
                    console.log(data);

                    $('#' + modelo + '-id_nivel3').append('<option value="">SELECCIONA--</option>');
                    $.each(data['niveles3'], function(index, data) {
                        $('#' + modelo + '-id_nivel3').append(
                            '<option value="' + index +
                            '">' + data + '</option>');
                    });


                    if (data['cambia_area'] == true) {
                        $('#' + modelo + '-id_area').empty();

                        $('#' + modelo + '-id_area').append('<option value="">SELECCIONA--</option>');
                        $.each(data['areas'], function(index, data) {
                            $('#' + modelo + '-id_area').append(
                                '<option value="' + index +
                                '">' + data + '</option>');
                        });
                    }
                }
            }
        });
    }
}


function changeNivel3(id_nivel, modelo) {
    if (modelo == 'cuestionario') {
        var id_empresa = $('#e_empresa').val();
    } else {
        var id_empresa = $('#' + modelo + '-id_empresa').val();
    }

    console.log('NIVEL 3 | id_nivel:' + id_nivel + ' | modelo: ' + modelo);

    var base = 'index.php?r=hccohc%2Finfonivel3';

    $('#' + modelo + '-id_nivel4').empty();

    if (id_nivel != '' && id_nivel != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id_nivel: id_nivel,
                id_empresa: id_empresa,
                modelo: modelo,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {
                    data = $.parseJSON(data);
                    console.log(data);

                    $('#' + modelo + '-id_nivel4').append('<option value="">SELECCIONA--</option>');
                    $.each(data['niveles4'], function(index, data) {
                        $('#' + modelo + '-id_nivel4').append(
                            '<option value="' + index +
                            '">' + data + '</option>');
                    });


                    if (data['cambia_area'] == true) {
                        $('#' + modelo + '-id_area').empty();

                        $('#' + modelo + '-id_area').append('<option value="">SELECCIONA--</option>');
                        $.each(data['areas'], function(index, data) {
                            $('#' + modelo + '-id_area').append(
                                '<option value="' + index +
                                '">' + data + '</option>');
                        });
                    }
                }
            }
        });
    }
}


function changeNivel4(id_nivel, modelo) {
    if (modelo == 'cuestionario') {
        var id_empresa = $('#e_empresa').val();
    } else {
        var id_empresa = $('#' + modelo + '-id_empresa').val();
    }

    console.log('NIVEL 4 | id_nivel:' + id_nivel + ' | modelo: ' + modelo);

    var base = 'index.php?r=hccohc%2Finfonivel4';

    if (id_nivel != '' && id_nivel != null) {
        $.ajax({
            url: base,
            type: 'post',
            data: {
                id_nivel: id_nivel,
                id_empresa: id_empresa,
                modelo: modelo,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                if (data) {
                    data = $.parseJSON(data);
                    console.log(data);

                    if (data['cambia_area'] == true) {
                        $('#' + modelo + '-id_area').empty();

                        $('#' + modelo + '-id_area').append('<option value="">SELECCIONA--</option>');
                        $.each(data['areas'], function(index, data) {
                            $('#' + modelo + '-id_area').append(
                                '<option value="' + index +
                                '">' + data + '</option>');
                        });
                    }

                }
            }
        });
    }
}


function aceptaTerminos(modulo, modulo2) {
    console.log('modulo: ' + modulo + ' | modulo2: ' + modulo2);
    valor = $("input:radio[name='" + modulo + "[uso_consentimiento]']:checked").val();
    valor2 = $("input:radio[name='" + modulo + "[retirar_consentimiento]']:checked").val();
    valor3 = $('#' + modulo2 + '-acuerdo_confidencialidad').is(":checked");

    console.log('Uso del consentimiento: ' + valor);
    console.log('Retirar consentimiento del consentimiento: ' + valor2);
    console.log('Acepta términos y condiciones: ' + valor3);

    if (valor == 2) {
        if (modulo2 == 'cuestionario') {
            nombre_empresa = $("#" + modulo2 + "-name_empresa").val();
        } else {
            nombre_empresa = $("#" + modulo2 + "-nombre_empresa").val();
        }

        console.log('nombre_empresa: ' + nombre_empresa);
        $('.nombre_empresa').text(nombre_empresa);
    } else {
        $('.nombre_empresa').text('');
    }

    if (valor3 == true && valor2 == 1 && (valor == 1 || valor == 2)) {
        console.log('Mostrar botón');
        $('#btnSv').css('display', 'inline');
    } else {
        console.log('Ocultar botón');
        $('#btnSv').css('display', 'none');
    }
}


function completaConsentimiento(elemento) {
    var value = elemento.value;
    var aux = elemento.id.split('-');

    console.log('valor: ' + value + ' aux' + aux);

    if (aux[1] == 'empresa') {
        $('.nombre_empresa').text(value);
        console.log('completar la empresa');
    } else {
        var nombre = $("#trabajadores-nombre").val();
        var apellidos = $("#trabajadores-apellidos").val();

        $('.nombre_cliente').text(nombre + ' ' + apellidos);

        console.log(nombre + ' ' + apellidos);
    }

}



$("#guardartrabajador").on("click", function(e) {
    e.preventDefault();
    $('#trabajadores-envia_form').val('1');

    const canvas = document.getElementById("signature-pad").querySelector("canvas");
    const dataURL = canvas.toDataURL();
    $("#trabajadores-firma").val(dataURL);


    var foto = $("#trabajadores-txt_base64_foto").val();
    console.log('FOTO------------------------------------------------');
    console.log(foto);

    var ine = $("#trabajadores-txt_base64_ine").val();
    console.log('INE------------------------------------------------');
    console.log(ine);

    var ine_reverso = $("#trabajadores-txt_base64_inereverso").val();
    console.log('INE REVERSO------------------------------------------------');
    console.log(ine_reverso);

    var formData = new FormData($('form[id="formOHC"]')[0]);
    var url = $('form[id="formOHC"]').attr('action');


    $.ajax({
        type: 'POST',
        url: url,
        method: 'POST',
        cache: false,
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.redirect) {
                window.location.href = response.redirect;
            } else {
                console.log('no envia');
            }
        }
    });
});




function capture_web_snapshot(modelo) {
    console.log('capture_web_snapshot');
    Webcam.snap(function(site_url) {
        $("#" + modelo + "-txt_base64_foto").val(site_url).trigger('change');
        document.getElementById('preview').innerHTML = '<img src="' + site_url + '"/>';
    });
}


function capture_web_snapshot2(modelo) {
    console.log('capture_web_snapshot2');
    Webcam.snap(function(site_url) {
        $("#" + modelo + "-txt_base64_ine").val(site_url).trigger('change');
        document.getElementById('preview2').innerHTML = '<img src="' + site_url + '"/>';
    });
}


function capture_web_snapshot3(modelo) {
    console.log('capture_web_snapshot3');
    Webcam.snap(function(site_url) {
        $("#" + modelo + "-txt_base64_inereverso").val(site_url).trigger('change');
        document.getElementById('preview3').innerHTML = '<img src="' + site_url + '"/>';
    });
}



$("#guardarconsentimiento").on("click", function(e) {
    e.preventDefault();

    modelo = $(this).val();
    console.log('modelo: ' + modelo + '-------------------------------------');
    $('#' + modelo + '-envia_form').val('1');

    var foto = $("#" + modelo + "-txt_base64_foto").val();
    console.log('FOTO------------------------------------------------');
    console.log(foto);

    var formData = new FormData($('form[id="formOHC"]')[0]);
    var url = $('form[id="formOHC"]').attr('action');


    $.ajax({
        type: 'POST',
        url: url,
        method: 'POST',
        cache: false,
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.redirect) {
                window.location.href = response.redirect;
            } else {
                console.log('no envia');
            }
        }
    });
});


$('#click_consentimiento1').click(function(e) {
    e.preventDefault();
    console.log('CONSENTIMIENTO TIPO 1------');
    $('#poes-tipo_consentimiento').val(1).trigger('change');

    $('#bloque_consentimiento1').css('display', 'block');
    $('#bloque_consentimiento1_pt2').css('display', 'block');
    $('#bloque_consentimiento2').css('display', 'none');

    $('#bloque_guardar').css('display', 'block');

    $("#click_consentimiento1").removeClass("btnnew2");
    $("#click_consentimiento1").addClass("btnnew4");
    $("#click_consentimiento2").removeClass("btnnew4");
    $("#click_consentimiento2").addClass("btnnew2");
});


$('#click_consentimiento2').click(function(e) {
    e.preventDefault();
    console.log('CONSENTIMIENTO TIPO 2------');
    $('#poes-tipo_consentimiento').val(2).trigger('change');

    $('#bloque_consentimiento1').css('display', 'none');
    $('#bloque_consentimiento1_pt2').css('display', 'none');
    $('#bloque_consentimiento2').css('display', 'block');

    $('#bloque_guardar').css('display', 'block');

    $("#click_consentimiento2").removeClass("btnnew2");
    $("#click_consentimiento2").addClass("btnnew4");
    $("#click_consentimiento1").removeClass("btnnew4");
    $("#click_consentimiento1").addClass("btnnew2");
});


function editarKPI(id_empresa, ruta) {
    console.log('KPIS id_empresa: ' + id_empresa + ' | ruta: ' + ruta);

    $('#modal-kpi').modal('show').find('#body-kpi').load(ruta);
}


function enlazarHC(id_poe, id_estudio, ruta) {
    console.log('id_poe: ' + id_poe + ' | id_estudio: ' + id_estudio + ' | ruta: ' + ruta);

    $('#modal-enlace').modal('show').find('#body-enlace').load(ruta);
    console.log('enlaza');
}


function enviarDias() {

    let nivel = $('#nivel').val();

    let modelo = 'nivelorganizacional' + nivel;

    let id_empresa = $('#' + modelo + '-id_empresa').val();
    let aux_dias_sin_accidentes = $('#' + modelo + '-aux_dias_sin_accidentes').val();
    let id_nivel1 = $('#id_nivel1').val();
    let id_nivel2 = $('#id_nivel2').val();
    let id_nivel3 = $('#id_nivel3').val();
    let id_nivel4 = $('#id_nivel4').val();

    let total_accidentes = $('#' + modelo + '-accidentes_anio_dias_sin_accidentes').val();
    let objetivo_accidentes = $('#' + modelo + '-objetivo_dias_sin_accidentes').val();
    let comentarios_accidentes = $('#' + modelo + '-comentario_dias_sin_accidentes').val();
    let cumplimiento_accidentes = $('#' + modelo + '-cumplimiento_dias_sin_accidentes').val();

    console.log('Nivel: ' + nivel + ' | modelo: ' + modelo + ' | id_nivel1: ' + id_nivel1 + ' | id_nivel2: ' + id_nivel2 + ' | id_nivel3: ' + id_nivel3 + ' | id_nivel4: ' + id_nivel4 + ' | id_empresa: ' + id_empresa + ' | aux_dias_sin_accidentes: ' + aux_dias_sin_accidentes + ' | total_accidentes: ' + total_accidentes + ' | objetivo_accidentes: ' + objetivo_accidentes + ' | comentarios_accidentes: ' + comentarios_accidentes + ' | cumplimiento_accidentes: ' + cumplimiento_accidentes);

    var base = 'index.php?r=diagramas%2Fguardardias';

    if (true) {
        if (aux_dias_sin_accidentes != '' && aux_dias_sin_accidentes != ' ' && aux_dias_sin_accidentes != null) {
            $.ajax({
                url: base,
                type: 'post',
                data: {
                    nivel: nivel,
                    id_empresa: id_empresa,
                    aux_dias_sin_accidentes: aux_dias_sin_accidentes,
                    id_nivel1: id_nivel1,
                    id_nivel2: id_nivel2,
                    id_nivel3: id_nivel3,
                    id_nivel4: id_nivel4,
                    total_accidentes: total_accidentes,
                    objetivo_accidentes: objetivo_accidentes,
                    comentarios_accidentes: comentarios_accidentes,
                    cumplimiento_accidentes: cumplimiento_accidentes,
                    _csrf: yii.getCsrfToken()
                },
                success: function(data) {
                    if (data) {

                        data = $.parseJSON(data);
                        console.log(data);

                        if (data['status'] == 100) {
                            $('#' + modelo + '-aux_dias_sin_accidentes').val(data['nivel_dias']).trigger('change');
                            $('#' + modelo + '-aux_fecha_dias_sin_accidentes').val(data['nivel_fecha']).trigger('change');
                            $('#' + modelo + '-aux_actualiza_dias_sin_accidentes').val(data['nivel_usuario']).trigger('change');
                        }

                    }
                }
            });
        }
    }
}


function cambiaCumplimiento(nivel) {
    console.log('Cambia %');

    var values = $('.porcentaje_cumplimiento').map(function() {
        return this.value;
    }).get();


    var cantidad = 0;
    var sumatoria = 0;
    var porcentaje = 0;

    $.each(values, function(index, val) {
        cantidad++;

        if (val != null && val != '' && val != ' ') {
            sumatoria += parseFloat(val);
        }
    });

    //Sumamos el cumplimiento de accidentes ----------------------------------
    cantidad++;
    let cumplimiento_accidentes = $('#nivelorganizacional' + nivel + '-cumplimiento_dias_sin_accidentes').val();
    sumatoria += parseFloat(cumplimiento_accidentes);
    //Sumamos el cumplimiento de accidentes ----------------------------------

    if (cantidad > 0) {
        porcentaje = sumatoria / cantidad;
    }

    $('#cumplimiento_kpi').html(porcentaje.toFixed(2) + '%');

    console.log('cantidad: ' + cantidad + ' | sumatoria: ' + sumatoria + ' | porcentaje: ' + porcentaje);
}


function cambiaAccidentes(nivel) {

    let id_limite_accidentes = 'nivelorganizacional' + nivel + '-objetivo_dias_sin_accidentes';
    let id_total_accidentes = 'nivelorganizacional' + nivel + '-accidentes_anio_dias_sin_accidentes';
    let id_cumplimiento = 'nivelorganizacional' + nivel + '-cumplimiento_dias_sin_accidentes';

    let limite_accidentes = $('#' + id_limite_accidentes).val();
    let total_accidentes = $('#' + id_total_accidentes).val();

    if (limite_accidentes != null && limite_accidentes != '' && limite_accidentes != ' ' && total_accidentes != null && total_accidentes != '' && total_accidentes != ' ') {
        if (total_accidentes > limite_accidentes) {
            var cumplimiento = 0;
            $('#' + id_cumplimiento).val(cumplimiento);
            $('#cumplimiento_accidentes').html(cumplimiento + '%');
        } else {
            var cumplimiento = 100;
            $('#' + id_cumplimiento).val(cumplimiento);
            $('#cumplimiento_accidentes').html(cumplimiento + '%');
        }
    }

    console.log('nivel: ' + nivel + ' | limite_accidentes: ' + limite_accidentes + ' | total_accidentes: ' + total_accidentes);
    //let id_nivel1 = $('#id_nivel1').val();
}

function cambiaResultadokpi(id, nivel) {
    var cumplimiento = 0;

    var id_final = id.replace("nivelorganizacional" + nivel + "-aux_kpis-", "");
    id_final = id_final.replace("-kpi_objetivo", "");
    id_final = id_final.replace("-kpi_real", "");
    id_final = id_final.replace("-kpi-container", "");
    id_final = id_final.replace("-kpi", "");

    let id_kpi = 'nivelorganizacional' + nivel + '-aux_kpis-' + id_final + '-kpi';
    let id_objetivo = 'nivelorganizacional' + nivel + '-aux_kpis-' + id_final + '-kpi_objetivo';
    let id_real = 'nivelorganizacional' + nivel + '-aux_kpis-' + id_final + '-kpi_real';
    let id_cumplimiento = 'nivelorganizacional' + nivel + '-aux_kpis-' + id_final + '-kpi_cumplimiento';

    let kpi = $('#' + id_kpi).val();
    let objetivo = $('#' + id_objetivo).val();
    let real = $('#' + id_real).val();

    if (kpi == 'A') {
        //'A'=>'ACCIDENTES',
        if (objetivo != null && objetivo != '' && objetivo != ' ' && real != null && real != '' && real != ' ') {
            if (real > objetivo) {
                cumplimiento = 0;
            } else {
                cumplimiento = 100;
            }
        }
    } else if (kpi == 'B') {
        //'B'=>'NUEVOS INGRESOS',
        if (objetivo != null && objetivo != '' && objetivo != ' ' && real != null && real != '' && real != ' ') {
            if (real != 0) {
                cumplimiento = (real * 100) / objetivo;
            }
        }
    } else if (kpi == 'C') {
        //'C'=>'INCAPACIDADES',
        if (objetivo != null && objetivo != '' && objetivo != ' ' && real != null && real != '' && real != ' ') {
            if (real != 0) {
                cumplimiento = (real * 100) / objetivo;
            }
        }
    } else if (kpi == 'E') {
        //'E'=>'POES'
        if (objetivo != null && objetivo != '' && objetivo != ' ' && real != null && real != '' && real != ' ') {
            if (real != 0) {
                cumplimiento = (real * 100) / objetivo;
            }
        }
    } else {
        //'D'=>'PROGRAMAS DE SALUD',
        if (objetivo != null && objetivo != '' && objetivo != ' ' && real != null && real != '' && real != ' ') {
            if (real != 0) {
                cumplimiento = (real * 100) / objetivo;
            }
        }
    }

    $('#' + id_cumplimiento).val(cumplimiento.toFixed(2)).trigger('change');
    console.log('id: ' + id + ' | nivel: ' + nivel + ' | id_final: ' + id_final + ' | kpi: ' + kpi + ' | objetivo: ' + objetivo + ' | real: ' + real + ' | cumplimiento: ' + cumplimiento);
}


function filtrarEstudios(id_trabajador, src) {
    src = src.toLowerCase();

    var busqueda = $.trim(src);
    console.log('id_trabajador: ' + id_trabajador + ' | src: ' + src + ' | busqueda trim: ' + busqueda);

    var all_filas = $('tr[name="worker_' + id_trabajador + '"]').map(function() {
        var id_seccion = this.id;
        var value_seccion = $(this).attr('value');

        console.log('id_seccion: ' + id_seccion + ' | value_seccion: ' + value_seccion);

        return id_seccion;
    }).get();


    if (busqueda != null && busqueda != '' && busqueda != ' ') {
        var all_find = $('tr[name="worker_' + id_trabajador + '"]').map(function() {
            var id_seccion = this.id;
            var value_seccion = $(this).attr('value');
            value_seccion = value_seccion.toLowerCase();

            if (value_seccion.includes(src)) {
                return id_seccion;
            }

        }).get();

        //console.log(all_filas);
        //console.log(all_find);


        all_filas.forEach((item, index, array) => {
            console.log('item: ' + item + ' | index: ' + index);

            console.log('---------------------OCULTAR');
            $("#" + item).css("display", "none");
        });

        all_find.forEach((item, index, array) => {
            console.log('---------------------FIND item: ' + item + ' | index: ' + index);

            $("#" + item).css("display", "");

            $('#' + item + ' td').each(function() {
                $(this).css("background-color", "#FFFF00");
            });
        });
    } else {
        all_filas.forEach((item, index, array) => {
            console.log('item: ' + item + ' | index: ' + index);

            $("#" + item).css("display", "");

            $('#' + item + ' td').each(function() {
                $(this).css("background-color", "transparent");
            });
        });
    }


}