<?php
//Zona horaria
date_default_timezone_set('America/Bogota');

if ( ! defined('BASEPATH')) exit('Lo sentimos, usted no tiene acceso a esta ruta');

/**
 * Controlador del m&oacute;dulo de Registro.
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy; Oscar Humberto Morales y John Arley Cano
 */
class Eventoregistro_controller extends CI_Controller {
    /**
    * Funci&oacute;n constructora de la clase Eventoregistro_controller. 
    * 
    * Se hereda el mismo constructor de la clase Controller para evitar sobreescribirlo y de esa manera 
    * conservar el funcionamiento de controlador.
    * 
    * @access	public
    */
   function __construct() {
        parent::__construct();
        //Se cargan los modelos
        $this->load->model(array('evento_model', 'email_model'));
        //Se cargan las librer&iacute;as y helpers
        $this->load->helper('html');
        $this->load->library('email');
        //si el usuario no esta logueado
        if($this->session->userdata('PK_IdEntidad') != TRUE)
        {            
            //redirecciono al controlador de sesion
            redirect('sesion_controller');                        
        }
    }//Fin construct
    
    /**
     * Muestra la vista principal del m&oacute;dulo de registo de eventos.
     * 
     * @access	public
     */
    function index() {
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'Crea tu evento';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Crear evento nuevo';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'eventoregistro/eventoregistro_view';
        
        //
        $this->data['IdEvento'] = '';
            
        //Array del campo nombre
        $this->data['nombre'] = '';

        //Array del campo Descripcion
        $this->data['descripcion'] = '';

        //Array del campo Fecha inicio
        $this->data['fechainicio'] = '';

        //Array del campo Fecha fin
        $this->data['fechafin'] = '';

        //Array del campo Ubicacion
        $this->data['ubicacion'] = '';

        //Array del campo de direccion
        $this->data['direccion'] = '';
        
        //Array del campo de ciudad
        $this->data['ciudad'] = $this->input->post('ciudad');        

        //Array del campo Subir Escarapela
        $this->data['escarapela'] = '';

        //Array del campo Subir Certificado
        $this->data['certificado'] = '';

        //Array del campo maximoasistentes
        $this->data['maximoasistentes'] = '';
        
        //Array del campo Imagen banner
        $this->data['imagenbanner'] = '';
        
        //Array del campo Permitir Impresion a los usuarios 
        $this->data['PermitirImpresionU'] = $this->input->post('PermitirImpresionUsuario');
        
        //se valida que solo no halla ningun check seleccionado 
        if($this->input->post('ImpresionCertificadoMedia')==0){
            //Array del campo Permitir Impresion certificado horizontal a los usuarios 
            $this->data['ImpresionCertificadoH'] = 1;
        }else{
            //Array del campo Permitir Impresion certificado horizontal a los usuarios 
            $this->data['ImpresionCertificadoH'] = $this->input->post('ImpresionCertificadoHorizontal');
        } 
            
        //Array del campo Permitir Impresion certificado media carta a los usuarios 
        $this->data['ImpresionCertificadoM'] = $this->input->post('ImpresionCertificadoMedia');            
        
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin index()

    /**
     * Verifica y retorna true la fecha de fin es mayor que la inicial
     * 
     * @access	private
     */
    function _validar_fecha_menor($fechafin){        
        $fechainicio = $this->fecha_comparacion($this->input->post('fechainicio'));
        $fechafin = $this->fecha_comparacion($fechafin);
        if($fechainicio>$fechafin){            
            return FALSE;
        }else{        
            return TRUE;
        }    
    }//Fin _validar_fecha_menor
    
    /**
     * Comparacion de fechas
     * 
     * @access	public
     */
    function fecha_comparacion($fecha){
        list($dia,$mes,$anio)=explode("-",$fecha);
        $horas = trim(substr($anio,4,3));
        $minutos = trim(substr($anio,8,2));
        $PM = trim(substr($anio,10,3));
        if($PM=="PM"){
            $horas = $horas+12;
        }        
        $hora = $horas.$minutos;        
        $anio = substr($anio,0,4);
        return $anio."".$mes."".$dia."".$hora;
    }
    
    /**
     * Se encarga de procesar y guardar todos los datos del evento
     * 
     * @access	public
     */
    function registro() {
        $this->load->model('licenciamiento_model');
        $evento_guardado= site_url('evento_controller');
        /*
         * Crear reglas de validación para los campos
         * La protección contra ataques XSS se configuró desde config.php
         * trim limpia los espacios en blanco
         * min_lenght define el mínimo de un campo
         * md5 encripta la contraseña
         * callback me verificará que ese campo no exista
         */
        $this->form_validation->set_rules('nombre', 'nombre', 'required|trim');
        $this->form_validation->set_rules('descripcion', 'descripcion', 'trim');
        $this->form_validation->set_rules('fechainicio', 'fechainicio', 'required|trim');
        $this->form_validation->set_rules('fechafin', 'fechafin', 'required|trim|callback__validar_fecha_menor');
        $this->form_validation->set_rules('maximoasistentes', 'maximoasistentes', 'required|trim|numeric');
        $this->form_validation->set_rules('maximoasistentes', 'maximoasistentes', 'required|trim|numeric');               
        $this->form_validation->set_rules('ubicacion', 'ubicacion', 'trim');        
        $this->form_validation->set_rules('direccion', 'direccion', 'trim');        
        $this->form_validation->set_rules('escarapela', 'escarapela', 'trim');        
        $this->form_validation->set_rules('imagenescarapela', 'imagenescarapela', 'trim');        
        $this->form_validation->set_rules('certificado', 'certificado', 'trim'); 
        $this->form_validation->set_rules('imagencertificado', 'imagencertificado', 'trim'); 
        $this->form_validation->set_rules('banner', 'banner', 'trim'); 
        $this->form_validation->set_rules('imagenbanner', 'imagenbanner', 'trim');         
        $this->form_validation->set_rules('ciudad', 'ciudad', 'trim');        
        $this->form_validation->set_rules('log', 'log', 'trim'); 

        //Si el método devuelve FALSE, la validación no se llevó corretamente
        $this->form_validation->set_message('required', 'Este campo es obligatorio');
        $this->form_validation->set_message('min_length', 'El campo %s debe tener como m&iacute;nimo 5 caracteres');
        $this->form_validation->set_message('max_length', 'El campo %s no puede exceder los 12 caracteres');
        $this->form_validation->set_message('matches', 'Las contrase&ntilde;as no coinciden');
        $this->form_validation->set_message('valid_email', 'El email no es v&aacute;lido');
        $this->form_validation->set_message('alpha', 'En este campo solo se permiten letras');
        $this->form_validation->set_message('numeric', 'En este campo solo se permiten n&uacute;meros');
        $this->form_validation->set_message('_validar_usuario', 'El nombre de usuario %s ya existe');
        $this->form_validation->set_message('_validar_fecha_menor', 'La fecha de fin no puede ser menor a la fecha de inicio');

        //Esta es la condición que ejecuta las reglas y no lo deja pasar. Si el método devuelve FALSE, la validación no se llevó corretamente
        if ($this->form_validation->run() == FALSE) {
            //Se imprime el mensaje de error
            $this->data['mensaje_alerta'] = "Hay datos obligatorios que aun no has llenado";
            $this->index();
        } else {
            $Id_Evento = $this->input->post('IdEvento');
            if($Id_Evento != ''){
                $campo_nombre = $this->input->post('nombre');
                $campo_descripcion = $this->input->post('descripcion');
                $campo_fechainicio = $this->input->post('fechainicio');
                $campo_fechafin = $this->input->post('fechafin');
                $campo_ubicacion = $this->input->post('ubicacion');
                $campo_direccion = $this->input->post('direccion');
                $campo_ciudad = $this->input->post('ciudad');
                $campo_maximoasistentes = $this->input->post('maximoasistentes');
                $campo_IdEntidad = $this->session->userdata('PK_IdEntidad');
                $ciudad_a = $this->input->post('log');
                $campo_Impresionusuario = $this->input->post('PermitirImpresionUsuario');
                $campo_ImpresionCertificadoH = $this->input->post('ImpresionCertificadoHorizontal');
                $campo_ImpresionCertificadoV = $this->input->post('ImpresionCertificadoVertical');
                $campo_ImpresionCertificadoM = $this->input->post('ImpresionCertificadoMedia');
                $Update = $this->evento_model->actualizar_Evento(
                        $Id_Evento, $campo_nombre, $campo_descripcion, $campo_fechainicio, 
                        $campo_fechafin, $campo_ubicacion, $campo_direccion, $campo_ciudad,  
                        $campo_maximoasistentes, $campo_IdEntidad, $campo_Impresionusuario, 
                        $campo_ImpresionCertificadoH, $campo_ImpresionCertificadoM);

                $this->subir_imagenes($Id_Evento);
                
                //Se muestra el mensaje de $eacute;xito
                $this->data['mensaje_exito'] = "Has actualizado tu evento con &eacute;xito.";
                
                //Se ingresa el registro de auditor&iacute;a
                $this->auditoria_model->evento_actualizado($Id_Evento, $campo_nombre);
                
                $this->data['eventos'] = $this->evento_model->listarEventos();
                //se establece el titulo de la pag&iacute;na
                $this->data['titulo'] = 'Event Green';
                //Se establece el t&iacute;tulo del formulario
                $this->data['titulo_formulario'] = 'Estos son tus eventos. Si no has creado ninguno, puedes hacerlo haciendo clic en "Crear Evento"';
                //se establece la vista que tiene el contenido principal
                $this->data['contenido_principal'] = 'evento/evento_view';
                //se carga el template
                $this->load->view('includes/template', $this->data);
            }else{
                //Se valida que pueda crear m&aacute;s eventos
                if($this->session->userdata('licencia') == 1 and $this->evento_model->validar_creacion_evento($this->session->userdata('PK_IdEntidad')) < 1){
                    //Se imprime el mensaje de error
                    $this->data['mensaje_error'] = "Ya has llegado al l&iacute;mite de eventos creados.";
                    
                    //Se ingresa el registro de auditor&iacute;a
                    $this->auditoria_model->cupo_agotado_eventos($this->input->post('nombre'));
                    
                    //Se listan los eventos
                    $this->data['eventos'] = $this->evento_model->listarEventos();
                    //se establece el titulo de la pag&iacute;na
                    $this->data['titulo'] = 'Eventos creados por m&iacute;';
                    //Se establece el t&iacute;tulo del formulario
                    $this->data['titulo_formulario'] = 'Si deseas seguir usando Event Green debes adquirirlo. Revisa los planes';
                    //se establece la vista que tiene el contenido principal
                    $this->data['contenido_principal'] = 'evento/evento_view';
                    //se carga el template
                    $this->load->view('includes/template', $this->data);
                }else{
                    $campo_nombre = $this->input->post('nombre');
                    $campo_descripcion = $this->input->post('descripcion');
                    $campo_fechainicio = $this->input->post('fechainicio');
                    $campo_fechafin = $this->input->post('fechafin');
                    $campo_ubicacion = $this->input->post('ubicacion');
                    $campo_direccion = $this->input->post('direccion');
                    $campo_ciudad = $this->input->post('ciudad');
                    $campo_maximoasistentes = $this->input->post('maximoasistentes');
                    $campo_IdEntidad = $this->session->userdata('PK_IdEntidad');
                    $ciudad_a = $this->input->post('log');
                    $campo_Impresionusuario = $this->input->post('PermitirImpresionUsuario');
                    $campo_ImpresionCertificadoH = $this->input->post('ImpresionCertificadoHorizontal');
                    $campo_ImpresionCertificadoV = $this->input->post('ImpresionCertificadoVertical');
                    $campo_ImpresionCertificadoM = $this->input->post('ImpresionCertificadoMedia');
                    $insert = $this->evento_model->insertar_Evento(
                            $campo_nombre, $campo_descripcion, $campo_fechainicio, $campo_fechafin, 
                            $campo_ubicacion, $campo_direccion, $campo_ciudad, 
                            $campo_maximoasistentes, $campo_IdEntidad, $campo_Impresionusuario, 
                            $campo_ImpresionCertificadoH, $campo_ImpresionCertificadoM);

                    //Se trae Id Ultimo evento Insertado
                    $Id_Evento = $this->db->insert_id();

                    //se guarda el archivo del certificado en el servidor
                    $this->subir_imagenes($Id_Evento); 

                    //Se ingresa el registro de auditor&iacute;a
                    $this->auditoria_model->evento_nuevo($Id_Evento, $campo_nombre);
                    
                    //Se agrega el evento al usuario
                    $this->evento_model->agregar_evento_permitido();
                    
                    //Echo Mensaje Enviado
                    $this->data['mensaje_exito'] = "Has creado tu evento exitosamente.";

                    $this->load->model('evento_model');
                    $this->data['eventos'] = $this->evento_model->listarEventos();

                    //se establece el titulo de la pag&iacute;na
                    $this->data['titulo'] = 'creaeventos.co - Eventos';
                    //Se establece el t&iacute;tulo del formulario
                    $this->data['titulo_formulario'] = 'Eventos creados por mí';
                    //se establece la vista que tiene el contenido principal
                    $this->data['contenido_principal'] = 'evento/evento_view';
                    //se carga el template
                    $this->load->view('includes/template', $this->data);
                }
            }
        }//fin if ($this->form_validation->run() == FALSE)                
    }

//Fin registro()

    function cargarevento() {
        $IdEvento = $this->input->post('id_evento');
        $datoseventos = $this->evento_model->cargarEventos($IdEvento);
        foreach ($datoseventos as $datosevento): endforeach;
        //echo $strt = "<pre>".print_r($datosevento,true)."</pre>";//imprimir matriz        
        if ($datosevento) { 
            //Array del campo IdEvento
            $this->data['IdEvento'] = $datosevento->PK_IdEvento;
            
            //Array del campo nombre
            $this->data['nombre'] = $datosevento->NombreEvento;

            //Array del campo Descripcion
            $this->data['descripcion'] = $datosevento->DescripcionEvento;

            //Array del campo Fecha inicio
            $this->data['fechainicio'] = $datosevento->FechaInicio;

            //Array del campo Fecha fin
            $this->data['fechafin'] = $datosevento->FechaFin;

            //Array del campo Ubicacion
            $this->data['ubicacion'] = $datosevento->DescripcionUbicacion;

            //Array del campo de direccion
            $this->data['direccion'] = $datosevento->Direccion_Completa;            
            
            //Array del campo de ciudad
            $this->data['ciudad'] = $datosevento->Ciudad;
            
            //Array del campo Subir Escarapela
            $this->data['escarapela'] = $datosevento->Escarapela_Evento;

            //Array del campo Subir Certificado
            $this->data['certificado'] = $datosevento->Certificado_Evento;

            //Array del campo maximoasistentes
            $this->data['maximoasistentes'] = $datosevento->MaximoParticipantes;            
            
            //Array del campo Subir Envio Correo
            $this->data['imagenbanner'] = $datosevento->Banner_Evento;
            
            //Array del campo permitir Impresion Usuarios
            $this->data['PermitirImpresionU'] = $datosevento->PermitirImpresionUsuario;
                        
            //Array del campo Permitir Impresion certificado horizontal a los usuarios 
            $this->data['ImpresionCertificadoH'] = $datosevento->ImpresionCertificadoHorizontal;

            //Array del campo Permitir Impresion certificado media carta a los usuarios 
            $this->data['ImpresionCertificadoM'] = $datosevento->ImpresionCertificadoMedia;  
            
            //se establece el titulo de la pag&iacute;na
            $this->data['titulo'] = 'Actualizaci&oacute;n de Eventos';
            //Se establece el t&iacute;tulo del formulario
            $this->data['titulo_formulario'] = 'Modificar evento';
            //se establece la vista que tiene el contenido principal
            $this->data['contenido_principal'] = 'eventoregistro/eventoregistro_view';                                           
            
            //se carga el template
            $this->load->view('includes/template', $this->data);
        }
    }

//Fin cargarevento()
    
    function subir_imagenes($IdEvento)
    {                 
        $carpeta = 'img_certificados';
        //echo $carpeta."<br>";
        $nombre_archivo=$IdEvento;
        //se verifica que exista la ruta de donde se van a guardar los archivos
        if (! is_dir($carpeta."/".$nombre_archivo)) {            
            //sino entonces se crea la ruta con todos los permisos
            @mkdir($carpeta."/".$nombre_archivo,0777);
            //echo "se creo la carpeta carpeta_nueva";
        }
        $carpeta = $carpeta."/".$nombre_archivo;
        //echo $carpeta."<br>";
        $resultado = "correcto";  
        //print_r($_FILES['certificado']);
        if(isset($_FILES['certificado'])) {
            foreach ($_FILES['certificado'] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['certificado']['tmp_name'];
                    $name = trim($_FILES['certificado']['name']);
                    //echo $_FILES['certificado']."<br>";
                    //echo $tmp_name;
                    //echo "Valor '".$name."' <br>";
                    if($_FILES['certificado']['error']==0){
                        //Se inserta nombre certificado en base de datos
                        $Certificado = $this->evento_model->guardarCertificado($IdEvento, $name);                                        
                    }
                    if( ! move_uploaded_file($tmp_name, $carpeta.'/'.$name))
                    {
                        $resultado = "Ocurri&oacute; un error al subir al certificado, verifique por favor.";
                    }
                }
            }

            //echo $resultado;
        }else {
            echo "Debe seleccionar al menos un certificado";
        }
        
        $carpeta = 'img_escarapelas';
        $nombre_archivo=$IdEvento;
        //echo($carpeta);
        //se verifica que exista la ruta de donde se van a guardar los archivos
        if (! is_dir($carpeta."/".$nombre_archivo)) {            
            //sino entonces se crea la ruta con todos los permisos
            @mkdir($carpeta."/".$nombre_archivo,0777);
            //echo "se creo la carpeta carpeta_nueva";
        }
        $carpeta = $carpeta."/".$nombre_archivo;
        $resultado = "correcto";

        if(isset($_FILES['escarapela'])) {
            //echo($_FILES['escarapela']);
            foreach ($_FILES['escarapela'] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['escarapela']['tmp_name'];
                    $name = $_FILES['escarapela']['name'];
                    //echo $_FILES['escarapela']."<br>";
                    //echo $tmp_name;
                    //echo "Valorfg '".$namee."' <br>";
                    if($_FILES['escarapela']['error']==0){
                        //Se inserta nombre escarapela en base de datos
                        $Escarapela = $this->evento_model->guardarEscarapela($IdEvento, $name);
                    }
                    if( ! move_uploaded_file($tmp_name, $carpeta.'/'.$name))
                    {
                        $resultado = "Ocurri&oacute; un error al subir la escarrapela, verifique por favor.";
                    }
                }
            }

            //echo $resultado;
        }else {
            echo "Debe seleccionar al menos una Escarapela";
        }
        
