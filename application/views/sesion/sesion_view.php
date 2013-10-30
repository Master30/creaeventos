<?php
    $atributos = array('id' => 'sesion_form', 'class' => 'id_form');
    $informe_pdf = array('width' => '800','height' => '600','scrollbars' => 'yes','status' => 'yes','resizable' => 'yes','screenx' => '0','screeny' => '0');

    //Array del campo nombre de usuario
    $nombre_usuario = array(
        'name'      => 'nombre_usuario',
        'id'        => 'nombre_usuario',
        'value'     => set_value('nombre'),
    );

    //Array del campo contrase&ntilde;a
    $password = array(
        'name'      => 'password',
        'id'        => 'password',
        'value'     => '',
    );

    //Array del submit
    $submit = array(
        'name'      => 'submit',
        'id'        => 'submit',
        'value'     => 'Ingresar',
    );
    
echo form_open('sesion_controller/verificar_login', $atributos);
?>
<div class="container_12">
    <div class="grid_7">
       <img src="<?php echo base_url(); ?>img/img-top2.png">
    </div>
    <div class="grid_5" >
        <div class="ui-widget-header titulo_general ui-corner-all" style="margin-top:10px;margin-right: 20px;">Identif&iacute;cate</div><br>
        <div id="form" align="center">
            <table>
                <tr>
                    <th><?php echo form_label('Ingresa tu usuario&nbsp;'); ?></th>
                    <td>
                        <div class="error">
                            <?php echo form_input($nombre_usuario);
                            echo form_error('nombre_usuario')?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><?php echo form_label('Ingresa tu contrase&ntilde;a&nbsp;'); ?></th>
                    <td>
                        <div class="error">
                            <?php echo form_password($password);
                            echo form_error('password') ?>
                        </div>
                    </td>
                </tr>
                <tr style="alignment-adjust: center">
                    <td colspan="2">
                        <center><?php echo form_submit($submit); ?></center><br>
                    </td>
                </tr>
            </table>
            <?php echo form_close(); ?>
            <div class="link_registro">
                <?php echo anchor('inscripcion', 'Inscribirme'); ?>
            </div>
            <div class="link_recuperar_password">
                <?php echo anchor('restablecer', 'Olvidaste tu contrase&ntilde;a?'); ?>
            </div>
            <div class="link_recuperar_password">
                <?php echo anchor('contacto', 'Estoy interesado'); ?>
            </div>
        </div>
    </div>
</div>
<div class="container_12">
    <div class="grid_12" >
        <div class="ui-widget-content ui-corner-all" style="padding:10px; margin-right: 20px;">
            <div class="ui-widget-header titulo_general ui-corner-all" >
                <p>Crea tus propios eventos</p>
            </div>
            <table style="border: none;">
                <tr>
                    <td>
                        <p>Con creaeventos.co podrás crear tus propios eventos o confirmar asistencia a los eventos a los que fuiste invitado.</p>
                        <p>Con creaeventos.co usted podrá enviar invitaciones a las personas que desee invitar y ellos podrán confirmar la asistencia.</p>
                        <p>Podrás ver quienes han confirmado, o  han ignorado tu invitacion.</p>
                    </td>
                    <td>
                        <img src="<?php echo base_url(); ?>img/asamblea.png">
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>