<?php
//Zona horaria
date_default_timezone_set('America/Bogota');

if ( ! defined('BASEPATH')) exit('Lo sentimos, usted no tiene acceso a esta ruta');

/**
 * Controlador del m&oacute;dulo Administracion.
 * @author 		John Arley Cano Salinas y Oscar Morales
 * @copyright	&copy;  John Arley Cano Salinas y Oscar Morales
 */
Class Administracion extends CI_Controller{
    /**
    * Funci&oacute;n constructora de la clase administracion. 
    * 
    * Se hereda el mismo constructor de la clase Controller para evitar sobreescribirlo y de esa manera 
    * conservar el funcionamiento de controlador.
    * 
    * @access	public
    */
   function __construct() {
        parent::__construct();
        //si el usuario no esta logueado
        if($this->session->userdata('PK_IdEntidad') != TRUE and $this->session->userdata('PK_IdEntidad') == 1)
        {            
            //redirecciono al controlador de sesion
            redirect('sesion_controller/cerrar');
        }
        //Se cargan los modelos
        $this->load->model('administracion_model');
        $this->load->model('licenciamiento_model');
        //Se cargan los helper y librer&iacute;as
        $this->load->helper('html');
    }
    
    /**
     * Muestra la vista principal del m&oacute;dulo de administracion.
     * 
     * @access	public
     */
    function index(){
        
    }//Fin index()
    
    /**
     * Muestra la vista de auditoria del m&oacute;dulo de administracion.
     * 
     * @access	public
     */
    function auditoria(){
        $this->data['auditorias'] = $this->auditoria_model->listar_auditoria();
        //se establece el titulo de la p&aacute;gina
        $this->data['titulo'] = 'Gesti&oacute;n de auditor&iacute;a';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Gestión de auditoría';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'administracion/auditoria_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin auditoria
    
    /**
     * Muestra la vista de usuario del m&oacute;dulo de administracion.
     * 
     * @access	public
     */
    function usuarios(){
        //Se carga el modelo con el listado de usuarios
        $this->data['usuarios'] = $this->administracion_model->usuarios();
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'Usuario';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Usuarios';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'administracion/usuarios_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin usuarios()
    
    /**
     * Muestra la vista de usuario del m&oacute;dulo de administracion pero
     * con informaci&oacute;n mas detallada
     * 
     * @access	public
     */
    function detalle_usuario(){
        //Se recibe el id de la entidad
        $this->data['id_entidad'] = $this->input->post('id_entidad');
        //Se carga el modelo con el listado de usuarios
        $this->data['usuarios'] = $this->administracion_model->detalle_usuario($this->input->post('id_entidad'));
        //Se carga el licenciamiento del usuario
        $this->data['licencias'] = $this->licenciamiento_model->listar_licenciamientos($this->input->post('id_entidad'));
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'creaeventos.co - Detalles de usuario';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Usuarios';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'administracion/detalle_usuarios_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin detalle_usuario
    
    /**
     * Muestra la vista de usuario especificando la licencia que manejan
     * 
     * @access	public
     */
    function licenciamiento(){
        //Se cargan los usuarios y sus licenciamientos
        $this->data['licencias'] = $this->licenciamiento_model->listar_licenciamientos(NULL);
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'Licenciamiento';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Administración de las licencias';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'administracion/licenciamiento_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin licenciamiento
}//Fin administracion
/* End of file administracion.php */
/* Location: ./creaeventos/application/controllers/administracion.php */