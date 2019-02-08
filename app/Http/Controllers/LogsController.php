<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Logs;
use App\Service\SearchService;

class LogsController extends Controller
{
    private $service;
    private $perPage;

    public function __construct(SearchService $service, int $perPage)
    {
        $this->service = $service;
        $this->perPage = $perPage;
    }

    public function index(SearchRequest $request)
    {
        $types = Logs::types();
        $result = $this->service->search($request, $this->perPage, $request->get('page', 1));
        $logs = $result->logs;
        $sort = $result->sort;
        $type = $result->type;

        return view('logs', compact('logs', 'sort', 'type', 'types'));
    }
}
