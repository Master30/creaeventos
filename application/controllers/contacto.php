<?php
//Zona horaria
date_default_timezone_set('America/Bogota');

if ( ! defined('BASEPATH')) exit('Lo sentimos, usted no tiene acceso a esta ruta');

/**
 * Controlador del m&oacute;dulo de Contacto.
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy;  Oscar Humberto Morales y John Arley Cano
 */
class Contacto extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->model(array('email_model', 'administracion_model'));
    }
    
    function index(){
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'creaeventos.co - Contacto';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'contacto/contacto_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin index()
    
    function enviar(){
        $email = $this->input->post('email');
        $nombre = $this->input->post('nombre');
        $telefono = $this->input->post('telefono');
        $mensaje = $this->input->post('mensaje');
        
        //
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
        
        //
        $this->form_validation->set_message('valid_email', 'El email no es v&aacute;lido');
        $this->form_validation->set_message('required', 'Este campo es obligatorio');
        
        //
        if ($this->form_validation->run() == FALSE){
            $this->data['mensaje_alerta'] = "El correo electr&oacute;nico no es v&aacute;lido";
            $this->index();
        }else{
            /*
            * Se preparan los datos del correo electr&oacute;nico
            */
            $usuarios = array('creaeventos@sumaservicios.com', 'johnarleycano@hotmail.com');
            $asunto = 'Nuevo interesado en creaeventos.co';
            $cabecera = 'bienvenido_cabecera.png'; 
            $cuerpo1 = '<br/>Se ha recibido una solicitud:<br/><br/>
                <b>Nombre: </b>'.$nombre.'<br/>
                <b>Correo:</b> '.$email.'<br/>
                <b>Tel&eacutefono:</b> '.$telefono.'<br/>
                <b>Mensaje:</b> '.$mensaje;

            $cuerpo2 = '';
            
            //Se ingresa el registro de auditor&iacute;a
            $this->auditoria_model->nuevo_interesado($nombre);
            
            //Se muestra el mensaje de &eacute;xito
            $this->data['mensaje_exito'] = "Tu mensaje se ha enviado con exito. Te daremos respuesta pronto.";

            //se establece el titulo de la pag&iacute;na
            $this->data['titulo'] = 'creaeventos.co - Identif&iacute;cate';
            //se establece la vista que tiene el contenido principal
            $this->data['contenido_principal'] = 'sesion/sesion_view';
            //se carga el template
            $this->load->view('includes/template', $this->data);
           
            //Se env&iacute;a el email
           $this->email_model->_enviar($usuarios, $asunto, $cabecera, $cuerpo1, $cuerpo2);
           //$this->email_model->_enviar($email, $asunto, $cabecera, $cuerpo1, $cuerpo2);
        }
    }//Fin enviar()
}//Fin contacto
/* End of file contacto.php */
/* Location: ./creaeventos/application/controllers/contacto.php */
