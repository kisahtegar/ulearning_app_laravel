<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    protected $title ='Members';

    // This function used to show grid view user
    protected function grid()
    {
        // This models refering to user model
        $grid = new Grid(new User());

        // this all field related in our database
        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));
        // $grid->column('access_token', __('Access Token'));
        
        // Disable some actions for user
        
        $grid->disableActions();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableFilter();

        return $grid;
    }

    // This function used to show detail user
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new User());

        $form->textarea('name', __('Name'));
        $form->textarea('email', __('Email'));

        return $form;
    }
}