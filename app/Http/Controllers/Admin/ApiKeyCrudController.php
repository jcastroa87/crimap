<?php

namespace App\Http\Controllers\Admin;

use App\Models\ApiKey;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Str;

class ApiKeyCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(ApiKey::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/api-key');
        CRUD::setEntityNameStrings('API key', 'API keys');
    }

    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('user_id');
        CRUD::column('name');
        CRUD::column('key')->limit(20); // Only show part of the key for security
        CRUD::column('is_active')->type('boolean');
        CRUD::column('expires_at');
        CRUD::column('last_used_at');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|min:3|max:255',
            'is_active' => 'boolean',
            'expires_at' => 'required|date|after:today',
        ]);

        CRUD::field('user_id')
            ->type('select2')
            ->entity('user')
            ->attribute('name')
            ->model('App\Models\User');

        CRUD::field('name');

        // Automatically generate a key
        CRUD::field('key')
            ->value('api_' . Str::random(32))
            ->attributes(['readonly' => 'readonly']);

        // Permissions as a checkbox array
        CRUD::field('permissions')
            ->type('checklist')
            ->options([
                'read:crime_types' => 'Read Crime Types',
                'read:crime_reports' => 'Read Crime Reports',
                'create:crime_reports' => 'Create Crime Reports',
                'update:crime_reports' => 'Update Crime Reports',
            ]);

        CRUD::field('is_active')->type('checkbox');
        CRUD::field('expires_at')->type('date');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        // Make the key readonly during updates
        CRUD::field('key')->attributes(['readonly' => 'readonly']);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        // Show the full key and permissions in the show view
        CRUD::column('key')->limit(0);
        CRUD::column('permissions');
    }
}
