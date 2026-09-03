<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Company $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->company->status;
        $nama   = $this->company->nama_perusahaan;

        return match ($status) {
            'active'   => $this->buildApprovedMail($nama),
            'revision' => $this->buildRevisionMail($nama),
            'rejected' => $this->buildRejectedMail($nama),
            default    => $this->buildDefaultMail($nama, $status),
        };
    }

    protected function buildApprovedMail(string $nama): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Dokumen Disetujui — ' . $nama)
            ->greeting('Selamat! 🎉')
            ->line("Dokumen perusahaan **{$nama}** telah **disetujui** oleh tim verifikasi TIM PPSDK.")
            ->line($this->company->catatan_admin
                ? '**Catatan Admin:** ' . $this->company->catatan_admin
                : 'Anda sekarang dapat mengakses seluruh fitur di portal perusahaan.')
            ->action('Buka Dashboard', url('/company/dashboard'))
            ->line('Terima kasih telah menggunakan sistem TIM PPSDK.')
            ->salutation('Salam, Tim Verifikasi TIM PPSDK');
    }

    protected function buildRevisionMail(string $nama): MailMessage
    {
        return (new MailMessage)
            ->subject('📝 Revisi Diperlukan — ' . $nama)
            ->greeting('Perhatian!')
            ->line("Dokumen perusahaan **{$nama}** memerlukan **revisi** sebelum dapat disetujui.")
            ->line('**Catatan dari Admin:**')
            ->line($this->company->catatan_admin ?? '-')
            ->action('Perbaiki Dokumen', url('/company/profil/edit'))
            ->line('Silakan perbaiki dokumen Anda sesuai catatan di atas, lalu kirimkan kembali.')
            ->salutation('Salam, Tim Verifikasi TIM PPSDK');
    }

    protected function buildRejectedMail(string $nama): MailMessage
    {
        return (new MailMessage)
            ->subject('❌ Dokumen Ditolak — ' . $nama)
            ->greeting('Mohon Maaf')
            ->line("Dokumen perusahaan **{$nama}** telah **ditolak** oleh tim verifikasi.")
            ->line('**Alasan Penolakan:**')
            ->line($this->company->rejection_reason ?? '-')
            ->line('Jika Anda merasa ini adalah kesalahan, silakan hubungi tim kami melalui email atau telepon yang tertera di website.')
            ->salutation('Salam, Tim Verifikasi TIM PPSDK');
    }

    protected function buildDefaultMail(string $nama, string $status): MailMessage
    {
        return (new MailMessage)
            ->subject('Update Status Dokumen — ' . $nama)
            ->greeting('Halo!')
            ->line("Status dokumen perusahaan **{$nama}** telah diperbarui menjadi: **{$status}**.")
            ->action('Lihat Detail', url('/company/dashboard'))
            ->salutation('Salam, Tim Verifikasi TIM PPSDK');
    }
}