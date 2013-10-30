<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Controlador del m&oacute;dulo de Inicio.
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy; Oscar Humberto Morales y John Arley Cano
 */
Class Inicio extends CI_Controller{
    function __construct() {
        parent::__construct();
        $this->load->library('table');
        $this->load->library('calendar');
        $this->load->model('licenciamiento_model');
        $this->load->model('evento_model');
        $this->load->helper('array');
        //si el usuario no esta logueado
        if($this->session->userdata('PK_IdEntidad') != TRUE)
        {            
            //redirecciono al controlador de sesion
            redirect('sesion_controller');                        
        }
        $this->load->helper('html');
    }//Fin construct

    /**
     * Muestra la vista principal de la aplicaci&oacute;n.
     * 
     * @access	public
     */
    function index(){
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'creaeventos.co - Inicio';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Bienvenido '.$this->session->userdata('nombre_usuario').', Revisa los eventos a los que estás invitado, crea y administra tus eventos';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'inicio/inicio_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin index()
}//Fin inicio
/* End of file inicio.php */
/* Location: ./creaeventos/application/controllers/inicio.php */
