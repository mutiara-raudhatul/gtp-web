let baseUrl = '';
let currentUrl = '';
let currentLat = 0, currentLng = 0
let userLat = 0, userLng = 0;
let web, map;
let infoWindow = new google.maps.InfoWindow();
let userInfoWindow = new google.maps.InfoWindow();
let directionsService, directionsRenderer;
let userMarker = new google.maps.Marker();
let destinationMarker = new google.maps.Marker();
let routeArray = [], circleArray = [], markerArray = {};
let bounds = new google.maps.LatLngBounds();
let selectedShape, drawingManager = new google.maps.drawing.DrawingManager();
let customStyled = [
    {
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative.land_parcel",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative.neighborhood",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    }
];

const formatter = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
});

function setBaseUrl(url) {
    baseUrl = url;
}

// Initialize and add the map
function initMap(lat = -0.7102134517843606, lng = 100.19420485758688) {
    directionsService = new google.maps.DirectionsService();
    const center = new google.maps.LatLng(lat, lng);
    map = new google.maps.Map(document.getElementById("googlemaps"), {
        zoom: 17,
        center: center,
        mapTypeId: 'roadmap',
    });
    var rendererOptions = {
        map: map
    }
    map.set('styles', customStyled);
    directionsRenderer = new google.maps.DirectionsRenderer(rendererOptions);
    digitVillage();
}

// Display tourism village digitizing
function digitVillage() {
    const digitasi = new google.maps.Data();
    $.ajax({
        url: baseUrl + '/api/village',
        type: 'POST',
        data: {
            digitasi: 'GTP01'
        },
        dataType: 'json',
        success: function (response) {
            const data = response.data;
            digitasi.addGeoJson(data);
            digitasi.setStyle({
                fillColor:'#00b300',
                strokeWeight:0.5,
                strokeColor:'#ffffff',
                fillOpacity: 0.1,
                clickable: false
            });
            digitasi.setMap(map);
        }
    });
}

function initMap2() {
    initMap();
    digitTracking();
}

function initMap3() {
    initMap();
    digitTalao();
}

function initMap4(lat = -0.7005628110637381, lng = 100.19529523662331) {
    directionsService = new google.maps.DirectionsService();
    const center = new google.maps.LatLng(lat, lng);
    map = new google.maps.Map(document.getElementById("googlemaps"), {
        zoom: 15,
        center: center,
        mapTypeId: 'roadmap',
    });
    var rendererOptions = {
        map: map
    }
    map.set('styles', customStyled);
    directionsRenderer = new google.maps.DirectionsRenderer(rendererOptions);
    digitNagari();
}

// Display nagari digitizing
function digitNagari() {
    const digitasi = new google.maps.Data();
    $.ajax({
        url: baseUrl + '/api/village',
        type: 'POST',
        data: {
            digitasi: 'V0001'
        },
        dataType: 'json',
        success: function (response) {
            const data = response.data;
            digitasi.addGeoJson(data);
            digitasi.setStyle({
                fillColor:'#00b300',
                strokeWeight:0.5,
                strokeColor:'#ffffff',
                fillOpacity: 0.1,
                clickable: false
            });
            digitasi.setMap(map);
        }
    });
}

// Remove user location
function clearUser() {
    userLat = 0;
    userLng = 0;
    userMarker.setMap(null);
}

// Set current location based on user location
function setUserLoc(lat, lng) {
    userLat = lat;
    userLng = lng;
    currentLat = userLat;
    currentLng = userLng;
}

// Remove any route shown
function clearRoute() {
    for(i in routeArray) {
        routeArray[i].setMap(null);
    }
    routeArray = [];
    $('#direction-row').hide();
}

// Remove any radius shown
function clearRadius() {
    for (i in circleArray) {
        circleArray[i].setMap(null);
    }
    circleArray = [];
}

// Remove any marker shown
function clearMarker() {
    for (i in markerArray) {
        markerArray[i].setMap(null);
    }
    markerArray = {};
}

// Get user's current position
function currentPosition() {
    clearRadius();
    clearRoute();

    google.maps.event.clearListeners(map, 'click');
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                infoWindow.close();
                clearUser();
                markerOption = {
                    position: pos,
                    animation: google.maps.Animation.DROP,
                    map: map,
                };
                userMarker.setOptions(markerOption);
                userInfoWindow.setContent("<p class='text-center'><span class='fw-bold'>You are here.</span> <br> lat: " + pos.lat + "<br>long: " + pos.lng+ "</p>");
                userInfoWindow.open(map, userMarker);
                map.setCenter(pos);
                setUserLoc(pos.lat, pos.lng);

                userMarker.addListener('click', () => {
                    userInfoWindow.open(map, userMarker);
                });
            },
            () => {
                handleLocationError(true, userInfoWindow, map.getCenter());
            }
        );
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, userInfoWindow, map.getCenter());
    }
}

// Error handler for geolocation
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(
        browserHasGeolocation
            ? "Error: The Geolocation service failed."
            : "Error: Your browser doesn't support geolocation."
    );
    infoWindow.open(map);
}

// User set position on map
function manualPosition() {

    clearRadius();
    clearRoute();

    if (userLat == 0 && userLng == 0) {
        Swal.fire('Click on Map');
    }
    map.addListener('click', (mapsMouseEvent) => {

        infoWindow.close();
        pos = mapsMouseEvent.latLng;

        clearUser();
        markerOption = {
            position: pos,
            animation: google.maps.Animation.DROP,
            map: map,
        };
        userMarker.setOptions(markerOption);
        userInfoWindow.setContent("<p class='text-center'><span class='fw-bold'>You are here.</span> <br> lat: " + pos.lat().toFixed(8) + "<br>long: " + pos.lng().toFixed(8)+ "</p>");
        userInfoWindow.open(map, userMarker);

        userMarker.addListener('click', () => {
            userInfoWindow.open(map, userMarker);
        });

        setUserLoc(pos.lat().toFixed(8), pos.lng().toFixed(8))
    });
}

// Render route on selected object
function routeTo(lat, lng, routeFromUser = true) {

    clearRadius();
    clearRoute();
    google.maps.event.clearListeners(map, 'click')

    let start, end;
    if (routeFromUser) {
        if (userLat == 0 && userLng == 0) {
            return Swal.fire('Determine your position first!');
        }
        setUserLoc(userLat, userLng);
    }
    start = new google.maps.LatLng(currentLat, currentLng);
    end = new google.maps.LatLng(lat, lng)
    let request = {
        origin: start,
        destination: end,
        travelMode: 'DRIVING'
    };
    directionsService.route(request, function(result, status) {
        if (status == 'OK') {
            directionsRenderer.setDirections(result);
            showSteps(result);
            directionsRenderer.setMap(map);
            routeArray.push(directionsRenderer);
        }
    });
    boundToRoute(start, end);
}

// route between two sets of coordinates
function routeAllActivitiesInDay(activities) {
    for (let i = 0; i < activities.length - 1; i++) {
        routeBetweenObjects(
            activities[i].lat, activities[i].lng,
            activities[i + 1].lat, activities[i + 1].lng
        );
    }
}

