<nav>
    <ul class="pagination justify-content-center mt-5">
      
        <!-- Page Numbers -->
        @php
            $totalPages = $paginator->lastPage();
            $currentPage = $paginator->currentPage();
            $pageRange = 5; // Maximum pages to show
            $startPage = max(1, $currentPage - floor($pageRange / 2));
            $endPage = min($totalPages, $currentPage + floor($pageRange / 2));

            if ($endPage - $startPage < $pageRange - 1) {
                $startPage = max(1, $endPage - $pageRange + 1);
            }
        @endphp

        <!-- Show ellipsis if the first page is not within the range -->
        @if ($startPage > 1)
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
        @endif

        <!-- Page Links -->
        @for ($page = $startPage; $page <= $endPage; $page++)
            <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
            </li>
        @endfor

        <!-- Show ellipsis if the last page is not within the range -->
        @if ($endPage < $totalPages)
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
        @endif


          <!-- Previous Page Link -->
          <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}"><</a>
        </li>


        <!-- Next Page Link -->
        <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}">></a>
        </li>
    </ul>
</nav>
