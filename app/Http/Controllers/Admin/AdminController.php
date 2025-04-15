<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AdminController
 * Base controller for all admin CRUD controllers
 */
abstract class AdminController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure basic CRUD settings
     *
     * @param string $model
     * @param string $modelName
     * @param string $routePrefix
     * @return void
     */
    protected function setupDefaults(string $model, string $modelName, string $routePrefix = null)
    {
        $routePrefix = $routePrefix ?? strtolower($modelName);

        CRUD::setModel($model);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/' . $routePrefix);
        CRUD::setEntityNameStrings(strtolower($modelName), strtolower($modelName) . 's');
    }
}
