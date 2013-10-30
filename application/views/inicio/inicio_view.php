<?php
//Se obtiene el id de la entidad
$entidad = $this->session->userdata('PK_IdEntidad');

//Se consultan los dias restantes y el tipo de licencia activa, y se agregan a la sesion
$dias = $this->licenciamiento_model->obtener_dias_restantes();
$licencia = $this->licenciamiento_model->verificar_licencia($entidad);

$this->session->set_userdata(array("dias" => $dias, "licencia" => $licencia));

$tabla_licencia = $this->auditoria_model->tabla_licencia();

/*
echo "entidad: ".$this->session->userdata('PK_IdEntidad');
echo "<br>dias: ".$this->session->userdata('dias');
echo "<br>tipo: ".$this->session->userdata('licencia');
*/

/******/
$icono_certificado = array(    'src' => 'img/iconos/certificado.png',    'title' => 'Revisa un certificado',    'align' => 'left',    'width' => '70',    'height' => '70',);
$informe_pdf = array('width' => '800','height' => '600','scrollbars' => 'yes','status' => 'yes','resizable' => 'yes','screenx' => '0','screeny' => '0');

switch ($licencia){
 case 1:
     echo 'Con la versi&oacute;n gratuita puedes crear dos eventos y administrarlos completamente. Extiende tu licencia y administra tus eventos por m&aacute;s tiempo.'.$tabla_licencia;
     echo '<br/><div align="center">'.anchor(base_url().'pagos.pdf', 'Otras formas de pago', array('id' => 'guardar', 'target' => 'blank', 'type' => 'button')).'</div>';
     break;
 case 2:
     echo 'Tu licencia de creaeventos.co est&aacute; cerca de expirar. Te restan '.$dias.' d&iacute;as. Te invitamos a que adquieras una nueva licencia y puedas seguir creando tus eventos:'.$tabla_licencia;
     echo '<br/><div align="center">'.anchor(base_url().'pagos.pdf', 'Otras formas de pago', array('id' => 'guardar', 'target' => 'blank', 'type' => 'button')).'</div>';
     break;
 case 3:
     echo '<p style="color: red">Tu licencia de creaeventos.co expir&oacute;. Adquiere una nueva licencia ahora y disfruta de todos los beneficios de creaeventos.co:</p><br/>'.$tabla_licencia;
     echo '<br/><div align="center">'.anchor(base_url().'pagos.pdf', 'Otras formas de pago', array('id' => 'guardar', 'target' => 'blank', 'type' => 'button')).'</div>';
     break;
}
?>
<br/>
<table width="100%">
    <tr onClick="location.href='<?php echo site_url(); ?>/mis_eventos'">
        <td>
            <?php echo anchor(site_url('mis_eventos'), img(array('src' => 'img/iconos/registro.png', 'title' => 'Revisa una escarapela', 'align' => 'left', 'width' => '70', 'height' => '70'))); ?>
            <div class="contenedor_inicio">
                <h1>Revisa tus eventos</h1>
                <?php
                $eventos = $this->evento_model->topicos_estados(NULL);
                if($eventos == 0){
                    echo '<p>No tienes invitaciones pendientes a eventos. Cuando te env&iacute;en invitaciones podr&aacute;s verlas en  <strong>Eventos/Mis Eventos</strong></p>';
                }else{
                    echo '<p>Has sido invitado a '.$eventos.' eventos. Puedes verlos en <strong>Eventos/Mis Eventos</strong></p>';
                }
                ?>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo anchor(site_url('evento_controller'), img(array('src' => 'img/iconos/confirmar.png', 'title' => 'Revisa un certificado', 'align' => 'left', 'width' => '70', 'height' => '70'))); ?>
            <div class="contenedor_inicio" onClick="location.href='<?php echo site_url(); ?>/evento_controller'">
                <h1>Crea y administra tus eventos</h1>
                <?php
                $eventos = $this->evento_model->topicos_eventos();
                if($eventos == 0){
                    echo '<p>No tienes eventos creados. Puedes crear tus eventos y gestionarlos en <strong>Eventos/Creados por m&iacute;</strong></p>';
                }else{
                    echo 'Has creado '.$eventos.' eventos. Puedes crear otros eventos y gestionarlos en <strong>Eventos/Creados por m&iacute;</strong></p>';
                }
                ?>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo anchor(site_url('evento_controller'), img(array('src' => 'img/iconos/enviar.png', 'title' => 'Env&iacute;a invitaciones', 'align' => 'left', 'width' => '70', 'height' => '70'))); ?>
            <div class="contenedor_inicio" onClick="location.href='<?php echo site_url(); ?>/evento_controller'">
                <h1>Env&iacute;a invitaciones para tus eventos</h1>
                <?php
                $invitaciones = $this->evento_model->topicos_invitaciones();
                if ($invitaciones == 0){
                     echo '<p>Cuando hayas creado un evento podr&aacute;s invitar v&iacute;a correo electr&oacute;nico a las personas que quieres que asistan</p>';
                }else{
                    echo 'Has enviado en total '.$invitaciones.' invitaciones a tus eventos';
                }
                ?>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo anchor_popup(site_url('escarapela/muestra'), img(array('src' => 'img/iconos/escarapela.png', 'title' => 'Revisa una escarapela', 'align' => 'left', 'width' => '70', 'height' => '70',)), $informe_pdf);?>
            <div class="contenedor_inicio" onclick="Javascript:window.open('<?php echo site_url('escarapela/muestra'); ?>')">
                <h1>Imprime tu escarapela</h1>
                <p>Imprime esta escarapela de muestra y as&iacute; tendr&aacute;s una referencia de las medidas para tu dise&ntilde;o</p>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo anchor_popup(site_url('certificado/muestra'), img($icono_certificado), $informe_pdf);?>
            <div class="contenedor_inicio" onclick="Javascript:window.open('<?php echo site_url('certificado/muestra'); ?>')">
                <h1>Imprime tu certificado</h1>
                <p>Cuando sea aprobada tu asistencia al evento y el evento finalice, podr&aacute;s generar tu certificado</p>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
</table>
<script type="text/javascript"> 
    $(document).ready(function() {
        $('#tabla_usuarios').dataTable( {
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false } );
    } );
</script>