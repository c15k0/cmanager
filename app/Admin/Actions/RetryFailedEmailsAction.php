<?php

namespace App\Admin\Actions;

use App\Jobs\PrepareMailAndSendJob;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\Collection;
use OpenAdmin\Admin\Actions\BatchAction;

class RetryFailedEmailsAction extends BatchAction
{
    public $name = '';
    public $icon = '';

    public function handle(Collection $collection)
    {
        $success = true;
        /** @var Campaign $model */
        foreach ($collection as $model) {
            foreach($model->failed_receivers as $receiver) {
                PrepareMailAndSendJob::dispatch($receiver)
                    ->onQueue('emails');
            }
        }

        return $success ?
            $this->response()->success(__('cm.campaigns.actions.retry_failed_emails_succeed'))->refresh() :
            $this->response()->error(__('cm.campaigns.actions.retry_failed_emails_error'))->refresh();
    }

}
