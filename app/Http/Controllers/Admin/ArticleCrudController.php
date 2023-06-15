<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\ArticleRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        CRUD::setModel(\App\Models\Article::class);
        CRUD::setRoute(config('backpack.base.route_prefix', 'admin').'/article');
        CRUD::setEntityNameStrings('article', 'articles');

        /*
        |--------------------------------------------------------------------------
        | LIST OPERATION
        |--------------------------------------------------------------------------
        */
        $this->crud->operation('list', function () {
           

            // $this->crud->addFilter([ // select2 filter
            //     'name' => 'category_id',
            //     'type' => 'select2',
            //     'label'=> 'Category',
            // ], function () {
            //     return \App\Models\Category::all()->keyBy('id')->pluck('name', 'id')->toArray();
            // }, function ($value) { // if the filter is active
            //     $this->crud->addClause('where', 'category_id', $value);
            // });

            // $this->crud->addFilter([ // select2_multiple filter
            //     'name' => 'tags',
            //     'type' => 'select2_multiple',
            //     'label'=> 'Tags',
            // ], function () {
            //     return \App\Models\Tag::all()->keyBy('id')->pluck('name', 'id')->toArray();
            // }, function ($values) { // if the filter is active
            //     $this->crud->query = $this->crud->query->whereHas('tags', function ($q) use ($values) {
            //         foreach (json_decode($values) as $key => $value) {
            //             if ($key == 0) {
            //                 $q->where('tags.id', $value);
            //             } else {
            //                 $q->orWhere('tags.id', $value);
            //             }
            //         }
            //     });
            // });
        });

        /*
        |--------------------------------------------------------------------------
        | CREATE & UPDATE OPERATIONS
        |--------------------------------------------------------------------------
        */
        
    }


    protected function setupListOperation()
    {
            CRUD::addColumn('title');
            CRUD::addColumn([
                'name'  => 'date',
                'label' => 'Date',
                'type'  => 'date',
            ]);
            CRUD::addColumn('status');
            CRUD::addColumn([
                'name'  => 'featured',
                'label' => 'Featured',
                'type'  => 'check',
            ]);
            CRUD::addColumn([
                'label'     => 'Category',
                'type'      => 'select',
                'name'      => 'category_id',
                'entity'    => 'category',
                'attribute' => 'name',
                'wrapper'   => [
                    'href' => function ($crud, $column, $entry, $related_key) {
                        return backpack_url('category/' . $related_key . '/show');
                    },
                ],
            ]);
            CRUD::addColumn('tags');


        if (!backpack_user()->can(config('permission.edit'))) {
            CRUD::removeButton('show');
            CRUD::removeButton('reorder');
        }
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    protected function setupCreateOperation()
    {
       

        CRUD::addField([
            'name'        => 'title',
            'label'       => 'Title',
            'type'        => 'text',
            'placeholder' => 'Your title here',
        ]);
        CRUD::addField([
            'name'  => 'slug',
            'label' => 'Slug (URL)',
            'type'  => 'text',
            'hint'  => 'Will be automatically generated from your title, if left empty.',
            // 'disabled' => 'disabled'
        ]);
        CRUD::addField([
            'name'    => 'date',
            'label'   => 'Date',
            'type'    => 'date',
            'default' => date('Y-m-d'),
        ]);

        // CRUD::field('content')->type('ckeditor');

        CRUD::addField([
            'name'        => 'content',
            'label'       => 'Content',
            'type'        => 'summernote',
            'placeholder' => 'Your textarea text here',
        ]);
        // CRUD::addField([
        //     'name'  => 'image',
        //     'label' => 'Image',
        //     'type'  => 'image',
        // ]);
        CRUD::addField([
              'label' => "Article Image",
    'name' => "image",
    'type' => 'upload',
    'crop' => true, // set to true to allow cropping, false to disable
    'aspect_ratio' => 1,

        ]); //->aspect_ratio(1);

        CRUD::addField([
            'label'         => 'Category',
            'type'          => 'text',
            'name'          => 'category_id',
            'entity'        => 'category',
            'attribute'     => 'name',
            'inline_create' => true,
            'ajax'          => true,
        ]);
        CRUD::addField([

            'label'     => "Tags",
            'type'      => 'select',
            'name'      => 'tags', // the method that defines the relationship in your Model

            // optional
            'entity'    => 'tags', // the method that defines the relationship in your Model
            'model'     => "App\Models\Tag", // foreign key model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?

            // also optional
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }), // force th

            
            // 'label' => 'Tags',
            // 'type'  => 'select_multiple',
            // 'name'  => 'tags', // the method that defines the relationship in your Model
            // 'entity' => 'tags', // the method that defines the relationship in your Model
            // 'attribute' => 'name', // foreign key attribute that is shown to user
            // 'pivot' => true, // on create&update, do you need to add/delete pivot table entries?

            // 'entity'    => 'tags', // the method that defines the relationship in your Model
            // 'model'     => "App\Models\Tag", // foreign key model
            // // also optional
            // 'options'   => (function ($query) {
            //     return $query->orderBy('name', 'ASC')->get();
            // }), 


        ]);
        CRUD::addField([
            'name'    => 'status',
            'label'   => 'Status',
            'type'    => 'select_from_array',
            'options' => [
                'PUBLISHED' => 'PUBLISHED',
                'DRAFT'     => 'DRAFT',
            ],
        ]);
        CRUD::addField([
            'name'  => 'featured',
            'label' => 'Featured item',
            'type'  => 'checkbox',
        ]);
  
    }

    

    /**
     * Respond to AJAX calls from the select2 with entries from the Category model.
     *
     * @return JSON
     */
    public function fetchCategory()
    {
        return $this->fetch(\App\Models\Category::class);
    }

    /**
     * Respond to AJAX calls from the select2 with entries from the Tag model.
     *
     * @return JSON
     */
    public function fetchTags()
    {
        return $this->fetch(\App\Models\Tag::class);
    }
}
