<?php

namespace App\Admin\Actions;

use App\Jobs\LaunchCampaignJob;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\Collection;
use OpenAdmin\Admin\Actions\BatchAction;

class LaunchCampaignAction extends BatchAction
{
    public $name = '';
    public $icon = '';

    public function handle(Collection $collection)
    {
        $success = true;
        /** @var Campaign $campaign */
        foreach ($collection as $campaign) {
            if(in_array($campaign->status, ['created', 'error'])) {
                $campaign->status = 'ready';
                $campaign->label = $campaign->template->label;
                $campaign->raw = $campaign->template->raw;
                $campaign->save();
                LaunchCampaignJob::dispatch($campaign)
                    ->onQueue('campaigns');
            } else {
                $success = false;
            }
        }

        return $success ?
            $this->response()->success(__('cm.campaigns.actions.campaign_launched_succeed'))->refresh() :
            $this->response()->error(__('cm.campaigns.actions.campaign_launched_error'))->refresh();
    }

}
