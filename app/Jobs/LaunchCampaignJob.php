<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Receiver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LaunchCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Campaign $campaign;

    /**
     * Create a new job instance.
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    protected function processContact(Contact $contact)
    {
        if(empty($contact->unsubscribed_at)) {
            $receiver = Receiver::query()
                ->where('campaign_id', $this->campaign->getKey())
                ->where('contact_id', $contact->getKey())
                ->get()->first();
            if (!$receiver) {
                $receiver = Receiver::create([
                    'campaign_id' => $this->campaign->getKey(),
                    'contact_id' => $contact->getKey(),
                    'hash' => sha1($this->campaign->getKey() . ':' . $contact->getKey()),
                    'status' => 'created',
                ]);
            }
            PrepareMailAndSendJob::dispatch($receiver)
                ->onQueue('emails');
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->campaign->refresh();
            /** @var Contact $contact */
            foreach ($this->campaign?->contacts as $contact) {
                $this->processContact($contact);
            }
            /** @var Group $group */
            foreach ($this->campaign?->groups as $group) {
                /** @var Contact $contact */
                foreach($group->contacts as $contact) {
                    $this->processContact($contact);
                }
            }
            $this->campaign->status = 'sending';
        } catch (\Exception $exception) {
            $this->campaign->status = 'error';
            Log::error($exception->getMessage());
        } finally {
            $this->campaign->save();
        }
    }
}
