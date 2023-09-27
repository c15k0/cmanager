<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\Receiver;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use function PHPUnit\Framework\throwException;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;
    protected Receiver $receiver;

    /**
     * Create a new message instance.
     */
    public function __construct(Receiver $receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS', 'info@flopez.es'), env('MAIL_FROM_NAME', '')),
            to: [new Address($this->receiver->contact->email, $this->receiver->contact->company_name)],
            subject: $this->receiver->campaign->label,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $content = $this->receiver->campaign->raw;
        foreach(['name', 'last_name', 'company_name'] as $field) {
            $content = str_replace("{{{$field}}}", $this->receiver->contact?->$field, $content);
        }
        return new Content(
            view: 'mails.base',
            with: [
                'content' => $content,
                'tracking' => $this->receiver->hash,
                'signature' => $this->receiver->campaign->customer->signature,
            ]
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

    public function send($mailer)
    {
        try {
            $send = parent::send($mailer);
            $this->receiver->status = 'sent';
            $this->receiver->error = null;
            $this->receiver->save();
            if(Receiver::query()
                    ->where('campaign_id', $this->receiver->campaign_id)
                    ->whereIn('status', ['created', 'error'])
                    ->count() === 0
            ) {
                $this->receiver->campaign->update([
                    'status' => 'sent',
                    'start_at' => now(),
                ]);
            }
        } catch (\Exception $exception) {
            $this->receiver->status = 'error';
            $this->receiver->error = $exception->getMessage();
            $this->receiver->save();
            $send = null;
            $this->receiver->campaign->update([
                'status' => 'error',
            ]);
            throwException($exception);
        }
        return $send;
    }

}
