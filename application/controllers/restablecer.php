<?php
//Zona horaria
date_default_timezone_set('America/Bogota');

if ( ! defined('BASEPATH')) exit('Lo sentimos, usted no tiene acceso a esta ruta');

/**
 * Controlador del m&oacute;dulo de Sesion.
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy;  Oscar Humberto Morales y John Arley Cano
 */
class Restablecer extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array('usuario_model', 'email_model'));
        $this->load->library(array('encrypt', 'email'));
    }

    function index() {
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'Recordar contraseña';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'restablecera/restablecer_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin index()

    //Fin recuperar_password
    function restablecer_clave(){
        /*
         * Crear reglas de validaci&oacute;n para los campos
         * La protecci&oacute;n contra ataques XSS se configur&oacute; desde config.php
         * trim limpia los espacios en blanco
         * min_lenght define el m&iacute;nimo de un campo
         * md5 encripta la contrase&ntilde;
         * callback me verificar&aacute; que ese campo no exista
         */
        $this->form_validation->set_rules('cedula', 'c&eacute;dula', 'required|trim');       
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback__validar_mail');
        
        //Si el método devuelve FALSE, la validaci&oacute;n no se llev&oacute; corretamente
        $this->form_validation->set_message('required', 'Este campo es obligatorio');
        $this->form_validation->set_message('min_length', 'El campo %s debe tener como m&iacute;nimo 5 caracteres');
        $this->form_validation->set_message('max_length', 'El campo %s no puede exceder los 12 caracteres');
        $this->form_validation->set_message('matches', 'Las contrase&ntilde;as no coinciden');
        $this->form_validation->set_message('valid_email', 'El email no es v&aacute;lido');
        $this->form_validation->set_message('alpha', 'En este campo solo se permiten letras');
        $this->form_validation->set_message('numeric', 'En este campo solo se permiten n&uacute;meros');
        $this->form_validation->set_message('_validar_usuario', 'El nombre de usuario %s ya existe');
        
        //Se ingresa el registro de auditor&iacute;a
        $this->auditoria_model->restablecer_clave($this->input->post('cedula'));
        
        //Esta es la condici&oacute;n que ejecuta las reglas y no lo deja pasar. Si el m&eacute;todo devuelve FALSE, la validaci&oacute;n no se llev&oacute; corretamente
        if ($this->form_validation->run() == FALSE)
        {
            //Se redirecciona al contrlador de sesion
            $this->data['mensaje_alerta'] = "Debes ingresar ambos datos";
            $this->index();
        }
        else
        {       
            $campo_cedula = $this->input->post('cedula');
            $campo_email = $this->input->post('email');
            $datos_usuario = $this->usuario_model->restaurar_clave($campo_cedula, $campo_email);                                    
            if ($datos_usuario) {
                /*
                 * Se preparan los datos del correo electr&oacute;nico
                 */
                $usuarios = $this->input->post('email');        //Usuarios que recibir&aacute;n el correo
                $asunto = 'Recupera tu contraseña';             //Asunto del correo
                $cabecera = 'restablecer_cabecera.png';
                $cuerpo1 = 
                'Has solicitado recordar tu nombre de usuario y contraseña, y por eso te estamos enviando nuevamente tus datos:<br><br>
                <b>Usuario: </b>'.$datos_usuario->Usuario.'<br>
                <b>Clave: </b>'.$this->encrypt->decode($datos_usuario->RestablecerClave).'<br><br>';
                $cuerpo2 = 'restablecer_cuerpo.png';            //Imagen del cuerpo del mensaje
                
                //Se env&iacute;a el email
                $this->email_model->_enviar($usuarios, $asunto, $cabecera, $cuerpo1, $cuerpo2);
                
                //Se imprime el mensaje de &eacute;xito
                $this->data['mensaje_exito'] = "Los datos de tu cuenta ya han sido enviados a tu correo electr&oacute;nico ";
                
                //se establece el titulo de la pag&iacute;na
				$this->data['titulo'] = 'creaeventos.co - Identif&iacute;cate';
				//se establece la vista que tiene el contenido principal
				$this->data['contenido_principal'] = 'sesion/sesion_view';
				//se carga el template
				$this->load->view('includes/template', $this->data);
            }       
            //Si la consulta no trajo ningun resultado
            else{
                //Se redirecciona al contrlador de sesion
                $this->data['mensaje_error'] = "Los datos que est&aacute;s ingresando no se encontraron en el sistema.";
                $this->index();
            }//fin  if ($datos_usuario) {                
        }
    }//restablecer_clave(){            
}//Fin Restablecer
/* End of file restablecer.php */
/* Location: ./creaeventos/application/controllers/restablecer.php */