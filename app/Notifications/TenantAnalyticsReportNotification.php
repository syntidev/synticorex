<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantAnalyticsReportNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Tenant $tenant,
        private readonly array $data,
        private readonly string $period,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage())
            ->subject("📊 Tu reporte {$this->period} — {$this->tenant->business_name}")
            ->greeting("Hola, {$this->tenant->business_name}")
            ->line("Visitas: {$this->data['visitors']} ({$this->data['change']}% vs período anterior)")
            ->line("Clicks WhatsApp: {$this->data['whatsapp_clicks']}")
            ->line("Escaneos QR: {$this->data['qr_scans']}");

        if (!empty($this->data['include_growth'])) {
            $mail->line("Hora pico: {$this->data['peak_hour']}:00h")
                 ->line("Fuente principal: {$this->data['top_source']}");
        }

        if (!empty($this->data['include_vision'])) {
            $mail->line("Dispositivo principal: {$this->data['top_device']}")
                 ->line("Sistema operativo: {$this->data['top_os']}");
        }

        if (empty($this->data['include_vision'])) {
            $mail->line('🔒 Desbloquea análisis de dispositivos, fuentes de tráfico detalladas y reporte PDF actualizando a Visión.')
                 ->action('Ver planes', url('/planes'));
        }

        if (!empty($this->data['attach_pdf'])) {
            $pdf = Pdf::loadView('pdf.analytics-report', [
                'tenant' => $this->tenant,
                'data' => $this->data,
                'period' => $this->period,
            ]);

            $filename = "reporte-{$this->tenant->subdomain}-{$this->period}.pdf";
            $mail->attachData($pdf->output(), $filename, ['mime' => 'application/pdf']);
        }

        return $mail;
    }
}
