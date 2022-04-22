var marker = null;
var latitud=-34.6094269;
var longitud=-58.386271;
var map;
var overlay;
var indice;
var marcadores={
	0:{
		nombre:'GOLFO SAN JORGE',
		descripcion:'Cerro Dragón, el principal yacimiento petrolero del país.',
		img:'images/operacionsustentable/nuestras_operaciones/card_GSJ.jpg',
		lat:-46.1217517,
		lon:-66.6714394

	},
	1:{
		nombre:'NOROESTE ARGENTINO',
		descripcion:'Producción modelo en medio de la selva de yungas.',
		img:'images/operacionsustentable/nuestras_operaciones/card_NOA.jpg',
		lat:-24.693336,
		lon:-63.6422508
	},
	2:{
		nombre:'NEUQUINA',
		descripcion:'El desarrollo de los no convencionales.',
		img:'images/operacionsustentable/nuestras_operaciones/card_NEUQUINA.jpg',
		lat:-38.5556767,
		lon:-70.2237729
	},
	3:{
		nombre:'MARINA AUSTRAL',
		descripcion:'Extracción del gas natural costas afuera.',
		img:'images/operacionsustentable/nuestras_operaciones/card_marina.jpg',
		lat:-47.1387969,
		lon:-66.2208631
	},
	4:{
		nombre:'TARIJA',
		descripcion:'Socios en el consorcio que opera el área Caipipendi',
		img:'images/operacionsustentable/nuestras_operaciones/americaSur/tarija.jpg',
		lat:-49.1387969,
		lon:-69.2208631
	}
};


function inicializarMapaSanIsidro() {
	var mapOptions = {
		center: new google.maps.LatLng(-34.6037389,-58.3815704),
		zoom: 10,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		scrollwheel: false
	};
	map = new google.maps.Map(document.getElementById("mapaSanIsidro"),mapOptions);


	map.addListener('center_changed', function() {
		dibujarMarcadoresSanIsidro(indice);
	});
	map.addListener('zoom_changed', function() {
		dibujarMarcadoresSanIsidro(indice);
	});

	dibujarMarcadoresSanIsidro(indice);
}

function dibujarMarcadoresSanIsidro(indice){
	var htmlText;
	LimpiarMarcadores();

	for (i in marcadores) {
		var pos = new google.maps.LatLng(marcadores[i]['lat'],marcadores[i]['lon']);

		htmlText='';

		overlay = new CustomMarker(
			pos, 
			map,
			{
				marker_id: i,
				html: htmlText
			}
			);	

	}
	

}

function LimpiarMarcadores() {
	$(".marker").parent().remove();
}