// route between two sets of coordinates
function routeBetweenObjects(startLat, startLng, endLat, endLng) {
    clearRadius();
    clearRoute();
    google.maps.event.clearListeners(map, 'click');
    
    // Create LatLng objects for the start and end coordinates
    const start = new google.maps.LatLng(startLat, startLng);
    const end = new google.maps.LatLng(endLat, endLng);
    
    let request = {
        origin: start,
        destination: end,
        travelMode: 'DRIVING'
    };

    directionsService.route(request, function(result, status) {
        if (status == 'OK') {
            directionsRenderer.setDirections(result);
            showSteps(result);
            directionsRenderer.setMap(map);
            routeArray.push(directionsRenderer);
        }
    });

    boundToRoute(start, end);
}

// Display tourism attraction digitizing
// Tracking Mangrove
function digitTracking() {
    const digitasi = new google.maps.Data();
    $.ajax({
        url: baseUrl + '/api/village',
        type: 'POST',
        data: {
            digitasi: 'A0001'
        },
        dataType: 'json',
        success: function (response) {
            const data = response.data;
            digitasi.addGeoJson(data);
            digitasi.setStyle({
                fillColor:'#FF0000',
                strokeWeight:0.8,
                strokeColor:'#FF0000',
                fillOpacity: 0.35,
                clickable: false
            });
            digitasi.setMap(map);
        }
    });
}

// Estuaria/Talao
function digitTalao() {
    const digitasi = new google.maps.Data();
    $.ajax({
        url: baseUrl + '/api/village',
        type: 'POST',
        data: {
            digitasi: 'A0002'
        },
        dataType: 'json',
        success: function (response) {
            const data = response.data;
            digitasi.addGeoJson(data);
            digitasi.setStyle({
                fillColor:'#0001ff',
                strokeWeight:0.5,
                strokeColor:'#ffffff',
                fillOpacity: 0.1,
                clickable: false
            });
            digitasi.setMap(map);
        }
    });
}

// Display marker for loaded object
function objectMarker(id, lat, lng, anim = true) {

    google.maps.event.clearListeners(map, 'click');
    let pos = new google.maps.LatLng(lat, lng);
    let marker = new google.maps.Marker();

    let icon;
    if (id.substring(0,3) === "GTP") {
        icon = baseUrl + '/media/icon/gtp.png';
    } else if (id.substring(0,1) === "A") {
        if(id === "A0001") {
            icon = baseUrl + '/media/icon/tracking.png';
        } else {
            icon = baseUrl + '/media/icon/talao.png';
        }
    } else if (id.substring(0,2) === "EV") {
        icon = baseUrl + '/media/icon/event.png';
    } else if (id.substring(0,1) === "P") {
        icon = baseUrl + '/media/icon/package.png';
    } else if (id.substring(0,2) === "HO") {
        icon = baseUrl + '/media/icon/homestay.png';
    } else if (id.substring(0,2) === "CP") {
        icon = baseUrl + '/media/icon/culinary.png';
    } else if (id.substring(0,2) === "SP") {
        icon = baseUrl + '/media/icon/souvenir.png';
    } else if (id.substring(0,2) === "WP") {
        icon = baseUrl + '/media/icon/worship.png';
    }

    markerOption = {
        position: pos,
        icon: icon,
        animation: google.maps.Animation.DROP,
        map: map,
    }
    marker.setOptions(markerOption);
    if (!anim) {
        marker.setAnimation(null);
    }
    marker.addListener('click', () => {
        infoWindow.close();
        objectInfoWindow(id);
        infoWindow.open(map, marker);
    });
    markerArray[id] = marker;
}

