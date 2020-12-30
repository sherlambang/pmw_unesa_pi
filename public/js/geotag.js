//input image kamera


var camera = document.getElementById('camera');
var frame = document.getElementById('frame');
var	longitude = getElementById('longitude');
var latitude = getElementById('latitude');


camera.addEventListener('change', function(e) {
    var file = e.target.files[0]; 
    // Do something with the image file.
    getLocation();
    frame.src = URL.createObjectURL(file);
});


function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    longitude.value = 0;
  latitude.value = 0;
  }
}

function showPosition(position) {
	longitude.value = position.coords.longitude;
	latitude.value = position.coords.latitude;
}
