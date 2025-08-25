<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/lab.ico" type="image/x-icon">

</head>

<?php

use yii\helpers\Html;
use app\models\Empresas;

?>

<?php
$nombre_empresa = '';
$direccion_empresa = '';
$correo_empresa = '';

if($model->dempresa){
    $nombre_empresa = $model->dempresa->razon;
    $direccion_empresa = $model->dempresa->direccion;
    $correo_empresa = $model->dempresa->correo_privacidad;
}
?>

<body>
    <div class="container px-5">
        <div style="margin-bottom: 15px;">
            <h1 class="title text-center">CARTA DE CONSENTIMIENTO INFORMADO</h1>
        </div>
        <div>

            <table style="line-height: 1.5; font-size:11px;width:100%;border-collapse: collapse;">
                <tbody>
                    <tr>
                        <!-- Linea para generar 10 espacios iguales -->
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                        <td style="width:5%"></td>
                    </tr>

                    <tr>
                        <td colspan="4">
                            <?php
                            $fecha_formato = '';
                            if($model->fecha_c != null && $model->fecha_c != '' && $model->fecha_c != ' '){
                                $fecha_formato = date('d / m / Y', strtotime($model->fecha_c));
                            }
                            
                            echo 'FECHA: <span class="border-bottom-dot">'.$fecha_formato.'</span>';
                            ?>
                        </td>
                        <td colspan="3">
                            <?php
                            $hora_formato = '';
                            if($model->fecha_c != null && $model->fecha_c != '' && $model->fecha_c != ' '){
                                $hora_formato = date('H : i', strtotime($model->hora_c));
                            }
                           
                            echo 'HORA: <span class="border-bottom-dot">'.$hora_formato.'</span>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="17" class="t3" style="padding-top:15px;">
                            DATOS DEL TRABAJADOR</td>
                        <!-- <td colspan="3" rowspan="5" class="text-center" style="border: 1px solid black;">
                            <?php
                            $ret = '';
                            if(isset($model->foto_web) && $model->foto_web != ""){
                              
                                $filePath = '/web'. '/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id.'/Consultas/'.$model->foto_web;
                                $ret = Html::img($filePath, ['alt'=>' ', 'class' => "fotopdf",'style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;height:120px;width:110px;object-fit: fill;']);
                            }
                            echo $ret;
                            ?>
                        </td> -->
                    </tr>
                    <tr>
                        <td colspan="4" class="t4">
                            NOMBRE COMPLETO:
                        </td>
                        <td colspan="12" class="border-bottom-dot">
                            <?=$model->nombre.' '.$model->apellidos?>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="4" class="t4">
                            FECHA NACIMIENTO:
                        </td>
                        <td colspan="2" class="border-bottom-dot">
                            <?php
                            $fecha_formato = date('d / m / Y', strtotime($model->fecha_nacimiento));
                            echo $fecha_formato;
                            ?>
                        </td>
                        <td colspan="1">
                        </td>
                        <td colspan="5" class="t4">
                            EDAD (AÑOS CUMPLIDOS):
                        </td>
                        <td colspan="1" class="border-bottom-dot">
                             <?=$model->edad;?>
                        </td>

                        <td colspan="2" class="t4">
                            SEXO:
                        </td>
                        <?php
                        $unchecked = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                        </svg>';

                        $checked = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-check-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                        <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>
                        </svg>';
                        ?>
                        <td colspan="1" class="">
                            <?php
                            if($model->sexo == 2){
                                echo '<span class="mx-2">F </span>'.$checked;
                            }else{
                                echo '<span class="mx-2">F </span>'.$unchecked;
                            }
                            ?>
                        </td>
                        <td colspan="1" class="">
                            <?php
                            if($model->sexo == 1){
                                echo '<span class="mx-2">M </span>'.$checked;
                            }else{
                                echo '<span class="mx-2">M </span>'.$unchecked;
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="t4">
                            IDENTIFICACIÓN: TIPO
                        </td>
                        <td colspan="4" class="border-bottom-dot">
                            <?php
                            echo $model->tipo_identificacion;
                            ?>
                        </td>
                        <td colspan="1">
                        </td>
                        <td colspan="2" class="t4">
                            NÚMERO
                        </td>
                        <td colspan="3" class="border-bottom-dot">
                            <?php
                            echo $model->numero_identificacion;
                            ?>
                        </td>
                    </tr>
                    

                    <tr>
                        <td colspan="20" class="text-justify" style="padding-top:20px;">
                            POR MEDIO DE LA PRESENTE, QUIEN SUSCRIBE C. <span
                                class="border-bottom"><?=$model->nombre.' '.$model->apellidos?></span>, EN PLENO USO DE
                            MIS FACULTADES MENTALES; HE SIDO INFORMADO DE EL/LOS PROCEDIMIENTO(S) QUE SE ME VA A
                            PRACTICAR, EL/LOS CUAL(ES) ES/SON MÍNIMAMENTE INVASIVO(S). ASÍ MISMO MANIFIESTO QUE SE HIZO
                            DE MI CONOCIMIENTO QUE EL PERSONAL DE RED MÉDICA ALFIL, ESTÁ DEBIDAMENTE CAPACITADO PARA LA
                            REALIZACIÓN DE CADA UNO DE EL/LOS PROCEDIMIENTO(S) Y EN NINGÚN MOMENTO POR LAS ACCIONES
                            QUE EN SU PROFESIÓN APLICAN PARA EL DESARROLLO DEL MISMO, PRESENTA DAÑO A LA INTEGRIDAD DE
                            NINGUNA PERSONA.
                        </td>
                    </tr>



                    <tr>
                        <td colspan="20" class="text-justify" style="padding-top:20px;">
                            FINALMENTE, Y CORRESPONDIENDO AL PRINCIPIO DE CONFIDENCIALIDAD, SE ME HA EXPLICADO QUE LA
                            INFORMACIÓN QUE DERIVE COMO RESULTADO, RESPECTO A EL/LOS PROCEDIMIENTO(S) PRACTICADO(S),
                            SERÁ MANEJADA DE MANERA CONFIDENCIAL Y ESTRICTAMENTE PARA EL USO DE:
                        </td>
                    </tr>
                    <tr>
                        <td colspan="20">
                            <?php
                            if($model->uso_consentimiento == 1){
                                echo $checked.' <span class="mx-2 t3">MI PERSONA </span>';
                            }else{
                                echo $unchecked.' <span class="mx-2 t3">MI PERSONA </span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="20">
                            <?php
                            $empresa = '';
                            if($model->dempresa){
                                $empresa = $model->dempresa->razon;
                            }
                            if($model->uso_consentimiento == 2){
                                echo $checked.' <span class="mx-2 t3">ÁREA DE RECURSOS HUMANOS DE LA EMPRESA: </span>'.'<span class="border-bottom-dot">'.$empresa.'</span>';
                            }else{
                                echo $unchecked.' <span class="mx-2 t3">ÁREA DE RECURSOS HUMANOS DE LA EMPRESA: </span>';
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="20" class="text-justify" style="padding-top:15px;">
                            EN MI PRESENCIA HAN SIDO LLENADOS TODOS LOS ESPACIOS EN BLANCO EXISTENTES EN ESTE DOCUMENTO.
                            TAMBIÉN ME ENCUENTRO ENTERADO(A) QUE TENGO LA FACULTAD DE RETIRAR ESTE CONSENTIMIENTO SI ASÍ
                            LO DESEO (EN EL CASO DE QUE NO SE ME HAYA EXPLICADO EL/LOS PROCEDIMIENTO(S)).
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" class="">
                            <?php
                            $mostrar_nombre = '';
                            if($model->retirar_consentimiento == 1){
                                $mostrar_nombre = $model->nombre.' '.$model->apellidos;
                                echo '<span class="mx-2 t3">SI </span>'.$checked;
                            }else{
                                echo '<span class="mx-2 t3">SI </span>'.$unchecked;
                            }
                            ?>
                        </td>
                        <td colspan="1" class="">
                            <?php
                            if($model->retirar_consentimiento == 0){
                                echo '<span class="mx-2 t3">NO </span>'.$checked;
                            }else{
                                echo '<span class="mx-2 t3">NO </span>'.$unchecked;
                            }
                            ?>
                        </td>
                        <td colspan="1">
                        </td>
                        <td colspan="1">
                            YO:
                        </td>
                        <td colspan="16" class="border-bottom-dot">
                            <?php
                            echo '<span class="">'.$mostrar_nombre.'</span>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="20" class="text-justify" style="padding-top:10px;">
                            DOY MI CONSENTIMIENTO PARA LA REALIZACIÓN DEL PROCEDIMIENTO(S) ANTERIORMENTE SEÑALADO(S).
                        </td>
                    </tr>
                    

                    <tr style="padding-top:20px;">
                        <td colspan="5"></td>
                        <td colspan="10" class="text-justify" style="margin-top:20px;border:1px solid #BFD7EA;">
                            <?php
                            //dd($model);
                            $ret = '';
                            if(isset($model->foto_web) && $model->foto_web != ""){
                                $filePath =  '/web'.'/resources/Empresas/'.$model->id_empresa.'/Trabajadores/'.$model->id_trabajador.'/Poes/'.$model->foto_web;
                                $ret = Html::img( $filePath, ['alt'=>' ', 'class' => "img-responsive",'style'=>'box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px; height:200px; width:320px;']);
                            }
                            echo $ret;
                            ?>
                        </td>
                        

                    <tr>
                        <td colspan="20" class="t3" style="padding-top:15px;">
                            AVISO DE PRIVACIDAD</td>
                    </tr>
                    <tr>
                        <td colspan="20" class="text-justify">
                            <p>De conformidad con lo establecido en la Ley Federal de Protección de Datos Personales en Posesión de los Particulares, <b><?=$nombre_empresa;?></b>  pone a su disposición el siguiente aviso de privacidad.
., tiene su domicilio ubicado en: <?=$direccion_empresa;?>, y es responsable del uso y protección de sus datos personales, en este sentido y atendiendo las obligaciones legales establecidas en la Ley Federal de Protección de Datos Personales en Posesión de los Particulares, su Reglamento y los Lineamientos del Aviso de Privacidad, a través de este instrumento se informa a los titulares de los datos, la información que de ellos se recaba y los fines que se le darán a la misma.

Los datos personales y datos sensibles del titular que se recaben mediante el uso de los sistemas informáticos propiedad de <b><?=$nombre_empresa;?></b>, serán utilizados única y estrictamente con la finalidad de determinar padecimientos, generar las historias clínicas y administrar los expedientes médicos de los titulares.

Para cumplir con las finalidades antes descritas, se recabarán los siguientes datos personales y datos personales sensibles: 

            </p><br>


            

            <p>Datos personales:
<b>Ficha de identificación: </b> Nombre completo, sexo, estado civil, escolaridad, fecha de nacimiento, edad, domicilio, teléfono personal, puesto de trabajo, antigüedad en el empleo, número de seguro social.

Datos personales sensibles relacionados con el estado de salud:
<b>Ficha Médica: </b> Antecedentes heredo familiares, antecedentes personales no patológicos, antecedentes personales patológicos, antecedentes personales higiénico-dietéticos; antecedentes laborales; actividad laboral actual; actividad laboral hasta tres anteriores; exploración física y recomendaciones.

Los datos personales y datos personales sensibles recabados podrán ser transferidos al empleador del titular como “Tercero Receptor”, con las siguientes finalidades: contratación, estudios de nuevo ingreso valoración de padecimientos clínicos del titular y su evolución; analizar los riesgos ocupacionales a los que ha sido expuesto el titular; cumplir con el programa anual de salud ocupacional del Tercero Receptor; y otros fines administrativos de Tercero Receptor.

El Tercero Receptor de los datos personales, no podrá utilizar los datos personales y datos personales sensibles transferidos de manera diversa a la establecida en el presente Aviso de Privacidad.

            </p>
<br>
           

            <p>Usted en todo momento tiene el derecho a conocer cuales datos personales nos fueron proporcionados, para qué los utilizamos y las condiciones del uso que les damos (Acceso). Asimismo, es su derecho solicitar la corrección de la información personal que nos haya proporcionado en el caso de que se encuentre desactualizada, sea inexacta o incompleta (Rectificación); de igual manera, tiene derecho a que su información se elimine de nuestros registros o bases de datos cuando considere que la misma no está siendo utilizada adecuadamente (Cancelación); así como también a oponerse al uso de sus datos personales para fines específicos (Oposición). 
            </p>

            

            <p>Para el ejercicio de cualquiera de los derechos de Acceso, Rectificación, Cancelación y Oposición (ARCO), se deberá presentar la solicitud respectiva a través del siguiente correo electrónico: <b><?=$correo_empresa;?></b>.


El presente aviso de privacidad puede sufrir modificaciones, cambios o actualizaciones derivadas de nuevos requerimientos legales; de nuestras propias necesidades por los productos o servicios que ofrecemos; de nuestras prácticas de privacidad; de cambios en nuestro modelo de negocio, o por otras causas, por lo cual, nos comprometemos a mantenerlo informado, mediante notificación vía electrónica, a más tardar a los 10 diez días naturales de efectuado el/los cambio/s, sobre los que pueda sufrir el presente aviso de privacidad.
</p>

                        </td>
                    </tr>



                </tbody>
            </table>

        </div>
    </div>
</body>

</html>