/**
 * Recopilación de funciones más utilizadas.
 * revision 22.09.2010
 *
 * Listado de funciones:
 *
 * tweetUser:		Muestra el último tweet de un usuario
 * relativeTime		Recupera en forma semántica el tiempo transcurrido desde un evento 
 * advtracking:		Manejador de estadisticas en modo asincronico (en segundo plano en vez de vínculo)
 * runSWF:			Muestra en archivo de flash en la página
 * ventana:			Genera una ventana en popup con el ancho y alto dado
 * equalHeight:		Igualador de alturas para dos o más columnas
 * vAlign:			Alinea un objeto con respecto a su contenedor
 * fieldtag:		Marca de agua para los input
 * revisabuscador:	Revisa que el formulario de búsqueda tenga el texto a buscar
 * BrowserSelector:	Selecciona el CSS correcto para cada navegador
 * jQuery Cookie:	Manejador de Cookies para la página
 * jQuery.ScrollTo:	Mueve un objeto desde la posición actual a otra posición
 * Lazy Load:		jQuery plugin para cargar imagenes en segundo plano o solo cuando se requiera mostrar
 * IE_PNG Fix:		Fix para IE6 con PNG transparentes
 * Geolocation:		Plugin que detecta tu coordenada (lat, lng)
 * Custom Radio:	Plugin que modifica visualmente el Checkbox y Radio button
 * cPalabrasLargas:	Cortador de palabras largas
 * Limit: 			Limita los caracteres en textarea 
 * swfObject:		Manejador de Objetos en flash (Utilizado en los reproductores de video)
 *
 *
 * Changelog:
 *
 * @revision 12.03.2012
 *	added: relativeTime - Recupera en forma semántica el tiempo transcurrido desde un evento
 *	added: tweetUser - Muestra el último tweet de un usuario
 *
 * @revision 22.06.2011
 *	added: advtracking - Manejador de estadisticas de clicks en modo asincronico
 *
 * @revision 29.11.2010
 *	added: swfObject - Manejador de Objetos para los reproductores de video del sitio
 *
 * @revision 20.11.2010
 *	modify: Limit - se modificó para que corte las palabras con la función cPalabrasLargas (se unio a la funcion limit)
 *
 * @revision 19.11.2010
 *	added: cPalabrasLargas - Corta las palabras que superen cierta longitud
 *
 * @revision 09.11.2010
 *	modify: BrowserSelector: Update versión (new version 05.11.2010)
 *
 * @revision 22.09.2010
 *	added: jQuery Custom Radio-buttons and Checkbox - plugin que modifica los checkbox y radiobutton
 *
 * @revision 20.09.2010
 *	added: jQuery-Geolocation-Plugin - plugin para detectar la posición del usuario
 *
 * @revision 23.08.2010
 *	added: IEPNG FIX - fox para ie con PNG transparentes
 *
 * @revision 18.08.2010
 *	added: Lazy Load - jQuery plugin for lazy loading images
 *
 * @revision 22.06.2010
 *	fix: runSWF wmode opaque en IE
 *
 * @revision 08.06.2010
 *	added: Plugin jQuery.ScrollTo para mover un objeto desde una posición hacia otra. Utilizado en los pasadores
 *
 */
 

/**
 * tweetUser 
 * Muestra el último tweet de un usuario
 *
 * @autor	Alexis Lesa
 * @param	user_timeline	string	Usuario de twitter
 * @param	dom_user_text	object	Objeto DOM donde muestra el texto del tweet
 * @param	dom_user_date	object	Objeto DOM donde muestra la fecha del tweet
 * @param	dom_user_picture	object	Objeto DOM donde muestra la imagen del usuario
 */
function tweetUser(user_timeline, dom_user_text, dom_user_date, dom_user_picture, callback) {

	user_limit = 1;
	url = "http://api.twitter.com/1/statuses/user_timeline/"+encodeURIComponent(user_timeline)+".json?count="+user_limit+"&callback=?";
	
	$.getJSON(url, function(json) {
		var results = null;
		results = json;

		$(results).each(function() {
		
			var screen_name = '';
			var profile_image_url = '';

			screen_name = this.user.screen_name;
			profile_image_url = this.user.profile_image_url;
			
			// Fix for IE
			created_at_date = this.created_at.replace(/^(\w+)\s(\w+)\s(\d+)(.*)(\s\d+)$/, "$1, $3 $2$5$4");

			var linkified_text = this.text.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&\?\/.=]+/, function(m) { return "<a href='" + m + "' target='_blank'>" + m + "</a>"; });
			
			linkified_text = linkified_text.replace(/@[A-Za-z0-9_]+/g, function(u){return u.link('http://twitter.com/'+u.replace(/^@/,''));});
			linkified_text = linkified_text.replace(/#[A-Za-z0-9_\-]+/g, function(u){return u.link('http://search.twitter.com/search?q='+u.replace(/^#/,'%23'));});

			tweet_time = relativeTime(created_at_date);
			
			if (dom_user_text) {
				$(dom_user_text).html(linkified_text);
			}
			if (dom_user_date) {
				$(dom_user_date).html(tweet_time);
			}
			
			if (dom_user_picture) {
				$(dom_user_picture).html(tweet_time);
			}
		});

		if (typeof callback == 'function') {
		  callback();
		}

	});
}	
 /**
 * relativeTime
 * Recupera en forma semántica el tiempo transcurrido desde un evento
 *
 * @autor	Alexis Lesa
 * @param	timeString	string	Fecha a recuperar información
 * @return	string	Tiempo transcurrido en español
 */
function relativeTime(timeString){
	var parsedDate = Date.parse(timeString);
	var delta = (Date.parse(Date()) - parsedDate) / 1000;
	var r = '';
	if (delta < 60) {
		r = 'hace ' + delta + ' segundos';
	} else if(delta < 120) {
		r = 'hace 1 minuto';
	} else if(delta < (45*60)) {
		r = 'hace ' + (parseInt(delta / 60, 10)).toString() + ' minutos';
	} else if(delta < (90*60)) {
		r = 'hace una hora';
	} else if(delta < (24*60*60)) {
		r = 'hace ' + (parseInt(delta / 3600, 10)).toString() + ' horas';
	} else if(delta < (48*60*60)) {
		r = 'hace 1 dia';
	} else {
		r = 'hace ' + (parseInt(delta / 86400, 10)).toString() + ' dias';
	}
	return r;
}

/**
 * Advtracking
 * Manejador asincronico de estadisticas. Genera un click a la publicidad en segundo plano
 *
 * @autor	Alexis Lesa
 * @param	url	string	Url generada por el admonitor
 */
function advtracking(url) {
	$.ajax({
		url: url,
		success: function(){

		}
	});
}

/**
 *	SWFObject v2.2 <http://code.google.com/p/swfobject/> 
 *	is released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
 */
