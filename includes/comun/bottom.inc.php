<script type="text/javascript">

/**
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-17943609-1']);
  _gaq.push(['_trackPageview']);
*/

	<?php
	/**
	 * Complemento Social, código para seguimiento social en Google Analytics
	 *
	 * Para agregar otras características de Google Analytics ver:
	 * http://code.google.com/apis/analytics/docs/tracking/asyncTracking.html
	 *
	 */
	?>

	<?php 
	/**
	 * Seguimiento en Facebook
	 * Mide el seguimiento de los "Me gusta", los "No me gusta", y las "Recomendar"
	 */

	/* FB: like */
	/*
	FB.Event.subscribe('edge.create', function(targetUrl) {
		_gaq.push(['_trackSocial', 'facebook', 'like', targetUrl]);
	});
	*/

	/* FB: unlike */
	/*
	FB.Event.subscribe('edge.remove', function(targetUrl) {
		_gaq.push(['_trackSocial', 'facebook', 'unlike', targetUrl]);
	});
	*/

	/* FB: share */ 
	/*
	FB.Event.subscribe('message.send', function(targetUrl) {
		_gaq.push(['_trackSocial', 'facebook', 'send', targetUrl]);
	});
	*/
	?>

	<?php
	/**
	 * Seguimiento en Twitter
	 * Mide el recomendar en Twitter
	 */

	/* Twitter */
	/*
	twttr.events.bind('tweet', function(event) {
		if (event) {
			var targetUrl;
			if (event.target && event.target.nodeName == 'IFRAME') {
				targetUrl = extractParamFromUri(event.target.src, 'url');
			}
			_gaq.push(['_trackSocial', 'twitter', 'tweet', targetUrl]);
		}
	});

	function extractParamFromUri(uri, paramName) {
		if (!uri) {
			return;
		}
		var uri = uri.split('#')[0];  // Remove anchor.
		var parts = uri.split('?');  // Check for query params.
		if (parts.length == 1) {
			return;
		}
		var query = decodeURI(parts[1]);

		paramName += '=';
		var params = query.split('&');
		for (var i = 0, param; param = params[i]; ++i) {
			if (param.indexOf(paramName) === 0) {
				return unescape(param.split('=')[1]);
			}
		}
	}
	*/ 
	?>  

/*	
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
*/
</script>