// Display info window for loaded object
function objectInfoWindow(id){
    let content = '';
    let contentButton = '';

    if (id.substring(0,3) === "GTP") {
        $.ajax({
            url: baseUrl + '/api/gtp/' + id,
            dataType: 'json',
            success: function (response) {
                let data = response.data;
                let name = data.name;

                content =
                    '<div class="text-center">' +
                    '<p class="fw-bold fs-6">'+ name +'</p>' +
                    '<p><i class="fa-solid fa-spa"></i> Tourism Village</p>' +
                    '</div>';

                infoWindow.setContent(content);
            }
        });
    } else if (id.substring(0,1) === "A") {
        $.ajax({
            url: baseUrl + '/api/attraction/' + id,
            dataType: 'json',
            success: function (response) {
                let data = response.data;
                let aid = data.id;
                let name = data.name;
                let lat = data.lat;
                let lng = data.lng;
                let type = data.type;
                let price = (data.price == 0) ? 'Free' : formatter.format(data.price);
                
                content =
                    '<div class="text-center">' +
                    '<p class="fw-bold fs-6">'+ name +'</p> <br>' +
                    '<p><i class="fa-solid fa-spa"></i> '+ type +'</p>' +
                    '<p><i class="fa-solid fa-money-bill me-2"></i> '+ price +'</p>' +
                    '</div>';
                
                if(aid == "A0001") {
                    contentButton =
                    '<br><div class="text-center">' +
                    '<a title="Nearby" class="btn icon btn-outline-primary mx-1" id="nearbyInfoWindow" onclick="openTrack(`'+ aid +'`,'+ lat +','+ lng +')"><i class="fa-solid fa-map-location-dot"></i></a>' +
                    '</div>'
                } else {
                    contentButton =
                    '<br><div class="text-center">' +
                    '<a title="Nearby" class="btn icon btn-outline-primary mx-1" id="nearbyInfoWindow" onclick="openNearby(`'+ aid +'`,'+ lat +','+ lng +')"><i class="fa-solid fa-compass"></i></a>' +
                    '</div>'
                }

                if (currentUrl.includes(id)) {
                    infoWindow.setContent(content);
                    infoWindow.open(map, markerArray[aid])
                } else {
                    infoWindow.setContent(content + contentButton);
                }
            }
        });
    } else if (id.substring(0,2) === "EV") {
        $.ajax({
            url: baseUrl + '/api/event/' + id,
            dataType: 'json',
            success: function (response) {
                let data = response.data;
                let evid = data.id;
                let name = data.name;
                let type = data.type;
                // let lat = data.lat;
                // let lng = data.lng;
                let price = (data.price == 0) ? 'Free' : formatter.format(data.price);

                content =
                    '<div class="text-center">' +
                    '<p class="fw-bold fs-6">'+ name +'</p> <br>' +
                    '<p><i class="fa-solid fa-spa"></i> '+ type +'</p>' +
                    '<p><i class="fa-solid fa-money-bill me-2"></i> '+ price +'</p>' +
                    '</div>';
                contentButton =
                    '<br><div class="text-center">' +
                    '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href='+baseUrl+'/web/event/'+evid+'><i class="fa-solid fa-info"></i></a>' +
                    '</div>'

                if (currentUrl.includes(id)) {
                    infoWindow.setContent(content);
                    infoWindow.open(map, markerArray[evid])
                } else {
                    infoWindow.setContent(content + contentButton);
                }
            }
        });
    } else if (id.substring(0,1) === "P") {
        $.ajax({
            url: baseUrl + '/api/package/' + id,
            dataType: 'json',
            success: function (response) {
                let data = response.data;
                let paid = data.id;
                let name = data.name;
                // let lat = data.lat;
                // let lng = data.lng;
                let type_name = data.type_name;
                let price = (data.price == 0) ? 'Free' : formatter.format(data.price);

                content =
                    '<div class="text-center">' +
                    '<p class="fw-bold fs-6">'+ name +'</p> <br>' +
                    '<p><i class="fa-solid fa-spa"></i> '+ type_name +'</p>' +
                    '<p><i class="fa-solid fa-money-bill me-2"></i> '+ price +'</p>' +
                    '</div>';
                contentButton =
                    '<br><div class="text-center">' +
                    '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href='+baseUrl+'/web/package/'+paid+'><i class="fa-solid fa-info"></i></a>' +
                    '</div>'

                if (currentUrl.includes(id)) {
                    infoWindow.setContent(content);
                    infoWindow.open(map, markerArray[paid])
                } else {
                    infoWindow.setContent(content + contentButton);
                }
            }
        }); 
    } else if (id.substring(0,2) === "FC") {
        $.ajax({
            url: baseUrl + '/api/facility/' + id,
            dataType: 'json',
            success: function (response) {
                let data = response.data;
                let name = data.name;

                content =
                    '<div class="text-center">' +
                    '<p class="fw-bold fs-6">'+ name +'</p>' +
                    '</div>';

                infoWindow.setContent(content);
            }
        });
    } else if (id.substring(0,2) === "HO") {
        $.ajax({
            url: baseUrl + '/api/homestay/' + id,
            dataType: 'json',
            success: function (response) {
                let data = response.data;
                let hoid = data.id;
                let name = data.name;
                let lat = data.lat;
                let lng = data.lng;
                let price = (data.price == 0) ? 'Free' : 'Rp ' + data.price;
                let address = data.address;

                content =
                    '<div class="text-center">' +
                    '<p class="fw-bold fs-6">'+ name +'</p> <br>' +
                    '<p><i class="fa-solid fa-money-bill me-2"></i> '+ price +'</p>' +
                    '<p><i class="fa-solid fa-map-pin"></i> '+ address +'</p>' +
                    '</div>';
                contentButton =
                    '<br><div class="text-center">' +
                    '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo('+lat+', '+lng+')"><i class="fa-solid fa-road"></i></a>' +
                    '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href='+baseUrl+'/web/homestay/'+hoid+'><i class="fa-solid fa-info"></i></a>' +
                    '</div>'

                if (currentUrl.includes(id)) {
                    infoWindow.setContent(content);
                    infoWindow.open(map, markerArray[hoid])
                } else {
                    infoWindow.setContent(content + contentButton);
                }
            }
        });
    } else if (id.substring(0,2) === "CP") {
        $.ajax({
            url: baseUrl + '/api/culinaryPlace/' + id,
            dataType: 'json',
            success: function (response) {
                let data = response.data;
                let cpid = data.id;
                let name = data.name;
                let lat = data.lat;
                let lng = data.lng;
                let contact = data.contact_person;
                let address = data.address;

                content =
                    '<div class="text-center">' +
                    '<p class="fw-bold fs-6">'+ name +'</p> <br>' +
                    '<p><i class="fa-solid fa-address-book"></i> '+ contact +'</p>' +
                    '<p><i class="fa-solid fa-map-pin"></i> '+ address +'</p>' +
                    '</div>';
                contentButton =
                    '<br><div class="text-center">' +
                    '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo('+lat+', '+lng+')"><i class="fa-solid fa-road"></i></a>' +
                    '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href='+baseUrl+'/web/culinaryPlace/'+cpid+'><i class="fa-solid fa-info"></i></a>' +
                    '</div>'

                if (currentUrl.includes(id)) {
                    infoWindow.setContent(content);
                    infoWindow.open(map, markerArray[cpid])
                } else {
                    infoWindow.setContent(content + contentButton);
                }
            }
        });
    }

    else if (id.substring(0,2) === "SP") {
        $.ajax({
            url: baseUrl + '/api/souvenirPlace/' + id,
            dataType: 'json',
            success: function (response) {
                let data = response.data;
                let spid = data.id;
                let name = data.name;
                let lat = data.lat;
                let lng = data.lng;
                let contact = data.contact_person;
                let address = data.address;

                content =
                    '<div class="text-center">' +
                    '<p class="fw-bold fs-6">'+ name +'</p> <br>' +
                    '<p><i class="fa-solid fa-address-book"></i> '+ contact +'</p>' +
                    '<p><i class="fa-solid fa-map-pin"></i> '+ address +'</p>' +
                    '</div>';
                contentButton =
                    '<br><div class="text-center">' +
                    '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo('+lat+', '+lng+')"><i class="fa-solid fa-road"></i></a>' +
                    '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href='+baseUrl+'/web/souvenirPlace/'+spid+'><i class="fa-solid fa-info"></i></a>' +
                    '</div>'

                if (currentUrl.includes(id)) {
                    infoWindow.setContent(content);
                    infoWindow.open(map, markerArray[spid])
                } else {
                    infoWindow.setContent(content + contentButton);
                }
            }
        });
    } else if (id.substring(0,2) === "WP") {
        $.ajax({
            url: baseUrl + '/api/worshipPlace/' + id,
            dataType: 'json',
            success: function (response) {
                let data = response.data;
                let wpid = data.id;
                let name = data.name;
                let lat = data.lat;
                let lng = data.lng;
                let capacity = data.capacity;
                let address = data.address;

                content =
                    '<div class="text-center">' +
                    '<p class="fw-bold fs-6">'+ name +'</p> <br>' +
                    '<p><i class="fa-solid fa-person-praying"></i> '+ capacity +'</p>' +
                    '<p><i class="fa-solid fa-map-pin"></i> '+ address +'</p>' +
                    '</div>';
                contentButton =
                    '<br><div class="text-center">' +
                    '<a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="routeTo('+lat+', '+lng+')"><i class="fa-solid fa-road"></i></a>' +
                    '<a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href='+baseUrl+'/web/worshipPlace/'+wpid+'><i class="fa-solid fa-info"></i></a>' +
                    '</div>'

                if (currentUrl.includes(id)) {
                    infoWindow.setContent(content);
                    infoWindow.open(map, markerArray[wpid])
                } else {
                    infoWindow.setContent(content + contentButton);
                }
            }
        });
    }
}

// Render map to contains all object marker
function boundToObject() {
    if (Object.keys(markerArray).length > 0) {
        bounds = new google.maps.LatLngBounds();
        for (i in markerArray) {
            bounds.extend(markerArray[i].getPosition())
        }
        map.fitBounds(bounds, 80);
    } else {
        let pos = new google.maps.LatLng(-0.7102134517843606, 100.19420485758688);
        map.panTo(pos);
    }
    
}

// Render map to contains route and its markers
function boundToRoute(start, end) {
    bounds = new google.maps.LatLngBounds();
    bounds.extend(start);
    bounds.extend(end);
    map.panToBounds(bounds, 100);
}