var swfobject = function() {
	
	var UNDEF = "undefined",
		OBJECT = "object",
		SHOCKWAVE_FLASH = "Shockwave Flash",
		SHOCKWAVE_FLASH_AX = "ShockwaveFlash.ShockwaveFlash",
		FLASH_MIME_TYPE = "application/x-shockwave-flash",
		EXPRESS_INSTALL_ID = "SWFObjectExprInst",
		ON_READY_STATE_CHANGE = "onreadystatechange",
		
		win = window,
		doc = document,
		nav = navigator,
		
		plugin = false,
		domLoadFnArr = [main],
		regObjArr = [],
		objIdArr = [],
		listenersArr = [],
		storedAltContent,
		storedAltContentId,
		storedCallbackFn,
		storedCallbackObj,
		isDomLoaded = false,
		isExpressInstallActive = false,
		dynamicStylesheet,
		dynamicStylesheetMedia,
		autoHideShow = true,
	
	/* Centralized function for browser feature detection
		- User agent string detection is only used when no good alternative is possible
		- Is executed directly for optimal performance
	*/	
	ua = function() {
		var w3cdom = typeof doc.getElementById != UNDEF && typeof doc.getElementsByTagName != UNDEF && typeof doc.createElement != UNDEF,
			u = nav.userAgent.toLowerCase(),
			p = nav.platform.toLowerCase(),
			windows = p ? /win/.test(p) : /win/.test(u),
			mac = p ? /mac/.test(p) : /mac/.test(u),
			webkit = /webkit/.test(u) ? parseFloat(u.replace(/^.*webkit\/(\d+(\.\d+)?).*$/, "$1")) : false, // returns either the webkit version or false if not webkit
			ie = !+"\v1", // feature detection based on Andrea Giammarchi's solution: http://webreflection.blogspot.com/2009/01/32-bytes-to-know-if-your-browser-is-ie.html
			playerVersion = [0,0,0],
			d = null;
		if (typeof nav.plugins != UNDEF && typeof nav.plugins[SHOCKWAVE_FLASH] == OBJECT) {
			d = nav.plugins[SHOCKWAVE_FLASH].description;
			if (d && !(typeof nav.mimeTypes != UNDEF && nav.mimeTypes[FLASH_MIME_TYPE] && !nav.mimeTypes[FLASH_MIME_TYPE].enabledPlugin)) { // navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin indicates whether plug-ins are enabled or disabled in Safari 3+
				plugin = true;
				ie = false; // cascaded feature detection for Internet Explorer
				d = d.replace(/^.*\s+(\S+\s+\S+$)/, "$1");
				playerVersion[0] = parseInt(d.replace(/^(.*)\..*$/, "$1"), 10);
				playerVersion[1] = parseInt(d.replace(/^.*\.(.*)\s.*$/, "$1"), 10);
				playerVersion[2] = /[a-zA-Z]/.test(d) ? parseInt(d.replace(/^.*[a-zA-Z]+(.*)$/, "$1"), 10) : 0;
			}
		}
		else if (typeof win.ActiveXObject != UNDEF) {
			try {
				var a = new ActiveXObject(SHOCKWAVE_FLASH_AX);
				if (a) { // a will return null when ActiveX is disabled
					d = a.GetVariable("$version");
					if (d) {
						ie = true; // cascaded feature detection for Internet Explorer
						d = d.split(" ")[1].split(",");
						playerVersion = [parseInt(d[0], 10), parseInt(d[1], 10), parseInt(d[2], 10)];
					}
				}
			}
			catch(e) {}
		}
		return { w3:w3cdom, pv:playerVersion, wk:webkit, ie:ie, win:windows, mac:mac };
	}(),
	
	/* Cross-browser onDomLoad
		- Will fire an event as soon as the DOM of a web page is loaded
		- Internet Explorer workaround based on Diego Perini's solution: http://javascript.nwbox.com/IEContentLoaded/
		- Regular onload serves as fallback
	*/ 
	onDomLoad = function() {
		if (!ua.w3) { return; }
		if ((typeof doc.readyState != UNDEF && doc.readyState == "complete") || (typeof doc.readyState == UNDEF && (doc.getElementsByTagName("body")[0] || doc.body))) { // function is fired after onload, e.g. when script is inserted dynamically 
			callDomLoadFunctions();
		}
		if (!isDomLoaded) {
			if (typeof doc.addEventListener != UNDEF) {
				doc.addEventListener("DOMContentLoaded", callDomLoadFunctions, false);
			}		
			if (ua.ie && ua.win) {
				doc.attachEvent(ON_READY_STATE_CHANGE, function() {
					if (doc.readyState == "complete") {
						doc.detachEvent(ON_READY_STATE_CHANGE, arguments.callee);
						callDomLoadFunctions();
					}
				});
				if (win == top) { // if not inside an iframe
					(function(){
						if (isDomLoaded) { return; }
						try {
							doc.documentElement.doScroll("left");
						}
						catch(e) {
							setTimeout(arguments.callee, 0);
							return;
						}
						callDomLoadFunctions();
					})();
				}
			}
			if (ua.wk) {
				(function(){
					if (isDomLoaded) { return; }
					if (!/loaded|complete/.test(doc.readyState)) {
						setTimeout(arguments.callee, 0);
						return;
					}
					callDomLoadFunctions();
				})();
			}
			addLoadEvent(callDomLoadFunctions);
		}
	}();
	
	function callDomLoadFunctions() {
		if (isDomLoaded) { return; }
		try { // test if we can really add/remove elements to/from the DOM; we don't want to fire it too early
			var t = doc.getElementsByTagName("body")[0].appendChild(createElement("span"));
			t.parentNode.removeChild(t);
		}
		catch (e) { return; }
		isDomLoaded = true;
		var dl = domLoadFnArr.length;
		for (var i = 0; i < dl; i++) {
			domLoadFnArr[i]();
		}
	}
	
	function addDomLoadEvent(fn) {
		if (isDomLoaded) {
			fn();
		}
		else { 
			domLoadFnArr[domLoadFnArr.length] = fn; // Array.push() is only available in IE5.5+
		}
	}
	
	/* Cross-browser onload
		- Based on James Edwards' solution: http://brothercake.com/site/resources/scripts/onload/
		- Will fire an event as soon as a web page including all of its assets are loaded 
	 */
	function addLoadEvent(fn) {
		if (typeof win.addEventListener != UNDEF) {
			win.addEventListener("load", fn, false);
		}
		else if (typeof doc.addEventListener != UNDEF) {
			doc.addEventListener("load", fn, false);
		}
		else if (typeof win.attachEvent != UNDEF) {
			addListener(win, "onload", fn);
		}
		else if (typeof win.onload == "function") {
			var fnOld = win.onload;
			win.onload = function() {
				fnOld();
				fn();
			};
		}
		else {
			win.onload = fn;
		}
	}
	
	/* Main function
		- Will preferably execute onDomLoad, otherwise onload (as a fallback)
	*/
	function main() { 
		if (plugin) {
			testPlayerVersion();
		}
		else {
			matchVersions();
		}
	}
	
	/* Detect the Flash Player version for non-Internet Explorer browsers
		- Detecting the plug-in version via the object element is more precise than using the plugins collection item's description:
		  a. Both release and build numbers can be detected
		  b. Avoid wrong descriptions by corrupt installers provided by Adobe
		  c. Avoid wrong descriptions by multiple Flash Player entries in the plugin Array, caused by incorrect browser imports
		- Disadvantage of this method is that it depends on the availability of the DOM, while the plugins collection is immediately available
	*/
	function testPlayerVersion() {
		var b = doc.getElementsByTagName("body")[0];
		var o = createElement(OBJECT);
		o.setAttribute("type", FLASH_MIME_TYPE);
		var t = b.appendChild(o);
		if (t) {
			var counter = 0;
			(function(){
				if (typeof t.GetVariable != UNDEF) {
					var d = t.GetVariable("$version");
					if (d) {
						d = d.split(" ")[1].split(",");
						ua.pv = [parseInt(d[0], 10), parseInt(d[1], 10), parseInt(d[2], 10)];
					}
				}
				else if (counter < 10) {
					counter++;
					setTimeout(arguments.callee, 10);
					return;
				}
				b.removeChild(o);
				t = null;
				matchVersions();
			})();
		}
		else {
			matchVersions();
		}
	}
	
	/* Perform Flash Player and SWF version matching; static publishing only
	*/
	function matchVersions() {
		var rl = regObjArr.length;
		if (rl > 0) {
			for (var i = 0; i < rl; i++) { // for each registered object element
				var id = regObjArr[i].id;
				var cb = regObjArr[i].callbackFn;
				var cbObj = {success:false, id:id};
				if (ua.pv[0] > 0) {
					var obj = getElementById(id);
					if (obj) {
						if (hasPlayerVersion(regObjArr[i].swfVersion) && !(ua.wk && ua.wk < 312)) { // Flash Player version >= published SWF version: Houston, we have a match!
							setVisibility(id, true);
							if (cb) {
								cbObj.success = true;
								cbObj.ref = getObjectById(id);
								cb(cbObj);
							}
						}
						else if (regObjArr[i].expressInstall && canExpressInstall()) { // show the Adobe Express Install dialog if set by the web page author and if supported
							var att = {};
							att.data = regObjArr[i].expressInstall;
							att.width = obj.getAttribute("width") || "0";
							att.height = obj.getAttribute("height") || "0";
							if (obj.getAttribute("class")) { att.styleclass = obj.getAttribute("class"); }
							if (obj.getAttribute("align")) { att.align = obj.getAttribute("align"); }
							// parse HTML object param element's name-value pairs
							var par = {};
							var p = obj.getElementsByTagName("param");
							var pl = p.length;
							for (var j = 0; j < pl; j++) {
								if (p[j].getAttribute("name").toLowerCase() != "movie") {
									par[p[j].getAttribute("name")] = p[j].getAttribute("value");
								}
							}
							showExpressInstall(att, par, id, cb);
						}
						else { // Flash Player and SWF version mismatch or an older Webkit engine that ignores the HTML object element's nested param elements: display alternative content instead of SWF
							displayAltContent(obj);
							if (cb) { cb(cbObj); }
						}
					}
				}
				else {	// if no Flash Player is installed or the fp version cannot be detected we let the HTML object element do its job (either show a SWF or alternative content)
					setVisibility(id, true);
					if (cb) {
						var o = getObjectById(id); // test whether there is an HTML object element or not
						if (o && typeof o.SetVariable != UNDEF) { 
							cbObj.success = true;
							cbObj.ref = o;
						}
						cb(cbObj);
					}
				}
			}
		}
	}
	
	function getObjectById(objectIdStr) {
		var r = null;
		var o = getElementById(objectIdStr);
		if (o && o.nodeName == "OBJECT") {
			if (typeof o.SetVariable != UNDEF) {
				r = o;
			}
			else {
				var n = o.getElementsByTagName(OBJECT)[0];
				if (n) {
					r = n;
				}
			}
		}
		return r;
	}
	
	/* Requirements for Adobe Express Install
		- only one instance can be active at a time
		- fp 6.0.65 or higher
		- Win/Mac OS only
		- no Webkit engines older than version 312
	*/
	function canExpressInstall() {
		return !isExpressInstallActive && hasPlayerVersion("6.0.65") && (ua.win || ua.mac) && !(ua.wk && ua.wk < 312);
	}
	
	/* Show the Adobe Express Install dialog
		- Reference: http://www.adobe.com/cfusion/knowledgebase/index.cfm?id=6a253b75
	*/
	function showExpressInstall(att, par, replaceElemIdStr, callbackFn) {
		isExpressInstallActive = true;
		storedCallbackFn = callbackFn || null;
		storedCallbackObj = {success:false, id:replaceElemIdStr};
		var obj = getElementById(replaceElemIdStr);
		if (obj) {
			if (obj.nodeName == "OBJECT") { // static publishing
				storedAltContent = abstractAltContent(obj);
				storedAltContentId = null;
			}
			else { // dynamic publishing
				storedAltContent = obj;
				storedAltContentId = replaceElemIdStr;
			}
			att.id = EXPRESS_INSTALL_ID;
			if (typeof att.width == UNDEF || (!/%$/.test(att.width) && parseInt(att.width, 10) < 310)) { att.width = "310"; }
			if (typeof att.height == UNDEF || (!/%$/.test(att.height) && parseInt(att.height, 10) < 137)) { att.height = "137"; }
			doc.title = doc.title.slice(0, 47) + " - Flash Player Installation";
			var pt = ua.ie && ua.win ? "ActiveX" : "PlugIn",
				fv = "MMredirectURL=" + win.location.toString().replace(/&/g,"%26") + "&MMplayerType=" + pt + "&MMdoctitle=" + doc.title;
			if (typeof par.flashvars != UNDEF) {
				par.flashvars += "&" + fv;
			}
			else {
				par.flashvars = fv;
			}
			// IE only: when a SWF is loading (AND: not available in cache) wait for the readyState of the object element to become 4 before removing it,
			// because you cannot properly cancel a loading SWF file without breaking browser load references, also obj.onreadystatechange doesn't work
			if (ua.ie && ua.win && obj.readyState != 4) {
				var newObj = createElement("div");
				replaceElemIdStr += "SWFObjectNew";
				newObj.setAttribute("id", replaceElemIdStr);
				obj.parentNode.insertBefore(newObj, obj); // insert placeholder div that will be replaced by the object element that loads expressinstall.swf
				obj.style.display = "none";
				(function(){
					if (obj.readyState == 4) {
						obj.parentNode.removeChild(obj);
					}
					else {
						setTimeout(arguments.callee, 10);
					}
				})();
			}
			createSWF(att, par, replaceElemIdStr);
		}
	}
	
	/* Functions to abstract and display alternative content
	*/
	function displayAltContent(obj) {
		if (ua.ie && ua.win && obj.readyState != 4) {
			// IE only: when a SWF is loading (AND: not available in cache) wait for the readyState of the object element to become 4 before removing it,
			// because you cannot properly cancel a loading SWF file without breaking browser load references, also obj.onreadystatechange doesn't work
			var el = createElement("div");
			obj.parentNode.insertBefore(el, obj); // insert placeholder div that will be replaced by the alternative content
			el.parentNode.replaceChild(abstractAltContent(obj), el);
			obj.style.display = "none";
			(function(){
				if (obj.readyState == 4) {
					obj.parentNode.removeChild(obj);
				}
				else {
					setTimeout(arguments.callee, 10);
				}
			})();
		}
		else {
			obj.parentNode.replaceChild(abstractAltContent(obj), obj);
		}
	} 

	function abstractAltContent(obj) {
		var ac = createElement("div");
		if (ua.win && ua.ie) {
			ac.innerHTML = obj.innerHTML;
		}
		else {
			var nestedObj = obj.getElementsByTagName(OBJECT)[0];
			if (nestedObj) {
				var c = nestedObj.childNodes;
				if (c) {
					var cl = c.length;
					for (var i = 0; i < cl; i++) {
						if (!(c[i].nodeType == 1 && c[i].nodeName == "PARAM") && !(c[i].nodeType == 8)) {
							ac.appendChild(c[i].cloneNode(true));
						}
					}
				}
			}
		}
		return ac;
	}
	
	/* Cross-browser dynamic SWF creation
	*/
	function createSWF(attObj, parObj, id) {
		var r, el = getElementById(id);
		if (ua.wk && ua.wk < 312) { return r; }
		if (el) {
			if (typeof attObj.id == UNDEF) { // if no 'id' is defined for the object element, it will inherit the 'id' from the alternative content
				attObj.id = id;
			}
			if (ua.ie && ua.win) { // Internet Explorer + the HTML object element + W3C DOM methods do not combine: fall back to outerHTML
				var att = "";
				for (var i in attObj) {
					if (attObj[i] != Object.prototype[i]) { // filter out prototype additions from other potential libraries
						if (i.toLowerCase() == "data") {
							parObj.movie = attObj[i];
						}
						else if (i.toLowerCase() == "styleclass") { // 'class' is an ECMA4 reserved keyword
							att += ' class="' + attObj[i] + '"';
						}
						else if (i.toLowerCase() != "classid") {
							att += ' ' + i + '="' + attObj[i] + '"';
						}
					}
				}
				var par = "";
				for (var j in parObj) {
					if (parObj[j] != Object.prototype[j]) { // filter out prototype additions from other potential libraries
						par += '<param name="' + j + '" value="' + parObj[j] + '" />';
					}
				}
				el.outerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"' + att + '>' + par + '</object>';
				objIdArr[objIdArr.length] = attObj.id; // stored to fix object 'leaks' on unload (dynamic publishing only)
				r = getElementById(attObj.id);	
			}
			else { // well-behaving browsers
				var o = createElement(OBJECT);
				o.setAttribute("type", FLASH_MIME_TYPE);
				for (var m in attObj) {
					if (attObj[m] != Object.prototype[m]) { // filter out prototype additions from other potential libraries
						if (m.toLowerCase() == "styleclass") { // 'class' is an ECMA4 reserved keyword
							o.setAttribute("class", attObj[m]);
						}
						else if (m.toLowerCase() != "classid") { // filter out IE specific attribute
							o.setAttribute(m, attObj[m]);
						}
					}
				}
				for (var n in parObj) {
					if (parObj[n] != Object.prototype[n] && n.toLowerCase() != "movie") { // filter out prototype additions from other potential libraries and IE specific param element
						createObjParam(o, n, parObj[n]);
					}
				}
				el.parentNode.replaceChild(o, el);
				r = o;
			}
		}
		return r;
	}
	
	function createObjParam(el, pName, pValue) {
		var p = createElement("param");
		p.setAttribute("name", pName);	
		p.setAttribute("value", pValue);
		el.appendChild(p);
	}
	
	/* Cross-browser SWF removal
		- Especially needed to safely and completely remove a SWF in Internet Explorer
	*/
	function removeSWF(id) {
		var obj = getElementById(id);
		if (obj && obj.nodeName == "OBJECT") {
			if (ua.ie && ua.win) {
				obj.style.display = "none";
				(function(){
					if (obj.readyState == 4) {
						removeObjectInIE(id);
					}
					else {
						setTimeout(arguments.callee, 10);
					}
				})();
			}
			else {
				obj.parentNode.removeChild(obj);
			}
		}
	}
	
	function removeObjectInIE(id) {
		var obj = getElementById(id);
		if (obj) {
			for (var i in obj) {
				if (typeof obj[i] == "function") {
					obj[i] = null;
				}
			}
			obj.parentNode.removeChild(obj);
		}
	}
	
	/* Functions to optimize JavaScript compression
	*/
	function getElementById(id) {
		var el = null;
		try {
			el = doc.getElementById(id);
		}
		catch (e) {}
		return el;
	}
	
	function createElement(el) {
		return doc.createElement(el);
	}
	
	/* Updated attachEvent function for Internet Explorer
		- Stores attachEvent information in an Array, so on unload the detachEvent functions can be called to avoid memory leaks
	*/	
	function addListener(target, eventType, fn) {
		target.attachEvent(eventType, fn);
		listenersArr[listenersArr.length] = [target, eventType, fn];
	}
	
	/* Flash Player and SWF content version matching
	*/
	function hasPlayerVersion(rv) {
		var pv = ua.pv, v = rv.split(".");
		v[0] = parseInt(v[0], 10);
		v[1] = parseInt(v[1], 10) || 0; // supports short notation, e.g. "9" instead of "9.0.0"
		v[2] = parseInt(v[2], 10) || 0;
		return (pv[0] > v[0] || (pv[0] == v[0] && pv[1] > v[1]) || (pv[0] == v[0] && pv[1] == v[1] && pv[2] >= v[2])) ? true : false;
	}
	
	/* Cross-browser dynamic CSS creation
		- Based on Bobby van der Sluis' solution: http://www.bobbyvandersluis.com/articles/dynamicCSS.php
	*/	
	function createCSS(sel, decl, media, newStyle) {
		if (ua.ie && ua.mac) { return; }
		var h = doc.getElementsByTagName("head")[0];
		if (!h) { return; } // to also support badly authored HTML pages that lack a head element
		var m = (media && typeof media == "string") ? media : "screen";
		if (newStyle) {
			dynamicStylesheet = null;
			dynamicStylesheetMedia = null;
		}
		if (!dynamicStylesheet || dynamicStylesheetMedia != m) { 
			// create dynamic stylesheet + get a global reference to it
			var s = createElement("style");
			s.setAttribute("type", "text/css");
			s.setAttribute("media", m);
			dynamicStylesheet = h.appendChild(s);
			if (ua.ie && ua.win && typeof doc.styleSheets != UNDEF && doc.styleSheets.length > 0) {
				dynamicStylesheet = doc.styleSheets[doc.styleSheets.length - 1];
			}
			dynamicStylesheetMedia = m;
		}
		// add style rule
		if (ua.ie && ua.win) {
			if (dynamicStylesheet && typeof dynamicStylesheet.addRule == OBJECT) {
				dynamicStylesheet.addRule(sel, decl);
			}
		}
		else {
			if (dynamicStylesheet && typeof doc.createTextNode != UNDEF) {
				dynamicStylesheet.appendChild(doc.createTextNode(sel + " {" + decl + "}"));
			}
		}
	}
	
	function setVisibility(id, isVisible) {
		if (!autoHideShow) { return; }
		var v = isVisible ? "visible" : "hidden";
		if (isDomLoaded && getElementById(id)) {
			getElementById(id).style.visibility = v;
		}
		else {
			createCSS("#" + id, "visibility:" + v);
		}
	}

	/* Filter to avoid XSS attacks
	*/
	function urlEncodeIfNecessary(s) {
		var regex = /[\\\"<>\.;]/;
		var hasBadChars = regex.exec(s) != null;
		return hasBadChars && typeof encodeURIComponent != UNDEF ? encodeURIComponent(s) : s;
	}
	
	/* Release memory to avoid memory leaks caused by closures, fix hanging audio/video threads and force open sockets/NetConnections to disconnect (Internet Explorer only)
	*/
	var cleanup = function() {
		if (ua.ie && ua.win) {
			window.attachEvent("onunload", function() {
				// remove listeners to avoid memory leaks
				var ll = listenersArr.length;
				for (var i = 0; i < ll; i++) {
					listenersArr[i][0].detachEvent(listenersArr[i][1], listenersArr[i][2]);
				}
				// cleanup dynamically embedded objects to fix audio/video threads and force open sockets and NetConnections to disconnect
				var il = objIdArr.length;
				for (var j = 0; j < il; j++) {
					removeSWF(objIdArr[j]);
				}
				// cleanup library's main closures to avoid memory leaks
				for (var k in ua) {
					ua[k] = null;
				}
				ua = null;
				for (var l in swfobject) {
					swfobject[l] = null;
				}
				swfobject = null;
			});
		}
	}();
	
	return {
		/* Public API
			- Reference: http://code.google.com/p/swfobject/wiki/documentation
		*/ 
		registerObject: function(objectIdStr, swfVersionStr, xiSwfUrlStr, callbackFn) {
			if (ua.w3 && objectIdStr && swfVersionStr) {
				var regObj = {};
				regObj.id = objectIdStr;
				regObj.swfVersion = swfVersionStr;
				regObj.expressInstall = xiSwfUrlStr;
				regObj.callbackFn = callbackFn;
				regObjArr[regObjArr.length] = regObj;
				setVisibility(objectIdStr, false);
			}
			else if (callbackFn) {
				callbackFn({success:false, id:objectIdStr});
			}
		},
		
		getObjectById: function(objectIdStr) {
			if (ua.w3) {
				return getObjectById(objectIdStr);
			}
		},
		
		embedSWF: function(swfUrlStr, replaceElemIdStr, widthStr, heightStr, swfVersionStr, xiSwfUrlStr, flashvarsObj, parObj, attObj, callbackFn) {
			var callbackObj = {success:false, id:replaceElemIdStr};
			if (ua.w3 && !(ua.wk && ua.wk < 312) && swfUrlStr && replaceElemIdStr && widthStr && heightStr && swfVersionStr) {
				setVisibility(replaceElemIdStr, false);
				addDomLoadEvent(function() {
					widthStr += ""; // auto-convert to string
					heightStr += "";
					var att = {};
					if (attObj && typeof attObj === OBJECT) {
						for (var i in attObj) { // copy object to avoid the use of references, because web authors often reuse attObj for multiple SWFs
							att[i] = attObj[i];
						}
					}
					att.data = swfUrlStr;
					att.width = widthStr;
					att.height = heightStr;
					var par = {}; 
					if (parObj && typeof parObj === OBJECT) {
						for (var j in parObj) { // copy object to avoid the use of references, because web authors often reuse parObj for multiple SWFs
							par[j] = parObj[j];
						}
					}
					if (flashvarsObj && typeof flashvarsObj === OBJECT) {
						for (var k in flashvarsObj) { // copy object to avoid the use of references, because web authors often reuse flashvarsObj for multiple SWFs
							if (typeof par.flashvars != UNDEF) {
								par.flashvars += "&" + k + "=" + flashvarsObj[k];
							}
							else {
								par.flashvars = k + "=" + flashvarsObj[k];
							}
						}
					}
					if (hasPlayerVersion(swfVersionStr)) { // create SWF
						var obj = createSWF(att, par, replaceElemIdStr);
						if (att.id == replaceElemIdStr) {
							setVisibility(replaceElemIdStr, true);
						}
						callbackObj.success = true;
						callbackObj.ref = obj;
					}
					else if (xiSwfUrlStr && canExpressInstall()) { // show Adobe Express Install
						att.data = xiSwfUrlStr;
						showExpressInstall(att, par, replaceElemIdStr, callbackFn);
						return;
					}
					else { // show alternative content
						setVisibility(replaceElemIdStr, true);
					}
					if (callbackFn) { callbackFn(callbackObj); }
				});
			}
			else if (callbackFn) { callbackFn(callbackObj);	}
		},
		
		switchOffAutoHideShow: function() {
			autoHideShow = false;
		},
		
		ua: ua,
		
		getFlashPlayerVersion: function() {
			return { major:ua.pv[0], minor:ua.pv[1], release:ua.pv[2] };
		},
		
		hasFlashPlayerVersion: hasPlayerVersion,
		
		createSWF: function(attObj, parObj, replaceElemIdStr) {
			if (ua.w3) {
				return createSWF(attObj, parObj, replaceElemIdStr);
			}
			else {
				return undefined;
			}
		},
		
		showExpressInstall: function(att, par, replaceElemIdStr, callbackFn) {
			if (ua.w3 && canExpressInstall()) {
				showExpressInstall(att, par, replaceElemIdStr, callbackFn);
			}
		},
		
		removeSWF: function(objElemIdStr) {
			if (ua.w3) {
				removeSWF(objElemIdStr);
			}
		},
		
		createCSS: function(selStr, declStr, mediaStr, newStyleBoolean) {
			if (ua.w3) {
				createCSS(selStr, declStr, mediaStr, newStyleBoolean);
			}
		},
		
		addDomLoadEvent: addDomLoadEvent,
		
		addLoadEvent: addLoadEvent,
		
		getQueryParamValue: function(param) {
			var q = doc.location.search || doc.location.hash;
			if (q) {
				if (/\?/.test(q)) { q = q.split("?")[1]; } // strip question mark
				if (param == null) {
					return urlEncodeIfNecessary(q);
				}
				var pairs = q.split("&");
				for (var i = 0; i < pairs.length; i++) {
					if (pairs[i].substring(0, pairs[i].indexOf("=")) == param) {
						return urlEncodeIfNecessary(pairs[i].substring((pairs[i].indexOf("=") + 1)));
					}
				}
			}
			return "";
		},
		
		// For internal usage only
		expressInstallCallback: function() {
			if (isExpressInstallActive) {
				var obj = getElementById(EXPRESS_INSTALL_ID);
				if (obj && storedAltContent) {
					obj.parentNode.replaceChild(storedAltContent, obj);
					if (storedAltContentId) {
						setVisibility(storedAltContentId, true);
						if (ua.ie && ua.win) { storedAltContent.style.display = "block"; }
					}
					if (storedCallbackFn) { storedCallbackFn(storedCallbackObj); }
				}
				isExpressInstallActive = false;
			} 
		}
	};
}();

 
/**
 * Corta las palabras que superen una cierta cantidad de caracteres
 *
 * @access 	public
 * @param 	texto2	string	Texto a consultar
 * @param 	maximo	integer	Cantidad máxima de caracteres a cortar (opcional, por defecto 25 caracteres)
 * @return	string	Texto cortado según los parametros definidos
 */
function cPalabrasLargas(texto2, maximo) {

	maximo = (!maximo) ? 25 : maximo;

	pLink = /^\[url[=]?[\w\*":.\/]*\][\w\*":.\/\[\]]+\[\/url\]$/;
	pImagen = /^\[img?(=left)?(=right)?\][\w:".\/]*\[\/img(=left)?(=right)?\]$/;
	pUrl = /^https?:\/\/[\w\.]+/;
	pCodigo = /^<[\w\."\'$\(\)\= #\?&%@\[\]\;]+>$/;
	pCodigo2 = /="[\w\.:\/\-"\+\=\?\&\#%@$'\(\)\[\]\;]+"/;
	pMail = /^[\w\_\.çñ]{2,255}@[\w]{2,255}\.[a-z]{1,3}\.?[a-z]{0,3}$/;

	palabras = texto2.split(/\s/);
	largo = palabras.length;

	for(m=0;m<largo;m++){
		if(palabras[m].length > maximo){

			if (pLink.test(palabras[m])) {
				continue;
			}

			if (pImagen.test(palabras[m])) {
				continue;
			}

			if (pUrl.test(palabras[m])) {
				continue;
			}

			if (pCodigo.test(palabras[m])) {
				continue;
			}

			if (pCodigo2.test(palabras[m])) {
				continue;
			}

			if (pMail.test(palabras[m])) {
				continue;
			}

			texto3=palabras[m].substr(0,maximo) + " ";

			for (h=maximo; h<palabras[m].length; h+=maximo) {
				texto3+=palabras[m].substr(h,maximo);
			}

			texto2=texto2.replace(palabras[m],texto3);
			
			texto2 = cPalabrasLargas(texto2, maximo);
		}
	}
	
	return texto2;
}

/**
 * ***********************************************************************************************
 * Limita los caracteres en textarea
 * ***********************************************************************************************
 *
 * Limit – Limit the number of characters in a textarea jQuery plugin
 * copyright: http://www.unwrongest.com/projects/limit/
 * 
 * elemento: elemento a realizar la comprobación del limite de caracteres
 * limite: cantidad máxima de caracteres a limitar
 * campo_testigo: elemento al cual se le va a cargar la cantidad de caracteres restantes (permite html o campos de input
 * maximo: largo máximo de las palabras
 *
 * Uso: $(elemento).limit(limite, campo_testigo, maximo);
 * Ejemplo: $('#myTextarea').limit('140','#charsLeft', 15);
 * 
 */
(function($){
	$.fn.extend({  
		limit: function(limit,element,maximo) {

			var interval, f;
			var self = $(this);

			$(this).focus(function(){
				interval = window.setInterval(substring,100);
			});

			$(this).blur(function(){
				clearInterval(interval);
				substring();
			});

			substringFunction = "function substring(){ var val = $(self).val(); ";
			
			if (maximo) {
				substringFunction+= "val = cPalabrasLargas(val, maximo); ";
				substringFunction+= "$(self).val(val);";
			}
			
			substringFunction+= "var length = val.length; if(length > limit){$(self).val($(self).val().substring(0,limit));}";

			if(typeof element != 'undefined') {
				if ($(element).is("input")) {
					substringFunction += "if($(element).val() != limit-length){$(element).val((limit-length<=0)?'0':limit-length);}"
				} else {
					substringFunction += "if($(element).html() != limit-length){$(element).html((limit-length<=0)?'0':limit-length);}"
				}
			}

			substringFunction += "}";

			eval(substringFunction);
			
			substring();
		} 
	}); 
})(jQuery);

/**
 * jQuery Custom Radio-buttons and Checkbox; basically it's styling/theming for Checkbox and Radiobutton elements in forms
 * By Dharmavirsinh Jhala - dharmavir@gmail.com
 * Date of Release: 13th March 10
 * Version: 0.8
 *
 * Extraido desde: http://blogs.digitss.com/javascript/jquery-javascript/jquery-fancy-custom-radio-and-checkbox/
 * Modificado por Alexis Lesa para que los cambios se realizen con clases (22.09.2010)
 *
 * USAGE:
 *	$(document).ready(function(){
 *		$(":radio").behaveLikeCheckbox();
 *	}
 */
jQuery.fn.extend({
	dgStyle: function() {
		$.each($(this), function() {
			var elm	=	$(this).children().get(0);
			elmType = $(elm).attr("type");
			$(this).data('type',elmType);
			$(this).data('checked',$(elm).attr("checked"));
			$(this).dgClear();
		});
		$(this).mousedown(function() { $(this).dgEffect(); });
		$(this).mouseup(function() { $(this).dgHandle(); });	
	},
	dgClear: function() {
		if($(this).data("checked") == true) {
			$(this).addClass("selected");
		} else {
			$(this).removeClass("selected");
		}	
	},
	dgEffect: function() {
		if($(this).data("checked") == true) {
			$(this).addClass("selected");
		} else {
			$(this).removeClass("selected");
		}
	},
	dgHandle: function() {
		var elm	= $(this).children().get(0);
		if($(this).data("checked") == true) {
			$(elm).dgUncheck(this);
		} else {
			$(elm).dgCheck(this);
		}
		
		if($(this).data('type') == 'radio') {
			$.each($("input[name='"+$(elm).attr("name")+"']"),function() {
				if(elm!=this) {
					$(this).dgUncheck(-1);
				}
			});
		}
	},
	dgCheck: function(div) {
		$(this).attr("checked",true);
		$(div).data('checked',true).addClass("selected");
	},
	dgUncheck: function(div) {
		$(this).attr("checked",false);
		if(div != -1) {
			$(div).data('checked',false).removeClass("selected");
		} else {
			$(this).parent().data("checked",false).removeClass("selected");
		}
	}
});
 
/**
 * A jQuery-Geolocation-Plugin
 *
 * @author Thomas Michelbach <thomas@nomoresleep.net>
 * @copyright NoMoreSleep(tm) <http://developer.nomoresleep.net>
 * @version 0.1
 *
 * Ejemplo de uso
 * if(navigator.geolocation) {
 * 		gl = navigator.geolocation.getCurrentPosition( function(position) {
 *
 *			alert("Latitud:" + position.coords.latitude + " - Long:" + position.coords.longitude);
 *		
 *		}, function() {
 *		},{timeout:15000});
 * }
 *
 */

(function($){
	
	$.extend($.support,{
		geolocation:function(){
			return $.geolocation.support();
		}
	});

	$.geolocation = {        
		find:function(success, error, options){
			if($.geolocation.support()){
				options = $.extend({highAccuracy: false, track: false}, options);
				($.geolocation.object())[(options.track ? 'watchPosition' : 'getCurrentPosition')](function(location){
					success(location.coords);
				}, function(){
					error();
				}, {enableHighAccuracy: options.highAccuracy});		
			}else{
				error();				
			}
		},
		object:function(){
			return navigator.geolocation;
		},
		support:function(){
			return ($.geolocation.object()) ? true : false;
		}
	}
	
})(jQuery);

 
/**
 * ***********************************************************************
 * IE5.5+ PNG Alpha Fix v2.0 Alpha: Background Tiling Support
 * (c) 2008-2009 Angus Turnbull http://www.twinhelix.com
 *
 * This is licensed under the GNU LGPL, version 2.1 or later.
 * For details, see: http://creativecommons.org/licenses/LGPL/2.1/
 *
 * ***********************************************************************
 */
var IEPNGFix = window.IEPNGFix || {};

IEPNGFix.tileBG = function(elm, pngSrc, ready) {
	// Params: A reference to a DOM element, the PNG src file pathname, and a
	// hidden "ready-to-run" passed when called back after image preloading.

	var data = this.data[elm.uniqueID],
		elmW = Math.max(elm.clientWidth, elm.scrollWidth),
		elmH = Math.max(elm.clientHeight, elm.scrollHeight),
		bgX = elm.currentStyle.backgroundPositionX,
		bgY = elm.currentStyle.backgroundPositionY,
		bgR = elm.currentStyle.backgroundRepeat;

	// Cache of DIVs created per element, and image preloader/data.
	if (!data.tiles) {
		data.tiles = {
			elm: elm,
			src: '',
			cache: [],
			img: new Image(),
			old: {}
		};
	}
	var tiles = data.tiles,
		pngW = tiles.img.width,
		pngH = tiles.img.height;

	if (pngSrc) {
		if (!ready && pngSrc != tiles.src) {
			// New image? Preload it with a callback to detect dimensions.
			tiles.img.onload = function() {
				this.onload = null;
				IEPNGFix.tileBG(elm, pngSrc, 1);
			};
			return tiles.img.src = pngSrc;
		}
	} else {
		// No image?
		if (tiles.src) ready = 1;
		pngW = pngH = 0;
	}
	tiles.src = pngSrc;

	if (!ready && elmW == tiles.old.w && elmH == tiles.old.h &&
		bgX == tiles.old.x && bgY == tiles.old.y && bgR == tiles.old.r) {
		return;
	}

	// Convert English and percentage positions to pixels.
	var pos = {
			top: '0%',
			left: '0%',
			center: '50%',
			bottom: '100%',
			right: '100%'
		},
		x,
		y,
		pc;
	x = pos[bgX] || bgX;
	y = pos[bgY] || bgY;
	if (pc = x.match(/(\d+)%/)) {
		x = Math.round((elmW - pngW) * (parseInt(pc[1]) / 100));
	}
	if (pc = y.match(/(\d+)%/)) {
		y = Math.round((elmH - pngH) * (parseInt(pc[1]) / 100));
	}
	x = parseInt(x);
	y = parseInt(y);

	// Handle backgroundRepeat.
	var repeatX = { 'repeat': 1, 'repeat-x': 1 }[bgR],
		repeatY = { 'repeat': 1, 'repeat-y': 1 }[bgR];
	if (repeatX) {
		x %= pngW;
		if (x > 0) x -= pngW;
	}
	if (repeatY) {
		y %= pngH;
		if (y > 0) y -= pngH;
	}

	// Go!
	this.hook.enabled = 0;
	if (!({ relative: 1, absolute: 1 }[elm.currentStyle.position])) {
		elm.style.position = 'relative';
	}
	var count = 0,
		xPos,
		maxX = repeatX ? elmW : x + 0.1,
		yPos,
		maxY = repeatY ? elmH : y + 0.1,
		d,
		s,
		isNew;
	if (pngW && pngH) {
		for (xPos = x; xPos < maxX; xPos += pngW) {
			for (yPos = y; yPos < maxY; yPos += pngH) {
				isNew = 0;
				if (!tiles.cache[count]) {
					tiles.cache[count] = document.createElement('div');
					isNew = 1;
				}
				var clipR = Math.max(0, xPos + pngW > elmW ? elmW - xPos : pngW),
					clipB = Math.max(0, yPos + pngH > elmH ? elmH - yPos : pngH);
				d = tiles.cache[count];
				s = d.style;
				s.behavior = 'none';
				s.left = (xPos - parseInt(elm.currentStyle.paddingLeft)) + 'px';
				s.top = yPos + 'px';
				s.width = clipR + 'px';
				s.height = clipB + 'px';
				s.clip = 'rect(' +
					(yPos < 0 ? 0 - yPos : 0) + 'px,' +
					clipR + 'px,' +
					clipB + 'px,' +
					(xPos < 0 ? 0 - xPos : 0) + 'px)';
				s.display = 'block';
				if (isNew) {
					s.position = 'absolute';
					s.zIndex = -999;
					if (elm.firstChild) {
						elm.insertBefore(d, elm.firstChild);
					} else {
						elm.appendChild(d);
					}
				}
				this.fix(d, pngSrc, 0);
				count++;
			}
		}
	}
	while (count < tiles.cache.length) {
		this.fix(tiles.cache[count], '', 0);
		tiles.cache[count++].style.display = 'none';
	}

	this.hook.enabled = 1;

	// Cache so updates are infrequent.
	tiles.old = {
		w: elmW,
		h: elmH,
		x: bgX,
		y: bgY,
		r: bgR
	};
};

IEPNGFix.update = function() {
	// Update all PNG backgrounds.
	for (var i in IEPNGFix.data) {
		var t = IEPNGFix.data[i].tiles;
		if (t && t.elm && t.src) {
			IEPNGFix.tileBG(t.elm, t.src);
		}
	}
};
IEPNGFix.update.timer = 0;

if (window.attachEvent && !window.opera) {
	window.attachEvent('onresize', function() {
		clearTimeout(IEPNGFix.update.timer);
		IEPNGFix.update.timer = setTimeout(IEPNGFix.update, 100);
	});
}

/**
 * ***********************************************************************
 * Lazy Load - jQuery plugin for lazy loading images
 *
 * Copyright (c) 2007-2009 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/lazyload
 *
 * Version:  1.5.0
 *
 * ***********************************************************************
 */
(function($) {

    $.fn.lazyload = function(options) {
        var settings = {
            threshold    : 0,
            failurelimit : 0,
            event        : "scroll",
            effect       : "show",
            container    : window
        };
                
        if(options) {
            $.extend(settings, options);
        }

        /* Fire one scroll event per scroll. Not one scroll event per image. */
        var elements = this;
        if ("scroll" == settings.event) {
            $(settings.container).bind("scroll", function(event) {
                
                var counter = 0;
                elements.each(function() {
                    if ($.abovethetop(this, settings) ||
                        $.leftofbegin(this, settings)) {
                            /* Nothing. */
                    } else if (!$.belowthefold(this, settings) &&
                        !$.rightoffold(this, settings)) {
                            $(this).trigger("appear");
                    } else {
                        if (counter++ > settings.failurelimit) {
                            return false;
                        }
                    }
                });
                /* Remove image from array so it is not looped next time. */
                var temp = $.grep(elements, function(element) {
                    return !element.loaded;
                });
                elements = $(temp);
            });
        }
        
        this.each(function() {
            var self = this;
            
            /* Save original only if it is not defined in HTML. */
            if (undefined == $(self).attr("original")) {
                $(self).attr("original", $(self).attr("src"));     
            }

            if ("scroll" != settings.event || 
                    undefined == $(self).attr("src") || 
                    settings.placeholder == $(self).attr("src") || 
                    ($.abovethetop(self, settings) ||
                     $.leftofbegin(self, settings) || 
                     $.belowthefold(self, settings) || 
                     $.rightoffold(self, settings) )) {
                        
                if (settings.placeholder) {
                    $(self).attr("src", settings.placeholder);      
                } else {
                    $(self).removeAttr("src");
                }
                self.loaded = false;
            } else {
                self.loaded = true;
            }
            
            /* When appear is triggered load original image. */
            $(self).one("appear", function() {
                if (!this.loaded) {
                    $("<img />")
                        .bind("load", function() {
                            $(self)
                                .hide()
                                .attr("src", $(self).attr("original"))
                                [settings.effect](settings.effectspeed);
                            self.loaded = true;
                        })
                        .attr("src", $(self).attr("original"));
                };
            });

            /* When wanted event is triggered load original image */
            /* by triggering appear.                              */
            if ("scroll" != settings.event) {
                $(self).bind(settings.event, function(event) {
                    if (!self.loaded) {
                        $(self).trigger("appear");
                    }
                });
            }
        });
        
        /* Force initial check if images should appear. */
        $(settings.container).trigger(settings.event);
        
        return this;

    };

    /* Convenience methods in jQuery namespace.           */
    /* Use as  $.belowthefold(element, {threshold : 100, container : window}) */

    $.belowthefold = function(element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).height() + $(window).scrollTop();
        } else {
            var fold = $(settings.container).offset().top + $(settings.container).height();
        }
        return fold <= $(element).offset().top - settings.threshold;
    };
    
    $.rightoffold = function(element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).width() + $(window).scrollLeft();
        } else {
            var fold = $(settings.container).offset().left + $(settings.container).width();
        }
        return fold <= $(element).offset().left - settings.threshold;
    };
        
    $.abovethetop = function(element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).scrollTop();
        } else {
            var fold = $(settings.container).offset().top;
        }
        return fold >= $(element).offset().top + settings.threshold  + $(element).height();
    };
    
    $.leftofbegin = function(element, settings) {
        if (settings.container === undefined || settings.container === window) {
            var fold = $(window).scrollLeft();
        } else {
            var fold = $(settings.container).offset().left;
        }
        return fold >= $(element).offset().left + settings.threshold + $(element).width();
    };
    /* Custom selectors for your convenience.   */
    /* Use as $("img:below-the-fold").something() */

    $.extend($.expr[':'], {
        "below-the-fold" : "$.belowthefold(a, {threshold : 0, container: window})",
        "above-the-fold" : "!$.belowthefold(a, {threshold : 0, container: window})",
        "right-of-fold"  : "$.rightoffold(a, {threshold : 0, container: window})",
        "left-of-fold"   : "!$.rightoffold(a, {threshold : 0, container: window})"
    });
    
})(jQuery);

  
/**
 * *********************************************************************** 
 * jQuery.ScrollTo - Easy element scrolling using jQuery.
 * Copyright (c) 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * Date: 5/25/2009
 * @author Ariel Flesler
 * @version 1.4.2
 *
 * http://flesler.blogspot.com/2007/10/jqueryscrollto.html
 * *********************************************************************** 
 */
