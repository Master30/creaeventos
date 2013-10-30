<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta charset="<?php echo config_item('charset'); ?>" />
        <?php $this->load->view('includes/header'); ?>
    </head>
    <body>
        <div class="container_12">
            <section id="cuerpo">
                <article id="contenido">
                    <?php $this->load->view($contenido_principal); ?>
                </article>
            </section>
            <?php $this->load->view('includes/footer'); ?>
        </div>
        <!-- Mensajes de los Metodos --> 
        <?php if (isset($msgs)) { ?> 
            <div id="msg"> 
                <p> 
                    <?php echo $msgs; ?> 
                    probar impresion de Mensaje
                </p> 
            </div>
            <script language="javascript">
                timerID = setTimeout("fTimer()", 5000);
            </script>               
            <?php
        }
        ?>        
        <!-- FIN Mensajes de los Metodos --> 
        <script>
        //para redireccionar el boton cancelar y volver    
        function redirect(url){
              location.href = url;
        }	
        </script>