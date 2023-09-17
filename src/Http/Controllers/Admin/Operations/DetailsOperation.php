<?php

namespace Eleven59\BackpackShop\Http\Controllers\Admin\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use function view;

trait DetailsOperation
{
    /**
     * Define show order details route
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupDetailsRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/details', [
            'as'        => $routeName.'.details',
            'uses'      => $controller.'@details',
            'operation' => 'details',
        ]);
    }

    /**
     * Add button to show order details to every order in the CRUD table
     */
    protected function setupDetailsDefaults()
    {
        CRUD::allowAccess('details');

        CRUD::operation('details', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            CRUD::addButton('line', 'details', 'view', 'eleven59.backpack-shop::buttons.details', 'beginning');
        });
    }

    /**
     * Show order details
     *
     * @param $id
     * @return View
     */
    public function details($id) :View
    {
        CRUD::hasAccessOrFail('details');

        // prepare the fields you need to show
        $this->data['id'] = $id;
        $this->data['crud'] = $this->crud;
        $this->data['title'] = CRUD::getTitle() ?? 'Order details: '.$this->crud->entity_name;
        $this->data['entry'] = $this->crud->model::findOrFail($id);

        // load the view
        return view('eleven59.backpack-shop::operations.details', $this->data);
    }
}
