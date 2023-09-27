<?php

namespace App\Jobs;

use App\Mail\GenericMail;
use App\Models\Receiver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PrepareMailAndSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Receiver $receiver;

    /**
     * Create a new job instance.
     */
    public function __construct(Receiver $receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $email = new GenericMail($this->receiver);
            Mail::onQueue('emails', $email);
            if(!in_array($this->receiver->campaign->status, ['sending', 'error'])) {
                $this->receiver->campaign->update([
                    'status' => 'sending',
                ]);
            }
        } catch (\Exception $exception) {
            $this->receiver->status = 'error';
            $this->receiver->error = $exception->getMessage();
            $this->receiver->campaign->update([
                'status' => 'error',
            ]);
        } finally {
            $this->receiver->save();
        }
    }
}
