<?php
// Query lấy danh mục sản phẩm từ Database
$sql_footer_cat = "SELECT * FROM CATEGORIES WHERE parent_id IS NULL ORDER BY thu_tu ASC";
$res_footer_cat = mysqli_query($conn, $sql_footer_cat);
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<footer class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row pt-4">
            <div class="col-md-4 mb-4">
                <h3 class="fw-bold mb-3" style="letter-spacing: -1px;">RABU Keyboard</h3>
                <p class="text-secondary small">
                    Elevating your typing experience with world-class Magnetic Switch technology. Precision in every
                    keystroke, designed for enthusiasts and pro gamers.
                </p>
                <div class="d-flex gap-3 mt-4">
                    <a href="https://www.facebook.com/Sayurisiucute" class="text-white fs-5"><i
                            class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="text-white fs-5"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="https://www.instagram.com/fujihana_sayuri/" class="text-white fs-5"><i
                            class="fa-brands fa-instagram"></i></a>
                    <a href="https://discord.gg/TgQDd6d7" class="text-white fs-5"><i
                            class="fa-brands fa-discord"></i></a>
                </div>
            </div>

            <div class="col-6 col-md-2 mb-4">
                <h6 class="fw-bold text-uppercase mb-3 small">Shop</h6>
                <ul class="nav flex-column">
                    <?php
                    if (mysqli_num_rows($res_footer_cat) > 0) {
                        while ($f_cat = mysqli_fetch_assoc($res_footer_cat)) {
                            echo '<li class="nav-item mb-2">';
                            echo '<a href="index.php?page=products&cat=' . $f_cat['slug'] . '" class="nav-link p-0 text-secondary small footer-link">' . $f_cat['ten'] . '</a>';
                            echo '</li>';
                        }
                    } else {
                        echo '<li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-secondary small footer-link">Keyboards</a></li>';
                    }
                    ?>
                </ul>
            </div>

            <div class="col-6 col-md-2 mb-4">
                <h6 class="fw-bold text-uppercase mb-3 small">Support</h6>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="index.php?page=order_status"
                            class="nav-link p-0 text-secondary small footer-link">Order Status</a>
                    </li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-secondary small footer-link">Shipping
                            Policy</a></li>
                    <li class="nav-item mb-2"><a href="#"
                            class="nav-link p-0 text-secondary small footer-link">Warranty</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-secondary small footer-link">Contact
                            Us</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-4">
                <h6 class="fw-bold text-uppercase mb-3 small">Stay in the loop</h6>
                <p class="text-secondary small">Subscribe to get notified about new drops.</p>
                <form class="mt-3 mb-4">
                    <div class="input-group">
                        <input type="email"
                            class="form-control bg-transparent border-secondary text-white rounded-0 shadow-none"
                            placeholder="Enter your email">
                        <button class="btn btn-light rounded-0 fw-bold px-4" type="button">JOIN</button>
                    </div>
                </form>

                <h6 class="fw-bold text-uppercase mb-2 small" style="font-size: 0.7rem; color: #ffffff;">Our Location
                </h6>
                <div id="footer-map" class="rounded-3 shadow-sm border border-secondary"
                    style="height: 160px; width: 100%;">
                </div>
            </div>
        </div>

        <div
            class="d-flex flex-column flex-sm-row justify-content-between py-4 mt-5 border-top border-secondary align-items-center">
            <p class="text-secondary mb-0" style="font-size: 0.75rem;">© 2026 RABU Keyboard. All rights reserved.</p>
            <div class="d-flex gap-3 align-items-center mt-3 mt-sm-0">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" height="15"
                    style="filter: brightness(0) invert(1); opacity: 0.5;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" height="20"
                    style="filter: brightness(0) invert(1); opacity: 0.5;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" height="18"
                    style="filter: brightness(0) invert(1); opacity: 0.5;">
            </div>
        </div>
    </div>
</footer>

<script>
    var myLocation = [10.369544, 105.431011];

    var footerMap = L.map('footer-map', {
        zoomControl: false,
        attributionControl: false
    }).setView(myLocation, 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(footerMap);

    // 1. Định nghĩa Icon màu đỏ
    var redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // 2. Gắn Marker với Icon vừa tạo
    var storeMarker = L.marker(myLocation, { icon: redIcon }).addTo(footerMap);

    storeMarker.bindPopup("<b style='color:black'>RABU Keyboard</b><br><small style='color:gray'>Custom mechanical keyboards</small>").openPopup();

    footerMap.on('click', function () {
        window.open("https://www.google.com/maps?q=" + myLocation[0] + "," + myLocation[1], '_blank');
    });
</script>

<style>
    #footer-map {
        transition: all 0.5s ease;
    }

    #footer-map:hover {
        filter: grayscale(0%) opacity(1);
        border-color: #ffcc00;
    }
</style>