        $carpeta = 'img_banner';
        //echo $carpeta."<br>";
        $nombre_archivo=$IdEvento;
        //se verifica que exista la ruta de donde se van a guardar los archivos
        if (! is_dir($carpeta."/".$nombre_archivo)) {            
            //sino entonces se crea la ruta con todos los permisos
            @mkdir($carpeta."/".$nombre_archivo,0777);
            //echo "se creo la carpeta carpeta_nueva";
        }
        $carpeta = $carpeta."/".$nombre_archivo;
        //echo $carpeta."<br>";
        $resultado = "correcto";  
        //print_r($_FILES['certificado']);
        if(isset($_FILES['banner'])) {
            foreach ($_FILES['banner'] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['banner']['tmp_name'];
                    $name = trim($_FILES['banner']['name']);
                    //echo $_FILES['certificado']."<br>";
                    //echo $tmp_name;
                    //echo "Valor '".$name."' <br>";
                    if($_FILES['banner']['error']==0){
                        //Se inserta nombre certificado en base de datos
                        $Certificado = $this->evento_model->guardarBanner($IdEvento, $name);                                        
                    }
                    if( ! move_uploaded_file($tmp_name, $carpeta.'/'.$name))
                    {
                        $resultado = "Ocurri&oacute; un error al subir imagen correo, verifique por favor.";
                    }
                }
            }

            //echo $resultado;
        }else {
            echo "Debe seleccionar al menos una imagen para enviar por correo";
        }
    }
//fin  subir_imagenes($IdEvento)
}
//Fin _controller
?>
