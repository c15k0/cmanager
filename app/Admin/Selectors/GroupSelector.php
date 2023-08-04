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
        if(!$user->isRole('administrator')) {
            $this->model()
                ->whereIn('customer_id', $user->customers()->pluck('id')->toArray());
        }
        $this->column('name', __('Name'));
        $this->column('customer.name', __('Customer'));

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
