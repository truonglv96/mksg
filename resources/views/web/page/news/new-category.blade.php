@extends('web.master')

@section('title', $title ?? 'Tin Tức - Mắt Kính Sài Gòn')

@section('content')
<main class="container mx-auto px-4 py-8">
    {{-- Breadcrumb Component --}}
    @include('web.partials.breadcrumb')
    
    {{-- Hero giống bản HTML gốc --}}
    <section class="news-hero">
        <span
            class="inline-flex items-center gap-2 px-3 py-1 text-sm font-semibold text-red-600 bg-white/70 border border-red-200 rounded-full mb-4 shadow-sm w-fit">
            📰 Trang tin Mắt Kính Sài Gòn
        </span>
        <h1>Kho kiến thức &amp; bản tin cập nhật cho người yêu đôi mắt</h1>
        <p class="text-gray-600 text-lg max-w-3xl">
            Cùng khám phá xu hướng kính mới, mẹo chăm sóc mắt và câu chuyện tại hệ thống showroom của Mắt Kính Sài
            Gòn.
            Chúng tôi cập nhật mỗi tuần để bạn luôn dẫn đầu phong cách và bảo vệ thị lực tốt nhất.
        </p>
        <div class="news-hero__meta">
            <span>📍 3 showroom tại TP.HCM</span>
            <span>🕒 Thứ 2 - Thứ 7: 08h00 - 20h30</span>
            <span>📞 Hotline: 0888 368 888</span>
        </div>
    </section>

    {{-- Bộ lọc: chọn danh mục theo URL giống danh mục sản phẩm + search theo request --}}
    <section class="news-filter">
        <form class="news-search" method="GET" action="">
            <input
                type="search"
                id="news-search"
                name="keyword"
                value="{{ request('keyword') }}"
                placeholder="Tìm kiếm bài viết, chủ đề hoặc thương hiệu...">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M21 21l-5.2-5.2m1.45-4.55A6.25 6.25 0 1110 4.25a6.25 6.25 0 017.25 6.25z" />
            </svg>
        </form>
        @php
            // Category làm gốc cho nhóm filter hiện tại
            $baseCategory = $typesBaseCategory ?? null;
            $baseNewsUrl = url('/tin-tuc');
            if ($baseCategory) {
                $baseNewsUrl = url('/tin-tuc/' . $baseCategory->getFullPath());
            }
        @endphp

        <a href="{{ $baseNewsUrl }}"
           class="news-chip {{ !isset($currentCategory) || !$currentCategory || ($baseCategory && $currentCategory && $currentCategory->id === $baseCategory->id) ? 'active' : '' }}">
            Tất cả
        </a>

        @if(isset($newsTypes) && $newsTypes->count())
            @foreach($newsTypes as $type)
                @php
                    // Nếu đang ở nhóm con: path = fullPath(baseCategory) . '/' . aliasCon
                    // Nếu đang ở root: path = aliasCon
                    $path = $baseCategory
                        ? $baseCategory->getFullPath() . '/' . $type->alias
                        : $type->alias;
                    $isActive = isset($currentCategory) && $currentCategory && $currentCategory->id == $type->id;
                @endphp
                <a href="{{ url('/tin-tuc/' . $path) }}"
                   class="news-chip {{ $isActive ? 'active' : '' }}">
                    {{ $type->name ?? $type->title ?? 'Danh mục' }}
                </a>
            @endforeach
        @else
            <button type="button" class="news-chip" data-filter="kien-thuc">Kiến thức mắt</button>
            <button type="button" class="news-chip" data-filter="xu-huong">Xu hướng kính</button>
            <button type="button" class="news-chip" data-filter="cham-soc">Chăm sóc mắt</button>
            <button type="button" class="news-chip" data-filter="su-kien">Sự kiện nổi bật</button>
        @endif
    </section>

    

    {{-- Layout: grid + sidebar giống bản HTML gốc --}}
    <section class="news-layout">
        <div class="w-full">
            <div class="news-grid" id="news-grid">
                @if(isset($news) && $news->count())
                    @foreach($news as $item)
                        @php
                            $itemCategories = method_exists($item, 'categoriesNewsByID') ? $item->categoriesNewsByID() : [];
                            $itemCategory = !empty($itemCategories) ? reset($itemCategories) : null;
                            $categoryId = $itemCategory->id ?? '';
                            $excerptSource = $item->description ?? $item->content ?? '';
                            $excerpt = \Illuminate\Support\Str::limit(strip_tags($excerptSource), 200);
                        @endphp
                        <article class="news-card" data-category-id="{{ $categoryId }}">
                            <div class="news-card__image">
                                <a href="{{ route('new.detail', $item->alias) }}">
                                    <img src="{{ $item->getImage() }}" alt="{{ $item->title }}">
                                </a>
                            </div>
                            <div class="news-card__content">
                                <span class="news-badge text-red-600 bg-red-50 uppercase">
                                    {{ $itemCategory->name ?? 'Tin tức' }}
                                </span>
                                <h3 class="news-title">
                                    <a href="{{ route('new.detail', $item->alias) }}" class="hover:text-red-600">
                                        {{ $item->name }}
                                    </a>
                                </h3>
                                @if($excerpt)
                                    <p>{!! $excerpt !!}</p>
                                @endif
                                <div class="news-card__footer">
                                    @php
                                        $created = optional($item->created_at);
                                        $dateText = $created->isValid()
                                            ? $created->format('d/m/Y')
                                            : '';
                                    @endphp
                                    <span>
                                        {{ $dateText }}
                                        @if($dateText)
                                            • 5 phút đọc
                                        @endif
                                    </span>
                                    <a href="{{ route('new.detail', $item->alias) }}">Đọc tiếp →</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <p class="text-center text-sm text-slate-500 py-6 col-span-full">
                        Hiện chưa có bài viết nào.
                    </p>
                @endif
            </div>

            {{-- Pagination: dynamic nhưng style bám theo .news-pagination trong CSS (giống HTML gốc: các nút tròn) --}}
            @if(isset($news) && $news->hasPages())
                <nav class="news-pagination" aria-label="Phân trang bài viết">
                    @php
                        $news->appends(request()->query());
                        $currentPage = $news->currentPage();
                        $lastPage = $news->lastPage();
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                    @endphp

                    {{-- Prev --}}
                    @if($news->onFirstPage())
                        <button type="button" aria-label="Trang trước" disabled>←</button>
                    @else
                        <button type="button" aria-label="Trang trước"
                            onclick="window.location='{{ $news->previousPageUrl() }}'">←</button>
                    @endif

                    {{-- First + dots --}}
                    @if($startPage > 1)
                        <button type="button" onclick="window.location='{{ $news->url(1) }}'">1</button>
                        @if($startPage > 2)
                            <button type="button" disabled>...</button>
                        @endif
                    @endif

                    {{-- Range --}}
                    @for($page = $startPage; $page <= $endPage; $page++)
                        @if($page == $currentPage)
                            <button type="button" class="active">{{ $page }}</button>
                        @else
                            <button type="button" onclick="window.location='{{ $news->url($page) }}'">
                                {{ $page }}
                            </button>
                        @endif
                    @endfor

                    {{-- Dots + last --}}
                    @if($endPage < $lastPage)
                        @if($endPage < $lastPage - 1)
                            <button type="button" disabled>...</button>
                        @endif
                        <button type="button" onclick="window.location='{{ $news->url($lastPage) }}'">
                            {{ $lastPage }}
                        </button>
                    @endif

                    {{-- Next --}}
                    @if($news->hasMorePages())
                        <button type="button" aria-label="Trang tiếp theo"
                            onclick="window.location='{{ $news->nextPageUrl() }}'">→</button>
                    @else
                        <button type="button" aria-label="Trang tiếp theo" disabled>→</button>
                    @endif
                </nav>
            @endif
        </div>
    </section>

</main>

@endsection