// Add user position to map bound
function boundToRadius(lat, lng, rad) {
    let userBound = new google.maps.LatLng(lat, lng);
    const radiusCircle = new google.maps.Circle({
        center: userBound,
        radius: Number(rad)
    });
    map.fitBounds(radiusCircle.getBounds());
}

// Draw radius circle
function drawRadius(position, radius) {
    const radiusCircle = new google.maps.Circle({
        center: position,
        radius: radius,
        map: map,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
    });
    circleArray.push(radiusCircle);
    boundToRadius(currentLat, currentLng, radius);
}

// Update radiusValue on search by radius
function updateRadius(postfix) {
    document.getElementById('radiusValue' + postfix).innerHTML = (document.getElementById('inputRadius' + postfix).value * 100) + " m";
}

// pan to selected object
function focusObject(id) {
    google.maps.event.trigger(markerArray[id], 'click');
    map.panTo(markerArray[id].getPosition())
}

// display objects by feature used
function displayFoundObject(response) {
    $('#table-data').empty();
    let data = response.data;
    let counter = 1;
    for (i in data) {
        let item = data[i];
        let row;

            row =
                '<tr>'+
                '<td>'+ counter +'</td>' +
                '<td class="fw-bold">'+ item.name +'</td>' +
                '<td>'+
                '<a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-primary mx-1" onclick="focusObject(`'+ item.id +'`);">' +
                '<span class="material-symbols-outlined">info</span>' +
                '</a>' +
                '</td>'+
                '</tr>';

        $('#table-data').append(row);
        objectMarker(item.id, item.lat, item.lng);
        counter++;
    }
}

// display steps of direction to selected route
function showSteps(directionResult) {
    $('#direction-row').show();
    $('#table-direction').empty();
    let myRoute = directionResult.routes[0].legs[0];
    for (let i = 0; i < myRoute.steps.length; i++) {
        let distance = myRoute.steps[i].distance.value;
        let instruction = myRoute.steps[i].instructions;
        let row =
            '<tr>' +
            '<td>'+ distance.toLocaleString("id-ID") +'</td>' +
            '<td>'+ instruction +'</td>' +
            '</tr>';
        $('#table-direction').append(row);
    }
}

// close nearby search section
function closeNearby() {
    $('#direction-row').hide();
    $('#check-track-col').hide();
    $('#check-nearby-col').hide();
    $('#result-track-col').hide();
    $('#result-nearby-col').hide();
    $('#list-at-col').show();
    $('#list-ev-col').show();
}

// open nearby search section
function openTrack(id, lat, lng) {
    $('#list-at-col').hide();
    $('#check-track-col').show();

    currentLat = lat;
    currentLng = lng;
    let pos = new google.maps.LatLng(currentLat, currentLng);
    map.panTo(pos);

    document.getElementById('inputTrackAlong').setAttribute('onclick', 'checkTrack("'+id+'")');
}

// open nearby search section
function openNearby(id, lat, lng) {
    $('#list-at-col').hide();
    $('#check-nearby-col').show();

    currentLat = lat;
    currentLng = lng;
    let pos = new google.maps.LatLng(currentLat, currentLng);
    map.panTo(pos);

    document.getElementById('inputRadiusNearby').setAttribute('onchange', 'updateRadius("Nearby"); checkNearby("'+id+'")');
}

// open nearby search section
function openExplore() {
    $('#list-object-col').hide();
    $('#check-explore-col').show();

    document.getElementById('inputRadiusNearby').setAttribute('onchange', 'updateRadius("Nearby"); checkExplore()');
}

function closeExplore() {
    $('#check-explore-col').hide();
    $('#list-object-col').show();
    $('#result-explore-col').hide();
}

function checkExplore() {
    if (userLat == 0 && userLng == 0) {
        document.getElementById('radiusValueNearby').innerHTML = "0 m";
        document.getElementById('inputRadiusNearby').value = 0;
        return Swal.fire('Determine your position first!');
    }

    clearRadius();
    clearRoute();
    clearMarker();
    destinationMarker.setMap(null);
    google.maps.event.clearListeners(map, 'click');

    $('#table-cp').empty();
    $('#table-ho').empty();
    $('#table-sp').empty();
    $('#table-wp').empty();

    $('#table-cp').hide();
    $('#table-ho').hide();
    $('#table-sp').hide();
    $('#table-wp').hide();

    let pos = new google.maps.LatLng(currentLat, currentLng);
    let radiusValue = parseFloat(document.getElementById('inputRadiusNearby').value) * 100;
    map.panTo(pos);

    const checkCP = document.getElementById('check-cp').checked;
    const checkHO = document.getElementById('check-ho').checked;
    const checkSP = document.getElementById('check-sp').checked;
    const checkWP = document.getElementById('check-wp').checked;

    if (!checkCP && !checkHO && !checkSP && !checkWP) {
        document.getElementById('radiusValueNearby').innerHTML = "0 m";
        document.getElementById('inputRadiusNearby').value = 0;
        return Swal.fire('Please choose one object');
    }

    if (checkCP) {
        findExplore('cp', radiusValue);
        $('#table-cp').show();
    }
    if (checkHO) {
        findExplore('ho', radiusValue);
        $('#table-ho').show();
    }
    if (checkSP) {
        findExplore('sp', radiusValue);
        $('#table-sp').show();
    }
    if (checkWP) {
        findExplore('wp', radiusValue);
        $('#table-wp').show();
    }
    drawRadius(new google.maps.LatLng(currentLat, currentLng), radiusValue);
    $('#result-explore-col').show();
}

// Fetch object nearby by category
function findExplore(category, radius) {
    // let pos = new google.maps.LatLng(currentLat, currentLng);
    if (category === 'cp') {
        $.ajax({
            url: baseUrl + '/api/culinaryPlace/findByRadius',
            type: 'POST',
            data: {
                lat: currentLat,
                long: currentLng,
                radius: radius
            },
            dataType: 'json',
            success: function (response) {
                displayExploreResult(category, response);
            }
        });
    } else if (category === 'ho') {
        $.ajax({
            url: baseUrl + '/api/homestay/findByRadius',
            type: 'POST',
            data: {
                lat: currentLat,
                long: currentLng,
                radius: radius
            },
            dataType: 'json',
            success: function (response) {
                displayExploreResult(category, response);
            }
        });
    } else if (category === 'sp') {
        $.ajax({
            url: baseUrl + '/api/souvenirPlace/findByRadius',
            type: 'POST',
            data: {
                lat: currentLat,
                long: currentLng,
                radius: radius
            },
            dataType: 'json',
            success: function (response) {
                displayExploreResult(category, response);
            }
        });
    } else if (category === 'wp') {
        $.ajax({
            url: baseUrl + '/api/worshipPlace/findByRadius',
            type: 'POST',
            data: {
                lat: currentLat,
                long: currentLng,
                radius: radius
            },
            dataType: 'json',
            success: function (response) {
                displayExploreResult(category, response);
            }
        });
    }
}

