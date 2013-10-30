<!DOCTYPE>              
    <div class="container_12">
        <div align="center" class="grid_11" id="footer">
            <?php
            if ($this->session->userdata('nombre_usuario') == TRUE){
                echo '<b>Usuario en l&iacute;nea:</b> '.$this->session->userdata('nombre_usuario')." ".$this->session->userdata('apellido_usuario');
            }
            ?>
            <p>&copy; Derechos reservados - <strong>Suma Servicios</strong> - Medell&iacute;n, Antioquia, Colombia
            <a href="<?php echo base_url(); ?>" target="blank">creaeventos.co - Sitio Oficial</a><?php echo " - ".date("Y"); ?></p>
            <p>Para una mejor visualizaci&oacute;n puedes actualizar el navegador que prefieras</p>
            <table>
                <tr>
                    <td>
                        <a href="https://www.google.com/intl/es/chrome/browser/?hl=es" target="blank"><img src="<?php echo base_url(); ?>img/iconos/chrome.png" /></a>
                        <a href="http://www.mozilla.org/es-ES/firefox/new/" target="blank"><img src="<?php echo base_url(); ?>img/iconos/firefox.png" /></a>
                        <a href="http://www.opera.com/" target="blank"><img src="<?php echo base_url(); ?>img/iconos/opera.png" /></a>
                        <a href="http://www.apple.com/es/safari/" target="blank"><img src="<?php echo base_url(); ?>img/iconos/safari.png" /></a>
                        <a href="http://windows.microsoft.com/es-ES/internet-explorer/download-ie" target="blank"><img src="<?php echo base_url(); ?>img/iconos/iexplorer.png" /></a>
                    </td>
                </tr>
            </table>
        </div>
            
        </div>
    </body>
</html>
