<?php
echo form_open('invitaraevento_controller/enviar/'.$id_evento);

//Array del campo Email
$campo_email = array(
    'name'      => 'email',
    'id'        => 'email',
    'style' => 'width: 90%; height: 200px;',
    'value'     => set_value('email'),
);

//Array del submit
$submit = array(
    'name'      => 'submit',
    'id'        => 'submit',
    'value'     => 'Invitar',
);

//Array del botón regresar
$regresar = array(
    'type' => 'button',
    'name' => 'regresar',
    'id' => 'regresar',
    'value' => 'Regresar',
    'onclick' => 'history.back()'
);
?>

<div id="form" class="container_12">
    <div class="grid_11" id="registro_view">
        <?php
        echo form_fieldset('Digitar los correos', 'class="fieldset"');
        echo form_fieldset_close();
        
        echo form_label('Digita cada direcci&oacute;n separada por coma (,) y as&iacute; enviar&aacute;s las invitaciones con un solo clic');
        ?>
        <table align="center" width="100%">
            <tr>
                <td style="vertical-align: middle"><?php echo form_input($campo_email); ?></td>
                <td style="vertical-align: middle"><?php echo form_submit($submit); ?></td>
                <td>
                    <span class="error"><?php echo form_error('email'); ?></span>
                </td>
            </tr>
        </table>
        <?php
        echo form_close();
        ?>
    </div>
</div>