function displayExploreResult(category, response) {
    let data = response.data;
    let headerName;
    if (category === 'cp') {
        headerName = 'Culinary Place';
    } else if (category === 'ho') {
        headerName = 'Homestay';
    } else if (category === 'sp') {
        headerName = 'Souvenir Place'
    } else if (category === 'wp') {
        headerName = 'Worship Place'
    }

    let table =
        '<thead><tr>' +
        '<th>'+ headerName +' Name</th>' +
        '<th colspan="1">Action</th>' +
        '</tr></thead>' +
        '<tbody id="data-'+category+'">' +
        '</tbody>';
    $('#table-'+category).append(table);

    for (i in data) {
        let item = data[i];
        let row =
            '<tr>'+
            '<td>'+ item.name +'</td>' +
            '<td>'+
            '<a title="Location" class="btn-sm icon btn-primary" onclick="focusObject(`'+ item.id +'`);"><i class="fa-solid fa-map-location-dot"></i></a>' +
            '</td>'+
            '</tr>';
        $('#data-'+category).append(row);
        objectMarkerExplore(item.id, item.lat, item.lng, item.status);
    }
}

function objectMarkerExplore(id, lat, lng, status, anim = true) {

    google.maps.event.clearListeners(map, 'click');
    let pos = new google.maps.LatLng(lat, lng);
    let marker = new google.maps.Marker();

    let icon;
    if (id.substring(0,2) === "HO") {
        if (status === "1") {
            icon = baseUrl + '/media/icon/hogtp.png';
        } else {
            icon = baseUrl + '/media/icon/homestay.png';
        }
    } else if (id.substring(0,2) === "CP") {
        if (status === "1") {
            icon = baseUrl + '/media/icon/cpgtp.png';
        } else {
            icon = baseUrl + '/media/icon/culinary.png';
        }
    } else if (id.substring(0,2) === "SP") {
        if (status === "1") {
            icon = baseUrl + '/media/icon/souvenir.png';
        } else {
            icon = baseUrl + '/media/icon/souvenir.png';
        }
    } else if (id.substring(0,2) === "WP") {
        if (status === "1") {
            icon = baseUrl + '/media/icon/wpgtp.png';
        } else {
            icon = baseUrl + '/media/icon/worship.png';
        }
    }

    markerOption = {
        position: pos,
        icon: icon,
        animation: google.maps.Animation.DROP,
        map: map,
    }
    marker.setOptions(markerOption);
    if (!anim) {
        marker.setAnimation(null);
    }
    marker.addListener('click', () => {
        infoWindow.close();
        objectInfoWindow(id);
        infoWindow.open(map, marker);
    });
    markerArray[id] = marker;
}

// Search Result Object Around
function checkNearby(id) {
    clearRadius();
    clearRoute();
    clearMarker();
    clearUser();
    destinationMarker.setMap(null);
    google.maps.event.clearListeners(map, 'click')

    objectMarker(id, currentLat, currentLng, false);

    $('#table-F0001').empty();
    $('#table-F0002').empty();
    $('#table-F0003').empty();
    $('#table-F0004').empty();
    $('#table-F0005').empty();
    $('#table-F0006').empty();
    $('#table-F0007').empty();
    $('#table-F0008').empty();
    $('#table-F0009').empty();
    $('#table-F0010').empty();

    $('#table-F0001').hide();
    $('#table-F0002').hide();
    $('#table-F0003').hide();
    $('#table-F0004').hide();
    $('#table-F0005').hide();
    $('#table-F0006').hide();
    $('#table-F0007').hide();
    $('#table-F0008').hide();
    $('#table-F0009').hide();
    $('#table-F0010').hide();

    let radiusValue = parseFloat(document.getElementById('inputRadiusNearby').value) * 100;

    const checkCP = document.getElementById('F0001').checked;
    const checkGA = document.getElementById('F0002').checked;
    const checkOF = document.getElementById('F0003').checked;
    const checkPA = document.getElementById('F0004').checked;
    const checkPB = document.getElementById('F0005').checked;
    const checkSA = document.getElementById('F0006').checked;
    const checkSP = document.getElementById('F0007').checked;
    const checkTH = document.getElementById('F0008').checked;
    const checkVT = document.getElementById('F0009').checked;
    const checkWP = document.getElementById('F0010').checked;

    if (!checkCP && !checkGA && !checkOF && !checkPA && !checkPB && !checkSA
        && !checkSP && !checkTH && !checkVT && !checkWP) {
        document.getElementById('radiusValueNearby').innerHTML = "0 m";
        document.getElementById('inputRadiusNearby').value = 0;
        return Swal.fire('Please choose one facility!');
    }

    if (checkCP) {
        findNearby('F0001', radiusValue);
        $('#table-F0001').show();
    }
    if (checkGA) {
        findNearby('F0002', radiusValue);
        $('#table-F0002').show();
    }
    if (checkOF) {
        findNearby('F0003', radiusValue);
        $('#table-F0003').show();
    }
    if (checkPA) {
        findNearby('F0004', radiusValue);
        $('#table-F0004').show();
    }
    if (checkPB) {
        findNearby('F0005', radiusValue);
        $('#table-F0005').show();
    }
    if (checkSA) {
        findNearby('F0006', radiusValue);
        $('#table-F0006').show();
    }
    if (checkSP) {
        findNearby('F0007', radiusValue);
        $('#table-F0007').show();
    }
    if (checkTH) {
        findNearby('F0008', radiusValue);
        $('#table-F0008').show();
    }
    if (checkVT) {
        findNearby('F0009', radiusValue);
        $('#table-F0009').show();
    }
    if (checkWP) {
        findNearby('F0010', radiusValue);
        $('#table-F0010').show();
    }
    drawRadius(new google.maps.LatLng(currentLat, currentLng), radiusValue);
    $('#result-nearby-col').show();
}

// Check facility along tracking
function checkTrack(id) {
    clearRadius();
    clearRoute();
    clearMarker();
    clearUser();
    destinationMarker.setMap(null);
    google.maps.event.clearListeners(map, 'click')

    objectMarker(id, currentLat, currentLng, false);

    // let i = 1;
    // for(i > 0; i <= 10; i++) {
    //     $('#table-F000'+i).empty();
    //     $('#table-F000'+i).hide();
    // }

    $('#table-F0001').empty();
    $('#table-F0002').empty();
    $('#table-F0003').empty();
    $('#table-F0004').empty();
    $('#table-F0005').empty();
    $('#table-F0006').empty();
    $('#table-F0007').empty();
    $('#table-F0008').empty();
    $('#table-F0009').empty();
    $('#table-F0010').empty();

    $('#table-F0001').hide();
    $('#table-F0002').hide();
    $('#table-F0003').hide();
    $('#table-F0004').hide();
    $('#table-F0005').hide();
    $('#table-F0006').hide();
    $('#table-F0007').hide();
    $('#table-F0008').hide();
    $('#table-F0009').hide();
    $('#table-F0010').hide();

    const checkCP = document.getElementById('F0001').checked;
    const checkGA = document.getElementById('F0002').checked;
    const checkOF = document.getElementById('F0003').checked;
    const checkPA = document.getElementById('F0004').checked;
    const checkPB = document.getElementById('F0005').checked;
    const checkSA = document.getElementById('F0006').checked;
    const checkSP = document.getElementById('F0007').checked;
    const checkTH = document.getElementById('F0008').checked;
    const checkVT = document.getElementById('F0009').checked;
    const checkWP = document.getElementById('F0010').checked;

    if (!checkCP && !checkGA && !checkOF && !checkPA && !checkPB && !checkSA
        && !checkSP && !checkTH && !checkVT && !checkWP) {
        return Swal.fire('Please choose one facility!');
    }

    if (checkCP) {
        findTracking('F0001');
        $('#table-F0001').show();
    }
    if (checkGA) {
        findTracking('F0002');
        $('#table-F0002').show();
    }
    if (checkOF) {
        findTracking('F0003');
        $('#table-F0003').show();
    }
    if (checkPA) {
        findTracking('F0004');
        $('#table-F0004').show();
    }
    if (checkPB) {
        findTracking('F0005');
        $('#table-F0005').show();
    }
    if (checkSA) {
        findTracking('F0006');
        $('#table-F0006').show();
    }
    if (checkSP) {
        findTracking('F0007');
        $('#table-F0007').show();
    }
    if (checkTH) {
        findTracking('F0008');
        $('#table-F0008').show();
    }
    if (checkVT) {
        findTracking('F0009');
        $('#table-F0009').show();
    }
    if (checkWP) {
        findTracking('F0010');
        $('#table-F0010').show();
    }
    
    $('#result-track-col').show();
}

