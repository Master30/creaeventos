<?php
//Zona horaria
date_default_timezone_set('America/Bogota');

if ( ! defined('BASEPATH')) exit('Lo sentimos, usted no tiene acceso a esta ruta');

/**
 * Controlador del m&oacute;dulo de Sesion.
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy; Oscar Humberto Morales y John Arley Cano
 */
class Sesion_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array('usuario_model', 'licenciamiento_model'));
        $this->load->library(array('table'));
        $this->load->helper('html');
    }

    function index() {
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'creaeventos.co - Identif&iacute;cate';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'sesion/sesion_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }

//Fin index()

//Fin recuperar_password

    function verificar_login() {
        $this->form_validation->set_rules('nombre_usuario', 'nombre de usuario', 'required|trim|alphanumeric');
        $this->form_validation->set_rules('password', 'contrase&ntilde;a', 'required|min_length[5]|md5');

        //Si el método devuelve FALSE, la validación no se llevó corretamente
        $this->form_validation->set_message('required', 'Este campo es obligatorio');
        $this->form_validation->set_message('min_length', 'El campo %s debe tener como m&iacute;nimo 5 caracteres');
        $this->form_validation->set_message('alpha', 'En este campo solo se permiten letras');
        $this->form_validation->set_message('numeric', 'En este campo solo se permiten n&uacute;meros');
        //Esta es la condición que ejecuta las reglas y no lo deja pasar. Si el método devuelve FALSE, la validación no se llevó corretamente
        if ($this->form_validation->run() == FALSE) {
            //
            $this->data['mensaje_alerta'] = "Hay datos obligatorios que aun no has llenado";
            $this->index();
        } else {
            $nombre_usuario = $this->input->post('nombre_usuario');
            $password = $this->input->post('password');
            //echo($nombre_usuario);
            //echo($password);
            $datos_usuario = $this->usuario_model->validar_login($nombre_usuario, $password);
            
            //Se ingresa el registro de auditor&iacute;a
            $this->auditoria_model->intento_inicio_sesion($nombre_usuario);
            
            //Si la consulta trajo algun resultado
            if ($datos_usuario) {
                //se verifica que el usuario este activo
                //echo($datos_usuario);
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
                    
                    //Se muestra el mensaje de exito
                    $this->data['mensaje_exito'] = "Tus datos se validaron correctamente";
                     //se establece el titulo de la pag&iacute;na
                    $this->data['titulo'] = 'creaeventos.co - Inicio';
                    //Se establece el t&iacute;tulo del formulario
                    $this->data['titulo_formulario'] = 'Bienvenido a creaeventos.co';
                    //se establece la vista que tiene el contenido principal
                    $this->data['contenido_principal'] = 'inicio/inicio_view';
                    //se carga el template
                    $this->load->view('includes/template', $this->data);
                                        
                    //Se ingresa el registro de auditor&iacute;a
                    $this->auditoria_model->inicio_sesion();
                } else {
                    //Se imprime el mensaje de error
                    $this->data['mensaje_error'] = "Tu usuario est&aacute; desactivado";
                    $this->index();
                }
            }
            //Si la consulta no trajo ningun resultado
            else {
                //Se imprime el mensaje de error
                $this->data['mensaje_error'] = "Usuario y contrase&ntilde;a no v&aacute;lidos";
                
                //Se redirecciona al contrlador de sesion
                $this->index();
            }
        }
    }

//Fin verificar login

    function cerrar() {
        /**
         * Funcion encargada de destruir los datos de la sesi&oacute;n.
         * 
         * @access	public
         */
        //Se ingresa el registro de auditor&iacute;a
        $this->auditoria_model->cierre_sesion();
                    
        //Se destruye la sesion actual
        $this->session->sess_destroy();
        
        //Se redirige hacia el controlador principal
        redirect('inicio');
    }
}//Fin sesion_controller()