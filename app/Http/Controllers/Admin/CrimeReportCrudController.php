<?php

namespace App\Http\Controllers\Admin;

use App\Models\CrimeReport;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class CrimeReportCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(CrimeReport::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/crime-report');
        CRUD::setEntityNameStrings('crime report', 'crime reports');
        CRUD::orderBy('created_at', 'desc');
    }

    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('user_id');
        CRUD::column('crime_type_id');
        CRUD::column('latitude');
        CRUD::column('longitude');
        CRUD::column('status')->type('enum')->options([
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ]);
        CRUD::column('occurred_at');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        // Filters
        CRUD::filter('crime_type_id')
            ->type('select2')
            ->label('Crime Type')
            ->values(function () {
                return \App\Models\CrimeType::pluck('name', 'id')->toArray();
            });

        CRUD::filter('status')
            ->type('dropdown')
            ->label('Status')
            ->values([
                'pending' => 'Pending',
                'approved' => 'Approved',
                'rejected' => 'Rejected'
            ]);

        CRUD::filter('occurred_at')
            ->type('date_range')
            ->label('Date Range');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'user_id' => 'required|exists:users,id',
            'crime_type_id' => 'required|exists:crime_types,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'required|min:10',
            'occurred_at' => 'required|date',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        CRUD::field('user_id')
            ->type('select2')
            ->entity('user')
            ->attribute('name')
            ->model('App\Models\User');

        CRUD::field('crime_type_id')
            ->type('select2')
            ->entity('crimeType')
            ->attribute('name')
            ->model('App\Models\CrimeType');

        CRUD::field('latitude');
        CRUD::field('longitude');
        CRUD::field('description')->type('textarea');
        CRUD::field('occurred_at')->type('datetime');
        CRUD::field('status')->type('select_from_array')->options([
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ]);

        // Media files field as a json representation
        CRUD::field('media_files')
            ->type('repeatable')
            ->fields([
                [
                    'name' => 'path',
                    'type' => 'text',
                    'label' => 'File Path',
                ],
                [
                    'name' => 'original_name',
                    'type' => 'text',
                    'label' => 'Original Name',
                ],
                [
                    'name' => 'mime_type',
                    'type' => 'text',
                    'label' => 'MIME Type',
                ],
            ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        // Add the description field for showing
        CRUD::column('description');

        // Show media files
        CRUD::column('media_files')
            ->type('relationship')
            ->label('Media Files');
    }
}
