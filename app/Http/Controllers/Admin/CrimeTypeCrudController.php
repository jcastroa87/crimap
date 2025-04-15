<?php

namespace App\Http\Controllers\Admin;

use App\Models\CrimeType;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

class CrimeTypeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(CrimeType::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/crime-type');
        CRUD::setEntityNameStrings('crime type', 'crime types');
    }

    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('description');
        CRUD::column('icon')->type('text');
        CRUD::column('color')->type('color');
        CRUD::column('is_active')->type('boolean');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'name' => 'required|min:2|max:255',
            'description' => 'required|min:5',
            'icon' => 'required',
            'color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'is_active' => 'boolean',
        ]);

        CRUD::field('name');
        CRUD::field('description')->type('textarea');
        CRUD::field('icon')->type('text')
            ->hint('Enter a Font Awesome icon class name (e.g., fa-solid fa-house)');
        CRUD::field('color')->type('color');
        CRUD::field('is_active')->type('checkbox');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }
}
