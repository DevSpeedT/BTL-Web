<?php
$categories = [
    ["img" => "img/cat1.jpg", "name" => "Thời Trang Nam"],
    ["img" => "img/cat2.jpg", "name" => "Điện Thoại & Phụ Kiện"],
    ["img" => "img/cat3.jpg", "name" => "Thiết Bị Điện Tử"],
    ["img" => "img/cat4.jpg", "name" => "Máy Tính & Laptop"],
    ["img" => "img/cat5.jpg", "name" => "Mẹ & Bé"],
    ["img" => "img/cat6.jpg", "name" => "Túi Ví Nữ"],
    ["img" => "img/cat7.jpg", "name" => "Nhà Cửa & Đời Sống"],
    ["img" => "img/cat8.jpg", "name" => "Sắc Đẹp"],
    ["img" => "img/cat9.jpg", "name" => "Đồng Hồ"],
    ["img" => "img/cat10.jpg", "name" => "Giày Dép Nam"],
    ["img" => "img/cat11.jpg", "name" => "Thể Thao & Du Lịch"],
    ["img" => "img/cat12.jpg", "name" => "Ô Tô & Xe Máy"],
    ["img" => "img/cat13.jpg", "name" => "Sức Khỏe"],
    ["img" => "img/cat14.jpg", "name" => "Sách & Văn Phòng"],
    ["img" => "img/cat15.jpg", "name" => "Bách Hóa"],
    ["img" => "img/cat16.jpg", "name" => "Thiết Bị Gia Dụng"],
    ["img" => "img/cat17.jpg", "name" => "Đồ Chơi"],
    ["img" => "img/cat18.jpg", "name" => "Thực Phẩm"],
];
$slides = array_chunk($categories, 16);
?>

<section class="category-section container my-4">
    <h2 class="mb-3">DANH MỤC</h2>
    <div class="swiper categorySwiper">
        <div class="swiper-wrapper">
            <?php foreach ($slides as $slide): ?>
                <div class="swiper-slide">
                    <div class="category-grid">
                        <?php foreach ($slide as $cat): ?>
                            <div class="category-block">
                                <img src="<?= $cat['img'] ?>" alt="<?= $cat['name'] ?>">
                                <p><?= $cat['name'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</section>