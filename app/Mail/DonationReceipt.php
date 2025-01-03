<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Donation;
use Carbon\Carbon;
use PDF;

class DonationReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $donation;
    public $receiptNo;
    public $date;
    protected $pdf;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
        $this->receiptNo = 'RCP-' . str_pad($donation->id, 10, '0', STR_PAD_LEFT);
        $this->date = Carbon::parse($donation->created_at)->format('d M Y');

        // 生成 PDF
        $this->pdf = PDF::loadView('common.donation-receipt', [
            'donation' => $donation,
            'receiptNo' => $this->receiptNo,
            'date' => $this->date,
        ]);
    }

    public function build()
    {
        return $this->subject('Thank You for Your Donation to Pet4U - Receipt #' . $this->receiptNo)
                    ->markdown('common.donation-receipt')
                    ->attachData(
                        $this->pdf->output(),
                        'donation-receipt-' . $this->donation->id . '.pdf',
                        ['mime' => 'application/pdf']
                    );
    }
}