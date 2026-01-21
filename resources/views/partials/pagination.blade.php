@if ($paginator->hasPages())
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center d-flex flex-row mb-0">
            
            {{-- Nút Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link rounded-0" aria-hidden="true">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link rounded-0 shadow-sm" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">&laquo;</a>
                </li>
            @endif

            {{-- Các số trang --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link rounded-0">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link rounded-0 border-primary bg-primary">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link rounded-0 shadow-sm" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Nút Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link rounded-0 shadow-sm" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link rounded-0" aria-hidden="true">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif