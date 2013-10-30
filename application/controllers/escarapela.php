<?php
//Zona horaria
date_default_timezone_set('America/Bogota');

if ( ! defined('BASEPATH')) exit('Lo sentimos, usted no tiene acceso a esta ruta');
        
/**
 * Controlador para administrar impresion escarapela
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy;  Oscar Humberto Morales y John Arley Cano
 */
Class Escarapela extends CI_Controller{
    /**
    * Funci&oacute;n constructora de la clase Impresionescarapela. 
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
        $this->load->library(array('fpdf', 'ImprimirPDF'));
        $this->load->model('impresion_model');
        $this->load->helper('html');
        
    }//Fin construct

    /**
     * Se carga la Interface.
     * 
     * @access	public
     */
    function index(){
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'Seleccionar tipo de Impresi&oacute;n Escarapela';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Impresi&oacute;n Escarapela';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'impresion/escarapela_view';
        //Se recibe el id del evento    
        $this->data['IdEvento'] = $this->input->post('id_evento');
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin index()
    
    /**
    * Imprime la escarapela de muestra del inicio y de referencia para 
     * la ceraci&oacute;n de eventos
    * 
    * @access	public
    */
    function muestra(){
        //Establecemos la ruta para las fuentes del reporte
        define('FPDF_FONTPATH','application/font/');
        
        //Generamos una nueva hoja de PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        //Cargar la imagen de fondo de la escarapela
        $pdf->Image('img/plantillas/escarapela_muestra.png', 55, 0, 90, 130);
        
        $pdf->Ln();
        
        $pdf->SetXY(0,50);
        $pdf->SetFont('Arial','B',24);
        $pdf->MultiCell(0,7, utf8_decode(strtoupper($this->session->userdata('nombre_usuario'))),0,'C');//Nombres del participante
        $pdf->SetFont('Arial','B',12);
        $pdf->MultiCell(0,7, utf8_decode($this->session->userdata('apellido_usuario')),0,'C');//Apellidos del participante
        $pdf->Image('img/logo.png', 65, 130, 0, 0);//Logo

        //Limpiar búferes de salida
        ob_end_clean ();
        
        //Imprimimos el PDF
        $pdf->Output('Escarapela '.$this->session->userdata('nombre_usuario').' '.$this->session->userdata('apellido_usuario'), 'I');
        
        //Se ingresa el registro de auditor&iacute;a
        $this->auditoria_model->escarapela_muestra(NULL);
    }//Fin muestra()
    
    /**
    * Imprime la escarapela de cada asistente de a cuatro por hoja.
    * 
    * @access	public
    */
    function masiva(){                              
        $id_evento = $this->uri->segment(3);
        
        //Cargamos el metodo que nos muestra todos los eventos
        $eventos = $this->data['eventos'] = $this->evento_model->listarEventos($id_evento);
        $asistentes = $this->data['asistentes'] = $this->impresion_model->listarAsistentesEventos($id_evento, 1);
        //echo $strt = "<pre>".print_r($eventos,true)."</pre>";    //imprimir matriz
        //echo $strt = "<pre>".print_r($asistentes,true)."</pre>";    //imprimir matriz
        //exit();
        //Establecemos la ruta para las fuentes del reporte
        define('FPDF_FONTPATH','application/font/');
        
        //Generamos una nueva hoja de ImprimirPDF extension del FPDF
        $pdf = new ImprimirPDF();
        $i=0;
        foreach ($asistentes as $asistente):                
            $i++;            
            //echo($i."modulo 4 ".($i%4)."<br>");            
            //echo($i."modulo 3 ".($i%3)."<br>");            
            //echo($i."modulo 2 ".($i%2)."<br>");            
            //echo($i."sino ".($i)."<br>");            
            foreach ($eventos as $evento):                
                if(($i%4)==0){
                    //Cargar la imagen de fondo de la escarapela
                    if($evento->Escarapela_Evento == NULL){
                        //Escarapela por defecto
                        $pdf->Image('img/plantillas/escarapela.png', 105, 149, 90, 130);
                    }else{
                        $pdf->Image('img_escarapelas/'.$id_evento.'/'.$evento->Escarapela_Evento, 105, 149, 90, 130);
                    }
                    $pdf->SetXY(105,200);
                    $pdf->SetFont('Arial','B',24);
                    $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                    $Apellidos = utf8_decode($asistente->Apellidos);
                    $pdf->MultiCell(100,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetX(105);
                    $pdf->MultiCell(80,7, $Apellidos,0,'C');//Apellidos del participante
                }else if(($i%3)==0){
                    //Cargar la imagen de fondo de la escarapela
                    if($evento->Escarapela_Evento == NULL){
                        //Escarapela por defecto
                        $pdf->Image('img/plantillas/escarapela.png', 0, 149, 90, 130);
                    }else{
                        $pdf->Image('img_escarapelas/'.$id_evento.'/'.$evento->Escarapela_Evento, 0, 149, 90, 130);
                    }
                    $pdf->SetXY(0,200);
                    $pdf->SetFont('Arial','B',24);
                    $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                    $Apellidos = utf8_decode($asistente->Apellidos);
                    $pdf->MultiCell(90,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetX(0);
                    $pdf->MultiCell(90,7, $Apellidos,0,'C');//Apellidos del participante
                }else if(($i%2)==0){
                    //Cargar la imagen de fondo de la escarapela
                    if($evento->Escarapela_Evento == NULL){
                        //Escarapela por defecto
                        $pdf->Image('img/plantillas/escarapela.png', 105, 0, 90, 130);
                    }else{
                        $pdf->Image('img_escarapelas/'.$id_evento.'/'.$evento->Escarapela_Evento, 105, 0, 90, 130);
                    }
                    $pdf->SetXY(105,50);
                    $pdf->SetFont('Arial','B',24);
                    $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                    $Apellidos = utf8_decode($asistente->Apellidos);
                    $pdf->MultiCell(90,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetX(105);
                    $pdf->MultiCell(90,7, $Apellidos,0,'C');//Apellidos del participante
                }else{
                    $pdf->AddPage();
                    //Cargar la imagen de fondo de la escarapela
                    if($evento->Escarapela_Evento == NULL){
                        //Escarapela por defecto
                        $pdf->Image('img/plantillas/escarapela.png', 0, 0, 90, 130);
                    }else{
                        $pdf->Image('img_escarapelas/'.$id_evento.'/'.$evento->Escarapela_Evento, 0, 0, 90, 130);
                    }
                    $pdf->SetXY(0,50);
                    $pdf->SetFont('Arial','B',24);
                    $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                    $Apellidos = utf8_decode($asistente->Apellidos);
                    $pdf->MultiCell(90,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetX(0);
                    $pdf->MultiCell(90,7, $Apellidos,0,'C');//Apellidos del participante
                }
            endforeach;     
        endforeach;        
        //Limpiar búferes de salida
        ob_end_clean ();
        
        $pdf->imprime(true);
        $pdf->Output();
        $this->auditoria_model->escarapela_masiva($id_evento);
    }//Fin masiva()
    
    
    /**
    * Imprime la escarapela de cada participante.
    * 
    * @access	public
    */    
    function individual(){                              
        $id_evento = $this->uri->segment(3);
        
        //Cargamos el metodo que nos muestra todos los eventos
        $eventos = $this->data['eventos'] = $this->evento_model->listarEventos($id_evento);
        $asistentes = $this->data['asistentes'] = $this->impresion_model->listarAsistentesEventos($id_evento,1);
        //echo $strt = "<pre>".print_r($eventos,true)."</pre>";    //imprimir matriz
        //echo $strt = "<pre>".print_r($asistentes,true)."</pre>";    //imprimir matriz
        //exit();
        //Establecemos la ruta para las fuentes del reporte
        define('FPDF_FONTPATH','application/font/');
        
        //Generamos una nueva hoja de ImprimirPDF extension del FPDF
        $pdf = new ImprimirPDF();
        foreach ($asistentes as $asistente):                                 
            foreach ($eventos as $evento):                                
                    $pdf->AddPage();
                    //Cargar la imagen de fondo de la escarapela
                    if($evento->Escarapela_Evento == NULL){
                        $pdf->Image('img/plantillas/escarapela.png', 0, 0, 90, 130);
                    }else{
                        $pdf->Image('img_escarapelas/'.$id_evento.'/'.$evento->Escarapela_Evento, 0, 0, 90, 130);
                    }
                    $pdf->SetXY(0,50);
                    $pdf->SetFont('Arial','B',24);
                    $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                    $Apellidos = utf8_decode($asistente->Apellidos);
                    $pdf->MultiCell(90,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetX(0);
                    $pdf->MultiCell(90,7, $Apellidos,0,'C');//Apellidos del participante
            endforeach;     
        endforeach;        
        //exit();
        //Limpiar búferes de salida
        ob_end_clean ();
        
        $pdf->imprime(true);
        $pdf->Output();
        
        $this->auditoria_model->escarapela_individual($id_evento);
    }//Fin individual() 
    
    /**
    * Imprime la escarapela de cada participante en una hoja recortada.
    * 
    * @access	public
    */    
    function individual_media(){                              
        $id_evento = $this->uri->segment(3);
        
        //Cargamos el metodo que nos muestra todos los eventos
        $eventos = $this->data['eventos'] = $this->evento_model->listarEventos($id_evento);
        $asistentes = $this->data['asistentes'] = $this->impresion_model->listarAsistentesEventos($id_evento,1);
        //echo $strt = "<pre>".print_r($eventos,true)."</pre>";    //imprimir matriz
        //echo $strt = "<pre>".print_r($asistentes,true)."</pre>";    //imprimir matriz
        //exit();
        //Establecemos la ruta para las fuentes del reporte
        define('FPDF_FONTPATH','application/font/');
        
        //Generamos una nueva hoja de ImprimirPDF extension del FPDF
        //$pdf = new ImprimirPDF('P','mm', 'Letter');
        $pdf = new ImprimirPDF();
        foreach ($asistentes as $asistente):                                 
            foreach ($eventos as $evento):                                
                    $pdf->AddPage();
                    //Cargar la imagen de fondo de la escarapela
                    if($evento->Escarapela_Evento == NULL){
                        $pdf->Image('img/plantillas/escarapela.png', 60, 0, 90, 130);
                    }else{
                        $pdf->Image('img_escarapelas/'.$id_evento.'/'.$evento->Escarapela_Evento, 60, 0, 90, 130);
                    }
                    $pdf->SetXY(60,50);
                    $pdf->SetFont('Arial','B',24);
                    $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                    $Apellidos = utf8_decode($asistente->Apellidos);
                    $pdf->MultiCell(90,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetX(60);
                    $pdf->MultiCell(90,7, $Apellidos,0,'C');//Apellidos del participante
            endforeach;     
        endforeach;        
        //exit();
        //Limpiar búferes de salida
        ob_end_clean ();
        
        $pdf->imprime(true);
        $pdf->Output();
        
        
    }//Fin individual_media() 
    
    /**
    * funcion encargada de pasar a mayusculas las palabras con tildes.
    * 
    * @access	public
    */
    function pasarMayusculas($cadena){ 
        $cadena = str_replace("á", "a", $cadena); 
        $cadena = str_replace("é", "e", $cadena); 
        $cadena = str_replace("í", "i", $cadena); 
        $cadena = str_replace("ó", "o", $cadena);        
        $cadena = str_replace("ú", "u", $cadena); 
        $cadena = str_replace("ñ", "n", $cadena);
        return ($cadena); 
    }// pasarMayusculas($cadena) 
    
    /**
    * Imprime la escarapela de cada participante en una hoja recortada.
    * 
    * @access	public
    */    
    function individual_usuario(){                              
        $id_evento = $this->uri->segment(3);
        
        //Cargamos el metodo que nos muestra todos los eventos
        $eventos = $this->data['eventos'] = $this->evento_model->listarEventosUsuario($id_evento);
        $asistentes = $this->data['asistentes'] = $this->impresion_model->listarAsistentesEventosUsuario($id_evento);
        //echo $strt = "<pre>".print_r($eventos,true)."</pre>";    //imprimir matriz
        //echo $strt = "<pre>".print_r($asistentes,true)."</pre>";    //imprimir matriz
        //exit();
        //Establecemos la ruta para las fuentes del reporte
        define('FPDF_FONTPATH','application/font/');
        
        //Generamos una nueva hoja de ImprimirPDF extension del FPDF
        //$pdf = new ImprimirPDF('P','mm', 'Letter');
        $pdf = new ImprimirPDF();
        foreach ($asistentes as $asistente):                                 
            foreach ($eventos as $evento):                                
                    $pdf->AddPage();
                    //Cargar la imagen de fondo de la escarapela
                    if($evento->Escarapela_Evento == NULL){
                        $pdf->Image('img/plantillas/escarapela.png', 60, 0, 90, 130);
                        //$pdf->Image('img_escarapelas/'.$id_evento.'/'.$evento->Escarapela_Evento, 10, 10);
                    }else{
                        $pdf->Image('img_escarapelas/'.$id_evento.'/'.$evento->Escarapela_Evento, 60, 0, 90, 130);
                    }
                    $pdf->SetXY(60,50);
                    $pdf->SetFont('Arial','B',24);
                    $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                    $Apellidos = utf8_decode($asistente->Apellidos);
                    $pdf->MultiCell(90,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetX(60);
                    $pdf->MultiCell(90,7, $Apellidos,0,'C');//Apellidos del participante
            endforeach;     
        endforeach;        
        //exit();
        //Limpiar búferes de salida
        ob_end_clean ();
        
        $pdf->imprime(true);
        $pdf->Output();
    }//Fin individual_usuario() 
    
    /**
    * Imprime la escarapela de muestra del inicio y de referencia para 
     * la ceraci&oacute;n de eventos
    * 
    * @access	public
    */
    function banner(){
        //Establecemos la ruta para las fuentes del reporte
        define('FPDF_FONTPATH','application/font/');
        
        //Generamos una nueva hoja de PDF
        $pdf = new FPDF('L');
        $pdf->AddPage();
        
        $pdf->Cell(0,7, utf8_decode(strtoupper($this->session->userdata('nombre_usuario'))),0,'C');//Nombres del participante
        
        //Cargar la imagen de fondo de la escarapela
        $pdf->Image('img/plantillas/banner_muestra.png', 30, 40);
        
        $pdf->Ln();
        
        $pdf->SetXY(0,50);

        $pdf->Image('img/logo.png', 110, 70, 0, 0);//Logo

        //Limpiar búferes de salida
        ob_end_clean ();
        
        //Imprimimos el PDF
        $pdf->Output('Escarapela '.$this->session->userdata('nombre_usuario').' '.$this->session->userdata('apellido_usuario'), 'I');
        
        //Se ingresa el registro de auditor&iacute;a
        $this->auditoria_model->escarapela_muestra(NULL);
    }//Fin banner()
}//Fin escarapela
/* End of file auditoria.php */
/* Location: ./creaeventos/application/controllers/escarapela.php */