(function(d){var k=d.scrollTo=function(a,i,e){d(window).scrollTo(a,i,e)};k.defaults={axis:'xy',duration:parseFloat(d.fn.jquery)>=1.3?0:1};k.window=function(a){return d(window)._scrollable()};d.fn._scrollable=function(){return this.map(function(){var a=this,i=!a.nodeName||d.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!i)return a;var e=(a.contentWindow||a).document||a.ownerDocument||a;return d.browser.safari||e.compatMode=='BackCompat'?e.body:e.documentElement})};d.fn.scrollTo=function(n,j,b){if(typeof j=='object'){b=j;j=0}if(typeof b=='function')b={onAfter:b};if(n=='max')n=9e9;b=d.extend({},k.defaults,b);j=j||b.speed||b.duration;b.queue=b.queue&&b.axis.length>1;if(b.queue)j/=2;b.offset=p(b.offset);b.over=p(b.over);return this._scrollable().each(function(){var q=this,r=d(q),f=n,s,g={},u=r.is('html,body');switch(typeof f){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)){f=p(f);break}f=d(f,this);case'object':if(f.is||f.style)s=(f=d(f)).offset()}d.each(b.axis.split(''),function(a,i){var e=i=='x'?'Left':'Top',h=e.toLowerCase(),c='scroll'+e,l=q[c],m=k.max(q,i);if(s){g[c]=s[h]+(u?0:l-r.offset()[h]);if(b.margin){g[c]-=parseInt(f.css('margin'+e))||0;g[c]-=parseInt(f.css('border'+e+'Width'))||0}g[c]+=b.offset[h]||0;if(b.over[h])g[c]+=f[i=='x'?'width':'height']()*b.over[h]}else{var o=f[h];g[c]=o.slice&&o.slice(-1)=='%'?parseFloat(o)/100*m:o}if(/^\d+$/.test(g[c]))g[c]=g[c]<=0?0:Math.min(g[c],m);if(!a&&b.queue){if(l!=g[c])t(b.onAfterFirst);delete g[c]}});t(b.onAfter);function t(a){r.animate(g,j,b.easing,a&&function(){a.call(this,n,b)})}}).end()};k.max=function(a,i){var e=i=='x'?'Width':'Height',h='scroll'+e;if(!d(a).is('html,body'))return a[h]-d(a)[e.toLowerCase()]();var c='client'+e,l=a.ownerDocument.documentElement,m=a.ownerDocument.body;return Math.max(l[h],m[h])-Math.min(l[c],m[c])};function p(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);
  
  
/**
 * ***********************************************************************
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Create a cookie with the given name and value and other optional parameters.
 *
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Set the value of a cookie.
 * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true });
 * @desc Create a cookie with all available options.
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Create a session cookie.
 * @example $.cookie('the_cookie', null);
 * @desc Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
 *       used when the cookie was set.
 *
 * @param String name The name of the cookie.
 * @param String value The value of the cookie.
 * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
 * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
 *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
 *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
 *                             when the the browser exits.
 * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
 * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
 * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
 *                        require a secure protocol (like HTTPS).
 * @type undefined
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 *
 * Get the value of a cookie with the given name.
 *
 * @example $.cookie('the_cookie');
 * @desc Get the value of a cookie.
 *
 * @param String name The name of the cookie.
 * @return The value of the cookie.
 * @type String
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 * *********************************************************************** 
 */
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
 
