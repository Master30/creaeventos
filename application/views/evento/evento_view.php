<?php
//Se obtiene el id de la entidad
$entidad = $this->session->userdata('PK_IdEntidad');

//Se consultan los dias restantes y el tipo de licencia activa, y se agregan a la sesion
$dias = $this->licenciamiento_model->obtener_dias_restantes();
$licencia = $this->licenciamiento_model->verificar_licencia($entidad);

//Bot&oacute;n de crear eventos
$boton = form_input(array('type' => 'button', 'name' => 'nuevo', 'id' => 'nuevo', 'value' => 'Crear evento', 'onclick' => "redirect('eventoregistro_controller')"));
?>

<div id="form" class="container_12">
    <div class="grid_11" id="registro_view">
        <?php echo img('img/plantillas/creados_por_mi.png') ?>
        <div id="nuevo">
            <?php
            switch ($licencia){
                case 1:
                    echo 'Con la versi&oacute;n gratuita puedes crear dos eventos y administrarlos completamente. Extiende tu licencia y administra tus eventos por m&aacute;s tiempo.<br/>'.$boton;
                    break;
                case 2:
                    echo 'Tu licencia de creaeventos.co est&aacute; cerca de expirar. Te restan '.$dias.' d&iacute;as. Te invitamos a que adquieras una nueva licencia y puedas seguir creando tus eventos.<br/>'.$boton;
                    break;
                case 3:
                    echo '<p style="color: red">Tu licencia de creaeventos.co expir&oacute;. Adquiere una nueva licencia ahora y disfruta de todos los beneficios de creaeventos.co.</p>';
                    break;
                case 4:
                    echo $boton;
                    break;
            }
            ?>
        </div><br>
        <table cellpadding="0" cellspacing="0" border="" class="display" id="tabla_usuarios" style="font-size: 15px;">
            <thead> 
                <tr> 
                    <th class="primero" width="60%">Evento</th> 
                    <th width="30%">Fecha de Inicio</th> 
                    <th class="ultimo">Opciones</th>
                </tr> 
            </thead>
            <tbody>
            <?php foreach ($eventos as $evento): ?>
                <tr>
                    <td><?php echo $evento->NombreEvento; ?></td>
                    <td><?php echo $this->auditoria_model->formato_fecha($evento->FechaInicio); ?></td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <?php
                                    //Administrar evento
                                    echo form_open('confirmar_controller');
                                    echo form_hidden('id_evento', $evento->PK_IdEvento);
                                    echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/confirmar.png', 'title' => 'Administrar evento', 'width' => '30', 'height' => '30'));
                                    echo form_close();
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    //Ver evento
                                    echo form_open('evento_controller/ver_evento');
                                    echo form_hidden('id_evento', $evento->PK_IdEvento);
                                    echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/ver_evento.png', 'title' => 'Revisa el evento', 'width' => '30', 'height' => '30'));
                                    echo form_close();
                                    ?>
                                </td>
                                <?php if($dias == NULL or $licencia !=3){ ?>
                                <td>
                                    <?php
                                    //Modificar evento
                                    echo form_open('eventoregistro_controller/CargarEvento');
                                    echo form_hidden('id_evento', $evento->PK_IdEvento);
                                    echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/modificar_evento.png', 'title' => 'Modifica el evento', 'width' => '30', 'height' => '30'));
                                    echo form_close();
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    //Invitaciones
                                    echo form_open('invitaraevento_controller');
                                    echo form_hidden('id_evento', $evento->PK_IdEvento);
                                    echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/enviar.png', 'title' => 'Envía invitaciones por correo al evento', 'width' => '30', 'height' => '30'));
                                    echo form_close();
                                    ?>
                                </td>
                                <?php } ?>
                                <td>
                                    <?php
                                    
                                        //Impresi&oacute;n de escarapelas
                                        echo form_open('escarapela');
                                        echo form_hidden('id_evento', $evento->PK_IdEvento);
                                        if((($evento->Numero_Registrados) > 0) && (($evento->Estado) == 1)){
                                            echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/escarapela.png', 'title' => 'Impresi&oacute;n masiva de escarapelas', 'width' => '30', 'height' => '30'));
                                        }else{
                                            echo img(array('src' => base_url().'img/iconos/escarapela.png', 'title' => 'Impresi&oacute;n masiva de escarapelas', 'width' => '30', 'height' => '30'));
                                        }
                                        echo form_close();
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    
                                        //Impresi&oacute;n de certificados
                                        echo form_open('certificado');
                                        echo form_hidden('id_evento', $evento->PK_IdEvento);
                                        echo form_hidden('CertificadoMedia', $evento->ImpresionCertificadoMedia);
                                        if(($evento->Confirmados) > 0){
                                            echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/certificado.png', 'title' => 'Impresi&oacute;n masiva de certificados', 'width' => '30', 'height' => '30'));
                                        }else{
                                            echo img(array('src' => base_url().'img/iconos/certificado.png', 'title' => 'Impresi&oacute;n masiva de certificados', 'width' => '30', 'height' => '30'));
                                        }    
                                        echo form_close();
                                        
                                    ?>
                                </td>
                            </tr>
                        </table>
                        <?php
                        /*
                        echo anchor(site_url('confirmar_controller/index/'.$evento->PK_IdEvento), img($icono_confirmar));
                        echo anchor(site_url('evento_controller/ver_evento/'.$evento->PK_IdEvento), img($icono_ver_evento));
                        echo anchor(site_url('eventoregistro_controller/CargarEvento/'.$evento->PK_IdEvento), img($icono_modificar_evento));
                        echo anchor(site_url('escarapela/'), img($icono_escarapela));
                        echo anchor(site_url('certificado'), img($icono_certificado));
                        echo anchor(site_url('invitaraevento_controller/index/'.$evento->PK_IdEvento), img($icono_enviar));
                         */
                        ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table><br/><br/>
        <div class="clear"></div>
        <?php
        echo form_open('inicio');
        echo form_submit(array('name' => 'salir','id' => 'salir','value' => 'Regresar'));
        echo form_close(); 
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
            "bAutoWidth": false } );
    } );
</script>