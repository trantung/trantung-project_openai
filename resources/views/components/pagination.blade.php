<div class="pagination">
    <div class="count-pagination">
        <span>{{ $pagination->firstItem() }} - {{ $pagination->lastItem() }} of {{ $pagination->total() }}</span>
    </div>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            {{--Previous --}}
            @if ($pagination->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $pagination->appends(request()->query())->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            @endif

            {{-- page --}}
            @for ($i = 1; $i <= $pagination->lastPage(); $i++)
                <li class="page-item {{ $pagination->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $pagination->appends(request()->query())->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Next --}}
            @if ($pagination->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $pagination->appends(request()->query())->nextPageUrl() }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
</div>
