<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscription;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class SubscriptionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(Subscription::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/subscription');
        CRUD::setEntityNameStrings('subscription', 'subscriptions');
    }

    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('user_id');
        CRUD::column('name');
        CRUD::column('frequency');
        CRUD::column('price')->type('number')
            ->prefix('$')
            ->decimals(2);
        CRUD::column('status')->type('enum')->options([
            'active' => 'Active',
            'pending' => 'Pending',
            'cancelled' => 'Cancelled',
            'expired' => 'Expired',
        ]);
        CRUD::column('starts_at');
        CRUD::column('ends_at');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        // Filters
        CRUD::filter('status')
            ->type('dropdown')
            ->label('Status')
            ->values([
                'active' => 'Active',
                'pending' => 'Pending',
                'cancelled' => 'Cancelled',
                'expired' => 'Expired',
            ]);

        CRUD::filter('user_id')
            ->type('select2')
            ->label('User')
            ->values(function () {
                return \App\Models\User::pluck('name', 'id')->toArray();
            });
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|min:3|max:255',
            'description' => 'required',
            'frequency' => 'required|in:monthly,quarterly,annual',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,pending,cancelled,expired',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ]);

        CRUD::field('user_id')
            ->type('select2')
            ->entity('user')
            ->attribute('name')
            ->model('App\Models\User');

        CRUD::field('name');
        CRUD::field('description')->type('textarea');
        CRUD::field('frequency')
            ->type('select_from_array')
            ->options([
                'monthly' => 'Monthly',
                'quarterly' => 'Quarterly',
                'annual' => 'Annual',
            ]);

        CRUD::field('price')
            ->type('number')
            ->prefix('$')
            ->attributes([
                'step' => '0.01',
                'min' => '0',
            ]);

        CRUD::field('features')
            ->type('repeatable')
            ->fields([
                [
                    'name' => 'feature',
                    'type' => 'text',
                    'label' => 'Feature',
                ],
            ]);

        CRUD::field('status')
            ->type('select_from_array')
            ->options([
                'active' => 'Active',
                'pending' => 'Pending',
                'cancelled' => 'Cancelled',
                'expired' => 'Expired',
            ]);

        CRUD::field('starts_at')->type('datetime');
        CRUD::field('ends_at')->type('datetime');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        // Add additional fields for show view
        CRUD::column('description');
        CRUD::column('features');
    }
}
