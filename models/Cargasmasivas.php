<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cargasmasivas".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property int|null $total_trabajadores
 * @property int|null $total_success
 * @property int|null $tofal_error
 * @property string|null $archivo
 * @property string|null $create_date
 * @property int|null $create_user
 * @property string|null $delete_date
 * @property int|null $delete_user
 */
class Cargasmasivas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cargasmasivas';
    }

    public $file_excel;
    public $envia_form;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_excel'], 'file','extensions' => 'csv', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2],
            [['id_trabajador', 'id_empresa','id_pais','id_linea','id_ubicacion', 'total_trabajadores', 'total_success', 'total_error', 'create_user', 'delete_user',
            'extra1','extra2','extra3','extra4','extra5','extra6','extra7','extra8','extra9','extra10'
            ,'extra11','extra12','extra13','extra14','extra15','extra16','extra17','extra18','extra19','extra20','extra21','extra22','extra23','extra24','extra25','id_nivel1','id_nivel2','id_nivel3','id_nivel4'], 'integer'],
            [['create_date', 'delete_date'], 'safe'],
            [['id_empresa','file_excel'], 'required','on' =>['carga']],
            [['id_empresa','anio','id_trabajador','file_excel','extra1'], 'required','on' =>['cargapoe']],
            [['archivo'], 'string', 'max' => 300],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_trabajador' => Yii::t('app','Identificador Trabajador'),
            'id_empresa' => Yii::t('app','Empresa'),

            'id_nivel1' => Yii::t('app', 'Nivel 1'),
            'id_nivel2' => Yii::t('app', 'Nivel 2'),
            'id_nivel3' => Yii::t('app', 'Nivel 3'),
            'id_nivel4' => Yii::t('app', 'Nivel 4'),
            
            'id_pais' => Yii::t('app', 'País'),
            'id_linea' => Yii::t('app', 'Linea'),
            'id_ubicacion' => Yii::t('app', 'Ubicación'),
            'anio' => Yii::t('app','Año'),
            'total_trabajadores' => Yii::t('app','Total de Trabajadores'),
            'total_success' => Yii::t('app','Total Exitos'),
            'total_error' => Yii::t('app','Total Errores'),
            'archivo' => Yii::t('app','Archivo CSV'),
            'create_date' => Yii::t('app','Fecha Creación'),
            'create_user' => Yii::t('app','Registró'),
            'delete_date' => Yii::t('app','Fecha Baja'),
            'delete_user' => Yii::t('app','Eliminó'),
            'file_excel' => Yii::t('app', 'Listado Trabajadores .CSV'),
            'extra1' => Yii::t('app', '5° Columna - Extra 1'),
            'extra2' => Yii::t('app', '6° Columna - Extra 2'),
            'extra3' => Yii::t('app', '7° Columna - Extra 3'),
            'extra4' => Yii::t('app', '8° Columna - Extra 4'),
            'extra5' => Yii::t('app', '9° Columna - Extra 5'),
            'extra6' => Yii::t('app', '10° Columna - Extra 6'),
            'extra7' => Yii::t('app', '11° Columna - Extra 7'),
            'extra8' => Yii::t('app', '12° Columna - Extra 8'),
            'extra9' => Yii::t('app', '13° Columna - Extra 9'),
            'extra10' => Yii::t('app', '14° Columna - Extra 10'),
            'extra11' => Yii::t('app', '15° Columna - Extra 11'),
            'extra12' => Yii::t('app', '16° Columna - Extra 12'),
            'extra13' => Yii::t('app', '17° Columna - Extra 13'),
            'extra14' => Yii::t('app', '18° Columna - Extra 14'),
            'extra15' => Yii::t('app', '19° Columna - Extra 15'),
            'extra16' => Yii::t('app', '20° Columna - Extra 16'),
            'extra17' => Yii::t('app', '21° Columna - Extra 17'),
            'extra18' => Yii::t('app', '22° Columna - Extra 18'),
            'extra19' => Yii::t('app', '23° Columna - Extra 19'),
            'extra20' => Yii::t('app', '24° Columna - Extra 20'),
            'extra21' => Yii::t('app', '25° Columna - Extra 21'),
            'extra22' => Yii::t('app', '26° Columna - Extra 22'),
            'extra23' => Yii::t('app', '27° Columna - Extra 23'),
            'extra24' => Yii::t('app', '28° Columna - Extra 24'),
            'extra25' => Yii::t('app', '29° Columna - Extra 25'),
        ];
    }

    /**
     * Gets query for [[Trabajadores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajadores()
    {
        return $this->hasMany(Trabajadores::className(), ['id_cargamasiva' => 'id']);
    }

      /**
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa']);
    }

    public function getUCaptura()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'create_user']);
    }

    /**
     * Gets query for [[Consultorios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoes()
    {
        return $this->hasMany(Poes::className(), ['id_ordenpoetrabajador' => 'id'])->orderBy(['id'=>SORT_ASC])->where(['origen'=>3]);
    }

    public function getPais()
    {
        return $this->hasOne(Paises::class, ['id' => 'id_pais']);
    }

    public function getLinea()
    {
        return $this->hasOne(Lineas::class, ['id' => 'id_linea']);
    }

    public function getUbicacion()
    {
        return $this->hasOne(Ubicaciones::class, ['id' => 'id_ubicacion']);
    }

    public function getNivel1()
    {
        return $this->hasOne(NivelOrganizacional1::class, ['id' => 'id_nivel1']);
    }
    public function getNivel2()
    {
        return $this->hasOne(NivelOrganizacional2::class, ['id' => 'id_nivel2']);
    }
    public function getNivel3()
    {
        return $this->hasOne(NivelOrganizacional3::class, ['id' => 'id_nivel3']);
    }
    public function getNivel4()
    {
        return $this->hasOne(NivelOrganizacional4::class, ['id' => 'id_nivel4']);
    }

}
