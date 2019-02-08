<div class="search-bar pt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <form action="{{ route('logs.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                @if($sort === 'desc')
                                    <a class="btn btn-success" href="{{ route('logs.index', ['sort' => 'asc']) }}">Sort <span class="fa fa fa-caret-down"></span></a>
                                @elseif($sort === 'asc')
                                    <a class="btn btn-success" href="{{ route('logs.index', ['sort' => 'desc']) }}">Sort <span class="fa fa fa-caret-up"></span></a>
                                @else
                                    <a class="btn btn-success" href="{{ route('logs.index', ['sort' => 'asc']) }}">Sort <span class="fa fa fa-caret-down"></span></a>
                                @endif
                                <input type="hidden" name="sort" value="{{ $sort }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="type">
                                    <option value=""></option>
                                    @foreach($types as $index => $t)
                                        <option value="{{ $index }}"{{ ($type == $index) ? ' selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <button class="btn btn-success border" type="submit"><span class="fa fa-search"></span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
