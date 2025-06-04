<?php
$pageTitle = 'Home';
include '../includes/header.php';
?>

<!-- Hero Carousel -->
<div class="hero-carousel">
    <div class="carousel-container" id="carouselContainer">
        <!-- Slide 1 -->
        <div class="carousel-slide" style="background-image: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
            <div class="carousel-content">
                <h1 class="carousel-title">Frieren: Beyond Journey's End</h1>
                <div class="carousel-meta">
                    <span>⭐ 9.4</span>
                    <span>📺 28 Episodes</span>
                    <span>🎌 Sub</span>
                    <span>📅 2023</span>
                </div>
                <p class="carousel-description">
                    After the party of heroes defeated the Demon King, they all went their separate ways. Decades have passed, and the elven mage Frieren comes to realize that being nearly immortal, she has begun to outlive her former companions.
                </p>
                <div class="carousel-buttons">
                    <button class="btn-watch">▶ Watch Now</button>
                    <button class="btn-info">ℹ More Info</button>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-slide" style="background-image: linear-gradient(135deg, #2d1b69 0%, #11998e 100%);">
            <div class="carousel-content">
                <h1 class="carousel-title">Solo Leveling</h1>
                <div class="carousel-meta">
                    <span>⭐ 8.9</span>
                    <span>📺 12 Episodes</span>
                    <span>🎌 Sub</span>
                    <span>📅 2024</span>
                </div>
                <p class="carousel-description">
                    In a world where hunters battle deadly monsters, Sung Jin-Woo is the weakest of all hunters. But when he's trapped in a dungeon with his fellow hunters, he discovers a mysterious system that allows him to level up in ways he never imagined.
                </p>
                <div class="carousel-buttons">
                    <button class="btn-watch">▶ Watch Now</button>
                    <button class="btn-info">ℹ More Info</button>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-slide" style="background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="carousel-content">
                <h1 class="carousel-title">The Apothecary Diaries</h1>
                <div class="carousel-meta">
                    <span>⭐ 8.7</span>
                    <span>📺 24 Episodes</span>
                    <span>🎌 Sub</span>
                    <span>📅 2023</span>
                </div>
                <p class="carousel-description">
                    Maomao, a young pharmacist, finds herself working as a servant in the imperial palace. Using her knowledge of medicine and poison, she solves mysteries and navigates the dangerous world of court intrigue.
                </p>
                <div class="carousel-buttons">
                    <button class="btn-watch">▶ Watch Now</button>
                    <button class="btn-info">ℹ More Info</button>
                </div>
            </div>
        </div>

        <!-- Slide 4 -->
        <div class="carousel-slide" style="background-image: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);">
            <div class="carousel-content">
                <h1 class="carousel-title">Chainsaw Man</h1>
                <div class="carousel-meta">
                    <span>⭐ 8.8</span>
                    <span>📺 12 Episodes</span>
                    <span>🎌 Sub</span>
                    <span>📅 2022</span>
                </div>
                <p class="carousel-description">
                    Denji is a young man trapped in poverty, working off his deceased father's debt by hunting devils with his pet devil Pochita. When Denji is killed, he merges with Pochita to become Chainsaw Man.
                </p>
                <div class="carousel-buttons">
                    <button class="btn-watch">▶ Watch Now</button>
                    <button class="btn-info">ℹ More Info</button>
                </div>
            </div>
        </div>

        <!-- Slide 5 -->
        <div class="carousel-slide" style="background-image: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
            <div class="carousel-content">
                <h1 class="carousel-title">Spy x Family</h1>
                <div class="carousel-meta">
                    <span>⭐ 9.0</span>
                    <span>📺 25 Episodes</span>
                    <span>🎌 Sub</span>
                    <span>📅 2022</span>
                </div>
                <p class="carousel-description">
                    Master spy Twilight must create a fake family to get close to his target. He adopts a telepathic girl and marries an assassin, not knowing their true identities, creating a family of secrets.
                </p>
                <div class="carousel-buttons">
                    <button class="btn-watch">▶ Watch Now</button>
                    <button class="btn-info">ℹ More Info</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <button class="carousel-nav carousel-prev" onclick="changeSlide(-1)">‹</button>
    <button class="carousel-nav carousel-next" onclick="changeSlide(1)">›</button>

    <!-- Indicators -->
    <div class="carousel-indicators">
        <button class="indicator active" onclick="currentSlide(1)"></button>
        <button class="indicator" onclick="currentSlide(2)"></button>
        <button class="indicator" onclick="currentSlide(3)"></button>
        <button class="indicator" onclick="currentSlide(4)"></button>
        <button class="indicator" onclick="currentSlide(5)"></button>
    </div>
</div>

<div class="container">
    <!-- Trending Section -->
    <section class="anime-section">
        <div class="section-header">
            <h2 class="section-title">Trending Now</h2>
            <a href="#" class="view-all">View All →</a>
        </div>
        <div class="anime-grid">

            <?php
            $trending_anime = [
                ['title' => 'Frieren: Beyond Journey\'s End', 'episode' => 12, 'type' => 'Sub'],
                ['title' => 'Solo Leveling', 'episode' => 8, 'type' => 'Sub'],
                ['title' => 'The Apothecary Diaries', 'episode' => 24, 'type' => 'Sub'],
                ['title' => 'Jujutsu Kaisen', 'episode' => 23, 'type' => 'Sub'],
                ['title' => 'Chainsaw Man', 'episode' => 12, 'type' => 'Sub'],
                ['title' => 'Spy x Family', 'episode' => 25, 'type' => 'Sub']
            ];
            foreach ($trending_anime as $i => $anime): ?>
                <div class="anime-card">
                    <div class="anime-thumbnail" style="background: linear-gradient(135deg, #<?php echo dechex(rand(0x333333, 0x666666)); ?> 0%, #<?php echo dechex(rand(0x111111, 0x444444)); ?> 100%);"></div>
                    <div class="anime-info">
                        <h3 class="anime-title"><?php echo htmlspecialchars($anime['title']); ?></h3>
                        <p class="anime-meta">Episode <?php echo $anime['episode']; ?> • <?php echo $anime['type']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Recent Updates Section -->
    <section class="anime-section">
        <div class="section-header">
            <h2 class="section-title">Recent Updates</h2>
            <a href="#" class="view-all">View All →</a>
        </div>
        <div class="anime-grid">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="anime-card">
                    <div class="anime-thumbnail"></div>
                    <div class="anime-info">
                        <h3 class="anime-title">Recent Anime <?php echo $i; ?></h3>
                        <p class="anime-meta">Updated <?php echo rand(1, 24); ?>h ago</p>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </section>

    <!-- Popular This Season -->
    <section class="anime-section">
        <div class="section-header">
            <h2 class="section-title">Popular This Season</h2>
            <a href="#" class="view-all">View All →</a>
        </div>
        <div class="anime-grid">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="anime-card">
                    <div class="anime-thumbnail"></div>
                    <div class="anime-info">
                        <h3 class="anime-title">Seasonal Hit <?php echo $i; ?></h3>
                        <p class="anime-meta">★ <?php echo number_format(rand(70, 99) / 10, 1); ?></p>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </section>
</div>

<script>
let currentSlideIndex = 0;
const slides = document.querySelectorAll('.carousel-slide');
const indicators = document.querySelectorAll('.indicator');
const totalSlides = slides.length;
let autoSlideInterval;

// Initialize carousel
function initCarousel() {
    showSlide(currentSlideIndex);
    startAutoSlide();
    
    // Add touch/swipe support with real-time feedback
    let touchStartX = 0;
    let touchCurrentX = 0;
    let touchStartTranslate = 0;
    let isTouching = false;
    const carousel = document.getElementById('carouselContainer');
    
    carousel.addEventListener('touchstart', (e) => {
        isTouching = true;
        touchStartX = e.touches[0].clientX;
        touchStartTranslate = -currentSlideIndex * 100;
        carousel.style.transition = 'none';
        stopAutoSlide();
    });
    
    carousel.addEventListener('touchmove', (e) => {
        if (!isTouching) return;
        e.preventDefault();
        touchCurrentX = e.touches[0].clientX;
        const dragDistance = touchCurrentX - touchStartX;
        const dragPercentage = (dragDistance / carousel.offsetWidth) * 100;
        let newTranslate = touchStartTranslate + dragPercentage;
        
        // Apply boundaries with elastic effect
        const maxTranslate = 0; // First slide boundary
        const minTranslate = -(totalSlides - 1) * 100; // Last slide boundary
        
        if (newTranslate > maxTranslate) {
            // Elastic resistance when dragging past first slide
            const overDrag = newTranslate - maxTranslate;
            newTranslate = maxTranslate + (overDrag * 0.3); // 30% resistance
        } else if (newTranslate < minTranslate) {
            // Elastic resistance when dragging past last slide
            const overDrag = minTranslate - newTranslate;
            newTranslate = minTranslate - (overDrag * 0.3); // 30% resistance
        }
        
        // Apply real-time transform
        carousel.style.transform = `translateX(${newTranslate}%)`;
    });
    
    carousel.addEventListener('touchend', (e) => {
        if (!isTouching) return;
        isTouching = false;
        carousel.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        
        const dragDistance = touchCurrentX - touchStartX;
        const dragPercentage = (dragDistance / carousel.offsetWidth) * 100;
        
        if (Math.abs(dragPercentage) > 20) { // 20% threshold for touch
            if (dragPercentage > 0 && currentSlideIndex > 0) {
                changeSlide(-1);
            } else if (dragPercentage < 0 && currentSlideIndex < totalSlides - 1) {
                changeSlide(1);
            } else {
                // At boundary, snap back to current slide
                showSlide(currentSlideIndex);
            }
        } else {
            // Snap back to current slide if drag wasn't significant
            showSlide(currentSlideIndex);
        }
        
        // Restart auto-slide with a delay
        setTimeout(() => {
            startAutoSlide();
        }, 1000);
    });
    
    // Mouse drag support with real-time feedback
    let isDragging = false;
    let startPos = 0;
    let currentTranslate = 0;
    let prevTranslate = 0;
    
    carousel.addEventListener('mousedown', (e) => {
        isDragging = true;
        startPos = e.clientX;
        prevTranslate = -currentSlideIndex * 100;
        carousel.style.cursor = 'grabbing';
        carousel.style.transition = 'none';
        stopAutoSlide();
        e.preventDefault();
    });
    
    carousel.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const currentPosition = e.clientX;
        const dragDistance = currentPosition - startPos;
        const dragPercentage = (dragDistance / carousel.offsetWidth) * 100;
        let newTranslate = prevTranslate + dragPercentage;
        
        // Apply boundaries with elastic effect
        const maxTranslate = 0; // First slide boundary
        const minTranslate = -(totalSlides - 1) * 100; // Last slide boundary
        
        if (newTranslate > maxTranslate) {
            // Elastic resistance when dragging past first slide
            const overDrag = newTranslate - maxTranslate;
            newTranslate = maxTranslate + (overDrag * 0.3); // 30% resistance
        } else if (newTranslate < minTranslate) {
            // Elastic resistance when dragging past last slide
            const overDrag = minTranslate - newTranslate;
            newTranslate = minTranslate - (overDrag * 0.3); // 30% resistance
        }
        
        currentTranslate = newTranslate;
        
        // Apply real-time transform
        carousel.style.transform = `translateX(${currentTranslate}%)`;
    });
    
    carousel.addEventListener('mouseup', () => {
        if (!isDragging) return;
        isDragging = false;
        carousel.style.cursor = 'grab';
        
        // Smooth transition back
        carousel.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        
        const movedBy = currentTranslate - prevTranslate;
        const moveThreshold = 25; // Increased threshold for better control
        
        if (Math.abs(movedBy) > moveThreshold) {
            if (movedBy > 0 && currentSlideIndex > 0) {
                changeSlide(-1);
            } else if (movedBy < 0 && currentSlideIndex < totalSlides - 1) {
                changeSlide(1);
            } else {
                // At boundary, snap back to current slide
                showSlide(currentSlideIndex);
            }
        } else {
            // Snap back to current slide if drag wasn't significant
            showSlide(currentSlideIndex);
        }
        
        currentTranslate = 0;
        prevTranslate = 0;
        
        // Restart auto-slide with a delay to prevent immediate transition
        setTimeout(() => {
            startAutoSlide();
        }, 1000);
    });
    
    carousel.addEventListener('mouseleave', () => {
        if (isDragging) {
            isDragging = false;
            carousel.style.cursor = 'grab';
            carousel.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            showSlide(currentSlideIndex); // Snap back to current slide
            currentTranslate = 0;
            prevTranslate = 0;
            
            // Restart auto-slide with a delay
            setTimeout(() => {
                startAutoSlide();
            }, 1000);
        }
    });
}


