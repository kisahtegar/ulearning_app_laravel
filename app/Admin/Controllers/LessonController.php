<?php

namespace App\Admin\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LessonController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Lesson';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Lesson());

        $grid->column('id', __('Id'));
        $grid->column('course_id', __('Course id'));
        $grid->column('name', __('Name'));
        $grid->column('thumbnail', __('Thumbnail'))->image(50, 50);
        $grid->column('description', __('Description'));
       // $grid->column('video', __('Video'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Lesson::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('name', __('Name'));

        $show->field('course_id', __('Course name'));


        $show->field('thumbnail', __('Thumbnail'));
        $show->field('description', __('Description'));
        $show->field('video', __('Video'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Lesson());

        //$form->display('id', __('ID'));

        // this will query course name and id
        $result = Course::pluck('name', 'id');
        // dd($result);
        $form->text('name', __('Name'));
        $form->select('course_id', __('Courses'))->options($result);
        $form->image('thumbnail', __('Thumbnail'))->uniqueName();
        $form->textarea('description', __('Description'));

        if($form->isEditing()){

                // access this during form eddting
                // dump($form->video);

                $form->table('video', function ($form) {
                    $form->text('name');
                    $form->hidden('old_url');
                    $form->hidden('old_thumbnail');
                    $form->image('thumbnail')->uniqueName();

                    //any kind of media
                    $form->file('url');
                });

        }else{

                // normal form submission or form creating

                $form->table('video', function ($form) {
                    $form->text('name')->rules('required');
                    $form->image('thumbnail')->uniqueName()->rules('required');
                    //any kind of media
                    $form->file('url')->rules('required');
                });
        }

        // saving call back gets called before submitting to the datbase
        // but after clicking the submit button
        //  a good place to process grabbed data or form data
        $form->saving(function (Form $form){
            
            
            if($form->isEditing()){
                // for debugging purposes
                // dump($form->model()->video);
                // dump($form->video);

                //here is the place to process data and
                //the below one gets the editted data
                $video = $form->video;
                // the below gets data from the database
                $res = $form->model()->video;
                //for each of the key, get the value
                $path = env('APP_URL') . "uploads/";

                $newVideo = [];
                foreach ($video as $k => $v) {
                    $oldVideo = [];
                    //user did not type anything
                    if (empty($v['url'])) {
                        $oldVideo["old_url"] = empty($res[$k]['url']) ? ""
                            //replacing the domian path from the value
                            : str_replace($path, "", $res[$k]['url']);
                    } else {
                        //this is a new editted value
                        $oldVideo["url"] = $v['url'];
                    }

                    if (empty($v['thumbnail'])) {
                        $oldVideo["old_thumbnail"] = empty($res[$k]['thumbnail']) ? ""
                            //replacing the domian path from the value
                            : str_replace($path, "", $res[$k]['thumbnail']);
                    } else {
                        //this is a new editted value
                        $oldVideo["thumbnail"] = $v['thumbnail'];
                    }

                    if (empty($v['name'])) {
                        $oldVideo["name"] = empty($res[$k]['name']) ? ""

                            :  $res[$k]['name'];
                    } else {
                        //this is a new editted value
                        $oldVideo["name"] = $v['name'];
                    }


                    $oldVideo['_remove_']=$v['_remove_'];
                    array_push($newVideo, $oldVideo);
                   // dump($newVideo);
                }
                $form->video = $newVideo;
           }


        });

        return $form;
    }
}