/**
 * ***********************************************************************
 * CSS Browser Selector v0.4.0 (Nov 02, 2010)
 * Rafael Lima (http://rafael.adm.br)
 * http://rafael.adm.br/css_browser_selector
 * License: http://creativecommons.org/licenses/by/2.5/
 * Contributors: http://rafael.adm.br/css_browser_selector#contributors
 * *********************************************************************** 
 */
function css_browser_selector(u){var ua=u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1},g='gecko',w='webkit',s='safari',o='opera',m='mobile',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3.6')?g+' ff3 ff3_6':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('blackberry')?m+' blackberry':is('android')?m+' android':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?m+' j2me':is('iphone')?m+' iphone':is('ipod')?m+' ipod':is('ipad')?m+' ipad':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win'+(is('windows nt 6.0')?' vista':''):is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;}; css_browser_selector(navigator.userAgent);
css_browser_selector(navigator.userAgent);

/**
 * ***********************************************************************
 * Revisa que el formulario de búsqueda tenga el texto a buscar
 *
 * param form formulario donde se esta realizando la búsqueda
 * param tag (opcional) si el campo posee un texto de marca de agua, revisa que no tenga ese texto en el input
 *
 * *********************************************************************** 
 */
function revisabuscador(form, tag) {
	tag = (tag!="") ? tag : "";
	if (form.q.value == "" || form.q.value == tag) {
		alert("Debe ingresar un texto");
		form.q.focus();
		return false;
	} else {
		return true;
	}
}

