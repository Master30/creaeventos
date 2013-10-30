<?php
//Zona horaria
date_default_timezone_set('America/Bogota');

if ( ! defined('BASEPATH')) exit('Lo sentimos, usted no tiene acceso a esta ruta');

/**
 * Controlador para administrar los pagos
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy;  Oscar Humberto Morales y John Arley Cano
 */
Class Licenciamiento extends CI_Controller{
    /**
    * Funci&oacute;n constructora de la clase Licenciamiento. 
    * 
    * Se hereda el mismo constructor de la clase Controller para evitar sobreescribirlo y de esa manera 
    * conservar el funcionamiento de controlador.
    * 
    * @access	public
    */
    function __construct() {
        parent::__construct();
        //si el usuario no esta logueado
        if($this->session->userdata('PK_IdEntidad') != TRUE)
        {            
            //redirecciono al controlador de sesion
            redirect('sesion_controller');                        
        }
        //Se cargan los modelos
        $this->load->model(array('licenciamiento_model','administracion_model', 'email_model'));
        //Se cargan los helpers
        $this->load->helper('html');
    }//Fin construct
    
    /**
     * Se carga la Interface.
     * 
     * @access	public
     */
    function index(){
        //Se cargan las licencias del usuario
        $this->data['licencias'] = $this->licenciamiento_model->listar_licenciamientos($this->session->userdata('PK_IdEntidad'));
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'Adquirir creaeventos.co';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Extiende los beneficios de creaeventos.co y revisa cuántas licencias has adquirido';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'licenciamiento/licenciamiento_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin index()
    
    /*
     * Funcion encargada de agregar la licencia mensual
     */
    function licenciamiento_mensual(){
        //Se reciben los datos del formulario
        $id_entidad = $this->input->post('id_entidad');
        $tipo_licencia = $this->input->post('tipo_licencia');
        $valor = $this->input->post('valor');
        $nombre = $this->input->post('nombre');
        
        $licencia = array(
            'Fk_Id_Entidad' => $id_entidad,
            'Tipo_Licencia' => $tipo_licencia,
            'Valor' => $valor
        );
        
        //Se inserta la licencia en la base de datos
        $this->licenciamiento_model->licenciamiento_mensual($licencia);
        
        //Se registra la acci&oacute;n de auditor&iacute;a
        $this->auditoria_model->licenciamiento_mensual($id_entidad);
        
        /*
         * Se preparan los datos del correo electr&oacute;nico
         */
        $usuarios = $this->input->post('email');        //Usuarios que recibir&aacute;n el correo
        $asunto = 'Licencia activada';                  //Asunto del correo
        $cabecera = 'licencia_cabecera.png';            //Imagen de cabecera que llevar&aacute; el correo
        $cuerpo1 = 
        'Hola '.$nombre.', la licencia de creaeventos.co que has adquirido ahora se encuentra activada.<br/>
        A partir de este momento y durante '.$tipo_licencia.' días puedes usar creaeventos.co con todas sus funciones.<br/><br/>
        Muchas gracias por elegirnos y deseamos que disfrutres nuestra aplicación!';
        $cuerpo2 = 'vacio_cuerpo.png';             //Imagen del cuerpo del mensaje
        
        //Se muestra el mensaje de &eacute;xito
        $this->data['mensaje_exito'] = "Se ha agregado la licencia correctamente";
        
        //Se recibe el id de la entidad
        $this->data['id_entidad'] = $id_entidad;
        //Se carga el modelo con el listado de usuarios
        $this->data['usuarios'] = $this->administracion_model->detalle_usuario($id_entidad);
        //Se carga el licenciamiento del usuario
        $this->data['licencias'] = $this->licenciamiento_model->listar_licenciamientos($id_entidad);
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'creaeventos.co - Detalles de usuario';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Usuarios';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'administracion/detalle_usuarios_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
        
        //Se env&iacute;a el email
        $this->email_model->_enviar($usuarios, $asunto, $cabecera, $cuerpo1, $cuerpo2);
    }//Fin licenciamiento_mensual()
    
    /*
     * Funcion encargada de agregar la licencia anual
     */
    function licenciamiento_anual(){
        //Se reciben los datos del formulario
        $id_entidad = $this->input->post('id_entidad');
        $tipo_licencia = $this->input->post('tipo_licencia');
        $valor = $this->input->post('valor');
        $nombre = $this->input->post('nombre');
        
        $licencia = array(
            'Fk_Id_Entidad' => $id_entidad,
            'Tipo_Licencia' => $tipo_licencia,
            'Valor' => $valor
        );
        
        //Se inserta la licencia en la base de datos
        $this->licenciamiento_model->licenciamiento_anual($licencia);
        
        //Se registra la acci&oacute;n de auditor&iacute;a
        $this->auditoria_model->licenciamiento_anual($id_entidad);
        
        /*
         * Se preparan los datos del correo electr&oacute;nico
         */
        $usuarios = $this->input->post('email');        //Usuarios que recibir&aacute;n el correo
        $asunto = 'Licencia activada';                  //Asunto del correo
        $cabecera = 'licencia_cabecera.png';            //Imagen de cabecera que llevar&aacute; el correo
        $cuerpo1 = 
        'Hola '.$nombre.', la licencia de creaeventos.co que has adquirido ahora se encuentra activada.<br/>
        A partir de este momento y durante '.$tipo_licencia.' días puedes usar creaeventos.co con todas sus funciones.<br/><br/>
        Muchas gracias por elegirnos y deseamos que disfrutres nuestra aplicación!';
        $cuerpo2 = 'vacio_cuerpo.png';             //Imagen del cuerpo del mensaje
        
        //Se muestra el mensaje de &eacute;xito
        $this->data['mensaje_exito'] = "Se ha agregado la licencia correctamente";
        
        //Se recibe el id de la entidad
        $this->data['id_entidad'] = $id_entidad;
        //Se carga el modelo con el listado de usuarios
        $this->data['usuarios'] = $this->administracion_model->detalle_usuario($id_entidad);
        //Se carga el licenciamiento del usuario
        $this->data['licencias'] = $this->licenciamiento_model->listar_licenciamientos($id_entidad);
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'creaeventos.co - Detalles de usuario';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Usuarios';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'administracion/detalle_usuarios_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
        
        //Se env&iacute;a el email
        $this->email_model->_enviar($usuarios, $asunto, $cabecera, $cuerpo1, $cuerpo2);
    }//Fin licenciamiento_anual()
    
    /*
     * Funcion encargada de agregar licencias especiales que pueden necesitarse
     */
    function licenciamiento_adicional(){
        //Se reciben los datos del formulario
        $id_entidad = $this->input->post('id_entidad');
        $tipo_licencia = $this->input->post('tipo_licencia');
        $valor = $this->input->post('valor');
        $nombre = $this->input->post('nombre');
        
        $licencia = array(
            'Fk_Id_Entidad' => $id_entidad,
            'Tipo_Licencia' => $tipo_licencia,
            'Valor' => $valor
        );
        
        //Se inserta la licencia en la base de datos
        $this->licenciamiento_model->licenciamiento_adicional($licencia);
        
        //Se registra la acci&oacute;n de auditor&iacute;a
        $this->auditoria_model->licenciamiento_adicional($id_entidad, $tipo_licencia);
        
        /*
         * Se preparan los datos del correo electr&oacute;nico
         */
        $usuarios = $this->input->post('email');        //Usuarios que recibir&aacute;n el correo
        $asunto = 'Licencia adicional activada';        //Asunto del correo
        $cabecera = 'licencia_cabecera.png';            //Imagen de cabecera que llevar&aacute; el correo
        $cuerpo1 = 
        'Hola '.$nombre.', has obtenido una licencia adicional de creaeventos.co.<br/>
        A partir de este momento y durante '.$tipo_licencia.' días puedes seguir usando creaeventos.co.<br/><br/>
        Muchas gracias por elegirnos y deseamos que sigas disfrutando de nuestra aplicación!';
        $cuerpo2 = 'vacio_cuerpo.png';             //Imagen del cuerpo del mensaje
        
        //Se muestra el mensaje de &eacute;xito
        $this->data['mensaje_exito'] = "Se ha agregado la licencia adicional correctamente";
        
        //Se recibe el id de la entidad
        $this->data['id_entidad'] = $id_entidad;
        //Se carga el modelo con el listado de usuarios
        $this->data['usuarios'] = $this->administracion_model->detalle_usuario($id_entidad);
        //Se carga el licenciamiento del usuario
        $this->data['licencias'] = $this->licenciamiento_model->listar_licenciamientos($id_entidad);
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'creaeventos.co - Detalles de usuario';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Usuarios';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'administracion/detalle_usuarios_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
        
        //Se env&iacute;a el email
        $this->email_model->_enviar($usuarios, $asunto, $cabecera, $cuerpo1, $cuerpo2);
    }//Fin licenciamiento_adicional()
}//Fin licenciamiento
/* End of file licenciamiento.php */
/* Location: ./creaeventos/application/controllers/licenciamiento.php */