// Fetch object along tracking
function findTracking(category) {
    let pos = new google.maps.LatLng(currentLat, currentLng);
    // if (category === 'FC') {
        const ftype = new google.maps.Data();
        $.ajax({
            url: baseUrl + '/api/facility/findByTrack',
            type: 'POST',
            data: {
                ftype: category
            },
            dataType: 'json',
            success: function (response) {
                displayTrackResult(category, response);
            }
        });
    // }
}

// Fetch object nearby by category
function findNearby(category, radius) {
    let pos = new google.maps.LatLng(currentLat, currentLng);
    // if (category === 'FC') {
        const ftype2 = new google.maps.Data();
        $.ajax({
            url: baseUrl + '/api/facility/findByRadius',
            type: 'POST',
            data: {
                ftype2: category,
                lat: currentLat,
                long: currentLng,
                radius: radius
            },
            dataType: 'json',
            success: function (response) {
                displayNearbyResult(category, response);
            }
        });
    // }
}

// Add nearby object to corresponding table
function displayTrackResult(category, response) {
    let data = response.data;
    let headerName;
    if (category === 'F0001') {
        headerName = 'Culinary Place';
    } else if (category === 'F0002') {
        headerName = 'Gazebo';
    } else if (category === 'F0003') {
        headerName = 'Outbond Field'
    } else if (category === 'F0004') {
        headerName = 'Parking Area'
    } else if (category === 'F0005') {
        headerName = 'Public Bathroom'
    } else if (category === 'F0006') {
        headerName = 'Selfie Area'
    } else if (category === 'F0007') {
        headerName = 'Souvenir Place'
    } else if (category === 'F0008') {
        headerName = 'Tree House'
    } else if (category === 'F0009') {
        headerName = 'Viewing Tower'
    } else if (category === 'F0010') {
        headerName = 'Worship Place'
    }

    let table =
        '<thead><tr>' +
        '<th>'+ headerName +' Name</th>' +
        '<th colspan="2">Action</th>' +
        '</tr></thead>' +
        '<tbody id="data-'+category+'">' +
        '</tbody>';
    $('#table-'+category).append(table);

    for (i in data) {
        let item = data[i];
        let row =
            '<tr>'+
            '<td>'+ item.name +'</td>' +
            '<td>'+
            '<a title="Location" class="btn-sm icon btn-primary" onclick="focusObject(`'+ item.facility_id +'`);"><i class="fa-solid fa-map-location-dot"></i></a>' +
            '</td>'+
            '<td>'+
            '<a title="Info" class="btn-sm icon btn-primary" onclick="infoModal(`'+ item.facility_id +'`)"><i class="fa-regular fa-images"></i></a>' +
            '</td>'+
            '</tr>';
        $('#data-'+category).append(row);
        objectMarkerFacility(item.facility_id, item.lat, item.long, item.type_id);
    }
}

function objectMarkerFacility(id, lat, lng, type, anim = true) {
    google.maps.event.clearListeners(map, 'click');
    let pos = new google.maps.LatLng(lat, lng);
    let marker = new google.maps.Marker();

    let icon;

    if(type === "F0001") {
        icon = baseUrl + '/media/icon/culinary.png';
    } else if (type === "F0002"){
        icon = baseUrl + '/media/icon/gazebo.png';
    } else if (type === "F0003"){
        icon = baseUrl + '/media/icon/outbond.png';
    } else if (type === "F0004"){
        icon = baseUrl + '/media/icon/parking.png';
    } else if (type === "F0005"){
        icon = baseUrl + '/media/icon/bathroom.png';
    } else if (type === "F0006"){
        icon = baseUrl + '/media/icon/selfie.png';
    } else if (type === "F0007"){
        icon = baseUrl + '/media/icon/souvenir.png';
    } else if (type === "F0008"){
        icon = baseUrl + '/media/icon/treehouse.png';
    } else if (type === "F0009"){
        icon = baseUrl + '/media/icon/tower.png';
    } else if (type === "F0010"){
        icon = baseUrl + '/media/icon/worship.png';
    }

    markerOption = {
        position: pos,
        icon: icon,
        animation: google.maps.Animation.DROP,
        map: map,
    }
    marker.setOptions(markerOption);
    if (!anim) {
        marker.setAnimation(null);
    }
    marker.addListener('click', () => {
        infoWindow.close();
        objectInfoWindow(id);
        infoWindow.open(map, marker);
    });
    markerArray[id] = marker;
}

// Add nearby object to corresponding table
function displayNearbyResult(category, response) {
    let data = response.data;
    let headerName;
    if (category === 'F0001') {
        headerName = 'Culinary Place';
    } else if (category === 'F0002') {
        headerName = 'Gazebo';
    } else if (category === 'F0003') {
        headerName = 'Outbond Field'
    } else if (category === 'F0004') {
        headerName = 'Parking Area'
    } else if (category === 'F0005') {
        headerName = 'Public Bathroom'
    } else if (category === 'F0006') {
        headerName = 'Selfie Area'
    } else if (category === 'F0007') {
        headerName = 'Souvenir Place'
    } else if (category === 'F0008') {
        headerName = 'Tree House'
    } else if (category === 'F0009') {
        headerName = 'Viewing Tower'
    } else if (category === 'F0010') {
        headerName = 'Worship Place'
    }

    let table =
        '<thead><tr>' +
        '<th>'+ headerName +' Name</th>' +
        '<th colspan="2">Action</th>' +
        '</tr></thead>' +
        '<tbody id="data-'+category+'">' +
        '</tbody>';
    $('#table-'+category).append(table);

    for (i in data) {
        let item = data[i];
        let row =
            '<tr>'+
            '<td>'+ item.name +'</td>' +
            '<td>'+
            '<a title="Location" class="btn-sm icon btn-primary" onclick="focusObject(`'+ item.id +'`);"><i class="fa-solid fa-map-location-dot"></i></a>' +
            '</td>'+
            '<td>'+
            '<a title="Info" class="btn-sm icon btn-primary" onclick="infoModal(`'+ item.id +'`)"><i class="fa-regular fa-images"></i></a>' +
            '</td>'+
            '</tr>';
        $('#data-'+category).append(row);
        objectMarkerFacility(item.id, item.lat, item.lng, item.type_id);
    }
}


