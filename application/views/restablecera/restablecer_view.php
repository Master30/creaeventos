<?php
$atributos = array('id' => 'restablecer_clave', 'class' => 'id_restablecer_clave');
echo form_open('restablecer/restablecer_clave', $atributos);

//Array del campo cedula
$campo_cedula = array(
     'name'      => 'cedula',
     'id'        => 'cedula',
     'value'     => set_value('cedula'),
 );

//Array del campo Email
$campo_email = array(
    'name'      => 'email',
    'id'        => 'email',
    'value'     => set_value('email'),
);

//Array del submit
$submit = array(
    'name'      => 'submit',
    'id'        => 'submit',
    'value'     => 'Restablecer',
);

//Array del botón regresar
$salir = array(
    'type' => 'button',
    'name' => 'salir',
    'id' => 'salir',
    'value' => 'Regresar',
    'onclick' => 'history.back()'
 );
?>
<div id="form" class="container_12">
    <div class="grid_11">
    <div class="titulos_formularios">Restablecer clave</div>
    <p>Para restablecer la clave de tu cuenta, por favor digita el n&uacute;mero de c&eacute;dula 
    y el correo electr&oacute;nico que usaste cuando te inscribiste.</p><br>
    <div class="grid_12">
        <table align="center" width="100%">
            <tr>
                <th><?php echo form_label('C&eacute;dula'); ?></th>
                <td>
                    <?php echo form_input($campo_cedula); ?>
                    <span class="error">
                    <?php echo form_error('cedula'); ?>
                    </span>
                </td>
                <th><?php echo form_label('Correo electr&oacute;nico');?></th>
                <td>
                    <?php echo form_input($campo_email); ?>
                    <span class="error">
                    <?php echo form_error('email'); ?>
                    </span>
                </td>
                <td>
                    <?php echo form_submit($submit); ?>
                </td>
                <td>
                    <?php echo form_input($salir); ?>
                </td>
            </tr>
        </table>
        <div>
        <?php echo form_close(); ?>            
        </div>
    </div>
</div>
</div>
