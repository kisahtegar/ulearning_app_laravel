<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\CourseType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Tree;

class CourseTypeController extends AdminController
{
    protected $title ='Course Type';

    // Actually for showing tree form of the menus.
    public function index(Content $content)
    {
        $tree = new Tree(new CourseType());
        return $content->header('Course Type')->body($tree);
    }

    // This function used to show view detail course type
    protected function detail($id)
    {
        // We are looking for a course type, we looking based in database base on id
        $show = new Show(CourseType::findOrFail($id));

        // this all field related in our database
        $show->field('id', __('Id'));
        $show->field('title', __('Category'));
        $show->field('description', __('Description'));
        $show->field('order', __('Order'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    // this form for create new course 
    protected function form()
    {
        $form = new Form(new CourseType());
        $form->select('parent_id', __('Parent Category'))->options((new CourseType())::selectOptions());
        $form->text('title', __('Title'));
        $form->textarea('description', __('Description'));
        $form->number('order', __('Order'));

        return $form;
    }
}