/**
 * ***********************************************************************
 * Alinea verticalmente el objeto definido con respecto a su objeto contenedor.
 *
 * Debe referenciarse a cada objeto por vez para realizar una alienación vertical correcta.
 * @example $("#bloque").vAlign();
 *
 * En caso de que sean multiples objetos del mismo identificador (id o clase) se debe utilizar de la siguiente forma:
 * @example $(".bloque").each ( function() { $(this).vAlign(); });
 *
 * *********************************************************************** 
 */
(function ($) {
	$.fn.vAlign = function() {
		return this.each(function(i){
		var ah = $(this).height();
		var ph = $(this).parent().height();
		var mh = (ph - ah) / 2;
		$(this).css('margin-top', mh);
		});
	};
})(jQuery);

 
/**
 * ***********************************************************************
 * jQuery Fieldtag Plugin
 * Version 1.1
 * 2009-05-07 10:10:35
 * URL: http://ajaxcssblog.com/jquery/fieldtag-watermark-inputfields/
 * Description: jQuery Plugin to dynamically tag an inputfield, with a class and/or text
 * Author: Matthias Jäggli
 * Copyright: Copyright (c) 2009 Matthias Jäggli under dual MIT/GPL license.
 *
 * Changelog
 * 1.1
 * Support for proper clearing while submitting the form of tagged fields
 * 1.0
 * Initial release
 * *********************************************************************** 
 */
