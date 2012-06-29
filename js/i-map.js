imageFolder = "/images/";

jQuery(document).ready(function() {
	InitImaps();
});

// Initialise.
function InitImaps() {

	$(".i-map-progress").hide();
	
	SetBaseIcon();
	var controls = "";
	$(".i-map").each(function() {

	    if (this.nodeName == "UL") {
	        controls = "large";
	        gMap = CreateGMap($(".g-map-container").get(0), controls);
	    }
	});
	
	SetCollectionLinks(gMap);
	SetCategoryLinks(gMap);

	$(".i-map-collection:visible").each(function() {
		PlotCategory(this, gMap);
	});
	
	$(".i-map-progress").each(function() {
		$(this).hide();
	});
	

	// Oculto el preload y muestro el contenedor principal
	$(".i-map-progress-contenedor").hide();
}

// setup layout / actions for the collections menu
function SetCollectionLinks(gMap) {

	$(".i-map-collection li h3").each(function() {
		$(this).wrapInner("<a href='javascript:;;' ></a>");
	});
	
	$(".i-map-collection li h3 a").each(function() {
		$(this).wrapInner("<span class='txt'></span>");
	});
	
	$(".i-map-collection li h3 a").click(function() {

		// Remuevo a todas la clase selected
		$(".i-map-collection li h3 a").removeClass("selected");
		
		// A la que esta seleccionada le agrego la clase selected
		$(this).addClass("selected");
		
		datamark = $(this).parent().parent();
		if (gMap) {
			GetPoint(datamark, function(point) {
				marker = new GMarker(point);
				displayMyPoint(marker, gMap, datamark);
			});
		} else {
			alert("error: El mapa no se ha creado");
		}
		return false;
	});
	
	// hide location data except for the titles
	$(".i-map-collection li *").addClass("hidden");
	$(".i-map-collection li .location-name").show();
	$(".i-map-collection li .location-name a").show();
}

//setup all the animation for the categories
function SetCategoryLinks(gMap) {
	$(".i-map .i-map-collection").hide();
	$(".i-map .cat-name").wrapInner("<a href='javascript:;;'></a>").append("<a class=\"expand\" title=\"Expandir\" href=\"javascript:;;\">+</a>");
	$(".i-map .cat-name").hover(function() {
	    $(this).addClass("hover");
	}, function() {
	    $(this).removeClass("hover");
	});
	
	$(".i-map .cat-name a").click(function() {

	    $(".i-map-progress").fadeIn();
		
	    $(".i-map .cat-name a.selected").removeClass("selected");
		
	    $(".i-map .cat-name a.collapse").remove();
		
	    $(".i-map .cat-name a").parent().siblings(".i-map-collection").hide();
		
	    $(".i-map .cat-name a.expand").show();
		
	    $(this).parent().children("a[class!=expand]").addClass("selected");
		
	    $(this).parent().children("a.expand").hide();

	    $(this).parent().siblings(".i-map-collection").show();
		
		
	    PlotCategory($(this).parent().siblings(".i-map-collection"), gMap);
		
	    var fullList = $(this).parent().parent().parent()[0];
		
	    if (fullList.clientHeight < fullList.scrollHeight) {
	        // has scrollbar - add border
	        // $(".i-map").children("li:last").css({ "border-bottom": "1px solid #D4DCE0" });
	    } else {
	        // $(".i-map").children("li:last").css({ "border-bottom": "none" });
	    }

	    //add collapse option and functionality under .cat-name
	    $(this).parent().append("<a class=\"collapse\" title=\"Collapse\" href=\"javascript:;;\">-</a>").click(function() {
	        DefaultView(gMap);
	        $(".i-map-collection").hide();
	        $(".i-map .cat-name").removeClass("hover");
	        $(".i-map .cat-name a.selected").removeClass("selected");
	        $(".i-map .cat-name a.collapse").remove();
	        $(".i-map .cat-name a.expand").show();
	        return false;
	    });
		
	    return false;
	});
}

