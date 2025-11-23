@extends('web.master')

@section('title', $title ?? 'Tin Tức - Mắt Kính Sài Gòn')

@section('content')
<main class="container mx-auto px-4 py-8">
    <!-- News Hero Section -->
    <section class="news-hero">
        <h1>Tin Tức Mắt Kính Sài Gòn</h1>
        <p class="text-gray-600 text-lg">Cập nhật những tin tức mới nhất về sản phẩm và dịch vụ</p>
        <div class="news-hero__meta">
            <span>📰 <strong>Tổng số bài viết:</strong> 24</span>
            <span>📅 <strong>Cập nhật:</strong> Hôm nay</span>
        </div>
    </section>

    <!-- News Filter -->
    <div class="news-filter">
        <div class="news-search">
            <input type="text" id="news-search" placeholder="Tìm kiếm tin tức...">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <button type="button" class="news-chip active" data-filter="all">Tất cả</button>
        <button type="button" class="news-chip" data-filter="product">Sản phẩm</button>
        <button type="button" class="news-chip" data-filter="promotion">Khuyến mãi</button>
        <button type="button" class="news-chip" data-filter="knowledge">Kiến thức</button>
        <button type="button" class="news-chip" data-filter="event">Sự kiện</button>
    </div>

    <!-- News Featured Section -->
    <div class="news-featured">
        <article class="news-featured__main">
            <img src="https://matkinhsaigon.com.vn/img/news/featured-main.jpg" alt="Tin tức nổi bật">
            <div class="news-featured__content">
                <span class="news-badge">🔥 Nổi bật</span>
                <h2 class="text-2xl font-bold mb-2">Xu hướng kính mắt 2025: Những điều bạn cần biết</h2>
                <div class="news-meta">
                    <span>📅 15/01/2025</span>
                    <span>👁️ 1,234 lượt xem</span>
                    <span>🏷️ Kiến thức</span>
                </div>
            </div>
        </article>
        <div class="news-featured__secondary">
            <article>
                <img src="https://matkinhsaigon.com.vn/img/news/featured-1.jpg" alt="Tin tức 1">
                <span class="news-badge">Sản phẩm</span>
                <h3 class="font-bold text-lg mt-2">Bộ sưu tập kính mới nhất 2025</h3>
                <div class="news-meta">
                    <span>📅 14/01/2025</span>
                </div>
            </article>
            <article>
                <img src="https://matkinhsaigon.com.vn/img/news/featured-2.jpg" alt="Tin tức 2">
                <span class="news-badge">Khuyến mãi</span>
                <h3 class="font-bold text-lg mt-2">Giảm giá 30% cho tất cả sản phẩm</h3>
                <div class="news-meta">
                    <span>📅 13/01/2025</span>
                </div>
            </article>
        </div>
    </div>

    <!-- News Layout: Grid + Sidebar -->
    <div class="news-layout">
        <div>
            <div id="news-grid" class="news-grid">
                @for($i = 1; $i <= 12; $i++)
                <article class="news-card" data-category="{{ $i % 4 === 0 ? 'product' : ($i % 4 === 1 ? 'promotion' : ($i % 4 === 2 ? 'knowledge' : 'event')) }}">
                    <div class="news-card__image">
                        <img src="https://matkinhsaigon.com.vn/img/news/news-{{ $i }}.jpg" alt="Tin tức {{ $i }}">
                    </div>
                    <div class="news-card__content">
                        <span class="news-badge text-red-600 bg-red-50 uppercase">
                            @if($i % 4 === 0) Sản phẩm
                            @elseif($i % 4 === 1) Khuyến mãi
                            @elseif($i % 4 === 2) Kiến thức
                            @else Sự kiện
                            @endif
                        </span>
                        <h3>Tiêu đề tin tức số {{ $i }} - Xu hướng mới trong ngành kính mắt</h3>
                        <p>Mô tả ngắn về tin tức này. Đây là một bài viết thú vị về các xu hướng mới nhất trong ngành kính mắt và cách chọn lựa sản phẩm phù hợp...</p>
                        <div class="news-card__footer">
                            <span>📅 {{ date('d/m/Y', strtotime("-$i days")) }}</span>
                            <a href="{{ route('new.detail', $i) }}">Đọc tiếp →</a>
                        </div>
                    </div>
                </article>
                @endfor
            </div>

            <!-- News Pagination -->
            <div class="news-pagination">
                <button>&lt;</button>
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button>4</button>
                <button>&gt;</button>
            </div>
        </div>

        <!-- News Sidebar -->
        <aside class="news-aside">
            <h3>Tin nổi bật</h3>
            <ul class="news-trending">
                @for($i = 1; $i <= 5; $i++)
                <li>
                    <a href="{{ route('new.detail', $i) }}">Top {{ $i }} xu hướng kính mắt 2025</a>
                    <span>📅 {{ date('d/m/Y', strtotime("-$i days")) }}</span>
                </li>
                @endfor
            </ul>

            <div class="showroom-card">
                <strong>🏪 Showroom gần bạn</strong>
                <p>301B Điện Biên Phủ, Quận 3</p>
                <p>245C Xô Viết Nghệ Tĩnh, Bình Thạnh</p>
                <p>90 Nguyễn Hữu Thọ, Bà Rịa</p>
            </div>
        </aside>
    </div>

    <!-- Subscribe Card -->
    <div class="subscribe-card">
        <div>
            <h3 class="text-2xl font-bold mb-2">Đăng ký nhận tin</h3>
            <p class="text-gray-600">Nhận thông tin về sản phẩm mới và khuyến mãi độc quyền</p>
        </div>
        <form>
            <input type="email" placeholder="Nhập email của bạn">
            <button type="submit">Đăng ký</button>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
    // Đợi tất cả scripts load xong
    window.addEventListener('load', function() {
        setTimeout(function() {
            initNewsFilter();
        }, 100);
    });

    function initNewsFilter() {
        try {
            console.log('Initializing news filter...');
            
            // News filter logic
            const newsCards = Array.from(document.querySelectorAll('.news-card'));
            const newsChips = Array.from(document.querySelectorAll('.news-chip'));
            const searchInput = document.getElementById('news-search');
            const newsGrid = document.getElementById('news-grid');

            console.log('Found news cards:', newsCards.length);
            console.log('Found news chips:', newsChips.length);

            // Kiểm tra xem có elements không
            if (newsCards.length === 0) {
                console.warn('Không tìm thấy news cards');
                return;
            }

            if (newsChips.length === 0) {
                console.warn('Không tìm thấy news chips');
                return;
            }

            // Tạo empty state message nếu chưa có
            let emptyState = document.getElementById('news-empty-state');
            if (!emptyState && newsGrid) {
                emptyState = document.createElement('p');
                emptyState.className = 'text-center text-sm text-slate-500 py-6 hidden col-span-full';
                emptyState.textContent = 'Không tìm thấy bài viết phù hợp với bộ lọc hiện tại.';
                emptyState.id = 'news-empty-state';
                newsGrid.appendChild(emptyState);
            }

            let activeFilter = 'all';
            let searchTerm = '';

            const applyFilters = () => {
                let visibleCount = 0;
                newsCards.forEach(card => {
                    const category = card.dataset.category || '';
                    const matchesCategory = activeFilter === 'all' || category === activeFilter;
                    const text = card.textContent.toLowerCase();
                    const matchesSearch = !searchTerm || text.includes(searchTerm.toLowerCase());
                    const shouldShow = matchesCategory && matchesSearch;
                    
                    if (shouldShow) {
                        card.style.display = '';
                        card.classList.remove('hidden');
                        visibleCount += 1;
                    } else {
                        card.style.display = 'none';
                        card.classList.add('hidden');
                    }
                });

                // Hiển thị/ẩn empty state
                if (emptyState) {
                    if (visibleCount === 0) {
                        emptyState.style.display = '';
                        emptyState.classList.remove('hidden');
                    } else {
                        emptyState.style.display = 'none';
                        emptyState.classList.add('hidden');
                    }
                }
                
                console.log('Visible cards:', visibleCount);
            };

            // Event listeners cho filter chips
            newsChips.forEach(chip => {
                chip.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const filter = this.dataset.filter || 'all';
                    
                    if (filter === activeFilter) return;
                    
                    activeFilter = filter;
                    newsChips.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    console.log('Filter changed to:', activeFilter);
                    applyFilters();
                });
            });

            // Event listener cho search input
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    searchTerm = e.target.value.trim();
                    console.log('Search term:', searchTerm);
                    applyFilters();
                });
            }

            // Khởi tạo filter ban đầu
            applyFilters();
            
            console.log('News filter initialized successfully');
        } catch (error) {
            console.error('Error initializing news filter:', error);
        }
    }
</script>
@endpush
