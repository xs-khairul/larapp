<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\TagRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class TagCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        $this->crud->setModel("App\Models\Tag");
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/tag');
        $this->crud->setEntityNameStrings('tag', 'tags');
        $this->crud->setFromDb();
    }


    protected function setupListOperation()
    {
        CRUD::addColumn('name');
        CRUD::addColumn('slug');

        // dd(backpack_user()->can(config('permission.edit')));

if (!backpack_user()->can(config('permission.edit'))) {
    CRUD::removeButton('show');
    CRUD::removeButton('reorder');
}

// if (!backpack_user()->can(config('permission.delete'))) {
//     CRUD::removeButton('delete');
// }

// if (!backpack_user()->can(config('permission.view'))) {
//     CRUD::removeButton('preview');
// }

    }




       


    protected function setupCreateOperation()
    {
        // $this->crud->setValidation(TagRequest::class);
    }

    protected function setupUpdateOperation()
    {
        // $this->crud->setValidation(TagRequest::class);
    }
}
