<?php echo form_open('contacto/enviar', array('id' => 'form')); ?>
<div class="container_12">
    <div class="grid_11" align="center">
        <table width="50%">
            <tr>
                <td><?php echo form_label('Correo electr&oacute;nico*'); ?></td>
                <td><?php echo form_input(array('name' => 'email')); ?></td>
            </tr>
            <tr>
                <td><?php echo form_label('Nombre'); ?></td>
                <td><?php echo form_input(array('name' => 'nombre')); ?></td>
            </tr>
            <tr>
                <td><?php echo form_label('Tel&eacute;fono'); ?></td>
                <td><?php echo form_input(array('name' => 'telefono')); ?></td>
            </tr>
            <tr>
                <td><?php echo form_label('Mensaje'); ?></td>
                <td><?php echo form_textarea(array('id' => 'mensaje', 'name' => 'mensaje', 'style' => 'height: 150px;')); ?></td>
            </tr>
        </table><br/>
        <div align="center">
            <?php echo form_submit(array('value' => 'Contactar')); ?>
        </div>
        <?php echo form_close(); ?>
        <div id="footer">
            Suma Servicios - Bernardo Morales V&eacute;lez<br/>
            <b>Tel&eacute;fono:</b> 314 773 9632<br/>
            <b>Correos electr&oacute;nicos: </b><br/>gcomercial@sumaservicios.com - creaeventos@sumaservicios.com
        </div>
    </div>
</div>