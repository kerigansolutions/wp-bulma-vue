<template>
    <div class="google-map" :id="mapName"></div>
</template>

<script>
    export default {
        props: [
            'latitude',
            'longitude',
            'zoom'
        ],

        data: function () {
            return {
                mapName: this.name + "-map",
                markerCoordinates: [{
                    latitude: this.latitude,
                    longitude: this.longitude
                }]
            }
        },

        mounted: function () {
            const element = document.getElementById(this.mapName)
            const options = {
                zoom: this.zoom,
                center: new google.maps.LatLng(this.latitude,this.longitude),
                disableDefaultUI: true,
                zoomControl: true,
                scaleControl: true,
                styles: [
                    {
                        "featureType": "all",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "weight": "2.00"
                            }
                        ]
                    },
                    {
                        "featureType": "all",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "color": "#9c9c9c"
                            }
                        ]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.text",
                        "stylers": [
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "all",
                        "stylers": [
                            {
                                "color": "#f2f2f2"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#e9eef2"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.man_made",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#dddddd"
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "all",
                        "stylers": [
                            {
                                "saturation": -100
                            },
                            {
                                "lightness": 45
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#999999"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#7b7b7b"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "simplified"
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "labels.icon",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [
                            {
                                "color": "#46bcec"
                            },
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#04313b"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            }
                        ]
                    }
                ]
            }
            const map = new google.maps.Map(element, options);
            this.markerCoordinates.forEach((coord) => {
                const position = new google.maps.LatLng(coord.latitude, coord.longitude);
                const marker = new google.maps.Marker({
                    position,
                    map
                });
            });
        }

    }
</script>