// Show modal for object
function infoModal(id) {
    let title;
    let content;
    // let g;
    // let content = "";

    if (id.substring(0,2) === "FC") {
        $.ajax({
            url: baseUrl + '/api/facility/' + id,
            dataType: 'json',
            success: function (response) {
                let item = response.data;
                // g = item.gallery;
                title = '<h3>'+item.name+'</h3>';
                // g.forEach( a => {
                //     content += '<div><img src="/media/photos/facility/'+a+'" alt="'+ item.name +'" class="w-50"></div>'
                // });

                content =
                    '<div class="text-start">'+
                    '</div>'+
                    '<div>' +
                    '<img src="/media/photos/facility/'+item.gallery[0]+'" alt="'+ item.name +'" class="w-50">' +
                    '</div>';

                Swal.fire({
                    title: title,
                    html: content,
                    width: '50%',
                    position: 'top'
                });
            }
        });
    }
}
// Create legend
function getLegend() {
    const icons = {
        tracking :{
            name: 'Tracking Mangrove',
            icon: baseUrl + '/media/icon/tracking.png',
        },
        talao :{
            name: 'Water Attractions',
            icon: baseUrl + '/media/icon/talao.png',
        },
        event :{
            name: 'Event',
            icon: baseUrl + '/media/icon/event.png',
        },
        package :{
            name: 'Package',
            icon: baseUrl + '/media/icon/package.png',
        },
        package :{
            name: 'Package',
            icon: baseUrl + '/media/icon/package.png',
        },
        cp :{
            name: 'Culinary Place',
            icon: baseUrl + '/media/icon/culinary.png',
        },
        ga :{
            name: 'Gazebo',
            icon: baseUrl + '/media/icon/gazebo.png',
        },
        ho :{
            name: 'Homestay',
            icon: baseUrl + '/media/icon/homestay.png',
        },
        of :{
            name: 'Outbond Field',
            icon: baseUrl + '/media/icon/outbond.png',
        },
        pa :{
            name: 'Parking Area',
            icon: baseUrl + '/media/icon/parking.png',
        },
        pb :{
            name: 'Public Bathroom',
            icon: baseUrl + '/media/icon/bathroom.png',
        },
        sa :{
            name: 'Selfie Area',
            icon: baseUrl + '/media/icon/selfie.png',
        },
        sp :{
            name: 'Souvenir Place',
            icon: baseUrl + '/media/icon/souvenir.png',
        },
        th :{
            name: 'Tree House',
            icon: baseUrl + '/media/icon/treehouse.png',
        },
        vw :{
            name: 'Viewing Tower',
            icon: baseUrl + '/media/icon/tower.png',
        },
        wp :{
            name: 'Worship Place',
            icon: baseUrl + '/media/icon/worship.png',
        },
    }

    // const title = '<p class="fw-bold fs-6">Legend</p>';
    // $('#legend').append(title);

    for (key in icons) {
        const type = icons[key];
        const name = type.name;
        const icon = type.icon;
        const div = '<div><img src="' + icon + '"> ' + name +'</div>';

        $('#legend').append(div);
    }
    map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
}

// toggle legend element
function viewLegend() {
    if ($('#legend').is(':hidden')) {
        $('#legend').show();
    } else {
        $('#legend').hide();
    }
}

// Update preview of uploaded photo profile
function showPreview(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#avatar-preview').attr('src', e.target.result).width(300).height(300);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Find object by name
function findByName(category) {
    clearRadius();
    clearRoute();
    clearMarker();
    clearUser();
    destinationMarker.setMap(null);
    google.maps.event.clearListeners(map, 'click');
    closeNearby();

    let name;
    if (category === 'PA') {
        name = document.getElementById('namePA').value;
        $.ajax({
            url: baseUrl + '/api/package/findByName',
            type: 'POST',
            data: {
                name: name,
            },
            dataType: 'json',
            success: function (response) {
                displayFoundObject(response);
                boundToObject();
            }
        });
    }
}

// Get list of package type
function getType() {
    let type;
    $('#typePASelect').empty()
    $.ajax({
        url: baseUrl + '/api/package/type',
        dataType: 'json',
        success: function (response) {
            let data = response.data;
            for (i in data) {
                let item = data[i];
                type =
                    '<option value="'+ item.id +'">'+ item.type_name +'</option>';
                $('#typePASelect').append(type);
            }
        }
    });
}

// Find object by Type
function findByType(object) {
    clearRadius();
    clearRoute();
    clearMarker();
    clearUser();
    destinationMarker.setMap(null);
    google.maps.event.clearListeners(map, 'click');
    closeNearby();

    if (object === 'PA') {
        let type = document.getElementById('typePASelect').value;
        $.ajax({
            url: baseUrl + '/api/package/findByType',
            type: 'POST',
            data: {
                type: type,
            },
            dataType: 'json',
            success: function (response) {
                displayFoundObject(response);
                boundToObject();
            }
        });
    }
}

// Show All in Explore Ulakan
function showMap(id = null) {
    let URI;
    
    clearMarker();
    clearRadius();
    clearRoute();

    if (id == 'cp') {
        URI = baseUrl + '/api/culinaryPlace'
    } else if (id == 'ho') {
        URI = baseUrl + '/api/homestay'
    } else if (id == 'sp') {
        URI = baseUrl + '/api/souvenirPlace'
    } else if (id == 'wp') {
        URI = baseUrl + '/api/worshipPlace'
    }

    // currentUrl = '';
    $.ajax({
        url: URI,
        dataType: 'json',
        success: function (response) {
            let data = response.data
            for(i in data) {
                let item = data[i];
                // currentUrl = currentUrl + item.id;
                // currentUrl = currentUrl;
                objectMarkerExplore(item.id, item.lat, item.lng, item.status);
            }
            boundToObject();
        }
    })
}

// Set map to coordinate put by user
function findCoords(object) {
    clearMarker();
    google.maps.event.clearListeners(map, 'click');

    const lat = Number(document.getElementById('latitude').value);
    const lng = Number(document.getElementById('longitude').value);

    if (lat === 0 || lng === 0 || isNaN(lat) || isNaN(lng)) {
        return Swal.fire('Please input Lat and Long');
    }

    let pos = new google.maps.LatLng(lat, lng);
    map.panTo(pos);
}

// Unselect shape on drawing map
function clearSelection() {
    if (selectedShape) {
        selectedShape.setEditable(false);
        selectedShape = null;
    }
}

// Make selected shape editable on maps
function setSelection(shape) {
    clearSelection();
    selectedShape = shape;
    shape.setEditable(true);
}

// Remove selected shape on maps
function deleteSelectedShape() {
    if (selectedShape) {
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('geo-json').value = '';
        clearMarker();
        selectedShape.setMap(null);
        // To show:
        drawingManager.setOptions({
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true
        });
    }
}

// Initialize drawing manager on maps
function initDrawingManager(edit = false) {
    const drawingManagerOpts = {
        drawingMode: google.maps.drawing.OverlayType.POLYGON,
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [
                google.maps.drawing.OverlayType.POLYGON,
            ]
        },
        polygonOptions: {
            fillColor: 'blue',
            strokeColor: 'blue',
            editable: true,
        },
        map: map
    };
    drawingManager.setOptions(drawingManagerOpts);
    let newShape;

    if (!edit) {
        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
            drawingManager.setOptions({
                drawingControl: false,
                drawingMode: null,
            });
            newShape = event.overlay;
            newShape.type = event.type;
            setSelection(newShape);
            saveSelection(newShape);

            google.maps.event.addListener(newShape, 'click', function() {
                setSelection(newShape);
            });
            google.maps.event.addListener(newShape.getPath(), 'insert_at', () => {
                saveSelection(newShape);
            });
            google.maps.event.addListener(newShape.getPath(), 'remove_at', () => {
                saveSelection(newShape);
            });
            google.maps.event.addListener(newShape.getPath(), 'set_at', () => {
                saveSelection(newShape);
            });
        });
    } else {
        drawingManager.setOptions({
            drawingControl: false,
            drawingMode: null,
        });

        newShape = drawGeom();
        newShape.type = 'polygon';
        setSelection(newShape);
        saveSelection(newShape);

        google.maps.event.addListener(newShape, 'click', function() {
            setSelection(newShape);
        });
        google.maps.event.addListener(newShape.getPath(), 'insert_at', () => {
            saveSelection(newShape);
        });
        google.maps.event.addListener(newShape.getPath(), 'remove_at', () => {
            saveSelection(newShape);
        });
        google.maps.event.addListener(newShape.getPath(), 'set_at', () => {
            saveSelection(newShape);
        });
    }

    google.maps.event.addListener(map, 'click', clearSelection);
    google.maps.event.addDomListener(document.getElementById('clear-drawing'), 'click', deleteSelectedShape);
}

