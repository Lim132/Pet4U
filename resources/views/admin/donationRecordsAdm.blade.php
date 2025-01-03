@extends('layouts.app')

@section('content')
<style>
    /* 保持之前的样式 ... */

    /* 添加搜索框样式 */
    .search-box {
        margin-bottom: 20px;
    }

    .search-box input {
        border-color: #FFA500 !important;
        color: #FF8C00 !important;
    }

    .search-box input:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 140, 0, 0.25) !important;
    }

    /* 添加过滤器样式 */
    .filter-section {
        margin-bottom: 20px;
        padding: 15px;
        background-color: #FFF8DC;
        border: 1px solid #FFE4B5;
        border-radius: 4px;
    }

    .filter-section select {
        border-color: #FFA500 !important;
        color: #FF8C00 !important;
    }

    /* 导出按钮样式 */
    .btn-export {
        background-color: #FF8C00 !important;
        border-color: #FF8C00 !important;
        margin-bottom: 20px;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>All Donation Records</span>
                    <a href="{{ route('donationsExcel') }}" 
                       class="btn btn-sm btn-primary"
                       onclick="event.preventDefault(); exportDonations(this);">
                        <i class="fas fa-file-export"></i> Export to Excel
                    </a>
                </div>

                <div class="card-body">
                    <!-- 搜索和过滤部分 -->
                    <div class="filter-section">
                        <form action="{{ route('admin.donationRecords') }}" method="GET" class="row">
                            <div class="col-md-3">
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Search by ID, receipt no, donor name or email"
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="amount_filter" class="form-control">
                                    <option value="">All Amounts</option>
                                    <option value="0-50" {{ request('amount_filter') == '0-50' ? 'selected' : '' }}>RM 0-50</option>
                                    <option value="51-100" {{ request('amount_filter') == '51-100' ? 'selected' : '' }}>RM 51-100</option>
                                    <option value="101+" {{ request('amount_filter') == '101+' ? 'selected' : '' }}>RM 101+</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="date_filter" class="form-control">
                                    <option value="">All Dates</option>
                                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week</option>
                                    <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>This Month</option>
                                    <option value="year" {{ request('date_filter') == 'year' ? 'selected' : '' }}>This Year</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 统计信息 -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Donations</h5>
                                    <p class="card-text">RM {{ number_format($totalAmount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Donors</h5>
                                    <p class="card-text">{{ $totalDonors }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Today's Donations</h5>
                                    <p class="card-text">RM {{ number_format($todayAmount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">This Month</h5>
                                    <p class="card-text">RM {{ number_format($monthAmount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 捐款记录表格 -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Receipt No</th>
                                    <th>Amount (RM)</th>
                                    <th>Donor Name</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donations as $donation)
                                    <tr>
                                        <td>RCP-{{ str_pad($donation->id, 10, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ number_format($donation->amount, 2) }}</td>
                                        <td>{{ $donation->donor_name }}</td>
                                        <td>{{ $donation->donor_email }}</td>
                                        <td>{{ $donation->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <!-- <a href="{{ route('donation.receipt', $donation->id) }}" 
                                               class="btn btn-sm btn-primary"
                                               target="_blank">
                                                <i class="fas fa-download"></i> Receipt
                                            </a> -->
                                            <a href="{{ route('admin.donations.details', $donation->id) }}" 
                                               class="btn btn-sm btn-primary"
                                               target="_blank">
                                                <i class="fas fa-eye"></i> Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- 分页 -->
                    <div class="d-flex justify-content-center">
                        {{ $donations->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 详情模态框 -->
<div class="modal fade" id="donationDetails" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Donation Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- 动态加载详情 -->
            </div>
        </div>
    </div>
</div>


<!-- <script>
function viewDetails(id) {
    // 修改 URL 路径
    $.get(`/admin/donations/${id}/details`, function(data) {
        $('#donationDetails .modal-body').html(data);
        $('#donationDetails').modal('show');
    });
}
</script>  -->

<script>
function exportDonations(button) {
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
    window.location.href = button.href;
    setTimeout(() => {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-file-export"></i> Export to Excel';
    }, 3000);
}
</script>
@endsection