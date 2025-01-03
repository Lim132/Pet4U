<?php

namespace App\Exports;

use App\Models\Donation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DonationsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
     
    public function collection()
    {
        return Donation::with('user')->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Receipt No',
            'Date',
            'Amount (RM)',
            'User ID',
            'User Name',
            'First Name',
            'Last Name',
            'Donor Name',
            'Email',
            'Phone Number',
            'Message',
        ];
    }

    public function map($donation): array
    {
        return [
            $donation->id,
            'RCP-' . str_pad($donation->id, 10, '0', STR_PAD_LEFT),
            $donation->created_at->format('Y-m-d H:i:s'),
            number_format($donation->amount, 2),
            $donation->user_id ?? '-',
            $donation->user ? $donation->user->username : '-',
            $donation->user ? $donation->user->firstName : '-',
            $donation->user ? $donation->user->lastName : '-',
            $donation->donor_name,
            $donation->donor_email,
            $donation->user ? $donation->user->phone : '-',
            $donation->message ?? '-',
        ];
    }
}