function showSlide(index) {
    const carousel = document.getElementById('carouselContainer');
    
    // Clamp index to valid range
    index = Math.max(0, Math.min(index, totalSlides - 1));
    
    // Ensure smooth transition is enabled
    carousel.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    
    const translateX = -index * 100;
    carousel.style.transform = `translateX(${translateX}%)`;
    
    // Update current slide index
    currentSlideIndex = index;
    
    // Update indicators
    indicators.forEach((indicator, i) => {
        indicator.classList.toggle('active', i === index);
    });
}

function changeSlide(direction) {
    stopAutoSlide();
    
    let newIndex = currentSlideIndex + direction;
    
    // Handle wrapping
    if (newIndex >= totalSlides) {
        newIndex = 0;
    } else if (newIndex < 0) {
        newIndex = totalSlides - 1;
    }
    
    showSlide(newIndex);
    
    // Restart auto-slide with delay
    setTimeout(() => {
        startAutoSlide();
    }, 1000);
}

function currentSlide(index) {
    stopAutoSlide();
    showSlide(index - 1);
    
    // Restart auto-slide with delay
    setTimeout(() => {
        startAutoSlide();
    }, 1000);
}

function nextSlide() {
    const carousel = document.getElementById('carouselContainer');
    
    // Ensure transition is properly set
    carousel.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    
    let nextIndex = (currentSlideIndex + 1) % totalSlides;
    showSlide(nextIndex);
}

function startAutoSlide() {
    // Clear any existing interval first
    stopAutoSlide();
    // Increased interval to 8 seconds for better user experience
    autoSlideInterval = setInterval(nextSlide, 8000);
}

function stopAutoSlide() {
    clearInterval(autoSlideInterval);
}

// Pause auto-slide when hovering over carousel
document.querySelector('.hero-carousel').addEventListener('mouseenter', stopAutoSlide);
document.querySelector('.hero-carousel').addEventListener('mouseleave', startAutoSlide);

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initCarousel);

// Handle window resize
window.addEventListener('resize', () => {
    showSlide(currentSlideIndex);
});
</script>

<?php include '../includes/footer.php'; ?>