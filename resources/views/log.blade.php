
<div class="main-content container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class='breadcrumb-header'>
                    <!-- Breadcrumb bisa diisi jika diperlukan -->
                </nav>
            </div>
        </div>
    </div>

    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Activity Log</h4>
                </div>
                <div class="card-content">
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>ID User</th>
                                    <th>Nama User</th>
                                    <th>Activity</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($logs->isNotEmpty())
                                    @foreach ($logs as $log)
                                        <tr>
                                            <td>{{ htmlspecialchars($log->id_user) }}</td>
                                            <td>{{ htmlspecialchars($log->nama_user) }}</td>
                                            <td>{{ htmlspecialchars($log->activity) }}</td>
                                            <td>{{ htmlspecialchars($log->timestamp) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No activity logs available.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

