<?php
include('db.php');

$content = '
<div class="about-container">
    <div class="hero-section">
        <h1>TIMEPIECE</h1>
        <p class="hero-subtitle">Nơi Thời Gian Trở Nên Đẳng Cấp</p>
        <p class="tagline">Chuyên cung cấp các dòng đồng hồ cao cấp từ những thương hiệu hàng đầu thế giới</p>
        <a href="#story" class="scroll-down">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>

    <div id="story" class="story-section">
        <h2>Câu Chuyện Của Chúng Tôi</h2>
        <div class="story-content">
            <div class="story-image-wrapper">
                <img src="https://png.pngtree.com/png-clipart/20230923/original/pngtree-clock-logo-icon-clock-idea-hour-vector-png-image_12532476.png" alt="TIMEPIECE Store" class="story-image">
            </div>
            <div class="story-text">
                <p class="story-highlight">Được thành lập vào năm 2010, TIMEPIECE đã trở thành điểm đến tin cậy cho những người yêu thích đồng hồ tại Việt Nam và thế giới.</p>
                <p>Với hơn 10 năm kinh nghiệm, chúng tôi tự hào mang đến:</p>
                <ul class="story-features">
                    <li><i class="fas fa-check"></i> Sản phẩm chính hãng 100%</li>
                    <li><i class="fas fa-check"></i> Dịch vụ chuyên nghiệp</li>
                    <li><i class="fas fa-check"></i> Chế độ bảo hành toàn cầu</li>
                    <li><i class="fas fa-check"></i> Tư vấn chuyên sâu</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="values-section">
        <h2>Giá Trị Cốt Lõi</h2>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-gem"></i>
                </div>
                <h3>Chất Lượng</h3>
                <p>Cam kết 100% hàng chính hãng với chế độ bảo hành toàn cầu</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Uy Tín</h3>
                <p>Xây dựng niềm tin với khách hàng qua từng giao dịch</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Tận Tâm</h3>
                <p>Dịch vụ chăm sóc khách hàng 24/7 với đội ngũ chuyên nghiệp</p>
            </div>
        </div>
    </div>

    <div class="brands-section">
        <h2>Thương Hiệu Đối Tác</h2>
        <div class="brands-slider">
            <div class="brand-item">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSxZrFN638eOk-QDDe2SFca2tYHi8d45R2Ixg&s" alt="Rolex">
            </div>
            <div class="brand-item">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Hublot_logo.png/640px-Hublot_logo.png" alt="Hublot">
            </div>
            <div class="brand-item">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/55/Richard_Mille_Logo.svg/2560px-Richard_Mille_Logo.svg.png" alt="Richard Mille">
            </div>
            <div class="brand-item">
                <img src="https://mondialbrand.com/wp-content/uploads/2024/01/Patek-Philippe-Logo.jpg" alt="Patek Philippe">
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
    padding: 200px 0;
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url("https://images.unsplash.com/photo-1547996160-81dfa63595aa") no-repeat center;
    background-size: cover;
    color: white;
    position: relative;
    margin-bottom: 0;
}

.hero-section h1 {
    font-size: 4.5em;
    margin-bottom: 10px;
    font-weight: 700;
    letter-spacing: 3px;
}

.hero-subtitle {
    font-size: 2em;
    margin-bottom: 20px;
    font-weight: 300;
}

.tagline {
    font-size: 1.2em;
    font-weight: 300;
    letter-spacing: 1px;
}

.scroll-down {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    font-size: 2em;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-30px);
    }
    60% {
        transform: translateY(-15px);
    }
}

.story-section {
    padding: 100px 0;
    background: #fff;
}

.story-content {
    display: flex;
    align-items: center;
    gap: 50px;
    margin-top: 50px;
}

.story-image-wrapper {
    flex: 1;
    position: relative;
}

.story-image {
    width: 100%;
    border-radius: 15px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.story-text {
    flex: 1;
}

.story-highlight {
    font-size: 1.2em;
    color: #333;
    margin-bottom: 30px;
    line-height: 1.6;
}

.story-features {
    list-style: none;
    padding: 0;
}

.story-features li {
    margin: 15px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.story-features i {
    color: #d4af37;
}

.values-section {
    padding: 100px 0;
    background: #f9f9f9;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
    margin-top: 50px;
}

.value-card {
    background: white;
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

.value-card:hover {
    transform: translateY(-15px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.value-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: #f8f4e5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.value-card i {
    font-size: 2em;
    color: #d4af37;
}

.brands-section {
    padding: 100px 0;
    background: #fff;
}

.brands-slider {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 50px;
    flex-wrap: wrap;
}

.brand-item {
    flex: 1;
    min-width: 200px;
    padding: 20px;
    text-align: center;
}

.brand-item img {
    max-width: 150px;
    filter: grayscale(100%);
    transition: all 0.3s ease;
}

.brand-item:hover img {
    filter: grayscale(0%);
    transform: scale(1.1);
}

.contact-section {
    padding: 100px 0;
    background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url("https://images.unsplash.com/photo-1557531365-e8b22d93dbd0") no-repeat center;
    background-size: cover;
    color: white;
    position: relative;
}

.contact-info {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
    margin-top: 50px;
    position: relative;
    z-index: 2;
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
        padding: 150px 20px;
    }
    
    .hero-section h1 {
        font-size: 3em;
    }
    
    .story-content {
        flex-direction: column;
        padding: 0 20px;
    }
    
    .values-grid {
        grid-template-columns: 1fr;
        padding: 0 20px;
    }
    
    .contact-info {
        grid-template-columns: 1fr;
        padding: 0 20px;
    }
    
    .brands-slider {
        justify-content: center;
    }
    
    .brand-item {
        min-width: 150px;
    }
}
</style>
';

include("layout.php");
