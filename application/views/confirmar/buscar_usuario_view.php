<?php
//Se obtiene el id de la entidad
$entidad = $this->session->userdata('PK_IdEntidad');

//Se consultan los dias restantes y el tipo de licencia activa, y se agregan a la sesion
$dias = $this->licenciamiento_model->obtener_dias_restantes();
$licencia = $this->licenciamiento_model->verificar_licencia($entidad);
?>

<div id="form" class="container_12">
    <div class="grid_11" id="registro_view">
        <?php
        echo img('img/plantillas/administrar.png');
        echo form_open('confirmar_controller/confirmar_nuevo');
        echo form_hidden('id_evento', $id_evento);
        
        switch ($licencia){
            case 1:
                echo 'Con la versi&oacute;n gratuita puedes crear dos eventos y administrarlos completamente. Extiende tu licencia y administra tus eventos por m&aacute;s tiempo.<br/>';
                $confirmar = true;
                break;
            case 2:
                echo 'Tu licencia de creaeventos.co est&aacute; cerca de expirar. Te restan '.$dias.' d&iacute;as. Te invitamos a que adquieras una nueva licencia y puedas seguir creando tus eventos.<br/>';
                $confirmar = true;
                break;
            case 3:
                echo '<p style="color: red">Tu licencia de creaeventos.co expir&oacute;. Adquiere una nueva licencia ahora y disfruta de todos los beneficios de creaeventos.co.</p>';
                $confirmar = false;
                break;
            case 4:
                $confirmar = true;
                break;
        }
        
        if($confirmar == true){
        ?>
        <div id="nuevo">
            <table width="100%">
                <tr valign="middle">
                    <td><?php echo form_label('Documento' ,'cedula'); ?></td>
                    <td><?php echo form_input(array('name' => 'cedula', 'id' => 'cedula', 'value' => set_value('cedula'))); ?></td>
                    <td><?php echo form_label('Nombre' ,'nombre'); ?></td>
                    <td><?php echo form_input(array('name' => 'nombre', 'id' => 'nombre', 'value' => set_value('nombre'))); ?></td>
                    <td rowspan="2" valign="middle"><?php echo form_input(array( 'type' => 'submit', 'name' => 'nuevo', 'id' => 'nuevo', 'value' => 'Confirmar')); ?></td>
                </tr>
                <tr valign="middle">
                    <td><?php echo form_label('Apellido' ,'apellido'); ?></td>
                    <td><?php echo form_input(array('name' => 'apellido', 'id' => 'apellido', 'value' => set_value('apellido'))); ?></td>
                    <td><?php echo form_label('Email', 'email'); ?></td>
                    <td><?php echo form_input(array('name' => 'email', 'id' => 'email', 'value' => set_value('email'))) ?></td>
                </tr>
            </table>
            <span class="error">
            <?php
            echo form_error('cedula');
            echo form_error('nombre');
            echo form_error('email');
            ?>
            </span>
        </div>
        
        <?php
        }
        echo form_close();
        echo form_fieldset('', 'class="fieldset"');
        echo form_fieldset_close();
        $estado = NULL;
        ?>
        
        <table cellpadding="0" cellspacing="0" border="" class="display" id="tabla_usuarios">
            <thead> 
                <tr> 
                    <th class="primero" width="30%">Nombres</th> 
                    <th width="30%">Correo electr&oacute;nico</th> 
                    <th width="20%">Estado</th>
                    <th class="ultimo" width="20%">Opciones</th>
                </tr> 
            </thead>
            <tbody>
            <?php foreach($inscritos as $inscrito): ?>
                <tr>
                    <td><?php echo $inscrito->Nombres.' '.$inscrito->Apellidos ?></td>
                    <td><?php echo $inscrito->Correo ?></td>
                    <?php
                    switch ($inscrito->Estado){
                        case NULL:
                            if($inscrito->Estado_Evento == 0){
                            ?>
                            <td>Invitaci&oacute;n ignorada</td>
                            <td></td>
                            <?php
                        }else{ ?>
                            <td>Invitado</td>
                            <td>
                                <table>
                                    <tr>
                                        <td><?php echo img(array('src' => 'img/iconos/invitado.png', 'title' => 'Est&aacute; invitado', 'width' => '30', 'height' => '30')); ?></td>
                                    </tr>
                                </table>
                            </td><?php
                        }
                        break;
                    case 1: ?>
                        <td>Registrado</td>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <?php
                                        //confirmar registro
                                        echo form_open('confirmar_controller/confirmar');
                                        echo form_hidden('id_entidad', $inscrito->PK_IdEntidad);
                                        echo form_hidden('id_evento', $id_evento);
                                        echo form_hidden('id_invitacion', $inscrito->PK_IdInvitacion);
                                        echo form_hidden('id_estado', $inscrito->Estado);
                                        echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos_alertas/asistido.png', 'title' => 'Confirmar asistencia', 'width' => '30', 'height' => '30'));
                                        echo form_close();
                                        //echo anchor(site_url("confirmar_controller/confirmar/".$inscrito->PK_IdEntidad.'/'.$id_evento.'/'.$inscrito->PK_IdInvitacion.'/'.$inscrito->Estado), img(array('src' => 'img/iconos_alertas/asistido.png', 'title' => 'Confirmar asistencia', 'width' => '30', 'height' => '30')));
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <?php break;
                    case 2: ?>
                        <td>Asisti&oacute;</td>
                        <td>
                            <table width="20%">
                                <tr>
                                    <td>
                                        <?php echo img(array('src' => 'img/iconos/asistio.png', 'title' => 'Ha asistido al evento', 'width' => '30', 'height' => '30')); ?>
                                    </td>
                                    <td>
                                        <?php
                                        //Desertar del evento
                                        echo form_open('evento_controller/desertar');
                                        echo form_hidden('id_entidad', $inscrito->PK_IdEntidad);
                                        echo form_hidden('id_evento', $id_evento);
                                        echo form_hidden('id_invitacion', $inscrito->PK_IdInvitacion);
                                        echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos_alertas/desertar.png', 'title' => 'Desertar del evento', 'width' => '30', 'height' => '30'));
                                        echo form_close();
                                        //echo anchor(site_url("evento_controller/desertar/".$inscrito->PK_IdEntidad.'/'.$id_evento.'/'.$inscrito->PK_IdInvitacion), img(array('src' => 'img/iconos_alertas/desertar.png', 'title' => 'Desertar del evento', 'width' => '30', 'height' => '30')));
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <?php break;
                    case 3: ?>
                        <td>Desertado</td>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <?php echo img(array('src' => 'img/iconos/desertado.png', 'title' => 'Abandon&oacute; el evento', 'width' => '30', 'height' => '30')); ?>
                                    </td>
                                    <td>
                                        <?php
                                        //Reintegrar
                                        echo form_open('evento_controller/reintegrar');
                                        echo form_hidden('id_entidad', $inscrito->PK_IdEntidad);
                                        echo form_hidden('id_evento', $id_evento);
                                        echo form_hidden('id_invitacion', $inscrito->PK_IdInvitacion);
                                        echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/reintegrar.png', 'title' => 'Reintegrar al evento', 'width' => '30', 'height' => '30'));
                                        echo form_close();
                                        //echo anchor(site_url("evento_controller/reintegrar/".$inscrito->PK_IdEntidad.'/'.$id_evento.'/'.$inscrito->PK_IdInvitacion), img(array('src' => 'img/iconos/reintegrar.png', 'title' => 'Reintegrar al evento', 'width' => '30', 'height' => '30')));
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <?php break;
                    case 4: ?>
                        <td>Registro Cancelado</td>
                        <td>
                            <table>
                                <tr>
                                    <td><?php echo img(array('src' => 'img/iconos/rechazar.png', 'title' => 'Cancel&oacute; su registro', 'width' => '30', 'height' => '30')); ?></td>
                                </tr>
                            </table>
                        </td>
                        <?php break;
                    case 5: ?>
                        <td>Finalizado</td>
                        <td>
                            <table width="20%">
                                <tr>
                                    <td><?php echo img(array('src' => 'img/iconos_alertas/finalizado.png', 'title' => 'Evento finalizado', 'width' => '30', 'height' => '30')); ?></td>
                                </tr>
                            </table>
                        </td>
                        <?php break;
                    case 6: ?>
                        <td>Invitaci&oacute;n vencida</td>
                        <td></td>
                        <?php break;
                    }//Fin switch
                    $estado = $inscrito->Estado_Evento;
                    ?>
                </tr>
            <?php endforeach; ?> 
            </tbody>					
        </table><br/><br/>
        <div class="clear"></div>
    
        <table>
            <tr>
                <td>
                    <?php
                    echo form_open('evento_controller');
                    echo form_submit(array('name' => 'salir','id' => 'salir','value' => 'Regresar'));
                    echo form_close();
                    ?>
                </td>
                <?php if($confirmar == true){ ?>
                <td>
                    <?php
                    if(isset($estado)){
                        if($estado == 1){
                            echo form_open('evento_controller/finalizar_evento');
                            echo form_hidden('id_evento', $id_evento);
                            echo form_input(array('type' => 'submit', 'name' => 'finalizar','id' => 'finalizar','value' => 'Finalizar evento'));
                            echo form_close();
                        }else{
                            echo form_open('evento_controller/abrir_evento');
                            echo form_hidden('id_evento', $id_evento);
                            echo form_input(array('type' => 'submit', 'name' => 'finalizar','id' => 'finalizar','value' => 'Abrir evento'));
                            echo form_close();
                        }
                    }
                    ?>
                </td>
                <?php } ?>
            </tr>
        </table>
        <?php
        if($inscritos){
            echo form_fieldset('', 'class="fieldset"');
            echo form_fieldset_close();
        ?>
            <table width="100%" style="font-size: 0.7em;">
                <tr>
                    <?php
                    if($estado == 1){ ?>
                        <td>
                            <?php  echo "Invitados: ".$this->evento_model->estados_evento($id_evento, NULL); ?>
                        </td>
                    <?php }else{ ?>
                        <td>
                            <?php echo "Invitados: 0"; ?>
                        </td>
                    <?php } ?>
                    <td>
                        <?php echo "Registrados: ".$this->evento_model->estados_evento($id_evento, 1); ?>
                    </td>
                    <td>
                        <?php echo "Asistidos: ".$this->evento_model->estados_evento($id_evento, 2); ?>
                    </td>
                    <td>
                        <?php echo "Desertados: ".$this->evento_model->estados_evento($id_evento, 3); ?>
                    </td>
                    <td>
                        <?php echo "Registros cancelados: ".$this->evento_model->estados_evento($id_evento, 4); ?>
                    </td>
                    <?php if($estado == 0){ ?>
                    <td>
                        <?php echo "Invitaciones ignoradas: ".$this->evento_model->estados_evento($id_evento, NULL); ?>
                    </td>
                    <?php } ?>
                </tr>
            </table>
        <?php }  ?>
    </div>
</div>

<script type="text/javascript"> 
    $(document).ready(function(){
        $('#tabla_usuarios').dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": false
        });
    });
</script>