<?php
/**
 * Clase para el procesamiento de publicidad
 *
 * <b>Que hace?</b> <br/>
 * Recupera información de las publicidades a mostrar.
 *
 * <b>Cómo funciona:</b> <br/>
 * La clase posee diversos tipos de funcionamiento, permitiendo 
 *	mostrar banners repetidos, internos o externos al sitio actual.
 *
 * <b>Cómo se usa:</b> <br>
 * Inicializando la clase Admonitor: <br/><br/>
 *
 * <code>
 * $adv = new Admonitor();
 * $adv->db = $db;
 * $adv->path = '/admonitor/';	// Path donde busca la config de la zona
 * $adv->bannerExterno = true;	// En caso de banners de formato externo
 * </code>
 *
 * Ejemplo de uso para mostrar banners en el sitio:
 * <code>
 * $banner=$adv->process('nombre-de-la-zona');
 * echo $banner;
 * </code>
 *
 * Ejemplo al imprimir el banner en la pantalla (en caso de uso externo):
 * <code>
 * $adv->bannerExterno = false;
 * $adv->sitioExterno = true;
 * $banner=$adv->process('nombre-de-la-zona', $repite);
 * echo $banner;
 * </code>
 *
 *
 * <b>Requerimientos:</b> <br/>
 * - PHP 5+ / MySQL 
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>20.04.2012 <br/>
 *	- Se agregó propiedad de bannerPath para modificar 
 *	la url donde se busca el controlador de impresión y vínculo.</li>
 *
 * <li>03.04.2012 <br/>
 *	 Fix: Se reparó error mínimo cuando intentaba mostrara banners vía JS.</li>
 *
 * <li>04.01.2012 <br/>
 *	- Added: Se agregó formato de uso externo vía js. <br/>
 *	Esto permite precacheo de las páginas y mostrar banners vía JS externo.<br/>
 *	- Modify: Se optimizó la clase y se pasaron algunas funciones y variables 
 *	a modo protegido y privadas.</li>
 *
 * <li>28.12.2011 <br/>
 *	- Added: Se agregó compatibilidad con google analytics 
 *	para que el destinatario pueda medir la eficiencia 
 *	de la campaña del banner. </li>
 *
 * <li>01.12.2011 <br/>
 *	- Fix: Se reparó problema al intentar mostrar un banner tipo PHP. 
 *	Este mostraba el código HTML y el PHP procesado. </li>
 *
 * <li>27.11.2011 <br/>
 *	- Modify: se modificó el script JS que maneja las publicidades en flash 
 *	para permitir publicidaded remotas desde otro servidor.</li>
 * 
 * <li>25.10.2011 <br/>
 *     - Fix: Se reparó error mínimo al intentar recuperar información 
 *	sobre un banner tipo PHP sin datos. </li>
 *
 * <li>23.09.2011 <br/>
 *     - Added: Se agregó que el sistema agregue ancho y alto a los banners HTML 
 *	que no tengan el tamaño explicito y tenga estos vía variable. </li>
 *
 * <li>16.09.2011 <br/>
 *     - Added: Se agregó que el sistema agregue clicTag a los banners HTML 
 *	que no tengan el clicktag explicito y tenga el filename vía variable.</li>
 *
 * <li>23.06.2011 <br/>
 *     - Added: Se agregó manejo de publicidad en segundo plano 
 *	para cuando se utiliza links en popups o en zonas institucionales 
 *	(por el momento solo cuando el link es javascript). </li>
 *
 * <li>26.05.2011 <br/>
 *     - Fix: Se reparó problema cuando no se puede recuperar el USER_AGENT 
 *	del navegador (utilizado para iPad y otros dispositivos). </li>
 *
 * <li>19.05.2011 <br/>
 *     - Fix: Se reparó nuevamente el problema al intentar tracear un vínculo 
 *	de banner de javascript y el resto de banners traceables. </li>
 *
 * <li>02.05.2011 <br/>
 *     - Fix: Se reparó problema al intentar tracear un vínculo de banner 
 *	de javascript. </li>
 *
 * <li>28.04.2011 <br/>
 *     - Added: Se agregó funciones para que el sistema 
 *	cuente los clicks de los banners. <br/>
 *     - Added: Se agregó funciones para que el sistema 
 *	cuente las impresiones por zonas del sitio. </li>
 *
 * <li>05.04.2011 <br/>
 *     - Fix: Se reparó error cuando se intenta levantar HTML 
 *	con comillas simples (el sistema no interpretaba las comillas 
 *	y generaba error). </li>
 *
 * <li>28.02.2011 <br/>
 *     - Added: Se agregó detección si estoy en dispositivos como iPad, 
 *	iPhone y iPod y no muestro banners tipo swf. </li>
 *
 * <li>01.02.2011 <br/>
 *     - Added: Se agregó opciones conteo y límites de impresión 
 *	y clicks de los banners. <br/>
 *     - Added: Se agregó exposición en rango de fecha para los banners.</li>
 *
 * <li>13.12.2010 <br/>
 *     - Modify: Optimizaciónes menores (inicialización de variables, etc).</li>
 *
 * <li>06.12.2010 <br/>
 *     - Modify: Se agregó formato de carga diferenciado. 
 *	Carga las publicidades flash una vez que el sitio cargo completamente.</li>
 * </ul>
 *
 * @package		Widgets
 * @subpackage	Publicidad
 * @access		public 
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @license		Comercial
 * @generated	06.12.2010
 * @version		1.0	- last revision 2012.04.03 
 */
