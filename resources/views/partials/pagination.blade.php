@php($paginator = $paginator ?? null)

@if ($paginator)
    <div class="rf-pagination">
        <p class="rf-page-info">
            Menampilkan
            <strong>{{ $paginator->firstItem() ?? 0 }}</strong>&ndash;<strong>{{ $paginator->lastItem() ?? 0 }}</strong>
            dari <strong>{{ $paginator->total() }}</strong> data
        </p>

        @if ($paginator->hasPages())
            <div class="rf-page-links">
                @if ($paginator->onFirstPage())
                    <span class="rf-page-link is-disabled">Sebelumnya</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="rf-page-link" rel="prev">Sebelumnya</a>
                @endif

                @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                    @if ($page === $paginator->currentPage())
                        <span class="rf-page-link is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="rf-page-link">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="rf-page-link" rel="next">Berikutnya</a>
                @else
                    <span class="rf-page-link is-disabled">Berikutnya</span>
                @endif
            </div>
        @endif
    </div>
@endif
