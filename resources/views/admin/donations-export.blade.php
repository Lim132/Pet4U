@extends('layouts.app')

@section('content')
<style>
    .export-container {
        max-width: 800px;
        margin: 20px auto;
    }
    
    .export-card {
        border: 2px solid #FFA500;
        border-radius: 8px;
    }
    
    .export-card-header {
        background-color: #FF8C00;
        color: white;
        padding: 15px 20px;
        border-bottom: 1px solid #FFA500;
    }
    
    .export-form {
        padding: 20px;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group label {
        color: #FF8C00;
        font-weight: bold;
    }
    
    .form-control {
        border-color: #FFE4B5;
    }
    
    .form-control:focus {
        border-color: #FFA500;
        box-shadow: 0 0 0 0.2rem rgba(255, 140, 0, 0.25);
    }
    
    .btn-export {
        background-color: #FF8C00;
        border-color: #FF8C00;
        color: white;
    }
    
    .btn-export:hover {
        background-color: #FFA500;
        border-color: #FFA500;
        color: white;
    }
</style>

<div class="export-container">
    <div class="export-card">
        <div class="export-card-header">
            <h4 class="mb-0">Export Donations</h4>
        </div>
        
        <div class="export-form">
            <form action="{{ route('admin.donations.export') }}" method="GET">
                <div class="form-group">
                    <label>Date Range</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="date" 
                                   name="start_date" 
                                   class="form-control" 
                                   value="{{ request('start_date', now()->subMonth()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <input type="date" 
                                   name="end_date" 
                                   class="form-control" 
                                   value="{{ request('end_date', now()->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Amount Range (RM)</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="number" 
                                   name="min_amount" 
                                   class="form-control" 
                                   placeholder="Minimum amount"
                                   step="0.01"
                                   value="{{ request('min_amount') }}">
                        </div>
                        <div class="col-md-6">
                            <input type="number" 
                                   name="max_amount" 
                                   class="form-control" 
                                   placeholder="Maximum amount"
                                   step="0.01"
                                   value="{{ request('max_amount') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>File Format</label>
                    <select name="format" class="form-control">
                        <option value="xlsx" {{ request('format') == 'xlsx' ? 'selected' : '' }}>Excel (XLSX)</option>
                        <option value="csv" {{ request('format') == 'csv' ? 'selected' : '' }}>CSV</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Columns to Export</label>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="col_receipt" 
                                       name="columns[]" 
                                       value="receipt"
                                       checked>
                                <label class="custom-control-label" for="col_receipt">Receipt No</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="col_date" 
                                       name="columns[]" 
                                       value="date"
                                       checked>
                                <label class="custom-control-label" for="col_date">Date</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="col_amount" 
                                       name="columns[]" 
                                       value="amount"
                                       checked>
                                <label class="custom-control-label" for="col_amount">Amount</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="col_donor" 
                                       name="columns[]" 
                                       value="donor"
                                       checked>
                                <label class="custom-control-label" for="col_donor">Donor Name</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="col_email" 
                                       name="columns[]" 
                                       value="email"
                                       checked>
                                <label class="custom-control-label" for="col_email">Email</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="col_message" 
                                       name="columns[]" 
                                       value="message"
                                       checked>
                                <label class="custom-control-label" for="col_message">Message</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-export">
                        <i class="fas fa-file-export"></i> Export Data
                    </button>
                    <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 