// initialise the single point map with driving directions.
function SetSingleMap(theiMap) {
	GetPoint($(theiMap), function(point) {
		if (!point) {
			$(theiMap).siblings(".g-map-container").remove();
			$(theiMap).after("<p class='i-map-error'>No se puede encontrar la ubicación</p>");
		} else {
			theiMap.gMap.setCenter(point, 15);
			PlotMarker(theiMap, 1, point);
		}
	});
	InitDirections(theiMap);
	$(theiMap).hide();
}

// Create GMap2 object with appropriate settings.
function CreateGMap(_domTarget, _config) {

	if (GBrowserIsCompatible()) {
		var gMap = new GMap2(_domTarget);
		DefaultView(gMap) 
		switch (_config) {
		    case "small":
		        gMap.addControl(new GSmallZoomControl());
		        gMap.addMapType(G_PHYSICAL_MAP);
		        gMap.addControl(new GMapTypeControl(true)); // true=short names
		        break;
		    case "large":
		        gMap.addControl(new GLargeMapControl());
		        gMap.enableScrollWheelZoom();
		        gMap.addMapType(G_PHYSICAL_MAP);
		        gMap.addControl(new GMapTypeControl(true)); // true=short names

		        // these layers need to be initialised manually. The 'more...' button is not part of the API (yet);
		        // map.addOverlay(new GLayer("com.panoramio.all"));
		        // map.addOverlay(new GLayer("org.wikipedia.en"));
		        break;
			default:
				// no controls
				break;
		}
		return gMap;
	}
}

function DefaultView(gMap) {
    gMap.clearOverlays();
	gMap.setCenter(new GLatLng(-31.7333, -60.5293), 12);
}

//called when opening a category on a collection, this will Plot ALL the points/areas/lines within the given category.
//this also generates all the LHS menu items within this collection.
function PlotCategory(_collection, theMap) {

	theMap.accBounds = new GLatLngBounds();
	theMap.clearOverlays();
	
	$("#message").hide();

	var n = 1;
	var returned = 0;
	var rendered = false;
	var total = $(_collection).children("li").length;

	$(_collection).children("li").each(function() {

	    var data = this;
	    var iconNo = n++;

	    // style links in sidebar
	    // var smallIconPath = imageFolder + "markers/smallMarkers/marker" + iconNo + ".gif";
	    var theLink = $(this).find("a:first");
		
	    // theLink.css({ "background": "url(" + smallIconPath + ") no-repeat" });
	    if($(this).find(".print-num").length==0)
			theLink.prepend("<span class='print-num'>" + iconNo + "</span>");
			
			
			// Agregado Clear por si el texto es mayor a 3 renglones
			theLink.append("<div class='clear'></div>");
			
			// Agregado Alexis Lesa 04.04.2010
			// theLink.prepend("<em class='li-map'></em>");
			
	    
	    // draw overlay element depending on whether we have a line, an area, or a point
	    if ($(this).find(".geo-line").length > 0) {

			PlotLine(data, iconNo, theMap);
	        returned++;
	    } else if ($(this).find(".geo-area").length > 0) {
	        PlotArea(data, iconNo, theMap);
	        returned++;
	    } else {
	        GetPoint($(this), function(point) {
	            returned++;
	            // cache point on <li> to avoid repeat requests
	            data.centre = point;
	            if (point) {
	                PlotMarker(data, iconNo, point, theMap);
	                theMap.accBounds.extend(point);
	            }
	            else {
	                theLink.css({ "background": "url(" + imageFolder + "icono_error.gif) no-repeat" }); // set error icon as bg
	            }
	            if (returned == total) {
	                FinishedLoading(theMap);
	                rendered = true;
	            }
	        });
	    }
	});

	if (returned == total && !rendered) {
	    FinishedLoading(theMap);
	}
}

