<?php
 $ubicaciones = Ubicaciones::find()->where(['id_empresa'=>$model->id])->andWhere(['id_pais'=>$pais->id_pais])->andWhere(['id_linea'=>$linea->id])->andWhere(['status'=>1])->all();
                                            $ubicaciones = null;
                                            if($ubicaciones){
                                                $retubicaciones = '<div class="nodo width'.$tamanio_l.'  text-center font10" style="top: 210px;left: '.$x_linea.'px;"><div class="background_color2 rounded-3 p-1"><div class="color5 font10">UBICACIONES</div>';
                                                foreach($ubicaciones as $keyu =>$ubicacion){
                                                    $retubicaciones .= '<span class="badge rounded-pill bg-light text-dark font10"><i class="bi bi-dot"></i>'.$ubicacion->ubicacion.'</span>';
                                                }
                                                $retubicaciones .= '</div></div>';
                                            } else {
                                                $retubicaciones = '<div class="nodo width'.$tamanio_l.'  text-center font9 color11 " style="top: 210px;left: '.$x_linea.'px;"><div class="background_color2 rounded-3 p-1"><div class="color5 font10">UBICACIONES</div>No Definidas</div></div>';
                                            }
                                            //$retlineas .= $retubicaciones;


                                            $areas = Areas::find()->where(['id_empresa'=>$model->id])->andWhere(['id_pais'=>$pais->id_pais])->andWhere(['id_linea'=>$linea->id])->andWhere(['status'=>1])->all();
                                            $areas = null;
                                            if($areas){
                                                $retareas = '<div class="nodo width'.$tamanio_l.'  text-center font10" style="top: 290px;left: '.$x_linea.'px;"><div class="background_color2 rounded-3 p-1"><div class="color5 font10">ÁREAS</div>';
                                                foreach($areas as $keya =>$area){
                                                    $retareas .= '<span class="badge rounded-pill bg-light text-dark font10"><i class="bi bi-dot"></i>'.$area->area.'</span>';
                                                }
                                                $retareas .= '</div></div>';
                                            } else {
                                                $retareas = '<div class="nodo width'.$tamanio_l.'  text-center font9 color11 " style="top: 290px;left: '.$x_linea.'px;"><div class="background_color2 rounded-3 p-1"><div class="color5 font10">ÁREAS</div>No Definidas</div></div>';
                                            }
                                            //$retlineas .= $retareas;


                                            $consultorios = Consultorios::find()->where(['id_empresa'=>$model->id])->andWhere(['id_pais'=>$pais->id_pais])->andWhere(['id_linea'=>$linea->id])->andWhere(['status'=>1])->all();
                                            $consultorios = null;
                                            if($consultorios){
                                                $retconsultorios = '<div class="nodo width'.$tamanio_l.'  text-center font10" style="top: 370px;left: '.$x_linea.'px;"><div class="background_color2 rounded-3 p-1"><div class="color5 font10">CONSULTORIOS</div>';
                                                foreach($consultorios as $keyc =>$consultorio){
                                                    $retconsultorios .= '<span class="badge rounded-pill bg-light text-dark font10"><i class="bi bi-dot"></i>'.$consultorio->consultorio.'</span>';
                                                }
                                                $retconsultorios .= '</div></div>';
                                            } else {
                                                $retconsultorios = '<div class="nodo width'.$tamanio_l.'  text-center font9 color11 " style="top: 370px;left: '.$x_linea.'px;"><div class="background_color2 rounded-3 p-1"><div class="color5 font10">CONSULTORIOS</div>No Definidos</div></div>';
                                            }
                                            //$retlineas .= $retconsultorios;


                                            $turnos = Turnos::find()->where(['id_empresa'=>$model->id])->andWhere(['id_pais'=>$pais->id_pais])->andWhere(['id_linea'=>$linea->id])->andWhere(['status'=>1])->all();
                                            $turnos = null;
                                            if($turnos){
                                                $retturnos = '<div class="nodo width'.$tamanio_l.'  text-center font10" style="top: 450px;left: '.$x_linea.'px;"><div class="background_color2 rounded-3 p-1"><div class="color5 font10">TURNOS</div>';
                                                foreach($turnos as $keyt =>$turno){
                                                    $retturnos .= '<span class="badge rounded-pill bg-light text-dark font10"><i class="bi bi-dot"></i>'.$turno->turno.'</span>';
                                                }
                                                $retturnos .= '</div></div>';
                                            } else {
                                                $retturnos = '<div class="nodo width'.$tamanio_l.'  text-center font9 color11 " style="top: 450px;left: '.$x_linea.'px;"><div class="background_color2 rounded-3 p-1"><div class="color5 font10">TURNOS</div>No Definidos</div></div>';
                                            }
                                            //$retlineas .= $retturnos;


                                            $programas = Programaempresa::find()->where(['id_empresa'=>$model->id])->andWhere(['id_pais'=>$pais->id_pais])->andWhere(['id_linea'=>$linea->id])->andWhere(['status'=>1])->all();
                                            $programas = null;
                                            if($programas){
                                                $retprogramasalud = '<div class="nodo width'.$tamanio_l.'  text-center font10" style="top: 460px;left: '.$x_linea.'px;"><div class="background_color2 rounded-3 p-1"><div class="color5 font10">PROGRAMAS DE SALUD</div>';
                                                foreach($programas as $keyp =>$programa){
                                                    if($programa->programasalud){
                                                        $retprogramasalud .= '<span class="badge rounded-pill bg-light text-dark font10"><i class="bi bi-dot"></i>'.$programa->programasalud->nombre.'</span>';
                                                    } 
                                                }
                                                $retprogramasalud .= '</div></div>';
                                            } else {
                                                $retprogramasalud = '<div class="nodo width'.$tamanio_l.'  text-center font9 color11 " style="top: 460px;left: '.$x_linea.'px;"><div class="background_color2 rounded-3 p-1"><div class="color5 font10">PROGRAMAS DE SALUD</div>No Definidos</div></div>';
                                            
                                            }
                                            //$retlineas .= $retprogramasalud;

        
                                            $posicionnew_linea = $x_linea - 20;
                                            $linealateral_contenido .= '<span style="border-left: 1px solid #483AA0; width:2px; height:295px; z-index: 1;position: absolute;top: 180px;left: '.$posicionnew_linea.'px;"></span>';
                                            $lineavertical_contenidolinea .= '<span style="border-bottom: 1px solid #483AA0; width:20px; height:1px; z-index: 1;position: absolute;top: 180px;left: '.$posicionnew_linea.'px;"></span>';
                                            $lineavertical_contenidoubicacion .= '<span style="border-bottom: 1px solid #483AA0; width:20px; height:1px; z-index: 1;position: absolute;top: 220px;left: '.$posicionnew_linea.'px;"></span>';
                                            $lineavertical_contenidoarea .= '<span style="border-bottom: 1px solid #483AA0; width:20px; height:1px; z-index: 1;position: absolute;top: 310px;left: '.$posicionnew_linea.'px;"></span>';
                                            $lineavertical_contenidoconsultorio .= '<span style="border-bottom: 1px solid #483AA0; width:20px; height:1px; z-index: 1;position: absolute;top: 390px;left: '.$posicionnew_linea.'px;"></span>';
                                            $lineavertical_contenidoprograma .= '<span style="border-bottom: 1px solid #483AA0; width:20px; height:1px; z-index: 1;position: absolute;top: 475px;left: '.$posicionnew_linea.'px;"></span>';

                                            $linealateral_contenido .= $lineavertical_contenidolinea.$lineavertical_contenidoubicacion.$lineavertical_contenidoarea.$lineavertical_contenidoconsultorio.$lineavertical_contenidoprograma;
                                            //$retlineas .= $linealateral_contenido;
?>