<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Donation Receipt</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                padding: 20px;
                color: #FF8C00; /* 将默认文字颜色改为深橙色 */
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
                color: #FF8C00;
            }
            .logo {
                max-width: 150px;
                margin-bottom: 15px;
                border-radius: 13px;
            }
            .receipt-details {
                margin-bottom: 30px;
                color: #FFA500; /* 正文使用标准橙色 */
            }
            .receipt-details strong {
                color: #FF8C00; /* 加粗文字使用深橙色 */
            }
            .receipt-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 30px;
            }
            .receipt-table th {
                padding: 10px;
                border: 1px solid #FFA500;
                background-color: #FFE4B5;
                color: #FF8C00;
            }
            .receipt-table td {
                padding: 10px;
                border: 1px solid #FFE4B5;
                color: #FFA500; /* 表格内容使用标准橙色 */
            }
            .footer {
                text-align: center;
                margin-top: 50px;
                font-size: 12px;
                color: #FFA500; /* 页脚使用标准橙色 */
                border-top: 2px solid #FFE4B5;
                padding-top: 20px;
            }
            .receipt-no {
                color: #FF8C00;
                font-size: 18px;
                margin-bottom: 15px;
            }
            .amount {
                color: #FF8C00;
                font-weight: bold;
            }
            p {
                color: #FFA500; /* 确保所有段落都使用标准橙色 */
            }
            strong {
                color: #FF8C00; /* 所有加粗文字使用深橙色 */
            }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="{{ public_path('images/logo.jpg') }}" class="logo" alt="Pet4U Logo">
            <h1>Pet4U</h1>
            <h2>Donation Receipt</h2>
        </div>

        <div class="receipt-details">
            <p class="receipt-no"><strong>Receipt No:</strong> {{ $receiptNo }}</p>
            <p><strong>Date:</strong> {{ $date }}</p>
            <p><strong>Donor Name:</strong> {{ $donation->donor_name }}</p>
            <p><strong>Email:</strong> {{ $donation->donor_email }}</p>
            @if($donation->message)
                <p><strong>Message:</strong> {{ $donation->message }}</p>
            @endif
        </div>

        <table class="receipt-table">
            <tr>
                <th>Description</th>
                <th>Amount (RM)</th>
            </tr>
            <tr>
                <td>Donation to Pet4U</td>
                <td class="amount">{{ number_format($donation->amount, 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>Thank you for your generous donation!</p>
            <p>Your support helps us continue our mission to help animals in need.</p>
            <p>This is a computer-generated receipt. No signature is required.</p>
        </div>
    </body>
</html>