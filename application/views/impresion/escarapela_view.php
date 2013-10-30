<table width="100%">        
    <tr onclick="window.open('<?php echo site_url('escarapela/masiva/'.$IdEvento); ?>', 'Impresion Escarapela')">
        <td width="70%">
            <?php echo anchor_popup(site_url('escarapela/masiva/'.$IdEvento), img(array('src' => 'img/iconos/impresion_masiva.jpg', 'title' => 'Impresi&oacute;n masiva', 'align' => 'left', 'width' => '170', 'height' => '170'))); ?>
            <div class="contenedor_inicio">
                <h1>Impresi&oacute;n masiva</h1>
                <p>Con solo dar clic podr&aacute;s imprimir todas las escarapelas de quienes est&eacute;n registrados. Obtendr&aacute;s cuatro por hoja</p>
            </div>
        </td>
    </tr>
    <tr onclick="window.open('<?php echo site_url('escarapela/individual/'.$IdEvento); ?>', 'Impresion Escarapela')">
        <td width="70%">
            <?php echo anchor_popup(site_url('escarapela/individual/'.$IdEvento), img(array('src' => 'img/iconos/impresion_individual.jpg', 'title' => 'impresión de 1 por hoja', 'align' => 'left', 'width' => '170', 'height' => '170'))); ?>
            <div class="contenedor_inicio">
                <h1>Impresi&oacute;n individual en hoja normal</h1>
                <p>Con esta configuraci&oacute;n podr&aacute;s imprimir una escarapela por hoja, en hoja normal</p>
            </div>
        </td>
    </tr>
    <tr onclick="window.open('<?php echo site_url('escarapela/individual_media/'.$IdEvento); ?>', 'Impresion Escarapela')">
        <td width="70%">
            <?php echo anchor_popup(site_url('escarapela/individual_media/'.$IdEvento), img(array('src' => 'img/iconos/impresion_media.jpg', 'title' => 'impresión de 1 por hoja', 'align' => 'left', 'width' => '170', 'height' => '170'))); ?>
            <div class="contenedor_inicio">
                <h1>Impresi&oacute;n individual en hoja media</h1>
                <p>Imprime escarapelas en hoja media</p>
            </div>
        </td>
    </tr>
</table>