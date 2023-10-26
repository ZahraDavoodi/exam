<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\Http\Controllers\SuperAdminController;
use File;
use Illuminate\Filesystem\Filesystem;

use Illuminate\Support\Facades\Validator;
class ExamController extends Controller
{
    public function add()
    {

        SuperAdminController::AdminAuthCheck();
        $all_exams = DB::table('tbl_exam')->get();

        if(Session::get('isProf')==true){
            $all_profs = DB::table('tbl_prof')->where('prof_id',Session::get('admin_id'))->get();
        }else{
            $all_profs = DB::table('tbl_prof')->get();
        }

        $all_classes = DB::table('tbl_class')->get();
        $all_qts = DB::table('tbl_questiontype')->get();
        $all_courses = DB::table('tbl_course')->get();

        return View('admin.add_exam')->with(['all_exams'=>$all_exams,'all_profs'=>$all_profs,'all_classes'=>$all_classes,'all_qts'=>$all_qts,'all_courses'=>$all_courses]);
    }
    public function all()
    {
        SuperAdminController::AdminAuthCheck();
        if(Session::get('isProf')==true){
            $all_exams = DB::table('tbl_exam')->where('prof_id',Session::get('admin_id'))->get();
        }else{
            $all_exams = DB::table('tbl_exam')->get();
        }

        $all_profs = DB::table('tbl_prof')->get();
        $all_classes = DB::table('tbl_class')->get();
        $all_courses = DB::table('tbl_course')->get();

        return View('admin.all_exams')->with(['all_exams'=>$all_exams,'all_profs'=>$all_profs,'all_courses'=>$all_courses]);
    }

    public function save(Request $request)
    {

        SuperAdminController::AdminAuthCheck();

        // add all new exam data in DB ::
        $data = array();
        $attr = array();
        $data['exam_name'] = $request->exam_name;
        $data['exam_qNum'] = $request->exam_qNum;
        $data['exam_maxScore'] = $request->maxScore;
        $data['exam_date'] = $request->exam_date;
        $data['exam_time'] = $request->exam_time;
        $data['exam_duration'] = $request->exam_duration;
        $data['qt_id'] = $request->qt_id;
        $data['prof_id'] = $request->prof_id;
        $data['course_id'] = $request->course_id;
        $data['class_id'] = $request->class_id;


        include 'jdf.php';
        $now = jdate('Y-m-d', '', '', '', 'en');
        $now = (string)$now;
        $today = $now;


        if ($request->publication_status == 'on') {
            $data['publication_status'] = 1;
        } else {
            $data['publication_status'] = 0;
        }



            if (DB::table('tbl_exam')->insert($data)) {

                        session::put('msg', 'امتحان جدید با موفقیت اضافه شد');
                       return Redirect::to('/okAdminShod/exam/all');
                    }
            else {
            session::put('msg', 'امتحان جدید با موفقیت اضافه نشد');
            return Redirect::to('/okAdminShod/exam/all');
            }

        }
    public function unactive($exam_id)
    {
        SuperAdminController::AdminAuthCheck();
        // Make Publication_status =  0
        DB::table('tbl_exam')
            ->where('exam_id',$exam_id)
            ->update(['publication_status'=>0]);
        session::put('msg','وضعیت امتحان به حالت غیرفعال تغییر یافت.');
        return Redirect::to('okAdminShod/exam/all');
    }
    public function active($exam_id)
    {
        SuperAdminController::AdminAuthCheck();
        // Make Publication_status =  1
        
        DB::table('tbl_exam')
            ->where('exam_id',$exam_id)
            ->update(['publication_status'=>1]);
        session::put('msg','وضعیت امتحان به حالت فعال تغییر یافت.');
        return Redirect::to('okAdminShod/exam/all');
    }
    public function edit($exam_id)
    {
        SuperAdminController::AdminAuthCheck();
        //echo $exam_id;
        $data =  DB::table('tbl_exam')
            ->where('exam_id',$exam_id)
            ->get()->first();

        if(Session::get('isProf')==true){
            $all_profs = DB::table('tbl_prof')->where('prof_id',Session::get('admin_id'))->get();
        }else{
            $all_profs = DB::table('tbl_prof')->get();
        }


        $all_classes = DB::table('tbl_class')->get();
        $all_qts = DB::table('tbl_questiontype')->get();
        $all_courses = DB::table('tbl_course')->get();

        return View('admin.edit_exam')->with(['exam_infos'=>$data,'all_profs'=>$all_profs,'all_classes'=>$all_classes,'all_qts'=>$all_qts,'all_courses'=>$all_courses]);


    }
    public function done_edit(Request $request , $exam_id)
    {
        SuperAdminController::AdminAuthCheck();


        $update_info['exam_name'] = $request->exam_name;
        $update_info['exam_qNUm'] = $request->exam_qNum;
        $update_info['exam_maxScore'] = $request->exam_maxScore;
        $update_info['exam_date'] = $request->exam_date;
        $update_info['exam_time'] = $request->exam_time;
        $update_info['exam_duration'] = $request->exam_duration;
        $update_info['qt_id'] = $request->qt_id;
        $update_info['prof_id'] = $request->prof_id;
        $update_info['class_id'] = $request->class_id;
        $update_info['course_id'] = $request->course_id;

        if ($request->publication_status == 'on') {
            $update_info['publication_status'] = 1;
        }else
        {
            $update_info['publication_status'] = 0;
        }


            $isUpdated = DB::table('tbl_exam')
                ->where('exam_id',$exam_id)
                ->update($update_info);

        if ($update_info) {
            session::put('msg','ویرایش امتحان به درستی انجام شد');
           return Redirect::to('/okAdminShod/exam/all');
        }else {
            session::put('msg','ویرایش امتحان به درستی انجام نشد');
            return Redirect::to('/okAdminShod/exam/all');
        }
    }
    public function delete($exam_id)
    {
        SuperAdminController::AdminAuthCheck();

        $isDeleted = DB::table('tbl_exam')
            ->where('exam_id',$exam_id)
            ->delete();

        if ($isDeleted) {
                    session::put('msg','امتحان با موفقیت حذف شد.');
                   return Redirect::to('/okAdminShod/exam/all');
                }


    }
    function showQuestion($exam_id)
    {
        SuperAdminController::AdminAuthCheck();
        $exam = DB::table('tbl_exam')
            ->where('exam_id',$exam_id)
            ->first();
        $questions = DB::table('tbl_questions')
            ->where('exam_id',$exam_id)
            ->get();
        $array_q=array();
        if(count($questions)){
            foreach ($questions as $q){
                     array_push($array_q,$q);

            }
        }
        return View('admin.show_question')->with(['exam_infos'=>$exam,'questions'=>$array_q]);
    }