class Admonitor {

    /**
     * Array con todos los banners de la página
     *
     * @access private
     * @var array 
     */
    private $bannerArr;
	
    /**
     * Información sobre las impresiones y clicks de cada banner
     *
     * @access private
     * @var array 
     */
    private $bannerData;
	
	/**
	 * Indica que el banner se muestra en otro sitio diferente al actual
	 *
	 * @access public
	 * @var boolean
	 */
	public $bannerExterno;
	
	/**
	 * Url donde se encuentra el controlador de impresión y vínculo del banner
	 *
	 * @access public
	 * @var string
	 */
	public $bannerPath;
    
    /**
     * Array que guarda todos los banners todavía no utilizados en la página.
     *
     * @access private
     * @var array 
     */
    private $bannerUsed;
	
    /**
     * Información sobe las zonas de banners
     *
     * @access private
     * @var array 
     */
    private $bannerZona;	
    
    /**
     * Objeto de manejo de base de datos
     *
     * @access public
     * @var object 
     */
    public $db;
	
    /**
     * Flag que indica que esta visualizando con un dispositivo movil
     *
     * @access private
     * @var boolean; 
     */
    private $mobile;
	
    /**
     * Path donde se ubican los archivos de las publicidades
     *
     * @access public
     * @var string 
     */
    public $path;

	/**
	 * Indica que la solicitud del banner proviene de un sitio diferente al actual
	 *
	 * @access public
	 * @var boolean
	 */
	public $sitioExterno;


    /**
     * Constructor de la clase
     *
     * @access public
     */
    public function __construct() {

        $this->path = '';
        $this->bannerArr = array();
        $this->bannerData = false;
		$this->bannerPath = '/admonitor';
        $this->bannerUsed = array();
        $this->bannerZona = false;

		$this->bannerExterno = false;
		$this->sitioExterno = false;

        $this->mobile = isMobile();
    }

