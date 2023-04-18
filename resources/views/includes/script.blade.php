<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/js/bootstrap-colorpicker.min.js"
    integrity="sha512-94dgCw8xWrVcgkmOc2fwKjO4dqy/X3q7IjFru6MHJKeaAzCvhkVtOS6S+co+RbcZvvPBngLzuVMApmxkuWZGwQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.13.1/datatables.min.js"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_t3Rx8xSCJTpFcNEHAHyzLvDANw-SAuQ&libraries=places&callback=initMap"
    async defer></script>

<script type="text/javascript">
    $(function() {
        $('#category-name').on('keyup', function() {
            $('#category-demo .category-demo-text').text($(this).val());
        });

    });

    $(function() {
        $('#color')
            .colorpicker({})
            .on('colorpickerChange', function(
            e) { //change the bacground color of the main when the color changes
                new_color = e.color.toString()
                console.log($('#category-demo').css('background-color', new_color))
                $('#category-demo').css('background-color', new_color)
            })
    });
    $(document).ready(() => {
        $('#icon').change(function() {
            console.log('tess')
            const file = this.files[0];
            console.log(file);
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    console.log(event.target.result);
                    $('#imgPreview').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });
</script>
<script>
    $(function() {
        $("#price").keyup(function(e) {
            $(this).val(format($(this).val()));
        });
    });
    var format = function(num) {
        var str = num.toString().replace("", ""),
            parts = false,
            output = [],
            i = 1,
            formatted = null;
        if (str.indexOf(".") > 0) {
            parts = str.split(".");
            str = parts[0];
        }
        str = str.split("").reverse();
        for (var j = 0, len = str.length; j < len; j++) {
            if (str[j] != ",") {
                output.push(str[j]);
                if (i % 3 == 0 && j < (len - 1)) {
                    output.push(",");
                }
                i++;
            }
        }
        formatted = output.reverse().join("");
        return ("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
    };
</script>

<script>
    const navbar = document.querySelector(".col-navbar");
    const cover = document.querySelector(".screen-cover");

    const sidebar_items = document.querySelectorAll(".sidebar-item");

    function toggleNavbar() {
        navbar.classList.toggle("d-none");
        cover.classList.toggle("d-none");
    }

    function toggleActive(e) {
        sidebar_items.forEach(function(v, k) {
            v.classList.remove("active");
        });
        e.closest(".sidebar-item").classList.add("active");
    }
</script>
<script>
    var map;
    var marker;
    var autocomplete;

    function initMap() {

        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: -6.917464,
                lng: 107.619123
            },
            zoom: 15
        });

        marker = new google.maps.Marker({
            map: map,
            position: {
                lat: -6.917464,
                lng: 107.619123
            },
            draggable: true
        });
    var geocoder = new google.maps.Geocoder();

        google.maps.event.addListener(marker, 'dragend', function(event) {
            var newLatLng = marker.getPosition();
            // Ambil alamat baru menggunakan Geocoder

            document.getElementById("latitude").value = event.latLng.lat();
            document.getElementById("longitude").value = event.latLng.lng();

     geocoder.geocode({
                'latLng': newLatLng
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        // Update form dengan alamat baru
                        document.getElementById('address').value = results[0].formatted_address;
                    }
                }
            });
        });

        autocomplete = new google.maps.places.Autocomplete(document.getElementById('search'));
        autocomplete.bindTo('bounds', map);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            document.getElementById("latitude").value = place.geometry.location.lat();
            document.getElementById("longitude").value = place.geometry.location.lng();
            document.getElementById("address").value = place.formatted_address;
        });
    }
</script>