function FinishedLoading(theMap) {

	$(".i-map-progress").fadeOut();

	theMap.setZoom(theMap.getBoundsZoomLevel(theMap.accBounds));
	theMap.setCenter(FindCentre(theMap.accBounds));
}

function InitDirections(_iMap) {
	$(_iMap).after(ReturnFormHtml());

	var theForm = $(_iMap).siblings(".directions-form-container:first")[0];
	if ($(_iMap).attr("title") != "") {
		$(theForm).prepend("<h2>" + $(_iMap).attr("title") + "</h2>");
	}
	// display the title if there is one (title will not be used for getting the driving directions)
	if ($(_iMap).attr("title").length > 0)
		$(theForm).find("#to-input").val($(_iMap).attr("title"));
	else if ($(_iMap).find(".geo").length > 0)
		$(theForm).find("#to-input").val($(_iMap).find(".geo .latitude:first").text()+","+$(_iMap).find(".geo .longitude:first").text());
	else
		$(theForm).find("#to-input").val($(_iMap).find(".street-address:first").text());
	theForm.directions = new GDirections(_iMap.gMap, $(theForm).find(".directions-output")[0]);
	GEvent.addListener(theForm.directions, "load", function() { onGDirectionsLoad(_iMap.gMap); });
	GEvent.addListener(theForm.directions, "error", function() { HandleErrors(theForm.directions);});
	$(theForm).find("#go-button").click(function() {
		GetDirections($(this).parents(".directions-form-container")[0]);
		return false;
	});
	$(theForm).find("#from-input, #to-input").keydown(function(ev) {
		if (ev.keyCode == 13) {
			ev.preventDefault();
			ev.stopPropagation();
			$(theForm).find("#go-button").click();
			return false;
		}
	});
}

// when get driving directions has been clicked.
function GetDirections(_theForm) {
	var defaultToAddress;
	var toInput = $(_theForm).find("#to-input").val();
	if ($(_theForm).parent().find(".geo").length > 0)
		defaultToAddress = $(_theForm).parent().find(".geo .latitude:first").text() + "," + $(_theForm).parent().find(".geo .longitude:first").text();
	else
		defaultToAddress = $(_theForm).parent().find(".street-address:first").text();

	// if the title of the map is in the to-input box then use the defaultToAddress (from the .geo or .street-address node.)
	if (toInput == $(_theForm).parent().find(".i-map:first").attr("title"))
		toAddress = defaultToAddress;
	else
		toAddress = toInput;
	
	_theForm.directions.load("from: " + GetDrivingLocation($(_theForm).find("#from-input").val()) + " to: " + GetDrivingLocation(toAddress), {});
}

function GetDrivingLocation(text){
	if(/^\-?\d+(\.\d+)?,\-?\d+(\.\d+)?$/.test(text))
		return text; // don't alter the location if it's a coordinate.
	else
		return text + " Entre Ríos, Argentina"; //
}

// when driving directions have loaded
function onGDirectionsLoad(gMap) {
	gMap.clearOverlays();
}

// given a dom element containing geo or adr data, determine the 'point' location and fire the callback function.
function GetPoint(_target, callback) {
	//_target is a dom element: from an i-map-collection to trawl for location information
	if (_target.centre) {
		// if we have already made a request for this point use that, otherwise use the geocoder

		callback(_target.centre);
	} else {
		// check if markup data includes lat / long
		if ($(_target).find(".geo").length > 0) {

			var lat = $(_target).find(".geo:first .latitude").text();
			var lng = $(_target).find(".geo:first .longitude").text();

			callback(new GLatLng(lat, lng));
		}
		// otherwise we'll need to use the API geocoder to get a lat / long
		else if ($(_target).find(".street-address").length > 0 || $(_target).find(".locality").length > 0) {

			var streetAddress = $(_target).find(".street-address:first").text();
			var locality = $(_target).find(".locality:first").text();
			var country = $(_target).find(".country-name:first").text();
			var fullAddress = streetAddress + ", " + locality + ", " + country;
			var geocoder = new GClientGeocoder();
			geocoder.getLatLng(fullAddress, callback);
		} else {

			callback(null);
			//alert("no location data provided in markup!");
		}
	}
}

