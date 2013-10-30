<?php
$icono_confirmar = array(    'src' => 'img/iconos/confirmar.png',    'title' => 'Administrar el evento',    'width' => '30',    'height' => '30');
$icono_modificar_evento = array(    'src' => 'img/iconos/modificar_evento.png',    'title' => 'Modifica el evento',    'width' => '30',    'height' => '30');
$icono_enviar = array(    'src' => 'img/iconos/enviar.png',    'title' => 'Enviar invitaciones por correo al evento',    'width' => '30',    'height' => '30');
$dias = $this->licenciamiento_model->obtener_dias_restantes();
?>
<div id="form" class="container_12">
    <?php
    foreach ($eventos as $evento):
    ?>
    
    <div class="grid_11" id="registro_view">
        <div align="center">
            <?php if($evento->Banner_Evento != NULL){echo img(array('src' => 'img_banner/'.$evento->PK_IdEvento.'/'.$evento->Banner_Evento, 'width' => '900', 'height' => '105'));} ?>
        </div>
        <?php
        echo form_fieldset($evento->NombreEvento, 'class="fieldset"');
        echo form_fieldset_close();
        ?>
        <table width="100%">
            <tr>
                <td colspan="2"><?php echo $evento->DescripcionEvento; ?></td>
            </tr>
        </table><br>
        <table width="100%">
            <tr>
                <td><b>Fecha de inicio: </b><?php echo $this->auditoria_model->formato_fecha($evento->FechaInicio).' - '.$evento->HoraInicio; ?></td>
                <td><b>Fecha de terminaci&oacute;n: </b><?php echo $this->auditoria_model->formato_fecha($evento->FechaFin).' - '.$evento->HoraFin; ?></td>
            </tr>
            <tr>
                <td><b>Lugar: </b><?php echo $evento->DescripcionUbicacion; ?></td>
                <td><b>Direcci&oacute;n: </b><?php echo $evento->Direccion_Completa; ?></td>
            </tr>
        </table><br>
        <table width="100%">
            <tr>
                <td colspan="2"><b>Ciudad: </b><?php echo $evento->Ciudad; ?></td>
            </tr>
        </table>
        
        <?php echo form_fieldset('Opciones', 'class="fieldset"'); ?>
        <table width="100%">
            <tr>
                <td>
                    <?php
                    echo form_open('evento_controller');
                    echo form_submit(array('name' => 'salir','id' => 'salir','value' => 'Regresar'));
                    echo form_close();
                    ?>
                </td>
                <td>
                    <?php if($dias > 0){ ?>
                    <table>
                        <tr>
                            <td>
                                <?php
                                //Administrar evento
                                echo form_open('confirmar_controller');
                                echo form_hidden('id_evento', $evento->PK_IdEvento);
                                echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/confirmar.png', 'title' => 'Administrar evento', 'width' => '30', 'height' => '30'));
                                echo form_close();
                                
                                //echo anchor(site_url('confirmar_controller/index/'.$evento->PK_IdEvento), img($icono_confirmar));
                                ?>
                            </td>
                            <td>
                                <?php
                                //Modificar evento
                                echo form_open('eventoregistro_controller/CargarEvento');
                                echo form_hidden('id_evento', $evento->PK_IdEvento);
                                echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/modificar_evento.png', 'title' => 'Modifica el evento', 'width' => '30', 'height' => '30'));
                                echo form_close();
                                //echo anchor(site_url('eventoregistro_controller/CargarEvento/'.$evento->PK_IdEvento), img($icono_modificar_evento));
                                ?>
                            </td>
                            <td>
                                <?php
                                //Invitaciones
                                echo form_open('invitaraevento_controller');
                                echo form_hidden('id_evento', $evento->PK_IdEvento);
                                echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/enviar.png', 'title' => 'Envía invitaciones por correo al evento', 'width' => '30', 'height' => '30'));
                                echo form_close();
                                //echo anchor(site_url('invitaraevento_controller/index/'.$evento->PK_IdEvento), img($icono_enviar));
                                ?>
                            </td>
                        </tr>
                    </table>
                    <?php
                    }
                    echo form_fieldset_close();
                    endforeach;
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>