(function($){
	$.fn.fieldtag = function(options){
		var opt = $.extend({
				markedClass: "tagged",
				standardText: false
			}, options);
		$(this)
			.focus(function(){
				if(!this.changed){
					this.clear();
				}
			})
			.blur(function(){
				if(!this.changed){
					this.addTag();
				}
			})
			.keyup(function(){
				this.changed = ($(this).val()? true : false);
			})
			.each(function(){
				this.title = $(this).attr("title"); //strange IE6 Bug, sometimes
				if($(this).val() == $(this).attr("title")){
					this.changed = false;
				}
				this.clear = function(){
					if(!this.changed){
						$(this)
							.val("")
							.removeClass(opt.markedClass);						
					}
				}
				this.addTag = function(){
					$(this)
						.val(opt.standardText === false? this.title : opt.standardText )
						.addClass(opt.markedClass);
				}
				if(this.form){
					this.form.tagFieldsToClear = this.form.tagFieldsToClear || [];
					this.form.tagFieldsToClear.push(this);
 
					if(this.form.tagFieldsAreCleared){ return true; }
					this.form.tagFieldsAreCleared = true;
 
					$(this.form).submit(function(){
						$(this.tagFieldsToClear).each(function(){
							this.clear();
						});
					});	
				}
			})
			.keyup()
			.blur();
		return $(this);
	}
})(jQuery);


