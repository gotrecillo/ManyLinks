<?php

namespace ManyLinks\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use ManyLinks\Http\Requests\LinkRequest as StoreRequest;
use ManyLinks\Http\Requests\LinkRequest as UpdateRequest;

class LinkCrudController extends CrudController
{
    public function setup()
    {
        $user = new PDO;
    
        $this->crud->setModel('ManyLinks\Models\Link');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/link');
        $this->crud->setEntityNameStrings('link', 'links');
        
        $this->crud->addColumns([
            [
                'name' => 'url',
            ],
            [
                'name' => 'title',
            ],
            [
                'name' => 'description',
            ]
        ]);
        
        $this->crud->addFields([
            [
                'name' => 'url',
            ],
            [
                'label' => "User",
                'type' => 'select2',
                'name' => 'user_id',
                'entity' => 'user',
                'attribute' => 'name',
                'model' => User::class,
            ],
            [
                'name' => 'title',
            ],
            [
                'name' => 'description',
            ]
        ]);
    }

    public function store(StoreRequest $request)
    {
        
        $redirect_location = parent::storeCrud($request);
        
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $redirect_location = parent::updateCrud($request);
     
        return $redirect_location;
    }
}
