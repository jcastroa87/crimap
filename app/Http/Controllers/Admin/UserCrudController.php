<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('email');
        CRUD::column('email_verified_at')->type('datetime');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        CRUD::field('name');
        CRUD::field('email')->type('email');
        CRUD::field('password')->type('password');
        CRUD::field('password_confirmation')->type('password');
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation([
            'name' => 'required|min:2|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore(CRUD::getCurrentEntryId()),
            ],
            'password' => 'sometimes|nullable|min:8|confirmed',
        ]);

        CRUD::field('name');
        CRUD::field('email')->type('email');
        CRUD::field('password')->type('password')->hint('Leave blank to keep current password');
        CRUD::field('password_confirmation')->type('password');
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }
}
