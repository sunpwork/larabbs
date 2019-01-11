<?php

namespace App\Admin\Controllers;

use App\Models\Reply;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class RepliesController extends Controller
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
            ->header('Index')
            ->description('description')
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
            ->header('Detail')
            ->description('description')
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
            ->header('Edit')
            ->description('description')
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
        $grid = new Grid(new Reply);
        $grid->model()->with(['topic', 'user']);

        $grid->id('Id')->sortable();
        $grid->column('content', '内容')->display(function ($content) {
            return '<div style="max-width:220px">' . $content . '</div>';
        });
        $grid->column('user', '作者')->display(function () {
            $avatar = $this->user->avatar;
            $value = empty($avatar) ? 'N/A' :
                '<img src="' . (stripos($avatar, 'http') === 0 ? $avatar : "/$avatar") . '" style="height:22px;width:22px"> '
                . $this->user->name;
            return model_link($value, $this->user);
        });
        $grid->column('topic', '话题')->display(function () {
            return '<div style="max-width:260px">' . model_link($this->topic->title, $this->topic) . '</div>';
        });

        $grid->actions(function ($action) {
            $action->disableView();
        });

        $grid->filter(function ($filter) {

            $filter->equal('user_id', '用户')->select(User::all()->pluck('name', 'id'));

            $filter->equal('topic_id', '话题')->select(Topic::all()->pluck('title', 'id'));
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
        $show = new Show(Reply::findOrFail($id));

        $show->id('Id');
        $show->topic_id('Topic id');
        $show->user_id('User id');
        $show->content('Content');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Reply);

        $form->textarea('content', 'Content');
        $form->select('user_id', '用户')->options(User::all()->pluck('name', 'id'));
        $form->select('topic_id', '话题')->options(Topic::all()->pluck('title', 'id'));

        $form->tools(function ($tools) {
            $tools->disableView();
        });

        $form->footer(function ($footer) {
            $footer->disableViewcheck();
        });

        return $form;
    }
}