// Get geoJSON of selected shape on map
function saveSelection(shape) {
    let str_input ='MULTIPOLYGON(((';
    let coord = [];
    let centroid = [0.0, 0.0];
    const paths = shape.getPath().getArray();

    for (let i = 0; i < paths.length; i++) {
        centroid[0] += paths[i].lat();
        centroid[1] += paths[i].lng();
        coord[i] = paths[i].lng() + ' ' + paths[i].lat();
        str_input += paths[i].lng() + ' ' + paths[i].lat() + ',';
    }

    str_input = str_input + '' + coord[0] + ')))';
    const totalPaths = paths.length;
    centroid[0] = centroid[0] / totalPaths;
    centroid[1] = centroid[1] / totalPaths;

    // console.log(str_input)

    let pos = new google.maps.LatLng(centroid[0], centroid[1]);
    map.panTo(pos);

    clearMarker();
    let marker = new google.maps.Marker();
    markerOption = {
        position: pos,
        animation: google.maps.Animation.DROP,
        map: map,
    }
    marker.setOptions(markerOption);
    markerArray['new'] = marker;

    document.getElementById('latitude').value = centroid[0].toFixed(8);
    document.getElementById('longitude').value = centroid[1].toFixed(8);
    document.getElementById('multipolygon').value = str_input;

    const dataLayer = new google.maps.Data();
    dataLayer.add(new google.maps.Data.Feature({
        geometry: new google.maps.Data.Polygon([shape.getPath().getArray()])
    }));
    dataLayer.toGeoJson(function (object) {
        document.getElementById('geo-json').value = JSON.stringify(object.features[0].geometry);
    });
}

// Draw current GeoJSON on drawing manager
function drawGeom() {
    const geoJSON = $('#geo-json').val();
    if (geoJSON !== '') {
        const geoObj = JSON.parse(geoJSON);
        const coords = geoObj.coordinates[0][0];
        // console.log(coords)
        let polygonCoords = []
        for (i in coords) {
            polygonCoords.push(
                {lat: coords[i][1], lng: coords[i][0]}
            );
        }
        // console.log(polygonCoords)
        const polygon = new google.maps.Polygon({
            paths: polygonCoords,
            fillColor: 'blue',
            strokeColor: 'blue',
            editable: true,
        });
        polygon.setMap(map);
        return polygon;
    }
}

// Delete selected object
function deleteObject(id = null, name = null, user = false) {
    if (id === null) {
        return Swal.fire('ID cannot be null');
    }

    let content, apiUri;
    if (id.substring(0,2) === 'EV') {
        content = 'Event';
        apiUri = 'event/';
    } else if (id.substring(0,1) === 'P') {
        content = 'Package';
        apiUri = 'package/'
    } else if (id.substring(0,2) === 'FC') {
        content = 'Facility';
        apiUri = 'facility/'
    } else if (id.substring(0,1) === 'S') {
        content = 'Service Package';
        apiUri = 'servicepackage/'
    } else if (id.substring(0,2) === 'HO') {
        content = 'Homestay';
        apiUri = 'homestay/'
    } else if (id.substring(0,1) === 'S') {
        content = 'Admin';
        apiUri = 'admin/'
    } else if (user === true) {
        content = 'User';
        apiUri = 'user/'
    }

    Swal.fire({
        title: 'Delete ' + content + '?',
        text: 'You are about to remove ' + name,
        icon: 'warning',
        showCancelButton: true,
        denyButtonText: 'Delete',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#343a40',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: baseUrl + '/api/' + apiUri + id,
                type: 'DELETE',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 200) {
                        Swal.fire('Deleted!', 'Successfully remove ' + name, 'success').then((result) => {
                            if(result.isConfirmed) {
                                document.location.reload();
                            }
                        });

                    } else {
                        Swal.fire('Failed', 'Delete ' + name + ' failed!', 'warning');
                    }
                }
            });
        }
    });
}


function deleteActivity(package_id = null, day = null, activity = null,  description= null) {

    id=package_id;
    if (id === null) {
        return Swal.fire('ID cannot be null');
    }

    let content, apiUri;
    if (id.substring(0,1) === 'P') {
        content = 'Activity Package Day';
        apiUri = 'packageday/'
    }

    Swal.fire({
        title: 'Delete ' + content + '?',
        text: 'You are about to remove "' + description + '" on day '+ day ,
        icon: 'warning',
        showCancelButton: true,
        denyButtonText: 'Delete',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#343a40',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: baseUrl + '/api/' + apiUri + id,
                type: 'DELETE',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 200) {
                        Swal.fire('Deleted!', 'Successfully remove "' + description + '" on day '+ day, 'success').then((result) => {
                            if(result.isConfirmed) {
                                document.location.reload();
                            }
                        });

                    } else {
                        Swal.fire('Failed', 'Delete ' + description + ' failed!', 'warning');
                    }
                }
            });
        }
    });
}