<div>
    <h1>Batches</h1>
    @foreach($batches as $item)
        <div>
            <h2>{{ $item->name }}</h2>
            <div>
                TotalJobs: {{ $item->totalJobs }}
            </div>
            <div>
                PendingJobs: {{ $item->pendingJobs }}
            </div>
            <div>
                FailedJobs: {{ $item->failedJobs }}
            </div>
        </div>
    @endforeach
</div>
