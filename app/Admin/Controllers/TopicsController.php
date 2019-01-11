<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Http\Controllers\Controller;
use App\Models\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TopicsController extends Controller
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
        $grid = new Grid(new Topic);

        $grid->model()->with(['user', 'category']);

        $grid->id('Id')->sortable();
        $grid->column('title', '话题')->display(function ($title) {
            return '<div style="max-width:260px">' . model_link($title, $this) . '</div>';
        });
        $grid->column('user', '作者')->display(function () {
            $avatar = $this->user->avatar;
            $value = empty($avatar) ? 'N/A' :
                '<img src="' . (stripos($avatar, 'http') === 0 ? $avatar : "/$avatar") . '" style="height:22px;width:22px"> '
                . $this->user->name;
            return model_link($value, $this->user);
        });

        $grid->column('category', '分类')->display(function () {
            return model_admin_link($this->category->name, $this->category);
        });

        $grid->reply_count('评论');

        $grid->actions(function ($action) {
            $action->disableView();
        });

        $grid->filter(function ($filter) {
//            $filter->where(function ($query) {
//                $query->whereHas('user', function ($query) {
//                    $query->where('name', 'like', "%{$this->input}%");
//                });
//
//            }, '作者');

            $filter->equal('user_id', '作者')->select(User::all()->pluck('name', 'id'));

            $filter->equal('category_id', '分类')->select(Category::all()->pluck('name', 'id'));
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
        $show = new Show(Topic::findOrFail($id));

        $show->id('Id');
        $show->title('Title');
        $show->body('Body');
        $show->user_id('User id');
        $show->category_id('Category id');
        $show->reply_count('Reply count');
        $show->view_count('View count');
        $show->last_reply_user_id('Last reply user id');
        $show->order('Order');
        $show->excerpt('Excerpt');
        $show->slug('Slug');
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
        $form = new Form(new Topic);

        $form->text('title', '标题');

        $form->select('user_id', '作者')->options(User::all()->pluck('name', 'id'));
        $form->select('category_id', '分类')->options(Category::all()->pluck('name', 'id'));

        $form->textarea('body', '内容');
        $form->number('reply_count', '评论');
        $form->number('view_count', '查看');

        $form->tools(function ($tools) {
            $tools->disableView();
        });

        $form->footer(function ($footer) {
            $footer->disableViewcheck();
        });

        return $form;
    }
}
