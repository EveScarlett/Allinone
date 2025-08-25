<?php
use app\models\Bitacora;
use app\models\DetalleCuestionario;
use app\models\Cuestionario;

/**
 * Crea un registro de creacion o actualización en la bitacora.
 * @param string $escenario Use 'crear' para crear un nuevo registro, o 'actualizar' para actualizar un registro.
 * @param int|null $id Indique el Id del registro, se solicita en el escenario de 'actualizar'
 * @return bool|object true, si se completa la accion, false si no se completa.
 */
function bitacora($escenario, $id = null) {
    
    switch ($escenario) {
        case 'crear':
            $bitacora = new Bitacora();
            $bitacora->id_usuario_creo = Yii::$app->user->identity->id;
            $bitacora->nombre_creo = Yii::$app->user->identity->name;
            $bitacora->fecha_creacion = Date("Y-m-d H:i:s");
            return ($bitacora->save()) ? $bitacora : false;
            break;
        case 'actualizar':
            $bitacora = Bitacora::findOne($id);
            $bitacora->id_usuario_actualizo = Yii::$app->user->identity->id;
            $bitacora->nombre_actualizo = Yii::$app->user->identity->name;
            $bitacora->fecha_actualizacion = Date("Y-m-d H:i:s");
            return ($bitacora->update()) ? $bitacora : false;
            break;
        default:
            return false;
            break;
    }
}

/**
 * Guarda los datos de una pregunta.
 * @param int $id_cuestionario Id del cuestionario realizado
 * @param int $id_pregunta El id de la pregunta
 * @param int $id_area El id del area de la pregunta
 * @return bool|object true, si se completa la accion, false si no se completa.
 */
function insertRespuesta($id_cuestionario, $id_pregunta, $id_area, $respuesta) { 
    $det_cuestionario = new DetalleCuestionario();
    $det_cuestionario->id_cuestionario = $id_cuestionario;
    $det_cuestionario->id_tipo_cuestionario = Cuestionario::find()->where(["id" => $id_cuestionario])->one()->id_tipo_cuestionario;
    $det_cuestionario->id_pregunta = $id_pregunta;
    $det_cuestionario->id_area = $id_area;
    $det_cuestionario->respuesta_1 = $respuesta;
    $det_cuestionario->status = 1;

    // return ($det_cuestionario->save()) ? true : $det_cuestionario->getErrors();
    return $det_cuestionario->save();
}

/**
 * Cuenta el numero de veces que aparece un valor en un array
 * @param array $array Array donde se buscara el valor.
 * @param mixed $valor Valor a buscar
 * @return int Regresa el numero de veces que aparece el valor.
 */
function CountValuesArray($array, $valor) {
    if (!is_array($array)) return 0;

    $i = 0;
    foreach ($array as $v) {
        if ($valor === $v) {
            $i++;
        }
    }
    
    return $i;
}

/**
 * Elimina el valor de un array en todas sus apariciones.
 * @param array &$array Array donde se buscara el elemento.
 * @param $valor Valor a eliminar en el array.
 * @return int|true Retorna 0 si no es un array ó true si se eliminaron los valores.
 */
function RemoveValueArray(&$array, $valor) {
    if (!is_array($array)) return 0;

    foreach ($array as $key => $value) {
        if ($valor === $value) {
            unset($array[$key]);
        }
    }

    return true;
}

/**
 * Guarda una imagen64 / firma en el servidor
 * * La ruta donde se guarda la imagen se compone por:
 * /../web/documentos/id_empresa/cuestionarios/id_tipo_cuestionario/firmas/id_cuestionario/firma.png
 * @param object $model Modelo del cuestionario.
 * @param string $image64 Imagen en base 64.
 * @param string $name_img Nombre de la imagen con el que se guardara
 * @return bool True, si la imagen se guarda, de lo contrario false.
 */
function saveImage($model, $image64, $name_img) {
    // web/documentos/id_empresa/cuestionarios/id_tipo_cuestionario/firmas/id_cuestionario/firma.png

    // $ruta = "./cuestionario/$id_cuestionario/";
    // definimos la ruta donde se guardara en el server
    // $ruta = __DIR__ . '/../web/cuestionario/' . $id_cuestionario . '/';
    //$ruta = __DIR__ . '/../web/cuestionario/' . $id_cuestionario . '/';

    $ruta = __DIR__ . '/../web/documentos/';
    $ruta .= $model->id_empresa . '/cuestionarios/';
    $ruta .= $model->id_tipo_cuestionario . "/firmas/";
    $ruta .= $model->id . "/";
    
    if (!is_dir($ruta)) {
        mkdir($ruta, 0777, true);
        umask(0);
        chmod($ruta, 0777);
    } else {
        umask(0);
        chmod($ruta, 0777);
    }

    // definimos la ruta donde se guardara en el server
    $ruta = $ruta . $name_img . ".png";

    // decodificamos el base64
    $datosBase64 = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image64));
    
    // guardamos la imagen en el server
    if(!file_put_contents($ruta, $datosBase64)){
        // retorno si falla
        return false;
    } else {
        // retorno si todo fue bien
        return true;
    }
}