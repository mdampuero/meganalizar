function CustomMarker(latlng, map, args) {
	this.latlng = latlng;	
	this.args = args;	
	this.setMap(map);	
}

CustomMarker.prototype = new google.maps.OverlayView();

CustomMarker.prototype.draw = function() {
	
	var self = this;
	
	var div = this.div;
	
	if (!div) {
	
		div = this.div = document.createElement('div');
		div.style.position = 'absolute';
		div.style.cursor = '';
		div.style.width = '500px';
		div.style.height = '200px';

		indicador = this.div = document.createElement('div');
		indicador.className = 'marker';
		indicador.style.position = 'relative';
		indicador.style.display = 'inline-block';		
		indicador.style.cursor = 'pointer';
		indicador.innerHTML='<i class="material-icons">place</i>';

		tarj = this.div = document.createElement('div');
		tarj.style.position = 'relative';
		tarj.style.width = '500px';
		tarj.style.height = '0px';
		tarj.innerHTML=self.args.html;

		div.appendChild(indicador);
		div.appendChild(tarj);
		
		
		if (typeof(self.args.marker_id) !== 'undefined') {
			div.dataset.marker_id = self.args.marker_id;
		}
		
		google.maps.event.addDomListener(indicador, "click", function(event) {
			$(this).parent().parent().find('.tarjetaHorizontal').hide();
			$(this).parent().find('.tarjetaHorizontal').css('height','200px');
			$(this).parent().find('.tarjetaHorizontal').fadeIn('fast');
			google.maps.event.trigger(self, "click");

		});
		google.maps.event.addDomListener(indicador, "mouseover", function(event) {
			$(".marker").parent().css('z-index','0');
			$(this).parent().css('z-index','1');

			
		});
		
		var panes = this.getPanes();
		panes.overlayImage.appendChild(div);
		
	}
	
	var point = this.getProjection().fromLatLngToDivPixel(this.latlng);
	
	if (point) {
		div.style.left = (point.x - 10) + 'px';
		div.style.top = (point.y - 20) + 'px';
	}
};

CustomMarker.prototype.remove = function() {
	if (this.div) {
		this.div.parentNode.removeChild(this.div);
		this.div = null;
	}	
};

CustomMarker.prototype.getPosition = function() {
	return this.latlng;	
};