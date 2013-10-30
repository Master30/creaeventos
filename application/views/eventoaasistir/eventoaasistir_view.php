<?php
//$icono_confirmar = array(    'src' => 'img/iconos/invitado.png',    'title' => 'Regístrate al evento',    'width' => '30',    'height' => '30',);
//$icono_rechazar = array(    'src' => 'img/iconos/rechazar.png',    'title' => 'Cancela tu registro al evento',    'width' => '30',    'height' => '30',);
//$icono_reingresar = array(    'src' => 'img/iconos/reingresar.png',    'title' => 'Reg&iacute;strate nuevamente al evento',    'width' => '30',    'height' => '30',);
$icono_escarapela = array(    'src' => 'img/iconos/escarapela.png',    'title' => 'Imprime tu escarapela',    'width' => '30',    'height' => '30',);
$icono_certificado = array(    'src' => 'img/iconos/certificado.png',    'title' => 'Imprime tu certificado',    'width' => '30',    'height' => '30',);
$informe_pdf = array('width' => '800','height' => '600','scrollbars' => 'yes','status' => 'yes','resizable' => 'yes','screenx' => '0','screeny' => '0');
?>

<div id="form" class="container_12">
    <div class="grid_11" id="registro_view">
        <?php echo img('img/plantillas/mis_eventos.png') ?>
        <table cellpadding="0" cellspacing="0" border="" class="display" id="tabla_usuarios" style="font-size: 15px;">
            <thead> 
                <tr> 
                    <th class="primero" width="50%">Evento</th>                                                  
                    <th width="20%">Fecha de Inicio</th> 
                    <th width="20%">Estado</th>
                    <th class="ultimo" width="10%">Opciones</th>
                </tr>  
            </thead>
            <tbody>
                <?php foreach ($eventos as $evento): ?>
                <tr>
                    <td><?php echo $evento->NombreEvento; ?></td>
                    <td><?php echo $this->auditoria_model->formato_fecha($evento->FechaInicio); ?></td>
                    <?php
                    switch ($evento->Estado){
                        /*
                         * Estado 0 = Invitado
                         */
                        case NULL:
                            if($evento->Estado_Evento == 1){ ?>
                                <td>Has sido invitado</td>
                                <td>
                                    <?php
                                    //Confirmar registro
                                    echo form_open('mis_eventos/confirmar_asistencia');
                                    echo form_hidden('id_evento', $evento->PK_IdEvento);
                                    echo form_hidden('id_invitacion', $evento->PK_IdInvitacion);
                                    echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/invitado.png', 'title' => 'Regístrate al evento', 'width' => '30', 'height' => '30'));
                                    echo form_close();
                                    //echo anchor(site_url('mis_eventos/confirmar_asistencia/'.$evento->PK_IdEvento.'/'.$evento->PK_IdInvitacion), img($icono_confirmar));
                                    ?>
                                </td><?php
                            }else{ ?>
                                <td>Invitaci&oacute;n vencida</td>
                                <td></td><?php
                            }
                            break;
                        /*
                         * Estado 1 = Registrado
                         */
                        case 1: ?>
                            <td>Est&aacute;s registrado</td>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <?php
                                            //Cancelar registro
                                            echo form_open('mis_eventos/rechazar_asistencia');
                                            echo form_hidden('id_evento', $evento->PK_IdEvento);
                                            echo form_hidden('id_invitacion', $evento->PK_IdInvitacion);
                                            echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/rechazar.png', 'title' => 'Cancela tu registro al evento', 'width' => '30', 'height' => '30'));
                                            echo form_close();
                                            //echo anchor(site_url('mis_eventos/rechazar_asistencia/'.$evento->PK_IdEvento.'/'.$evento->PK_IdInvitacion), img($icono_rechazar));
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($evento->PermitirImpresionUsuario == 1){
                                                echo anchor_popup(site_url('escarapela/individual_usuario/'.$evento->PK_IdEvento), img($icono_escarapela), $informe_pdf);
                                                //echo anchor_popup(site_url('escarapela/individual'), img($icono_escarapela), $informe_pdf);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <?php break;
                        /*
                         * Estado 2 = Asistido
                         */
                        case 2: ?>
                            <td>Est&aacute;s en el evento</td>
                            <td>
                                <?php echo img(array('src' => 'img/iconos/asistio.png', 'title' => 'Has asistido al evento', 'width' => '30', 'height' => '30')) ?>
                            </td>
                            <?php break;
                        /*
                         * Estado 3 = Desertado
                         */
                        case 3: ?>
                            <td>Abandonaste el evento</td>
                            <td><?php echo img(array('src' => 'img/iconos/desertado.png', 'title' => 'Desertar del evento', 'width' => '30', 'height' => '30'));?></td>
                            <?php break;
                        /*
                         * Estado 4 = Registro Cancelado
                         */
                        case 4:
                            //Si el evento est&aacute; cerrado, no puede reingresar
                            if($evento->Estado_Evento == 1){?>
                                <td>Cancelaste tu registro</td>
                                <td>
                                    <?php
                                    //Reingresar
                                    echo form_open('mis_eventos/reingresar');
                                    echo form_hidden('id_evento', $evento->PK_IdEvento);
                                    echo form_hidden('id_invitacion', $evento->PK_IdInvitacion);
                                    echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/reingresar.png', 'title' => 'Reg&iacute;strate nuevamente al evento', 'width' => '30', 'height' => '30'));
                                    echo form_close();
                                    //echo anchor(site_url('mis_eventos/reingresar/'.$evento->PK_IdEvento.'/'.$evento->PK_IdInvitacion), img($icono_reingresar))
                                    ?>
                                </td>
                            <?php
                            }else{ ?>
                                <td>Cancelaste tu registro</td>
                                <td></td>
                            <?php
                            }
                        break;
                        /*
                         * Estado 5 = Evento finalizado
                         */
                        case 5: ?>
                            <td>Evento finalizado</td>
                            <td>
                                <?php
                                //echo anchor_popup(site_url('informes_controller/certificado_individual/'.$evento->PK_IdEvento), img($icono_certificado), $informe_pdf);
                                echo anchor_popup(site_url('certificado/individual_usuario/'.$evento->PK_IdEvento), img($icono_certificado), $informe_pdf);
                                ?>
                            </td>
                            <?php break;
                        /*
                         * Estado 6 = invitacion ignorada
                         */
                        case 6: ?>
                            <td>Invitaci&oacute;n vencida</td>
                            <td></td>
                            <?php break;
                    }//Fin switch
                    ?>
                </tr>
                <?php endforeach; ?>
            </tbody>  
        </table><br/><br/>
        <div class="clear"></div>
        <?php
        echo form_input(array('type' => 'button', 'name' => 'salir','id' => 'salir','value' => 'Regresar','onclick' => 'history.back()'));
        ?>            
    </div>
</div>

<script type="text/javascript"> 
    $(document).ready(function() {
        $('#tabla_usuarios').dataTable( {
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": true } );
    } );
</script>