/**
 * ***********************************************************************
 * ***********************************************************************
 */
function equalHeight(group) {
	tallest = 0;
	group.each(function() {
		thisHeight = $(this).height();
		if (thisHeight > tallest) {
			tallest = thisHeight;
			}
	});
	
	group.height(tallest);
}

/**
 * ***********************************************************************
 * ***********************************************************************
 */
function ventana(url,nombre,ancho,alto,scroll) {
newWindow = window.open(url,nombre,'resizable=yes,menubar=no,location=no,toolbar=no,status=no,scrollbars='+scroll+',directories=no,width='+ancho+',height='+alto+',left='+(screen.availWidth-ancho)/2+',top='+(screen.availHeight-alto)/2);
}

/**
 * ***********************************************************************
 * ***********************************************************************  
 */
function runSWF(archivo, ancho, alto, version, bgcolor, id, menu, FlashVars, quality, allowScriptAccess, FullScreen) { 
	// Seteo valores por defecto
	var transparent_ie = '';
	var transparent_ns = '';
	var bgcolor_ie = '';
	var bgcolor_ns = '';

	var version_data="6,0,0,0";
	var menu_data=false;
	var id_data="flashMovie";
	var quality_data="high";
	var allowScriptAccess_data="always";
	var allowfullscreen_data_ie = "";
	var allowfullscreen_data_ns = ""

	// Modificación, a todos le pongo transparente.
	mode = 'wmode="transparent"';
	mode_ie = '<param name="wmode" value="transparent">\n';
	
	if(version!=""){
		var version_data=version;
	}

	if(menu!=""){
		var menu_data=menu;
	}

	if(bgcolor!="" && bgcolor != null){
		var bgcolor_data = bgcolor;
		var bgcolor_ie = '<param name="bgcolor" value='+bgcolor_data+'>\n';
		var bgcolor_ns = 'bgcolor='+bgcolor_data;
		
		mode = 'wmode="opaque"';
		mode_ie = '<param name="wmode" value="opaque">\n';
	
	}

	if(id!=""){
		var id_data=id;
	}

	if(quality!=""){
		var quality_data=quality;
	}

	if(allowScriptAccess!=""){
		var allowScriptAccess_data=allowScriptAccess;
	}

	if (FullScreen != "") {
		var allowfullscreen_data_ie = '<param name="allowfullscreen" value=true>';
		var allowfullscreen_data_ns = ' allowfullscreen=true';
	}

	document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase= "http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version= '+version_data+'" width='+ancho+' height='+alto+' id='+id_data+'>\n');
	document.write('<param name="movie" value='+archivo+'>\n');
	document.write('<param name= "allowScriptAccess" value= '+allowScriptAccess_data+'>\n');
	document.write('<param name="quality" value='+quality_data+'>\n');
	document.write('<param name="FlashVars" value='+FlashVars+'>\n');
	document.write(bgcolor_ie);
	document.write(transparent_ie);
	document.write(allowfullscreen_data_ie);
	document.write(mode_ie);

	document.write('<param name="menu" value='+menu_data+' >\n');
	document.write('<embed src='+archivo+' '+bgcolor_ns+' '+transparent_ns+' ' + mode + ' FlashVars='+FlashVars+' menu='+menu_data+' allowScriptAccess='+allowScriptAccess_data+' quality='+quality_data+allowfullscreen_data_ns+' pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width='+ancho+' height='+alto+' swLiveConnect=true name='+id_data+'></embed>');
	document.write('</object>\n');
}