<?php
/* consulto si estoy en modo debug y cargo esta parte */
if (!empty($ss_config['debug_mode'])) { ?>


	<?php 
	/* FIREBUG IE */
	/* <script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script> */ ?>

	<script type="text/javascript">
		/**
		 * http://www.openjs.com/scripts/events/keyboard_shortcuts/
		 * Version : 2.01.B
		 * By Binny V A
		 * License : BSD
		 */
		shortcut = {
			'all_shortcuts':{},//All the shortcuts are stored in this array
			'add': function(shortcut_combination,callback,opt) {
				//Provide a set of default options
				var default_options = {
					'type':'keydown',
					'propagate':false,
					'disable_in_input':false,
					'target':document,
					'keycode':false
				}
				if(!opt) opt = default_options;
				else {
					for(var dfo in default_options) {
						if(typeof opt[dfo] == 'undefined') opt[dfo] = default_options[dfo];
					}
				}

				var ele = opt.target;
				if(typeof opt.target == 'string') ele = document.getElementById(opt.target);
				var ths = this;
				shortcut_combination = shortcut_combination.toLowerCase();

				//The function to be called at keypress
				var func = function(e) {
					e = e || window.event;
					
					if(opt['disable_in_input']) { //Don't enable shortcut keys in Input, Textarea fields
						var element;
						if(e.target) element=e.target;
						else if(e.srcElement) element=e.srcElement;
						if(element.nodeType==3) element=element.parentNode;

						if(element.tagName == 'INPUT' || element.tagName == 'TEXTAREA') return;
					}
			
					//Find Which key is pressed
					if (e.keyCode) code = e.keyCode;
					else if (e.which) code = e.which;
					var character = String.fromCharCode(code).toLowerCase();
					
					if(code == 188) character=","; //If the user presses , when the type is onkeydown
					if(code == 190) character="."; //If the user presses , when the type is onkeydown

					var keys = shortcut_combination.split("+");
					//Key Pressed - counts the number of valid keypresses - if it is same as the number of keys, the shortcut function is invoked
					var kp = 0;
					
					//Work around for stupid Shift key bug created by using lowercase - as a result the shift+num combination was broken
					var shift_nums = {
						"`":"~",
						"1":"!",
						"2":"@",
						"3":"#",
						"4":"$",
						"5":"%",
						"6":"^",
						"7":"&",
						"8":"*",
						"9":"(",
						"0":")",
						"-":"_",
						"=":"+",
						";":":",
						"'":"\"",
						",":"<",
						".":">",
						"/":"?",
						"\\":"|"
					}
					//Special Keys - and their codes
					var special_keys = {
						'esc':27,
						'escape':27,
						'tab':9,
						'space':32,
						'return':13,
						'enter':13,
						'backspace':8,
			
						'scrolllock':145,
						'scroll_lock':145,
						'scroll':145,
						'capslock':20,
						'caps_lock':20,
						'caps':20,
						'numlock':144,
						'num_lock':144,
						'num':144,
						
						'pause':19,
						'break':19,
						
						'insert':45,
						'home':36,
						'delete':46,
						'end':35,
						
						'pageup':33,
						'page_up':33,
						'pu':33,
			
						'pagedown':34,
						'page_down':34,
						'pd':34,
			
						'left':37,
						'up':38,
						'right':39,
						'down':40,
			
						'f1':112,
						'f2':113,
						'f3':114,
						'f4':115,
						'f5':116,
						'f6':117,
						'f7':118,
						'f8':119,
						'f9':120,
						'f10':121,
						'f11':122,
						'f12':123
					}
			
					var modifiers = { 
						shift: { wanted:false, pressed:false},
						ctrl : { wanted:false, pressed:false},
						alt  : { wanted:false, pressed:false},
						meta : { wanted:false, pressed:false}	//Meta is Mac specific
					};
								
					if(e.ctrlKey)	modifiers.ctrl.pressed = true;
					if(e.shiftKey)	modifiers.shift.pressed = true;
					if(e.altKey)	modifiers.alt.pressed = true;
					if(e.metaKey)   modifiers.meta.pressed = true;
								
					for(var i=0; k=keys[i],i<keys.length; i++) {
						//Modifiers
						if(k == 'ctrl' || k == 'control') {
							kp++;
							modifiers.ctrl.wanted = true;

						} else if(k == 'shift') {
							kp++;
							modifiers.shift.wanted = true;

						} else if(k == 'alt') {
							kp++;
							modifiers.alt.wanted = true;
						} else if(k == 'meta') {
							kp++;
							modifiers.meta.wanted = true;
						} else if(k.length > 1) { //If it is a special key
							if(special_keys[k] == code) kp++;
							
						} else if(opt['keycode']) {
							if(opt['keycode'] == code) kp++;

						} else { //The special keys did not match
							if(character == k) kp++;
							else {
								if(shift_nums[character] && e.shiftKey) { //Stupid Shift key bug created by using lowercase
									character = shift_nums[character]; 
									if(character == k) kp++;
								}
							}
						}
					}
					
					if(kp == keys.length && 
								modifiers.ctrl.pressed == modifiers.ctrl.wanted &&
								modifiers.shift.pressed == modifiers.shift.wanted &&
								modifiers.alt.pressed == modifiers.alt.wanted &&
								modifiers.meta.pressed == modifiers.meta.wanted) {
						callback(e);
			
						if(!opt['propagate']) { //Stop the event
							//e.cancelBubble is supported by IE - this will kill the bubbling process.
							e.cancelBubble = true;
							e.returnValue = false;
			
							//e.stopPropagation works in Firefox.
							if (e.stopPropagation) {
								e.stopPropagation();
								e.preventDefault();
							}
							return false;
						}
					}
				}
				this.all_shortcuts[shortcut_combination] = {
					'callback':func, 
					'target':ele, 
					'event': opt['type']
				};
				//Attach the function with the event
				if(ele.addEventListener) ele.addEventListener(opt['type'], func, false);
				else if(ele.attachEvent) ele.attachEvent('on'+opt['type'], func);
				else ele['on'+opt['type']] = func;
			},

			//Remove the shortcut - just specify the shortcut and I will remove the binding
			'remove':function(shortcut_combination) {
				shortcut_combination = shortcut_combination.toLowerCase();
				var binding = this.all_shortcuts[shortcut_combination];
				delete(this.all_shortcuts[shortcut_combination])
				if(!binding) return;
				var type = binding['event'];
				var ele = binding['target'];
				var callback = binding['callback'];

				if(ele.detachEvent) ele.detachEvent('on'+type, callback);
				else if(ele.removeEventListener) ele.removeEventListener(type, callback, false);
				else ele['on'+type] = false;
			}
		}
		/** End function keyboard_shortcuts */

	
		var adv_html = "<div id='adv_debug' style='position:absolute; top:0px; left:0px; z-index:1000; padding:20px; margin:10px; border:solid 1px #CCC; background:#FFF; display:none;'><form action='' id='adv_debug_form' method='get'>";
		adv_html+= "<fieldset>";
		adv_html+= "<legend ><h2>Debug Mode</h2> <br/>Ingrese descripción del error o faltante:</legend>";
		adv_html+= "<input type='hidden' name='url' value='<?php echo $_SERVER['REQUEST_URI'];?>' />";
		adv_html+= "<input type='hidden' name='nav' value='<?php echo $_SERVER['HTTP_USER_AGENT'];?>' />";
		adv_html+= "<input type='text' name='info' value='' size='70' /><br/><br/><input type='submit' name='descargar' value='Guardar' />";
		adv_html+= "</fieldset>";
		adv_html+= "</form></div>"
		$("body").append(adv_html);

		shortcut.add("F1",function() {
			$("#adv_debug").show();
			$("#adv_debug_form input[name='info']").focus();
		});
		
		shortcut.add("F2",function() {
			ventana('/debug/index.php?mode=bugtracker', 'bugtracker', 400,400,'yes');
		});		
		
		$("#adv_debug_form").submit(function() {
			$.ajax({
				type: "POST",
				async: false,
				url: "/debug/log.php",
				data: $("#adv_debug_form").serialize(),
				success: function(msg){
					$("#adv_debug_form input[name='info']").val('');
					$("#adv_debug").fadeOut();
				}
			});
			return false;
		});
	</script>

<?php } ?>

</body>
</html>