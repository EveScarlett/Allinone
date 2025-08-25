        <!-- <div class="row">
            <div class="col-lg-12">
                <div class="row my-3">
                    <div class="col-lg-3 p-2">
                        <div class="card text-light bgcolor15 p-3">
                            <h6 class="title1 text-uppercase text-center"><?=$limite_usuarios; ?></h6>
                            <label class="font11  text-center">Usuarios Asignados <span
                                    class="mx-2"><?php echo '<i class="bi bi-person-fill"></i>';?></span></label>
                        </div>
                    </div>
                    <div class="col-lg-1 p-2">
                        <div class="card text-light p-3 <?php echo $class_disponible;?>">
                            <h6 class="title1 text-uppercase text-center"><?=$total_disponible; ?></h6>
                            <label class="font11  text-center">Disponibles</label>
                        </div>
                    </div>
                    <div class="col-lg-2 p-2" style="display:none;">
                        <div class="card shadow">
                           
                            <div class="card-body">
                            <?= ChartJs::widget([
                                    'type' => 'pie', 
                                    'id' => 'structurePie11',
                                    'options' => [
                                        'height' => 100,
                                        'width' => 100
                                    ],
                                    'data' => [
                                        'radius' =>  "90%",
                                        'labels' => ['Utilizados: '.$total_utilizado, 'Disponibles: '.$total_disponible], // Your labels
                                        'datasets' => [
                                            [
                                                'data' => [round($utilizados,2), round($disponibles,2)], // Your dataset
                                                'label' => '',
                                                'backgroundColor' => [
                                                        '#FF597B',
                                                        '#01D28E',
                                                        '#B15EFF'
                                                ],
                                                'borderColor' =>  [
                                                        '#FF597B',
                                                        '#01D28E',
                                                        '#B15EFF'
                                                ],
                                                'borderWidth' => 1,
                                                'hoverBorderColor'=>["#999","#999","#999"],                
                                            ]
                                        ]
                                    ],
                                ]);?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 p-2">
                        <div class="card text-light bgcolor8 p-3">
                            <h6 class="title1 text-uppercase text-center"><?=$administradores; ?></h6>
                            <label class="font11 text-center">Administradores Asignados<span
                                    class="mx-2"><?php echo '<i class="bi bi-eyeglasses"></i>';?></span></label>
                            <div>
                                <div class="progress mt-2">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated <?php echo $class_disponible1;?>"
                                        style="width:<?php echo $porcen_administradores;?>%"></div>
                                </div>
                                <div class="font10 text-center mt-1">
                                    Utilizados <?php echo $utilizados_administradores.'/'.$administradores;?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 p-2">
                        <div class="card text-light bgcolor9 p-3">
                            <h6 class="title1 text-uppercase text-center"><?=$medicos; ?></h6>
                            <label class="font11 text-center">Médicos Asignados<span
                                    class="mx-2"><?php echo '<i class="bi bi-person-raised-hand"></i>';?></span></label>

                            <div>
                                <div class="progress mt-2">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated  <?php echo $class_disponible2;?>"
                                        style="width:<?php echo $porcen_medicos;?>%"></div>
                                </div>
                                <div class="font10 text-center mt-1">
                                    Utilizados <?php echo $utilizados_medicos.'/'.$medicos;?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 p-2">
                        <div class="card text-light bgnocumple p-3">
                            <h6 class="title1 text-uppercase text-center"><?=$medicoslaborales; ?></h6>
                            <label class="font11 text-center">Firmas Médicos Laborales<span
                                    class="mx-2"><?php echo $icon_firma;?></span></label>
                            <div>
                                <div class="progress mt-2" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Utilizado">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated  <?php echo $class_disponible3;?>"
                                        style="width:<?php echo $porcen_medicoslaborales;?>%"></div>
                                </div>
                                <div class="font10 text-center mt-1">
                                    Utilizados <?php echo $utilizados_medicoslaborales.'/'.$medicoslaborales;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="row my-3">
                    <div class="col-lg-12 p-2">
                        <div class="card shadow small text-center font500 p-2 title2">
                            Información de Trabajores
                        </div>
                    </div>
                    <div class="col-lg-6 p-2">
                        <div class="card text-light bgcolor3 p-3">
                            <h6 class="title1 text-uppercase text-center"><?=$total; ?></h6>
                            <label class="font10  text-center">Trabajadores <span
                                    class="mx-2"><?php echo $icon_person;?></span></label>
                        </div>
                    </div>
                    <div class="col-lg-3 p-2">
                        <div class="card text-light bgcumple p-3">
                            <h6 class="title1 text-uppercase text-center"><?=$total_activo; ?></h6>
                            <label class="font10  text-center">Activos</label>
                        </div>
                    </div>
                    <div class="col-lg-3 p-2">
                        <div class="card text-light bgcolor6 p-3">
                            <h6 class="title1 text-uppercase text-center"><?=$total_baja; ?></h6>
                            <label class="font10  text-center">Bajas</label>
                        </div>
                    </div>
                    <div class="col-lg-4 p-2">
                        <div class="card shadow">
                            <div class="p-1">
                                <h6 class="small text-center font500">Trabajadores Activos</h6>
                            </div>
                            <div class="card-body">
                                <?= ChartJs::widget([
                                    'type' => 'pie', 
                                    'id' => 'structurePie2',
                                    'options' => [
                                        'height' => 100,
                                        'width' => 100
                                    ],
                                    'data' => [
                                        'radius' =>  "90%",
                                        'labels' => ['Hombres: '.$total_hombres, 'Mujeres: '.$total_mujeres, 'Otros: '.$total_otros], // Your labels
                                        'datasets' => [
                                            [
                                                'data' => [round($hombres,2), round($mujeres,2), round($otros,2)], // Your dataset
                                                'label' => '',
                                                'backgroundColor' => [
                                                        '#525CEB',
                                                        '#FE7BE5',
                                                        '#B15EFF'
                                                ],
                                                'borderColor' =>  [
                                                        '#525CEB',
                                                        '#FE7BE5',
                                                        '#B15EFF'
                                                ],
                                                'borderWidth' => 1,
                                                'hoverBorderColor'=>["#999","#999","#999"],                
                                            ]
                                        ]
                                    ],
                                ]);?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 p-2">
                        <div class="card shadow">
                            <div class="p-1">
                                <h6 class="small text-center font500">Trabajadores Activos por Puestos de Trabajo - Top
                                    10</h6>
                            </div>
                            <div class="card-body">
                                <?= ChartJs::widget([
                                    'type' => 'bar',  
                                    'options' => [
                                        'height' => 60,
                                        'width' => 100
                                    ],
                                    'data' => [
                                        'radius' =>  "90%",
                                        'labels' => $lbl_puestos, 
                                        'datasets' => [
                                            [
                                                'data' => $data_puestos, 
                                                'label' => '',
                                                'backgroundColor' => $colores_puestos,
                                                'borderColor' =>  [
                                                        '#fff',
                                                        '#fff',
                                                        '#fff'
                                                ],
                                                'borderWidth' => 1,
                                                'hoverBorderColor'=>["#999","#999","#999"],                
                                            ]
                                        ]
                                    ],
                                    ]);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row my-3">
                    <div class="col-lg-12 p-2">
                        <div class="card shadow small text-center font500 p-2 title2">
                            Información de Consultorio
                        </div>
                    </div>
                    <div class="col-lg-12 p-2">
                        <div class="card shadow small text-center font500 p-2 text-light bg-dark title2">
                            Cuestionarios & Evaluación Antropométrica
                        </div>
                    </div>
                    <div class="col-lg-4 p-2">
                        <div class="card shadow">
                            <div class="p-1">
                                <h6 class="small text-center font500">Cuestionarios Nórdicos</h6>
                            </div>
                            <div class="card-body">
                                <?= ChartJs::widget([
                                    'type' => 'doughnut', 
                                    'id' => 'structurePie3',
                                    'options' => [
                                        'height' => 100,
                                        'width' => 100
                                    ],
                                    'data' => [
                                        'radius' =>  "90%",
                                        'labels' => ['Hombres: '.$nordicos_hombres, 'Mujeres: '.$nordicos_mujeres, 'Otros: '.$nordicos_otros], // Your labels
                                        'datasets' => [
                                            [
                                                'data' => [round($totalnordicos_hombres,2), round($totalnordicos_mujeres,2), round($totalnordicos_otros,2)], // Your dataset
                                                'label' => '',
                                                'backgroundColor' => [
                                                        '#EEC759',
                                                        '#37B5B6',
                                                        '#FF6969'
                                                ],
                                                'borderColor' =>  [
                                                        '#EEC759',
                                                        '#37B5B6',
                                                        '#FF6969'
                                                ],
                                                'borderWidth' => 1,
                                                'hoverBorderColor'=>["#999","#999","#999"],                
                                            ]
                                        ]
                                    ],
                                ]);?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 p-2">
                        <div class="card shadow">
                            <div class="p-1">
                                <h6 class="small text-center font500">Evaluaciónes Antropométricas</h6>
                            </div>
                            <div class="card-body">
                                <?= ChartJs::widget([
                                    'type' => 'doughnut', 
                                    'id' => 'structurePie4',
                                    'options' => [
                                        'height' => 100,
                                        'width' => 100
                                    ],
                                    'data' => [
                                        'radius' =>  "90%",
                                        'labels' => ['Hombres: '.$antropometricas_hombres, 'Mujeres: '.$antropometricas_mujeres, 'Otros: '.$antropometricas_otros], // Your labels
                                        'datasets' => [
                                            [
                                                'data' => [round($totalantropometricas_hombres,2), round($totalantropometricas_mujeres,2), round($totalantropometricas_otros,2)], // Your dataset
                                                'label' => '',
                                                'backgroundColor' => [
                                                        '#FF6969',
                                                        '#7360DF',
                                                        '#96E9C6'
                                                ],
                                                'borderColor' =>  [
                                                        '#FF6969',
                                                        '#7360DF',
                                                        '#96E9C6'
                                                ],
                                                'borderWidth' => 1,
                                                'hoverBorderColor'=>["#999","#999","#999"],                
                                            ]
                                        ]
                                    ],
                                ]);?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="row">
                            <div class="col-lg-12 p-2">
                                <div class="card text-light bgcolor8 p-3">
                                    <h6 class="title1 text-uppercase text-center"><?=count($nordicos); ?></h6>
                                    <label class="font10  text-center">Cuestionarios Nórdicos</label>
                                </div>
                            </div>
                            <div class="col-lg-12 p-2">
                                <div class="card text-light bgcolor9 p-3">
                                    <h6 class="title1 text-uppercase text-center"><?=count($antropometricas); ?></h6>
                                    <label class="font10  text-center">Evaluaciones Antropométricas</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="row my-3">
                    <div class="col-lg-12 p-2">
                        <div class="card shadow small text-center font500 p-2 text-light bg-dark title2">
                            Historias Clínicas
                        </div>
                    </div>
                    <div class="col-lg-3 p-2">
                        <div class="row">
                            <div class="col-lg-12 p-2">
                                <div class="card text-light bgcolor11 p-3">
                                    <h6 class="title1 text-uppercase text-center"><?=$total_historias; ?></h6>
                                    <label class="font10  text-center">Historias Registradas</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 p-2">
                        <div class="card shadow">
                            <div class="p-1">
                                <h6 class="small text-center font500">Status Historias Clínicas</h6>
                            </div>
                            <div class="card-body">
                                <?= ChartJs::widget([
                                            'type' => 'polarArea', 
                                            'id' => 'structurePie10',
                                            'options' => [
                                                'height' => 120,
                                                'width' => 100
                                            ],
                                            'data' => [
                                                'radius' =>  "90%",
                                                'labels' => $statushc, 
                                                'datasets' => [
                                                    [
                                                        'data' => $statushctotal,
                                                        'label' => '',
                                                        'backgroundColor' => $statushccolor,
                                                        'borderColor' =>  [
                                                                '#fff',
                                                                '#fff',
                                                                '#fff'
                                                        ],
                                                        'borderWidth' => 1,
                                                        'hoverBorderColor'=>["#999","#999","#999"],                
                                                    ]
                                                ]
                                            ],
                                            ]);?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 p-2">
                        <div class="card shadow">
                            <div class="p-1">
                                <h6 class="small text-center font500">Trabajadores Activos con Historia Clínica</h6>
                            </div>
                            <div class="card-body">
                                <?= ChartJs::widget([
                                    'type' => 'bar',  
                                    'options' => [
                                        'height' => 100,
                                        'width' => 100
                                    ],
                                    'data' => [
                                        'radius' =>  "90%",
                                        'labels' => ['Realizados','Faltantes'], 
                                        'datasets' => [
                                            [
                                                'data' => [$trab_conhistoria,$trab_sinhistoria], 
                                                'label' => '',
                                                'backgroundColor' => ['#B799FF','#FFB84C'],
                                                'borderColor' =>  [
                                                        '#fff',
                                                        '#fff',
                                                        '#fff'
                                                ],
                                                'borderWidth' => 1,
                                                'hoverBorderColor'=>["#999","#999","#999"],                
                                            ]
                                        ]
                                    ],
                                    ]);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row my-3">
                    <div class="col-lg-12 p-2">
                        <div class="card shadow small text-center font500 p-2 text-light bg-dark title2">
                            Consultas Clínicas
                        </div>
                    </div>
                    <div class="col-lg-3 p-2">
                        <div class="row">
                            <div class="col-lg-12 p-2">
                                <div class="card text-light bgcolor10 p-3">
                                    <h6 class="title1 text-uppercase text-center"><?=$total_consultas; ?></h6>
                                    <label class="font10  text-center">Consultas Registradas</label>
                                </div>
                            </div>
                            <div class="col-lg-12 p-2">
                                <div class="card shadow">
                                    <div class="p-1">
                                        <h6 class="small text-center font500">Consultas Trabajadores</h6>
                                    </div>
                                    <div class="card-body">
                                        <?= ChartJs::widget([
                                            'type' => 'pie', 
                                            'id' => 'structurePie6',
                                            'options' => [
                                                'height' => 100,
                                                'width' => 100
                                            ],
                                            'data' => [
                                                'radius' =>  "90%",
                                                'labels' => ['Activos: '.count($consultas), 'Baja: '.($total_consultas-count($consultas))], // Your labels
                                                'datasets' => [
                                                    [
                                                        'data' => [round(count($consultas),2), round($total_consultas-count($consultas),2)], // Your dataset
                                                        'label' => '',
                                                        'backgroundColor' => [
                                                                '#6895D2',
                                                                '#BB2525',
                                                                '#FF6969'
                                                        ],
                                                        'borderColor' =>  [
                                                                '#6895D2',
                                                                '#BB2525',
                                                                '#FF6969'
                                                        ],
                                                        'borderWidth' => 1,
                                                        'hoverBorderColor'=>["#999","#999","#999"],                
                                                    ]
                                                ]
                                            ],
                                            ]);?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 p-2">
                        <div class="card shadow">
                            <div class="p-1">
                                <h6 class="small text-center font500">Consultas por Tipo</h6>
                            </div>
                            <div class="card-body">
                                <?php
                                
                                ?>
                                <?= ChartJs::widget([
                                    'type' => 'line', 
                                    'id' => 'structureBar5', 
                                    'options' => [
                                        'height' => 50,
                                        'width' => 100
                                    ],
                                    'data' => [
                                        'radius' =>  "90%",
                                        'labels' => $tipoexamen, 
                                        'datasets' => [
                                            [
                                                'data' => $tipoexamentotal,
                                                'label' => '',
                                                'fill'=> false,
                                                'borderColor'=> 'rgb(75, 192, 192)',
                                                'tension'=> 0.1,
                                                'hoverBorderColor'=>["#999","#999","#999"],                
                                            ]
                                        ]
                                    ],
                                    ]);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-8">
                <div class="row my-3">
                    <div class="col-lg-12 p-2">
                        <div class="card shadow small text-center font500 p-2 text-light bg-dark title2">
                            POES
                        </div>
                    </div>
                    <div class="col-lg-3 p-2">
                        <div class="row">
                            <div class="col-lg-12 p-2">
                                <div class="card text-light bgcolor12 p-3">
                                    <h6 class="title1 text-uppercase text-center"><?=$total_poesorig; ?></h6>
                                    <label class="font10  text-center">Poes Registrados</label>
                                </div>
                            </div>
                            <div class="col-lg-12 p-2">
                                <div class="card shadow">
                                    <div class="p-1">
                                        <h6 class="small text-center font500">Poes realizados por Año</h6>
                                    </div>
                                    <div class="card-body">
                                        <?= ChartJs::widget([
                                            'type' => 'line', 
                                            'id' => 'structureBar6',
                                            'options' => [
                                                'height' => 120,
                                                'width' => 100
                                            ],
                                            'data' => [
                                                'radius' =>  "90%",
                                                'labels' => $label_arrpoes, 
                                                'datasets' => [
                                                    [
                                                        'data' => $data_arrpoes, 
                                                        'label' => '',
                                                        'fill'=> false,
                                                        'borderColor'=> 'rgb(73, 66, 228)',
                                                        'tension'=> 0.1,
                                                        'hoverBorderColor'=>["#999","#999","#999"],                 
                                                    ]
                                                ]
                                            ],
                                            ]);?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 p-2">
                        <div class="card shadow">
                            <div class="p-1">
                                <h6 class="small text-center font500">Estudios Realizados</h6>
                            </div>
                            <div class="card-body">
                                <?php
                               
                                ?>
                                <?= ChartJs::widget([
                                    'type' => 'bar', 
                                    'id' => 'structureBar7', 
                                    'options' => [
                                        'height' => 50,
                                        'width' => 100
                                    ],
                                    'data' => [
                                        'radius' =>  "90%",
                                        'labels' => $label_arrestudios, 
                                        'datasets' => [
                                            [
                                                'data' => $data_arrestudios, 
                                                'backgroundColor' => $colores_arrestudios,
                                                'label' => '',
                                                'fill'=> false,
                                                'borderColor'=> 'rgb(75, 192, 192)',
                                                'tension'=> 0.1,
                                                'hoverBorderColor'=>["#999","#999","#999"],                
                                            ]
                                        ]
                                    ],
                                    ]);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row my-3">
                    <div class="col-lg-12 p-2">
                        <div class="card shadow small text-center font500 p-2 text-light bg-dark title2">
                            Incapacidades
                        </div>
                    </div>
                    <div class="col-lg-6 p-2">
                        <div class="card text-light bgcolor13 p-3">
                            <h6 class="title1 text-uppercase text-center"><?=$incapacidades; ?></h6>
                            <label class="font10  text-center">Incapacidades Registradas<span class="mx-2"><i
                                        class="bi bi-prescription2"></i></span></label>
                        </div>
                    </div>
                    <div class="col-lg-3 p-2">
                        <div class="card text-light bgcumple p-3">
                            <h6 class="title1 text-uppercase text-center"><?=$incapacidadesactivas; ?></h6>
                            <label class="font10  text-center">En curso</label>
                        </div>
                    </div>
                    <div class="col-lg-3 p-2">
                        <div class="card text-light bgcolor6 p-3">
                            <h6 class="title1 text-uppercase text-center"><?=$incapacidadesvencidas; ?></h6>
                            <label class="font10  text-center">Finalizadas</label>
                        </div>
                    </div>
                    <div class="col-lg-6 p-2">
                        <div class="card shadow">
                            <div class="p-1">
                                <h6 class="small text-center font500">Por Ramo</h6>
                            </div>
                            <div class="card-body">
                                <?php
                                
                                ?>
                                <?= ChartJs::widget([
                                    'type' => 'doughnut', 
                                    'id' => 'structureline10',
                                    'options' => [
                                        'height' => 100,
                                        'width' => 100
                                    ],
                                    'data' => [
                                        'radius' =>  "90%",
                                        'labels' => $riesgostiposinca_label, 
                                        'datasets' => [
                                            [
                                                'data' => $riesgostiposinca_data, 
                                                'label' => '',
                                                'backgroundColor' => $riesgostiposinca_color,
                                                'borderColor' =>  $riesgostiposinca_color,
                                                'borderWidth' => 1,
                                                'hoverBorderColor'=>["#999","#999","#999"],                
                                            ]
                                        ]
                                    ],
                                ]);?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 p-2">
                        <div class="row">
                            <div class="col-lg-12 p-2">
                                <div class="card shadow">
                                    <div class="p-1">
                                        <h6 class="small text-center font500">Por Tipo</h6>
                                    </div>
                                    <div class="card-body">
                                        <?php ?>
                                        <?= ChartJs::widget([
                                            'type' => 'line', 
                                            'id' => 'structureBar20',
                                            'options' => [
                                                'height' => 80,
                                                'width' => 100
                                            ],
                                            'data' => [
                                                'radius' =>  "90%",
                                                'labels' => $tipoexameninca_label, 
                                                'datasets' => [
                                                    [
                                                        'data' => $tipoexameninca_data, 
                                                        'label' => '',
                                                        'fill'=> false,
                                                        'borderColor'=> 'rgb(218, 12, 129)',
                                                        'tension'=> 0.1,
                                                        'hoverBorderColor'=>["#999","#999","#999"],                 
                                                    ]
                                                ]
                                            ],
                                        ]);?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->