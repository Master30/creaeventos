<?php
//Zona horaria
date_default_timezone_set('America/Bogota');

if ( ! defined('BASEPATH')) exit('Lo sentimos, usted no tiene acceso a esta ruta');

/**
 * Controlador del m&oacute;dulo de Registro.
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy; Oscar Humberto Morales y John Arley Cano
 */
class Inscripcion extends CI_Controller{
    /**
    * Funci&oacute;n constructora de la clase contratista. 
    * 
    * Se hereda el mismo constructor de la clase Controller para evitar sobreescribirlo y de esa manera 
    * conservar el funcionamiento de controlador.
    * 
    * @access	public
    */
    function __construct() {
        //con esta linea se hereda el constructor de la clase Controller
        parent::__construct();
        //Se cargan los modelos
        $this->load->model('usuario_model');
        $this->load->model('email_model');
        $this->load->model('licenciamiento_model');
        $this->load->library('email');
        //Se cargan las librer&iacute;as y helpers
        $this->load->library('encrypt');
        $this->load->helper('html');
    }
    
    function index(){        
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'Inscr&iacute;bete';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Reg&iacute;strate en la aplicaci&oacute;n ingresando los datos.';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'inscripcion/inscripcion_view';
        //Array del campo de ciudad
        $this->data['ciudad'] = $this->input->post('ciudad');
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin index()
    
    /**
     * Verifica y retorna true si el usuario ingresado existe.
     * 
     * @access	private
     */
    function _validar_usuario($nombre_usuario){
        return $this->usuario_model->validar_usuario($nombre_usuario);
    }//Fin _validar_usuario
    
    /**
     * Verifica y retorna true si el email ingresado existe.
     * 
     * @access	private
     */
    function _validar_email($email){
        return $this->usuario_model->validar_email($email);
    }//Fin _validar_email
    
    /**
     * Verifica y retorna false si el telefono ingresado es negativo.
     * 
     * @access	public
     */
    function _validar_telefono($telefono){
       if($telefono == '' || $telefono > 0){
            return true;
        }else{
            return false;
        }
    }//Fin _validar_telefono
    
    
    /**
     * Valida todos los datos e ingresa el registro del usuario.
     * 
     * @access	public
     */
    function validar(){
        /*
         * Crear reglas de validaci;oacute;n para los campos
         * La protecci&oacute;n contra ataques XSS se configur;oacute; desde config.php
         * trim limpia los espacios en blanco
         * min_lenght define el m&iacute;nimo de un campo
         * md5 encripta la contrase&ntilde;
         * callback me verificará que ese campo no exista en la base de datos
         */
        $this->form_validation->set_rules('cedula', 'c&eacute;dula', 'required|numeric|trim|callback__validar_telefono');
        $this->form_validation->set_rules('nombre', 'nombre', 'required|trim');
        $this->form_validation->set_rules('apellido', 'apellido', 'required|trim');
        $this->form_validation->set_rules('nombre_usuario', 'nombre de usuario', 'required|trim|alphanumeric|callback__validar_usuario');
        $this->form_validation->set_rules('password1', 'contrase&ntilde;a', 'required|min_length[4]');
        $this->form_validation->set_rules('password2', 'contrase&ntilde;a', 'required|min_length[4]|matches[password1]');
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback__validar_email');
        $this->form_validation->set_rules('telefono', 'telefono', 'trim|numeric|callback__validar_telefono');
        $this->form_validation->set_rules('_validar_usuario', 'usuario', 'trim|numeric');
        $this->form_validation->set_rules('ciudad', 'ciudad', 'trim'); 
        
        //Si el método devuelve FALSE, la validaci&oacute;n no se llev&oacute; corretamente
        $this->form_validation->set_message('required', 'Este campo es obligatorio');
        $this->form_validation->set_message('min_length', 'El campo %s debe tener como m&iacute;nimo 5 caracteres');
        $this->form_validation->set_message('max_length', 'El campo %s no puede exceder los 12 caracteres');
        $this->form_validation->set_message('matches', 'Las contrase&ntilde;as no coinciden');
        $this->form_validation->set_message('valid_email', 'El email no es v&aacute;lido');
        $this->form_validation->set_message('alpha', 'En este campo solo se permiten letras');
        $this->form_validation->set_message('numeric', 'En este campo solo se permiten n&uacute;meros y no deben llevar espacios en blanco');
        $this->form_validation->set_message('_validar_usuario', 'Este nombre de usuario ya existe. Registra otro');
        $this->form_validation->set_message('_validar_email', 'Este correo electr&oacute;nico ya est&aacute; registrado en nuestra base de datos.');
        $this->form_validation->set_message('_validar_telefono', 'Este campo no puede ser negativo.');
		
        //Esta es la condici&oacute;n que ejecuta las reglas y no lo deja pasar. Si el m&eacute;todo devuelve FALSE, la validaci&oacute;n no se llev&oacute; corretamente
        if ($this->form_validation->run() == FALSE){
            //Se muestra el mensaje de alerta
            $this->data['mensaje_alerta'] = "Faltan datos obligatorios por llenar.";
            $this->index();
        }else{
            /*
             * Se preparan los campos
             */
            $campo_cedula = $this->input->post('cedula');
            $campo_nombre = $this->input->post('nombre');
            $campo_apellido = $this->input->post('apellido');
            $campo_usuario = $this->input->post('nombre_usuario');
            $campo_password1 = md5($this->input->post('password1'));            
            $campo_email = $this->input->post('email');            
            $campo_telefono = $this->input->post('telefono');
            $campo_restablecer = $this->encrypt->encode($this->input->post('password1'));
            $campo_ciudad = $this->input->post('ciudad');
            $insert = $this->usuario_model->insertar_usuario(
                    $campo_cedula, 
                    $campo_nombre, 
                    $campo_apellido, 
                    $campo_usuario, 
                    $campo_password1, 
                    $campo_email, 
                    $campo_telefono,
                    $campo_restablecer,
                    $campo_ciudad);
            //Se obtiene el id de la entidad
            $id_entidad = $this->db->insert_id();
            
            //Se inserta el licenciamiento b&aacute;sico
            $licencia = array(
                'Fk_Id_Entidad' => $id_entidad,
            );
            $this->licenciamiento_model->licenciamiento_basico($licencia);
            
            $nombre_usuario = $campo_usuario;
            $password = $campo_password1;
            $datos_usuario = $this->usuario_model->validar_login($nombre_usuario, $password);

            //Si la consulta trajo algun resultado:
            if ($datos_usuario) {
                //se verifica que el usuario este activo
                if ($datos_usuario->Estado == 1) {
                    //Se arma un array indicando los datos que se van a cargan a la sesion
                    $datos_sesion = array(
                        "PK_IdEntidad" => $datos_usuario->PK_IdEntidad,
                        "nombre_usuario" => $datos_usuario->Nombres,
                        "apellido_usuario" => $datos_usuario->Apellidos,
                        "mail_usuario" => $datos_usuario->Email,
                    );

                    //Se cargan los datos a la sesion
                    $this->session->set_userdata($datos_sesion);
                    
                    /*
                     * Se preparan los datos del correo electr&oacute;nico
                     */
                    $usuarios = $this->input->post('email');    //Usuarios que recibir&aacute;n el correo
                    $asunto = 'Bienvenido';                     //Asunto del correo
                    $cabecera = 'bienvenido_cabecera.png';      //Imagen de cabecera que llevar&aacute; el correo
                    $cuerpo1 = $datos_usuario->Nombres.', Te damos la bienvenida a creaeventos.co, la manera m&aacute;s sencilla de inscribirte en un evento y obtener tu escarapela y certificado.<br><br>
                        Estos son los datos para que inicies sesi&oacute;n:<br><br>
                        <b>Usuario:</b> '.$campo_usuario.'<br>
                        <b>Clave:</b> '.$this->encrypt->decode($campo_restablecer);
                    
                    $cuerpo2 = 'bienvenido_cuerpo.png';         //Imagen del cuerpo del mensaje
                    
                    //Se ingresa el registro de auditor&iacute;a
                    $this->auditoria_model->nueva_inscripcion($campo_nombre, $campo_apellido, $id_entidad);
                    
                    //Se muestra el mensaje de &eacute;xito
                    $this->data['mensaje_exito'] = "Tu inscripci&oacute;n se ha llevado a cabo correctamente.";
                    
                    //se establece el titulo de la pag&iacute;na
                    $this->data['titulo'] = 'creaeventos.co - Inicio';
                    //Se establece el t&iacute;tulo del formulario
                    $this->data['titulo_formulario'] = 'Hola '.$this->session->userdata('nombre_usuario').', La licencia gratuita te permite administrar completamente dos eventos. Puedes ampliar la licencia';
                    //se establece la vista que tiene el contenido principal
                    $this->data['contenido_principal'] = 'inicio/inicio_view';
                    //se carga el template
                    $this->load->view('includes/template', $this->data);
                    
                    //Se env&iacute;a el email
                    $this->email_model->_enviar($usuarios, $asunto, $cabecera, $cuerpo1, $cuerpo2);
                } else {
                    $this->data['mensaje_error'] = "El usuario est&aacute; deshabilitado.";
                    $this->index();
                }
            }else{
                /*
                 * Si la consulta no trajo ningun resultado
                 * Se muestra el mensaje de error
                 */
                $this->data['mensaje_error'] = "El usuario y la contrase&ntilde;a no coinciden";
                $this->index();
            }
        }
    }//Fin validar()
}//Fin inicio
/* End of file inscripcion.php */
/* Location: ./creaeventos/application/controllers/inscripcion.php */
