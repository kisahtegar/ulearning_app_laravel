<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\CourseType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Tree;

class CourseController extends AdminController
{
    protected $title ='Courses';

    // This function used to show grid view / row of course
    protected function grid()
    {
        $grid = new Grid(new Course());
        // the first argument is the database field
        $grid->column('id', __('Id'));

        // this will change user_token to teacher names based on user token.
        $grid->column('user_token', __('Teacher'))->display(function ($token) {
            // for further processing data, you can create any method inside it or do operation
            return User::where('token', '=', $token)->value('name');
        });

        $grid->column('name', __('Name'));
        
        // 50,50 refers to the image size
        $grid->column('thumbnail', __('Thumbnail'))->image('', 50, 50);
        $grid->column('description', __('Description'));
        $grid->column('type_id', __('Type id'));
        $grid->column('price', __('Price'));
        $grid->column('lesson_num', __('Lesson num'));
        $grid->column('video_length', __('Video length'));
        $grid->column('created_at', __('Created at'));

        return $grid;
    }

    // This function used to show view detail course
    protected function detail($id)
    {
        $show = new Show(Course::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('thumbnail', __('Thumbnail'));
        $show->field('description', __('Description'));
        $show->field('price', __('Price'));
        $show->field('lesson_num', __('Lesson num'));
        $show->field('video_length', __('Video length'));
        $show->field('follow', __('Follow'));
        $show->field('score', __('Score'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    // Creating and editing
    protected function form()
    {
        $form = new Form(new Course());
        $form->text('name', __('Name'));

        // get our categories
        // key value pair
        // last one is the key, first one is the value
        // pluck is native laravel.
        $result = CourseType::pluck('title', 'id');
        // dd($result);
        
        // select methods helps you select one of the options they
        // comes from result variables
        $form->select('type_id', __('Category'))->options($result);
        
        // showing thumbnail for uploading
        $form->image('thumbnail', __('Thumbnail'))->uniqueName();
        // file is used for video and other format like pdf/doc
        $form->file('video', __('Video'))->uniqueName();
        $form->text('description', __('Description'));
        // decimal method helps with retrieving float format from database
        $form->decimal('price', __('Price'));
        $form->number('lesson_num', __('Lesson number'));
        $form->number('video_length', __('Video length'));
        // for the posting, who is posting
        $result = User::pluck('name', 'token');
        // dd($result);
        $form->select('user_token', __('Teacher'))->options($result);
        $form->display('created_at',__('Created at'));
        $form->display('updated_at',__('Updated at'));


        
        // $form->text('title', __('Title'));
        // $form->textarea('description', __('Description'));
        // $form->number('order', __('Order'));

        return $form;
    }
}
