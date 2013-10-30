<div id="form" class="container_12">
    <div class="grid_12" id="registro_view"> 
        <div class="titulos_formularios">
            <p>Hola <?php echo $this->session->userdata('nombre_usuario'); ?>, pulsa sobre cada uno de los tipos de impresi&oacute;n y ent&eacute;rate de como lo que puedes hacer:</p>
        </div><br>

        <table width="100%">
            <?php if($CertificadoMedia == 1){ ?>
                <tr onclick="window.open('<?php echo site_url('certificado/masivo/' . $IdEvento); ?>', 'Impresion Certificado')">
                    <td width="70%">
                        <?php echo img(array('src' => 'img/iconos/impresion_masiva.jpg', 'title' => 'impresión de 4 por hoja', 'align' => 'left', 'width' => '170', 'height' => '170')); ?>
                        <div class="contenedor_inicio">
                            <h1>Impresi&oacute;n masiva</h1>
                            <p>Imprime todos los certificados. Se imprimirán dos por hoja</p>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            <tr onclick="window.open('<?php echo site_url('certificado/individual/' . $IdEvento); ?>', 'Impresion Certificado')">
                <td width="70%">
                    <?php echo img(array('src' => 'img/iconos/impresion_masiva.jpg', 'title' => 'impresión de 1 por hoja', 'align' => 'left', 'width' => '170', 'height' => '170')); ?>
                    <div class="contenedor_inicio">
                        <h1>Impresi&oacute;n individual</h1>
                        <p>Con esta configuraci&oacute;n podr&aacute;s imprimir un certificado por hoja</p>
                    </div>
                </td>
            </tr>
            <?php if($CertificadoMedia == 1){ ?>
                <tr onclick="window.open('<?php echo site_url('certificado/individual_media/' . $IdEvento); ?>', 'Impresion Certificado')">
                    <td width="70%">
                        <?php echo img(array('src' => 'img/iconos/impresion_masiva.jpg', 'title' => 'impresión de 1 por hoja', 'align' => 'left', 'width' => '170', 'height' => '170')); ?>
                        <div class="contenedor_inicio">
                            <h1>Impresi&oacute;n individual en media carta</h1>
                            <p>Con esta configuraci&oacute;n podr&aacute;s imprimir dos certificados por hoja</p>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <div>
            <?php
            echo form_input(array('type' => 'button', 'name' => 'regresar', 'id' => 'regresar', 'value' => 'Regresar', 'onclick' => 'history.back()'));
            ?>            
        </div>
    </div>
</div>         