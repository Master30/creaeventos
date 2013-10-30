<?php
//Zona horaria
date_default_timezone_set('America/Bogota');

if ( ! defined('BASEPATH')) exit('Lo sentimos, usted no tiene acceso a esta ruta');

/**
 * Controlador para administrar impresion certificado
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy;  Oscar Humberto Morales y John Arley Cano
 */
Class Certificado extends CI_Controller{
    /**
    * Funci&oacute;n constructora de la clase Impresioncertificado. 
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
        $this->data['titulo'] = 'Seleccionar tipo de Impresi&oacute;n';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Impresi&oacute;n Certificado';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'impresion/certificado_view';
        //Se recibe el id del evento
        $this->data['IdEvento'] = $this->input->post('id_evento');
        //Se recibe el Tipo De Impresion del Certificado
        $this->data['CertificadoMedia'] = $this->input->post('CertificadoMedia');
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin index()
    
    
    /**
    * Imprime el certificado de prueba al inicio
    * 
    * @access	public
    */
    function muestra(){
        
        //Establecemos la ruta para las fuentes del reporte
        define('FPDF_FONTPATH','application/font/');
        
        //Generamos una nueva hoja de PDF
        $pdf = new FPDF('L','mm','Letter');
        $pdf->AddPage();
		
        //Cargar la imagen de fondo del certificado
        $pdf->Image('img/plantillas/certificado_muestra.png',0,0);
        
        //Limpiar búferes de salida
        ob_end_clean ();
        
        //Imprimimos el PDF
        $pdf->Output('Certificado de prueba', 'I');
        
        $this->auditoria_model->certificado_muestra(NULL);
    }//Fin muestra()
    
    /**
    * Imprime la certificado de cada asistente de a cuatro por hoja.
    * 
    * @access	public
    */
    function masivo(){                              
        $id_evento = $this->uri->segment(3);
        
        //Cargamos el metodo que nos muestra todos los eventos
        $eventos = $this->data['eventos'] = $this->evento_model->listarEventos($id_evento);
        $asistentes = $this->data['asistentes'] = $this->impresion_model->listarAsistentesEventos($id_evento,2);
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
//            echo($i."modulo 4 ".($i%4)."<br>");            
//            echo($i."modulo 3 ".($i%3)."<br>");            
//            echo($i."modulo 2 ".($i%2)."<br>");            
//            echo($i."sino ".($i)."<br>");            
            foreach ($eventos as $evento):                
                if(($i%2)==0){
                    //Cargar la imagen de fondo de la certificado
                    if($evento->Certificado_Evento == NULL){
                        $pdf->Image('img/plantillas/certificado.png', 0, 149, 210, 148);
                    }else{
                        $pdf->Image('img_certificados/'.$id_evento.'/'.$evento->Certificado_Evento, 0, 149, 210, 148);
                    }
                    $pdf->SetXY(5,215);
                    $pdf->SetFont('Arial','B',24);
                    $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                    $Apellidos = utf8_decode($asistente->Apellidos);
                    $pdf->MultiCell(190,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetX(5);
                    $pdf->MultiCell(190,7, $Apellidos,0,'C');//Apellidos del participante
                }else{
                    $pdf->AddPage();
                    //Cargar la imagen de fondo de la certificado
                    if($evento->Certificado_Evento == NULL){
                        $pdf->Image('img/plantillas/certificado.png', 0, 0, 210, 148);
                    }else{
                        $pdf->Image('img_certificados/'.$id_evento.'/'.$evento->Certificado_Evento, 0, 0, 210, 148);
                    }
                    $pdf->SetXY(5,65);
                    $pdf->SetFont('Arial','B',24);
                    $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                    $Apellidos = utf8_decode($asistente->Apellidos);
                    $pdf->MultiCell(190,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetX(5);
                    $pdf->MultiCell(190,7, $Apellidos,0,'C');//Apellidos del participante
                }
            endforeach;     
        endforeach;        
//        exit();
        //Limpiar búferes de salida
        ob_end_clean ();
        
        $pdf->imprime(true);
        $pdf->Output();
        
        $this->auditoria_model->certificado_masivo($id_evento);
    }//Fin masivo()
    
    
    /**
    * Imprime la certificado de cada participante.
    * 
    * @access	public
    */    
    function individual(){                              
        $id_evento = $this->uri->segment(3);
        
        //Cargamos el metodo que nos muestra todos los eventos
        $eventos = $this->data['eventos'] = $this->evento_model->listarEventos($id_evento);
        $asistentes = $this->data['asistentes'] = $this->impresion_model->listarAsistentesEventos($id_evento,2);
        //echo $strt = "<pre>".print_r($eventos,true)."</pre>";    //imprimir matriz
        //echo $strt = "<pre>".print_r($asistentes,true)."</pre>";    //imprimir matriz
        //exit();
        //Establecemos la ruta para las fuentes del reporte
        define('FPDF_FONTPATH','application/font/');
        $uy = 0;        
        foreach ($asistentes as $asistente):                                 
            foreach ($eventos as $evento):                
                //Certificado Horizontal
                if($evento->ImpresionCertificadoHorizontal == 1){
                    if($uy == 0){
                        //Generamos una nueva hoja de ImprimirPDF extension del FPDF                    
                        $pdf = new ImprimirPDF();
                    }
                    $pdf->AddPage("L");
                    //Cargar la imagen de fondo de la certificado
                    if($evento->Certificado_Evento == NULL){
                        $pdf->Image('img/plantillas/certificado.png', 0, 0, 298, 210);
                    }else{
                        $pdf->Image('img_certificados/'.$id_evento.'/'.$evento->Certificado_Evento, 0, 0, 298, 210);
                    }
                    $pdf->SetXY(0,95);
                    $pdf->SetFont('Arial','B',24);
                    $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                    $Apellidos = utf8_decode($asistente->Apellidos);
                    $pdf->MultiCell(297,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetX(0);
                    $pdf->MultiCell(297,7, $Apellidos,0,'C');//Apellidos del participante
                //Certificado Media Carta                        
                }else{
                    if($uy == 0){
                        //Generamos una nueva hoja de ImprimirPDF extension del FPDF
                        $pdf = new ImprimirPDF();
                    }    
                    $pdf->AddPage();
                    //Cargar la imagen de fondo de la certificado
                    if($evento->Certificado_Evento == NULL){
                        $pdf->Image('img/plantillas/certificado.png', 0, 0, 210, 148);
                    }else{
                        $pdf->Image('img_certificados/'.$id_evento.'/'.$evento->Certificado_Evento, 0, 0, 210, 148);
                    }
                    $pdf->SetXY(5,65);
                    $pdf->SetFont('Arial','B',24);
                    $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                    $Apellidos = utf8_decode($asistente->Apellidos);
                    $pdf->MultiCell(190,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetX(5);
                    $pdf->MultiCell(190,7, $Apellidos,0,'C');//Apellidos del participante
                } 
                $uy++;
            endforeach;     
        endforeach;        
//        exit();
        //Limpiar búferes de salida
        ob_end_clean ();
        
        $pdf->imprime(true);
        $pdf->Output();
        
        $this->auditoria_model->certificado_individual($id_evento);
    }//Fin individual() 
    
     /**
    * Imprime la certificado de cada participante en una hoja recortada.
    * 
    * @access	public
    */    
    function individual_media(){                              
        $id_evento = $this->uri->segment(3);
        
        //Cargamos el metodo que nos muestra todos los eventos
        $eventos = $this->data['eventos'] = $this->evento_model->listarEventos($id_evento);
        $asistentes = $this->data['asistentes'] = $this->impresion_model->listarAsistentesEventos($id_evento,2);
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
                    //Cargar la imagen de fondo de la certificado
                    if($evento->Certificado_Evento == NULL){
                        $pdf->Image('img/plantillas/certificado.png', 0, 0, 210, 148);
                    }else{
                        //$pdf->Image('img_certificados/'.$id_evento.'/'.$evento->Certificado_Evento, 60, 0, 105, 148);
                        $pdf->Image('img_certificados/'.$id_evento.'/'.$evento->Certificado_Evento, 0, 0, 210, 148);
                    }
                    $pdf->SetXY(5,65);
                    $pdf->SetFont('Arial','B',24);
                    $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                    $Apellidos = utf8_decode($asistente->Apellidos);
                    $pdf->MultiCell(190,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                    $pdf->SetFont('Arial','B',12);
                    $pdf->SetX(5);
                    $pdf->MultiCell(190,7, $Apellidos,0,'C');//Apellidos del participante
            endforeach;     
        endforeach;        
//        exit();
        //Limpiar búferes de salida
        ob_end_clean ();
        
        $pdf->imprime(true);
        $pdf->Output();
        
        $this->auditoria_model->certificado_individual_media($id_evento);
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
    * Imprime el certificado del usuario logueado.
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
                
        foreach ($asistentes as $asistente):                                 
            foreach ($eventos as $evento):                                
                    //Certificado Horizontal
                    if($evento->ImpresionCertificadoHorizontal == 1){
                        //Generamos una nueva hoja de ImprimirPDF extension del FPDF
                        $pdf = new ImprimirPDF("L");
                        $pdf->AddPage();
                        //Cargar la imagen de fondo de la certificado
                        if($evento->Certificado_Evento == NULL){
                            $pdf->Image('img/plantillas/certificado.png', 0, 0, 298, 210);
                        }else{
                            $pdf->Image('img_certificados/'.$id_evento.'/'.$evento->Certificado_Evento, 0, 0, 298, 210);
                        }
                        $pdf->SetXY(0,95);
                        $pdf->SetFont('Arial','B',24);
                        $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                        $Apellidos = utf8_decode($asistente->Apellidos);
                        $pdf->MultiCell(297,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                        $pdf->SetFont('Arial','B',12);
                        $pdf->SetX(0);
                        $pdf->MultiCell(297,7, $Apellidos,0,'C');//Apellidos del participante
                    //Certificado Media Carta                        
                    }else{
                        //Generamos una nueva hoja de ImprimirPDF extension del FPDF
                        $pdf = new ImprimirPDF();
                        $pdf->AddPage();
                        //Cargar la imagen de fondo de la certificado
                        if($evento->Certificado_Evento == NULL){
                            $pdf->Image('img/plantillas/certificado.png', 0, 0, 210, 148);
                        }else{
                            $pdf->Image('img_certificados/'.$id_evento.'/'.$evento->Certificado_Evento, 0, 0, 210, 148);
                        }
                        $pdf->SetXY(5,65);
                        $pdf->SetFont('Arial','B',24);
                        $Nombres = $this->pasarMayusculas($asistente->Nombres);                                
                        $Apellidos = utf8_decode($asistente->Apellidos);
                        $pdf->MultiCell(190,7,  strtoupper($Nombres),0,'C');//Nombres del participante
                        $pdf->SetFont('Arial','B',12);
                        $pdf->SetX(5);
                        $pdf->MultiCell(190,7, $Apellidos,0,'C');//Apellidos del participante
                    }    
            endforeach;     
        endforeach;        
        //exit();
        //Limpiar búferes de salida
        ob_end_clean ();
        
        $pdf->imprime(true);
        $pdf->Output();
        
        $this->auditoria_model->certificado_individual($id_evento);
    }//Fin individual_usuario()
}//Fin certificado
/* End of file certificado.php */
/* Location: ./creaeventos/application/controllers/certificado.php */