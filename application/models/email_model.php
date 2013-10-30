<?php
/**
 * Modelo que se encarga de enviar los correos electr&oacute;nicos
 * @author 		John Arley Cano Salinas - Oscar Humberto Morales
 * @copyright	&copy;  John Arley Cano Salinas - Oscar Humberto Morales
 */
Class Email_model extends CI_Model{
    /*
     * Variables globales de configuraci&oacute;n del correo
     */
	 /*
    var $protocolo = 'smtp';
    var $servidor_correo = 'ssl://smtp.googlemail.com';
    var $usuario_sistema = 'eventosescolme';
    var $password_sistema = '*.abc123*.';
    var $correo_sistema = 'eventosescolme@gmail.com';
    */
    var $protocolo = 'smtp';
    var $servidor_correo = 'ssl://smtp.googlemail.com';
    var $usuario_sistema = 'creaeventos@sumaservicios.com';
    var $password_sistema = 'Temario0417';
    var $correo_sistema = 'creaeventos@sumaservicios.com';
    
    /**
    * Env&iacute;a los correos electr&oacute;nicos
    * 
    * @access	private
    */
    function _enviar($usuarios, $asunto, $cabecera, $cuerpo1, $cuerpo2){
        //Se carga la librer&iacute;aa
        $this->load->library('email');
        
        $config['protocol'] = $this->protocolo;
        $config['smtp_host'] = $this->servidor_correo;
        $config['smtp_port'] = 465;
        $config['smtp_timeout'] = '10';
        $config['smtp_user'] = $this->usuario_sistema;
        $config['smtp_pass'] = $this->password_sistema;
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html';
        $config['validation'] = TRUE;
        $this->email->initialize($config);
        
        //Preparando el mensaje
        $this->email->from($this->correo_sistema, 'creaeventos.co');
        $this->email->to($usuarios); 
        $this->email->cc(''); 
        $this->email->bcc(''); 
        $this->email->subject($asunto);                     
        
        $mensaje = file_get_contents('application/views/email/plantilla.php');                  //Plantilla que arma el correo
        $mensaje = str_replace('{TITULO}', 'creaeventos.co - Email', $mensaje);                    //T&iacute;tulo del correo
        $mensaje = str_replace('{IMAGEN_CABECERA}', $cabecera, $mensaje);                       //Imagen de la cabecera del correo
        $mensaje = str_replace('{CUERPO1}', $cuerpo1, $mensaje);                                //Mensaje del correo
        $mensaje = str_replace('{IMAGEN_CUERPO}', $cuerpo2, $mensaje);                          //Imagen del cuerpo del correo
        $mensaje = str_replace('{URL}', 'http://www.creaeventos.co/', $mensaje);      //URL donde buscar&aacute; las im&aacute;genes y redireccionar&aacute; a la aplicaci&oacute;n
        
        $this->email->message($mensaje);
        
        //Env&iacute;o del email
        $this->email->send();
    }//Fin enviar()
}//Fin email
/* End of file email_model.php */
/* Location: ./creaeventos/application/controllers/email_model.php */