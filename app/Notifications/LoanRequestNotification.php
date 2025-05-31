<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class LoanRequestNotification extends Notification
{
    use Queueable;

    protected $loanRequest;
    protected $type;

    public function __construct($loanRequest, $type = 'new_request')
    {
        $this->loanRequest = $loanRequest;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $messages = [
        'new_request' => 'Pengajuan peminjaman baru dari ' . ($this->loanRequest->anggota->nm_anggota ?? 'Tidak diketahui'),
        'approved' => 'Peminjaman Anda telah disetujui',
        'rejected' => 'Peminjaman Anda ditolak: ' . $this->loanRequest->alasan_penolakan,
        ];

        return [
        'title' => 'Peminjaman Buku',
        'message' => $messages[$this->type],
        'loan_id' => $this->loanRequest->id,
        'type' => $this->type,
        ];
    }

    public function toMail($notifiable)
    {
    $mailMessage = (new MailMessage)
    ->subject('Notifikasi Peminjaman Buku');

    switch ($this->type) {
    case 'new_request':
        $mailMessage->line('Ada pengajuan peminjaman baru yang memerlukan persetujuan Anda.')
        ->action('Lihat Pengajuan', route('trsPinjam.pending-approvals'));
    break;
    case 'approved':
        $mailMessage->line('Peminjaman buku Anda telah disetujui.')
        ->line('Buku: ' . $this->loanRequest->koleksi->judul)
        ->line('Batas pengembalian: ' . $this->loanRequest->tgl_bts_kembali->format('d/m/Y'));
    break;
    case 'rejected':
    $mailMessage->line('Peminjaman buku Anda ditolak.')
    ->line('Alasan: ' . $this->loanRequest->alasan_penolakan);
    break;
    }

    return $mailMessage;
    }
}