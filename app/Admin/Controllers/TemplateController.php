<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class TemplateController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Template';

    public function __construct()
    {
        $this->title = __('cm.templates.title');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Template());
        /** @var User $user */
        $user = Auth::user();
        if ($user->isAdministrator() || $user->customers()->count() > 1) {
            $grid->column('customer.name', __('cm.contacts.customer_associated'));
        }
        if(!$user->isAdministrator()) {
            $grid->model()->whereIn('customer_id', $user->customers()->pluck('customers.id'));
        }
        $grid->column('name', __('cm.templates.name'));
        $grid->column('label', __('cm.templates.label'));
        $grid->column('updated_at', __('cm.updated_at'))
            ->display(function($timestamp) {
                $ts = \DateTime::createFromFormat('Y-m-d\TH:i:s', substr($timestamp, 0, 19));
                return $ts ? $ts->format('Y-m-d H:i:s') : substr($timestamp, 0, 19);
            })
        ;
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Template::findOrFail($id));
        $show->field('customer.name', __('cm.groups.customer_name'));
        $show->field('name', __('cm.templates.name'));
        $show->field('label', __('cm.templates.label'));
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Template());
        /** @var User $user */
        $user = Auth::user();
        if ($user->isAdministrator()) {
            $customers = Customer::query()->orderBy('name')->pluck('name', 'id')->toArray();
        } else {
            $customers = $user->customers()->pluck('customers.name', 'customers.id')->toArray();
        }
        if(count($customers) > 1) {
            $form->select('customer_id', __('cm.groups.customer_name'))
                ->required()
                ->options($customers)
                ->default(array_slice($customers, 0, 1));
        } else {
            $form->hidden('customer_id')->default(array_key_first($customers))->value(array_key_first($customers));
        }
        $form->text('name', __('cm.templates.name'))->required();
        $form->text('label', __('cm.templates.label'))->required();
        $form->ckeditor('raw', __('cm.templates.content'))->required()
        ->help('<h2>Consejos de plantillas</h2>' .
            '<p>Si se van a utilizar imágenes, se recomienda que en la pestaña de <em>V&iacute;nculo</em> se copie la' .
            ' url de la imagen y en el desplegable se seleccione <em>_blank</em>. Puedes editar la imagen haciendo doble click en ella.</p>' .
            '<p>No incluyas la firma del correo, el gestor automáticamente lo incluye en todos los correos junto con la opción de darse de baja de los emails</p>' .
            '<h3>Tags de reemplazo</h3>' .
            'Puedes usar los siguientes tags dentro de la plantilla para que el gestor lo cambie por el dato del contacto:' .
            '<ul>' .
            '<li><code>{{name}}</code>: Nombre del contacto</li>' .
            '<li><code>{{last_name}}</code>: Apellidos del contacto</li>' .
            '<li><code>{{company_name}}</code>: Nombre de la empresa</li>' .
            '<li><code>{{phone}}</code>: Teléfono del contacto</li>' .
            '<li><code>{{email}}</code>: Email del contacto</li>' .
            '</ul>'
        );
        return $form;
    }
}
