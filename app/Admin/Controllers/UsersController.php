<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use function foo\func;

class UsersController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('用户')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('用户')
            ->description('详情')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('用户')
            ->description('编辑')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->id('Id')->sortable();
        $grid->avatar('头像')->image('', 40, 40);
        $grid->name('用户名');
        $grid->email('邮箱');

        $grid->filter(function ($filter) {
            $filter->like('name', '用户名');
            $filter->like('email', '邮箱');
        });

        $grid->actions(function ($action) {
            $action->disableView();
        });

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
        $show = new Show(User::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->email('Email');
        $show->password('Password');
        $show->remember_token('Remember token');
        $show->created_at('Created at');
        $show->updated_at('Updated at');
        $show->avatar('Avatar');
        $show->introduction('Introduction');
        $show->notification_count('Notification count');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form
            ->image('avatar', '头像')
            ->rules('mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200')
            ->move('uploads/images/avatars/' . date("Ym/d", time()));
        $form
            ->text('name', '姓名')
            ->rules(function ($form) {
                return 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . $form->model()->id;
            });
        $form->email('email', '邮箱')->rules('required');
        $form->password('password', '密码');
        $form->text('introduction', '简介')->rules('max:80');

        $form->tools(function ($tools) {
            $tools->disableView();
        });

        $form->footer(function ($footer) {
            $footer->disableViewcheck();
        });

        return $form;
    }
}