    public function save_questions(Request $request)
    {
        SuperAdminController::AdminAuthCheck();
        $data=array();
        $isDeleted = DB::table('tbl_questions')
            ->where('exam_id', $request->exam_id)
            ->delete();

        $data['exam_id'] = $request->exam_id;
        $questions = $request->question;
        $grades = $request->q_grade;
        $nums = $request->q_num;

        $count = count($questions);
        for ($i=0; $i<$count;$i++){
            $data['q_title'] = $questions[$i];
            $data['q_grade']=$grades[$i];
            $data['q_num']=$nums[$i];

            if($data['q_title'] != ''){$q1_id = DB::table('tbl_questions')->insertGetId($data);}
        }
        if($q1_id){
            session::put('msg','سوالات ثبت شد.');
            return Redirect::to('/okAdminShod/exam/all');
        }else{
               session::put('msg','سوالات ثبت نشد.');
                return Redirect::to('/okAdminShod/exam/all');
            }

        }

    public function showTakenExams(){
        SuperAdminController::AdminAuthCheck();
        include 'jdf.php';
        $now = jdate('Y-m-d', '', '', '', 'en');

        if(Session::get('isProf')==true){
            $all_exams = DB::table('tbl_exam')->whereDate('exam_date','<=',$now)->where('prof_id',Session::get('admin_id'))->get();
        }else{
            $all_exams = DB::table('tbl_exam')->whereDate('exam_date','<=',$now)->get();
        }

        if(Session::get('isProf')==true){
            $all_profs = DB::table('tbl_prof')->where('prof_id',Session::get('admin_id'))->get();
        }else{
            $all_profs = DB::table('tbl_prof')->get();
        }

        $all_classes = DB::table('tbl_class')->get();
        $all_courses = DB::table('tbl_course')->get();

        return View('admin.all_takenExams')->with(['all_exams'=>$all_exams,'all_profs'=>$all_profs,'all_courses'=>$all_courses,'all-classes'=>$all_classes]);
    }

    public function showTakenExams_answers($exam_id){
        SuperAdminController::AdminAuthCheck();


        $exam_infos = DB::table('tbl_exam')->where('exam_id',$exam_id)->first();
        $class_id=$exam_infos->class_id;
        $all_customers = DB::table('tbl_customer')->where('class_id',$class_id)->get();
        $all_questions=DB::table('tbl_questions')->where('exam_id',$exam_id)->get();
        $all_answers = DB::table('tbl_answer')->where('exam_id',$exam_id)->orderby('customer_id','ASC')->get();
        $all_grades = DB::table('tbl_grade')->where('exam_id',$exam_id)->get();

        return View('admin.edit_answer')->with(['exam_infos'=>$exam_infos,'all_answers'=>$all_answers,'all_questions'=>$all_questions,'all_customers'=>$all_customers,'all_grades'=>$all_grades]);
    }


    public  function save_grade(Request $request)
    {
        SuperAdminController::AdminAuthCheck();
        $data=array();


        $data['exam_id'] = $request->exam_id;
        $data['customer_id'] = $request->customer_id;
        $grades = $request->grade;

        $count = count($grades);
        $q1_id=0;
        for ($i=0; $i<$count;$i++){
            $data['grade_grade'] = $grades[$i+1];
            $q1_id = DB::table('tbl_grade')->insertGetId($data);

        }

        if($q1_id){
            session::put('msg','نمرات ثبت شد.');
            return Redirect::to('/okAdminShod/taken_exams');
        }else{
            session::put('msg','نمرات ثبت نشد.');
            return Redirect::to('/okAdminShod/taken_exams');
        }
    }



}