    /**
     * Procesa la zona solicitada y devuelve el HTML del banner a mostrar
     *
     * @access	public
     * @param	string	$zona	Nombre de la zona para el banner
     * @param	boolean	$repeat	Si es true repite el anuncio 
								una vez mostrados todos en la misma página
     * @return	string	HTML del banner generado, false en caso error 
								o si no hay anuncios para mostrar
     */
    public function process($zona='', $repeat=true) {
	
        if ($this->bannerData === false) {
		
            if (!$this->bannerData = $this->loadDataBanner()) {
                return false;
            }
        }
        
        if ($this->bannerZona === false) {
		
            if (!$this->bannerZona = $this->loadDataZonas()) {
                return false;
            }
        }
    
        if (!isset($this->bannerArr[$zona])) {
		
            if ($zone = $this->loadZona($zona)) {
			
                $this->bannerArr[$zona] = $zone;
                $this->bannerUsed[$zona] = $zone;
				
            } else {

				return false;
			
            }
        }
        
        if (count($this->bannerUsed[$zona])) {
		
            $banner = array_pop($this->bannerUsed[$zona]);

        } else {
        
            if (!$repeat) {
                return false;
            }

            $banner = $this->bannerArr[$zona][0];
        }
		
		if ($this->bannerExterno) {
		
			$ret = "<script type=\"text/javascript\">
			var advRnd = Math.round(Math.random() * 1000000);
			document.writeln('<scr'+'ipt type=\"text/jav' + 'ascr' + 'ipt\" src=\"".$this->bannerPath."/ban.php?rand=' + advRnd + '&z=".$zona."&rep=".$repeat."\"></scr'+'ipt>');
			</script>";
			return $ret;
		}
    
        /**
         * Estructura del array del banner:
         * - id
         * - tipo    swf, img, html, php
         * - file    
         * - ancho    0-65536
         * - alto    0-65536
         * - link
         */
        $ret = '';
 
        $oInfo = array('z' => $zona, 'id' => $banner['id']);
        $id = base64_encode(serialize($oInfo));
 
        // Si el link es javascript no cargo función de track de click
        $bannerTraceClick = (strpos(strtolower($banner['link']), 'javascript') === false) ? true : false;

		$linkTrack = urlencode($this->bannerPath."/click.php?id={$id}");

        // Formato SWF de flash
        if ($banner["tipo"] == "swf") {
		
            $link = ($banner['link'] != '' && $bannerTraceClick) 
					? '?clickTag='.$linkTrack 
					: ($banner['link'] != '' ? "?clickTag=".$banner["link"].";advtracking('".$linkTrack."');" : "");
            
			$ret = '<script type="text/javascript">runSWF("'.$banner["file"].'", '.$banner["ancho"].', '.$banner["alto"].', "9", "#FFFFFF","", false,"'.$link.'","","always");</script>';

        }

        // Formato Archivo de imagen (JPG, PNG, GIF)
        if ($banner["tipo"] == "img") {
		
            if ($banner["link"] != "") {

                $link = ($banner["link"] != "" && $bannerTraceClick) ? $linkTrack : $banner["link"];

                $ret = '<a href="'.$link.'" target="_blank"><img src="'.$banner["file"].'" width="'.$banner["ancho"].'" height="'.$banner["alto"].'" alt="" /></a>';

            } else {

                $ret = '<img src="'.$banner["file"].'" width="'.$banner["ancho"].'" height="'.$banner["alto"].'" alt="" />';
            }
        }

        // Si tienen agregado HTML o es Formato HTML
        if ($banner["tipo"] == "html" || $banner["html"] != "") {

            $ret.= htmlspecialchars_decode($banner["html"], ENT_QUOTES);
 
            $oFile = $banner["file"];
            $oClick = "";
            
            if ($banner["link"] != "") {
            
                $link = ($bannerTraceClick)
                    ? $linkTrack 
                    : $banner["link"].";advtracking('".$linkTrack."');";
 
                if (strstr($ret, "clickTag") === false) {
 
                    $oFile.= "?clickTag=".$link;
 
                } else {

                    if (strstr($ret, "{clickTag}")) {
                        $oClick = $link;
                    }
                }
            }

            $ret = str_replace("{clickTag}", $oClick, $ret);
            $ret = str_replace("{filename}", $oFile, $ret);
			$ret = str_replace("{fileName}", $oFile, $ret);
            $ret = str_replace("{width}", $banner["ancho"], $ret);
            $ret = str_replace("{height}", $banner["alto"], $ret);
        }

        // Formato PHP 
        if ($banner["tipo"] == "php") {
            if ($banner["html"] != "") {
				$ret = eval("?>".$ret."<?");
            }
        }		
        
        /* Cargo una impresión al banner, solo una vez por página */
        if (!$this->bannerData[$banner["id"]]["view_unique"]) {
		
			$fecha = mktime(0,0,0,date('m'), date('d'), date('Y'));

            $this->bannerData[$banner["id"]]["view_unique"] = 1;
 
            $sql = "UPDATE LOW_PRIORITY publicidad_banners 
				SET banner_views = (banner_views+1) 
				WHERE banner_id = '{$banner["id"]}'";

            if ($res = $this->db->query($sql)) {
                
                $sql = "INSERT DELAYED INTO publicidad_stats 
                    (stats_fecha, stats_zona, stats_banner, stats_views, stats_clicks)
                    VALUES ('{$fecha}', '{$this->bannerZona[$zona]}', '{$banner["id"]}', 1, 0)
                ON DUPLICATE KEY UPDATE stats_views = (stats_views + 1)";

                if (!$res = $this->db->query($sql)) {
                    // No se pudo cargar la estadistica del banner
                }
            }
        }
 
		// Consulta si viene de un formato externo, modifico como se envía
		if ($this->sitioExterno) {
			$ret = str_replace("\r", "", $ret);
			$ret_arr = explode("\n", $ret);

			$ret = "";
			foreach($ret_arr as $k => $v) {

				$v = str_replace("script", "scr' + 'ipt", $v);

				if ($k) {
					$ret.= "+\n";
				}
				$ret.= "'".$v."'";
			}

			$ret = "document.writeln(\"<body>\");
			var encSrc = encodeURI(".$ret.");\n";
			$ret.= "document.writeln(decodeURI(encSrc));
			document.writeln(\"</body>\");
			document.close();";
		}

        return $ret;
    }
    
	/**    
     * Carga información sobre las impresiones y clicks de los banners
     *
     * @access	private
     * @return	array	Información sobre los banners
     */
    private function loadDataBanner() {

        if (!is_object($this->db)) {
            return false;
        }

        $sql = "SELECT banner_id, banner_views, banner_clicks FROM publicidad_banners";
        if (!$res = $this->db->query($sql)) {
            return false;
        }

        if (!$this->db->num_rows($res)) {
            return false;
        }

        $bannerData = array();
        for ($i=0; $i<$this->db->num_rows($res); $i++) {

            $rs = $this->db->next($res);
            $bannerData[$rs["banner_id"]] = array(
                "views" => $rs["banner_views"], 
                "clicks" => $rs["banner_clicks"], 
                "view_unique" => 0
            );

        }

        return $bannerData;
    }
    
	/**    
     * Carga información sobre las zonas del sitio
     *
     * @access	private
     * @return	array	Información sobre las zonas
     */
    private function loadDataZonas() {

        if (!is_object($this->db)) {
            return false;
        }

        $sql = "SELECT zona_id, zona_filename FROM publicidad_zonas";
        if (!$res = $this->db->query($sql)) {
            return false;
        }

        if (!$this->db->num_rows($res)) {
            return false;
        }

        $zona = array();
        for ($i=0; $i<$this->db->num_rows($res); $i++) {
            $rs = $this->db->next($res);
            $zona[$rs["zona_filename"]] = $rs["zona_id"];
        }

        return $zona;
    }
	
    /**
     * los banners de la zona
     *
     * @access	private
     * @param	string	$zona	Nombre de la zona
     * @return	array	Matriz de banners de la zona, false si no hay banners
     */
    private function loadZona($zona) {
    
        if ($this->path == '') {
            return false;
        }
        
        $url = $this->path.'publicidad.'.$zona.'.php';
        if (!file_exists($url)) {
            return false;
        }
 
        $banners = array();

		// Utilizado en el include del banner
		$banner_data = $this->bannerData;

        include($url);

        if (!count($banners)) {
            return false;
        }

        // Si estoy en mobile elimino los banners swf
        if ($this->mobile) {
		
            foreach ($banners as $bk => $bv) {
                if ($bv["tipo"] == "swf") {
                    unset($banners[$bk]);
                }
            }
        }

        shuffle($banners);

        return $banners;
    }
}
?>