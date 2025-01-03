<style>
    .donation-details {
        padding: 20px;
        color: #FF8C00; /* 深橙色 */
        font-family: Arial Black, Arial, sans-serif;
    }

    .donation-details .header {
        text-align: center;
        margin-bottom: 20px;
        color: #FF8C00;
        font-family: Arial Black, Arial, sans-serif;
    }

    .donation-details .logo {
        max-width: 100px;
        margin-bottom: 15px;
        border-radius: 8px;
        font-family: Arial Black, Arial, sans-serif;
    }

    .donation-details .details-section {
        margin-bottom: 20px;
        color: #FFA500; /* 标准橙色 */
        font-family: Arial Black, Arial, sans-serif;
    }

    .donation-details .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-family: Arial Black, Arial, sans-serif;
    }

    .donation-details .details-table th {
        padding: 10px;
        border: 1px solid #FFA500;
        background-color: #FFA500;
        color: white;
        font-family: Arial Black, Arial, sans-serif;
    }

    .donation-details .details-table td {
        padding: 10px;
        border: 1px solid #FFE4B5;
        color: #FFA500;
        font-family: "Times New Roman", Arial, sans-serif;
    }

    .donation-details strong {
        color: #FF8C00;
        font-weight: bold;
        font-family: Arial Black, Arial, sans-serif;
    }

    .donation-details .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.9em;
        font-family: Arial Black, Arial, sans-serif;
    }

    .donation-details .status-completed {
        background-color: #90EE90;
        color: #006400;
        font-family: Arial Black, Arial, sans-serif;
    }

    .donation-details .status-pending {
        background-color: #FFE4B5;
        color: #FF8C00;
        font-family: Arial Black, Arial, sans-serif;
    }
</style>
<link rel="stylesheet" href="{{ asset('css/header.css') }}">
@vite(['resources/sass/app.scss', 'resources/js/app.js'])


<div class="donation-details">
    <div class="header">
        <img src="{{ asset('images/logo.jpg') }}" class="logo" alt="Pet4U Logo">
        <h4>Donation Details</h4>
    </div>

    <div class="details-section">
        <table class="details-table">
            <tr>
                <th colspan="2">Transaction Information</th>
            </tr>
            <tr>
                <td><strong>Receipt No:</strong></td>
                <td>{{ $receiptNo }}</td>
            </tr>
            <tr>
                <td><strong>Date:</strong></td>
                <td>{{ $donation->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            <tr>
                <td><strong>Amount:</strong></td>
                <td>RM {{ number_format($donation->amount, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Message:</strong></td>
                <td>
                    {{ $donation->message ?? '-' }}
                </td>
            </tr>
        </table>

        <table class="details-table">
            <tr>
                <th colspan="2">Donor Information</th>
            </tr>
            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ $donation->donor_name }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $donation->donor_email }}</td>
            </tr>
            @if($donation->message)
            <tr>
                <td><strong>Message:</strong></td>
                <td>{{ $donation->message }}</td>
            </tr>
            @endif
        </table>

        @if($donation->payment_details)
        <table class="details-table">
            <tr>
                <th colspan="2">Payment Information</th>
            </tr>
            <tr>
                <td><strong>Payment Method:</strong></td>
                <td>{{ $donation->payment_method }}</td>
            </tr>
            <tr>
                <td><strong>Transaction ID:</strong></td>
                <td>{{ $donation->transaction_id }}</td>
            </tr>
        </table>
        @endif
    </div>

    <div class="text-center mt-3">
        <a href="{{ route('donation.receipt', $donation->id) }}" 
           class="btn btn-sm btn-primary"
           target="_blank">
            <i class="fas fa-download"></i> Download Receipt
        </a>
    </div>
</div> 