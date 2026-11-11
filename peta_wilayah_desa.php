<!DOCTYPE html>
<html>

<head>
    <title>Peta Wilayah Desa Tanete</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/leaflet.css">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        #map {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>

    <div id="map"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Turf.js/6.5.0/turf.min.js"></script>
    <script src="https://tanete.desa.id/desa/upload/polygon_desa.js"></script>
    <script src="https://tanete.desa.id/desa/upload/polygon_dusun.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof L !== "undefined" && typeof polygon_desa_tanete !== "undefined") {
                var posisi = [-5.037760557292417, 119.60992723703386];
                var map = L.map("map", {
                    maxZoom: 30,
                    minZoom: 1
                }).setView(posisi, 13);

                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution: "&copy; OpenStreetMap contributors"
                }).addTo(map);

                var style_polygon = {
                    stroke: true,
                    color: "#cc1111",
                    opacity: 1,
                    weight: 2,
                    fillColor: "#ff9494",
                    fillOpacity: 0.1
                };

                var polygon_layer = L.polygon(polygon_desa_tanete, style_polygon).bindTooltip("Wilayah Desa Tanete").addTo(map);
                
                if (typeof polygon_dusun_tanete !== "undefined") {
                    L.geoJSON(polygon_dusun_tanete, {
                        style: function(feature) {
                            return {
                                color: feature.properties.color,
                                weight: 2,
                                opacity: 1,
                                fillColor: feature.properties.color,
                                fillOpacity: 0.3
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            if (feature.properties && feature.properties.name) {
                                var tooltipContent = "Dusun " + feature.properties.name;
                                try {
                                    if (typeof turf !== "undefined" && turf.area) {
                                        var areaSqM = turf.area(feature);
                                        var areaHa = (areaSqM / 10000).toFixed(1); // 1 decimal place e.g. 21.5
                                        tooltipContent += "<br>Luas " + areaHa + " Ha";
                                    }
                                } catch (e) {
                                    console.error("Error calculating area:", e);
                                }
                                layer.bindTooltip(tooltipContent, {
                                    permanent: false,
                                    direction: "center",
                                    className: "dusun-label"
                                });
                            }
                        }
                    }).addTo(map);
                }
                
                map.fitBounds(polygon_layer.getBounds());
            } else {
                document.getElementById("map").innerHTML = "<p style='text-align:center; margin-top:20%; font-family:sans-serif;'>Gagal memuat peta.</p>";
            }
        });
    </script>

</body>

</html>