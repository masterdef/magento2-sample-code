function gmap_initialize() {
  var mapProp = {
    center: new google.maps.LatLng(50.4571663, 30.5072074),
    zoom: 4,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    disableDefaultUI: false,
    scrollwheel: false,
    navigationControl: false,
    mapTypeControl: false,
    scaleControl: false
  };

  var map = new google.maps.Map(document.getElementById("map"), mapProp);

  map.set('styles', [{
    featureType: 'poi',
    stylers: [{
      visibility: "off"
    }]
  }]);

  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(50.4571663, 30.5072074),
    map: map,
    title: 'Hi!'
  });
}

gmap_initialize();
