<?php
namespace App\Admin\Selectors;

use App\Models\Customer;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenAdmin\Admin\Grid\Filter;
use OpenAdmin\Admin\Grid\Selectable;

class GroupSelector extends Selectable {
    public $model = Group::class;

    public function make()
    {
        /** @var User $user */
        $user = Auth::user();
        $this->column('name', __('cm.contacts.name'));
        if(!$user->isRole('administrator')) {
            $this->model()
                ->whereIn('customer_id', $user
                    ->customers()
                    ->pluck('customers.id')
                    ->toArray()
                );
        } else {
            $this->column('customer.name', __('cm.contacts.customer_name'));
        }
        $this->column('contacts', __('cm.groups.contacts_count'))
            ->display(fn($contacts) => count($contacts));
        $this->column('bounced_contacts', __('cm.groups.bounced_count'))
            ->display(fn($contacts) => count($contacts));
        $this->column('unsubscribed_contacts', __('cm.groups.unsubscribed_count'))
            ->display(fn($contacts) => count($contacts));

        $this->filter(function(Filter $filter) use ($user) {
            $filter->like('name', __('Name'));
            if($user->isRole('administrator')) {
                $customers = Customer::query()->pluck('name', 'id')->toArray();
            } else {
                $customers = $user->customers()->pluck('customers.name', 'customers.id')->toArray();
            }
            if(count($customers) > 1) {
                $filter->in('customer_id', __('Customer'))
                    ->select($customers);
            }
        });
    }

}
