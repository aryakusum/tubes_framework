<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Penggajian;
use Barryvdh\DomPDF\Facade\Pdf;

class SlipGajiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $penggajian;
    public $detail;

    /**
     * Create a new message instance.
     */
    public function __construct(Penggajian $penggajian, $detail)
    {
        $this->penggajian = $penggajian;
        $this->detail = $detail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Slip Gaji Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.slip_gaji',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        $pdf = Pdf::loadView('pdf.slip_gaji', [
            'penggajian' => $this->penggajian,
            'detail' => $this->detail,
        ]);

        return $this->subject('Slip Gaji')
            ->markdown('emails.slip_gaji')
            ->attachData($pdf->output(), 'Slip-Gaji-' . $this->detail->pegawai->nama_pegawai . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
