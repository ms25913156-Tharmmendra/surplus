<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Post Surplus Food</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <style>
    body {
      font-family: Poppins, sans-serif;
      background-color: #f5f5f5;
      padding: 20px;
    }
    #map {
      height: 400px;
      margin-top: 15px;
      display: none;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .location-search {
      display: flex;
      gap: 10px;
    }
    input[type="text"] {
      flex: 1;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Post Surplus Food Details</h2>

    <form id="foodForm" method="POST" action="postfood.php">
      <div class="form-group">
        <label for="pickupLocation">Pickup Location</label>
        <div class="location-search">
          <input type="text" id="pickupLocation" name="pickupLocation" placeholder="Enter or pick address" required />
          <button type="button" class="btn btn-danger" id="getLocationBtn">
            <i class="fas fa-map-marker-alt"></i> Get Location
          </button>
        </div>
      </div>

      <input type="hidden" id="latitude" name="latitude" />
      <input type="hidden" id="longitude" name="longitude" />

      <!-- other food detail inputs here -->

      <div id="map"></div>

      <button type="submit" class="btn btn-success mt-3">Post Food Details</button>
    </form>
  </div>

  <script>
    let map, marker;

    document.getElementById("getLocationBtn").addEventListener("click", () => {
      const mapDiv = document.getElementById("map");
      mapDiv.style.display = "block";

      if (!map) {
        // Initialize map centered on Jaffna
        map = L.map("map").setView([9.6615, 80.0217], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
          attribution: "&copy; OpenStreetMap contributors",
        }).addTo(map);

        marker = L.marker([9.6615, 80.0217], { draggable: true }).addTo(map);

        marker.on("dragend", function (e) {
          const latlng = marker.getLatLng();
          updateLatLng(latlng.lat, latlng.lng);
          reverseGeocode(latlng.lat, latlng.lng);
        });
      }

      // Try to get current geolocation
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;

          map.setView([lat, lng], 14);
          marker.setLatLng([lat, lng]);
          updateLatLng(lat, lng);
          reverseGeocode(lat, lng);
        });
      }
    });

    // Handle address search
    document.getElementById("pickupLocation").addEventListener("change", function () {
      const address = this.value;
      fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
        .then((res) => res.json())
        .then((data) => {
          if (data.length > 0) {
            const lat = parseFloat(data[0].lat);
            const lon = parseFloat(data[0].lon);

            if (map && marker) {
              map.setView([lat, lon], 14);
              marker.setLatLng([lat, lon]);
            }

            updateLatLng(lat, lon);
          } else {
            alert("Location not found.");
          }
        });
    });

    function updateLatLng(lat, lng) {
      document.getElementById("latitude").value = lat;
      document.getElementById("longitude").value = lng;
    }

    function reverseGeocode(lat, lng) {
      fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
        .then((res) => res.json())
        .then((data) => {
          if (data && data.display_name) {
            document.getElementById("pickupLocation").value = data.display_name;
          }
        });
    }
  </script>

  <!-- Font Awesome for map icon -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
