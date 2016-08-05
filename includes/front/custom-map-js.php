<?php
	$cenetr_lat = get_post_meta( $post_ID, MAP_PREFIX .'center_latitude', true );
	$cenetr_long = get_post_meta( $post_ID, MAP_PREFIX .'center_longitude', true );
	$latitude = get_post_meta( $post_ID, MAP_PREFIX .'latitude', true );
	$longitude = get_post_meta( $post_ID, MAP_PREFIX .'longitude', true );
	$marker = get_post_meta( $post_ID, MAP_PREFIX .'marker', true );
	$marker_icon = wp_get_attachment_url( $marker );
	$height = get_post_meta( $post_ID, MAP_PREFIX .'height', true );
	$zoom_level = get_post_meta( $post_ID, MAP_PREFIX .'zoom_level', true );
	$address = get_post_meta( $post_ID, MAP_PREFIX .'address', true );
	$draggable = get_post_meta( $post_ID, MAP_PREFIX .'draggable', true );
	$scrollwheel = get_post_meta( $post_ID, MAP_PREFIX .'scrollwheel', true );
	$zoomControl = get_post_meta( $post_ID, MAP_PREFIX .'zoomcontrol', true );
	$disableDoubleClickZoom = get_post_meta( $post_ID, MAP_PREFIX .'disabledoubleclickzoom', true );
	$multi_address = get_post_meta( $post_ID, MAP_PREFIX . 'multi_address', true );
?>
<script type="text/javascript">
	function initMap() {
		var cenetr_lat = parseFloat('<?php echo intval($cenetr_lat);?>');
		var cenetr_long = parseFloat('<?php echo intval($cenetr_long);?>');
		var latitude = parseFloat('<?php echo intval($latitude);?>');
		var longitude = parseFloat('<?php echo intval($longitude);?>');
		var zoom_level = parseInt('<?php echo intval($zoom_level);?>');
		var scrollwheel = parseInt('<?php echo $scrollwheel;?>');
		var zoomControl = parseInt('<?php echo $zoomControl;?>');
		var disableDoubleClickZoom = parseInt('<?php echo $disableDoubleClickZoom;?>');
		var draggable = parseInt('<?php echo $draggable;?>');
		var marker_icon = '<?php echo $marker_icon;?>';
		var multiaddress = '<?php echo json_encode($multi_address);?>';
		multiaddress = jQuery.parseJSON(multiaddress);

	  	var myLatLng = {lat: cenetr_lat, lng: cenetr_long};

	  	var map = new google.maps.Map(document.getElementById('map'), {
	    	zoom: zoom_level,
	    	center: myLatLng,
	    	scrollwheel: scrollwheel,
		    zoomControl: zoomControl,
		    disableDoubleClickZoom: disableDoubleClickZoom,
		    draggable:draggable,
		    disableDefaultUI: true
	  	});

	  	jQuery(multiaddress).each(function(index, el) {
	  		var position = {lat: parseFloat(el.mapitem_latitude), lng: parseFloat(el.mapitem_longitude)};
			var infowindow = new google.maps.InfoWindow({
			   content: el.mapitem_address
			});

		  	var marker = new google.maps.Marker({
		    	position: position,
		    	map: map,
		    	title: el.mapitem_address,
		    	icon: marker_icon
		  	});

		  	marker.addListener('click', function() {
			   infowindow.open(map, marker);
			});
	  	});

	}
</script>