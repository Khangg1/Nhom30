<?php
include('db.php');

$content = '
<div class="about-container">
    <div class="hero-section">
        <h1>Người Sáng Lập</h1>
        <p class="hero-subtitle">Nguyễn Thành Trung</p>
        <p class="tagline">Người sáng lập và điều hành TIMEPIECE</p>
    </div>

    <div class="founder-section">
        <div class="founder-content">
            <div class="founder-image-wrapper">
                <img src="https://hoanghamobile.com/tin-tuc/wp-content/uploads/2023/09/hinh-nen-ronaldo-4.jpg" alt="Người sáng lập TIMEPIECE" class="founder-image">
            </div>
            <div class="founder-info">
                <h2>Giới Thiệu</h2>
                <p class="founder-highlight">Với hơn 15 năm kinh nghiệm trong ngành đồng hồ cao cấp, Nguyễn Thành Trung đã xây dựng TIMEPIECE trở thành thương hiệu uy tín hàng đầu tại Việt Nam.</p>
                <div class="founder-details">
                    <div class="detail-item">
                        <i class="fas fa-graduation-cap"></i>
                        <div>
                            <h3>Học Vấn</h3>
                            <p>Cử nhân CNTT - Trường Đại học Hutech</p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-briefcase"></i>
                        <div>
                            <h3>Kinh Nghiệm</h3>
                            <p>15+ năm trong ngành đồng hồ cao cấp</p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-award"></i>
                        <div>
                            <h3>Thành Tựu</h3>
                            <p>Top 10 Doanh nhân trẻ thành công 2020</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="vision-section">
        <h2>Tầm Nhìn & Sứ Mệnh</h2>
        <div class="vision-content">
            <div class="vision-card">
                <i class="fas fa-eye"></i>
                <h3>Tầm Nhìn</h3>
                <p>Trở thành điểm đến số 1 về đồng hồ cao cấp tại Việt Nam, mang đến những sản phẩm chất lượng và dịch vụ xuất sắc.</p>
            </div>
            <div class="vision-card">
                <i class="fas fa-bullseye"></i>
                <h3>Sứ Mệnh</h3>
                <p>Nâng tầm giá trị thời gian thông qua những chiếc đồng hồ đẳng cấp và dịch vụ chuyên nghiệp.</p>
            </div>
        </div>
    </div>

    <div class="contact-section">
        <h2>Liên Hệ Trực Tiếp</h2>
        <div class="contact-info">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email</h3>
                <p>nguyenvana@timepiece.com</p>
            </div>
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fab fa-linkedin"></i>
                </div>
                <h3>LinkedIn</h3>
                <p>linkedin.com/in/nguyenvana</p>
            </div>
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Đặt Lịch Hẹn</h3>
                <p>Vui lòng liên hệ thư ký qua số: 0123.456.789</p>
            </div>
        </div>
    </div>
</div>

<style>
.about-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0;
    font-family: "Poppins", sans-serif;
}

.hero-section {
    text-align: center;
    padding: 150px 0;
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url("https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9") no-repeat center;
    background-size: cover;
    color: white;
    position: relative;
    margin-bottom: 0;
}

.hero-section h1 {
    font-size: 4em;
    margin-bottom: 20px;
    font-weight: 700;
}

.hero-subtitle {
    font-size: 2.5em;
    margin-bottom: 20px;
    font-weight: 500;
}

.tagline {
    font-size: 1.2em;
    font-weight: 300;
}

.founder-section {
    padding: 100px 0;
    background: #fff;
}

.founder-content {
    display: flex;
    align-items: flex-start;
    gap: 50px;
    padding: 0 20px;
}

.founder-image-wrapper {
    flex: 1;
}

.founder-image {
    width: 100%;
    border-radius: 15px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.founder-info {
    flex: 1;
}

.founder-highlight {
    font-size: 1.2em;
    color: #333;
    margin-bottom: 30px;
    line-height: 1.6;
}

.founder-details {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.detail-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
}

.detail-item i {
    font-size: 2em;
    color: #d4af37;
}

.vision-section {
    padding: 100px 0;
    background: #f9f9f9;
}

.vision-content {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 40px;
    padding: 0 20px;
    margin-top: 50px;
}

.vision-card {
    background: white;
    padding: 40px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

.vision-card i {
    font-size: 3em;
    color: #d4af37;
    margin-bottom: 20px;
}

.contact-section {
    padding: 100px 0;
    background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url("https://images.unsplash.com/photo-1557531365-e8b22d93dbd0") no-repeat center;
    background-size: cover;
    color: white;
}

.contact-info {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
    padding: 0 20px;
    margin-top: 50px;
}

.contact-card {
    text-align: center;
    padding: 40px 20px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    transition: all 0.3s ease;
}

.contact-card:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-10px);
}

.contact-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 20px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.contact-card i {
    font-size: 1.8em;
    color: #d4af37;
}

h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 2.5em;
    font-weight: 600;
    color: #333;
}

.contact-section h2 {
    color: white;
}

@media (max-width: 768px) {
    .hero-section {
        padding: 100px 20px;
    }
    
    .hero-section h1 {
        font-size: 3em;
    }
    
    .founder-content {
        flex-direction: column;
    }
    
    .vision-content {
        grid-template-columns: 1fr;
    }
    
    .contact-info {
        grid-template-columns: 1fr;
    }
}
</style>
';

include("layout.php");
