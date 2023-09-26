<?php

namespace App\Admin\Actions;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Collection;
use OpenAdmin\Admin\Actions\BatchAction;

class CancelCampaignAction extends BatchAction
{
    public $name = '';
    public $icon = '';

    public function handle(Collection $collection)
    {
        $success = true;
        /** @var Campaign $campaign */
        foreach($collection as $campaign) {
            if(in_array($campaign->status, ['error', 'created'])) {
                $campaign->status = 'cancelled';
                $campaign->save();
            } else {
                $success = false;
            }
        }

        return $success ?
            $this->response()->success(__('cm.campaigns.actions.campaign_cancelled_succeed'))->refresh() :
            $this->response()->error(__('cm.campaigns.actions.campaign_cancelled_error'))->refresh();
    }

}
