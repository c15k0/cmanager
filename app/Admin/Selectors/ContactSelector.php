<?php

namespace App\Admin\Selectors;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OpenAdmin\Admin\Grid\Filter;
use OpenAdmin\Admin\Grid\Selectable;

class ContactSelector extends Selectable
{
    public $model = Contact::class;

    public function make()
    {
        /** @var User $user */
        $user = Auth::user();
        $this->model()
            ->addSelect('*')
            ->addSelect(DB::raw("CONCAT(name, ' ', COALESCE(last_name, '')) as full_name"));
        $this->column('full_name', __('cm.contacts.name'));
        $this->column('company_name', __('cm.contacts.company'));
        $this->column('email', __('cm.contacts.email'));
        if (!$user->isAdministrator()) {
            $this->model()
                ->whereIn('customer_id', $user
                    ->customers()
                    ->pluck('customers.id')
                    ->toArray()
                );
        } else {
            $this->column('customer.name', __('Customer'));
        }
        $this->column('bounced_at', __('cm.contacts.bounced_at'));
        $this->column('unsubscribed_at', __('cm.contacts.unsubscribed_at'));

        $this->filter(function (Filter $filter) use ($user) {
            $filter->like('name', __('cm.contacts.name'));
            $filter->like('last_name', __('cm.contacts.last_name'));
            $filter->like('company_name', __('cm.contacts.company'));
            $filter->like('email', __('cm.contacts.email'));
            if ($user->isRole('administrator')) {
                $customers = Customer::query()->pluck('name', 'id')->toArray();
            } else {
                $customers = $user->customers()->pluck('customers.name', 'customers.id')->toArray();
            }
            if (count($customers) > 1) {
                $filter->in('customer_id', __('Customer'))
                    ->select($customers);
            }
        });

    }

}
