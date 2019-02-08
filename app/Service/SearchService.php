<?php

namespace App\Service;

use App\Http\Requests\SearchRequest;
use App\Logs;
use App\Repository\LogsRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SearchService
{
    private $repository;

    public function __construct(LogsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function search(SearchRequest $request, int $perPage, int $page): SearchResult
    {
        $sort = $request['sort'] ?? 'desc';
        $type = $request['type'] ?? null;

        $query = Logs::orderBy('dt', $sort);

        if (!empty($type)) {
            $query->where('type', $type);
        }

        $total = $this->repository->countByType($type);

        if ($sort === 'asc') {
            $found = $this->findInCacheForAscOnLeft($type, $page, $perPage);
            if ($found->dt) {
                $query->where('dt', '>', $found->dt);
            }
        } else {
            $found = $this->findInCacheDesc($type, $page, $perPage, $total);
            if ($found->dt) {
                $query->where('dt', '<=', $found->dt);
            }
        }
        $logs = $this->repository->findLogs($sort, $type, $found->dt, $found->offset, $perPage);
//        $logs = $query->limit($perPage)->offset($found->offset)->get();

        $pagination = new LengthAwarePaginator($logs, $total, $perPage, $page);

        return new SearchResult($pagination, $sort, $type);
    }

    /**
     * @param $type
     * @param int $page
     * @param int $perPage
     * @return CachedPageDto
     */
    private function findInCacheForAscOnLeft($type, int $page, int $perPage): CachedPageDto
    {
        $data = Cache::get('type_' . (int)$type, []);

        // searching for nearest value
        $reversedData = array_reverse($data, true);
        foreach ($reversedData as $cachedPage => $cachedDt) {
            if ($cachedPage < $page) {
                $offset = $perPage * ($page - 1 - $cachedPage);
                return new CachedPageDto($cachedDt, $cachedPage, $offset);
            }
        }

        $cachedDt = 0;
        $offset = $perPage * ($page - 1);
        $cachedPage = 1;
        return new CachedPageDto($cachedDt, $cachedPage, $offset);
    }

    /**
     * @param $type
     * @param int $page
     * @param int $perPage
     * @param int $total
     * @return CachedPageDto
     */
    private function findInCacheForAscOnRight($type, int $page, int $perPage, int $total): CachedPageDto
    {
        $data = Cache::get('type_' . (int)$type, []);

        foreach ($data as $cachedPage => $cachedDt) {
            if ($cachedPage > $page) {
                $offset = $perPage * ($page - $cachedPage);
                return new CachedPageDto($cachedDt, $cachedPage, $offset);
            }
        }

        $cachedDt = 0;
        $offset = 0;
        $cachedPage = (int)($total / $perPage) + ($total % $perPage ? 1 : 0);

        return new CachedPageDto($cachedDt, $cachedPage, $offset);
    }

    private function findInCacheDesc($type, int $page, int $perPage, int $total): CachedPageDto
    {
        $totalPages = (int)($total / $perPage) + ($total % $perPage ? 1 : 0);
        $findAscPage = $totalPages - $page + 1;
        $ascCached = $this->findInCacheForAscOnRight($type, $findAscPage, $perPage, $total);
        if ($associatedDescPage = $totalPages - $ascCached->page) {
            $itemsOnLastPage = ($total % $perPage) ?: $perPage;
        } else {
            $itemsOnLastPage = $perPage;
        }

        $offset = $perPage - $itemsOnLastPage + ($perPage * ($page - $associatedDescPage - 1));

        return new CachedPageDto($ascCached->dt, null, $offset);
    }
}