// Given the dom element work out the html to render in the info-window
function BuildWindowContent(_target) {
	// copy the html from the dom
	var tempHtml = $(_target).find(".solo-popup").html();
	// alert(tempHtml);
	// remove the javascript links (from the title which opens then info-window)
	
	tempHtmlTitulo = tempHtml.replace(/<a[^>]*href=["']javascript:;;['"][^>]*><span[^>]*class=['"]?print-num['"]?[^>]*>[^<]*<\/span>(.*)<\/a>/i, '$1');
	// wrap the html in an info-window div.
	// alert(tempHtmlTitulo);
	
	tempHtml = "<div class='info-window'>" + tempHtmlTitulo + "</div>";
	return tempHtml;
}

// Create the HTML which makes up the driving directions input fields
function ReturnFormHtml() {
	var theHtml = "<div class='directions-form-container'><h3>Directions</h3><label>From: <input type='text' id='from-input' value='' /></label><label>To: <input type='text' id='to-input' value='' /></label><input type='button' value='Go' id='go-button' class='button' /><div class='directions-output'></div></div>";
	return theHtml;
}

// Generate readable error explanations.
function HandleErrors(directions) {
	if (directions.getStatus().code == G_GEO_UNKNOWN_ADDRESS)
		alert("No corresponding geographic location could be found for one of the specified addresses. This may be due to the fact that the address is relatively new, or it may be incorrect.");
	else if (directions.getStatus().code == G_GEO_SERVER_ERROR)
		alert("A geocoding or directions request could not be successfully processed, yet the exact reason for the failure is not known.");
	else if (directions.getStatus().code == G_GEO_MISSING_QUERY)
		alert("The HTTP q parameter was either missing or had no value. For geocoder requests, this means that an empty address was specified as input. For directions requests, this means that no query was specified in the input.");
	//   else if (directions.getStatus().code == G_UNAVAILABLE_ADDRESS)  <--- Doc bug... this is either not defined, or Doc is wrong
	//     alert("The geocode for the given address or the route for the given directions query cannot be returned due to legal or contractual reasons.");
	else if (directions.getStatus().code == G_GEO_BAD_KEY)
		alert("The given key is either invalid or does not match the domain for which it was given.");
	else if (directions.getStatus().code == G_GEO_BAD_REQUEST)
		alert("A directions request could not be successfully parsed.");
	else alert("An unknown error occurred. Possible reasons; No route, Ambiguous location names");
}

function SetSelected(_target) {
	$(".i-map-collection li h3 a").removeClass("selected");
	$(_target).addClass("selected");
}

// setup a global scope object to re-use whenever creating a marker
function SetBaseIcon() {
	baseIcon = new GIcon();
	// baseIcon.shadow = imageFolder+"markers/marker_shadow.png";
	baseIcon.iconSize = new GSize(31, 30);
	// baseIcon.shadowSize = new GSize(52, 28);
	baseIcon.iconAnchor = new GPoint(14, 28);
	baseIcon.infoWindowAnchor = new GPoint(9, 2);
	baseIcon.infoShadowAnchor = new GPoint(18, 25);
}

// draw the marker on the map
function PlotMarker(data, iconNo, point, theMap) {
	// var theMap = FindMap(data);
	// var iconPath = imageFolder+"markers/marker" + iconNo + ".png";
	var iconPath = "/images/map/" + iconNo + ".gif";
	var icon = new GIcon(baseIcon);
	icon.image = iconPath;
	var marker = new GMarker(point, icon);
	theMap.addOverlay(marker);
	GEvent.addListener(marker, "click", function() {
	
		// Modificado por Alexis, la idea es poder mostrar el popup con estilo propio
		// marker.openInfoWindow(BuildWindowContent($(data)));
		displayMyPoint(marker, theMap, $(data));
		SetSelected($(data).find("a:first"));
	});
	
	$("#message").appendTo(theMap.getPane(G_MAP_FLOAT_SHADOW_PANE));

	if (!data.marker)
		data.marker = marker;
}

function displayMyPoint(marker, map, data) {
	$("#message").hide();
	
	if (data) {
		var info = BuildWindowContent($(data));
		$("#contenido").html(info);
	}
					
	var moveEnd = GEvent.addListener(map, "moveend", function(){
		var markerOffset = map.fromLatLngToDivPixel(marker.getLatLng());
		$("#message")
			.fadeIn()
			.css({ top:markerOffset.y-142, left:markerOffset.x-14 }); //194
	
		GEvent.removeListener(moveEnd);
	});
	map.panTo(marker.getLatLng());
}

// draw an area on the map
function PlotArea(data, iconNo, theMap) {
	// var theMap = FindMap(data);
	AttachDataToDom(data);

	if (data.pointArray[0] != data.pointArray[data.pointArray.length - 1])
		data.pointArray[data.pointArray.length] = data.pointArray[0];

	var polygon = new GPolygon(data.pointArray, "#006BB7", 3, 1, "#006BB7", 0.2);

	theMap.addOverlay(polygon);
	PlotMarker(data, iconNo, data.centre);
}

// draw a line on the map
function PlotLine(data, iconNo, theMap) {
	// var theMap = FindMap(data);
	AttachDataToDom(data);
	var line = new GPolyline(data.pointArray, "#006BB7", 3, 1, "#006BB7", 0.2);
	theMap.addOverlay(line);
	PlotMarker(data, iconNo, data.pointArray[0]);
	PlotMarker(data, iconNo, data.pointArray[data.pointArray.length - 1]);
}

//Gather all the points in an area or line and attach them and relevant derived data to the dom for easy access.
function AttachDataToDom(data) {
	var theMap = FindMap(data);
	if (true) {
		data.pointArray = new Array;
		var i = 0;
		$(data).find(".geo-line > .geo, .geo-area > .geo").each(function() {
			var lat = $(this).find(".latitude").text();
			var lng = $(this).find(".longitude").text();
			var point = new GLatLng(lat, lng);
			data.pointArray[i] = point;
			theMap.accBounds.extend(point);
			i++;
		});
	}
	$(data).andse
	data.bounds = FindBounds(data.pointArray);
	data.centre = FindCentre(data.bounds);
	data.zoom = theMap.getBoundsZoomLevel(data.bounds);
	data.zoom--; // areas & lines need to zoom one more step for aesthetic reasons & so info window does not push the plotted element off the screen
}

// Create a GlatLngBounds object for the given collection of points
function FindBounds(pointArray) {
	var bounds = new GLatLngBounds();
	for (var i = 0; i < pointArray.length; i++) {
		bounds.extend(pointArray[i]);
	}
	return bounds;
}

// Find the Centre given a GlatLngBounds object (the built in function doesn't seem to work)
function FindCentre(bounds) {
	var clat = (bounds.getNorthEast().lat() + bounds.getSouthWest().lat()) / 2; // get lat & long for center of rectangle described by bounds
	var clng = (bounds.getNorthEast().lng() + bounds.getSouthWest().lng()) / 2;
	return new GLatLng(clat, clng); 									// set new center
}

// Zoom and centre 'theMap' to contain the given GlatLngBounds object
function SetViewFrame(bounds, theMap) {
	theMap.setZoom(theMap.getBoundsZoomLevel(bounds)); 						    // set the zoom to accomodate all marked points
	theMap.setCenter(FindCentre(bounds)); 									// set new center
}

// Find the GMap2 object by recursing up the dom tree to find where it has been attached.
function FindMap(data) {
    if (data.gMap)
        return data.gMap;
    if ($(data).parent().length > 0)
        return FindMap($(data).parent()[0]);
    return null;
}