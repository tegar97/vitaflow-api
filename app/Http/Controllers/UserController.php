<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\MyDrinkActivity;
use App\Models\MyMission;
use App\Models\MyNutrion;
use App\Models\MyProgram;
use App\Models\MyRunningActivity;
use App\Models\MyWeightTrackActivity;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function survey(Request $request)
    {

        // Check auth
        $auth = auth()->user();

        if (!$auth) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        // Validasi input dari user
        $request->validate([
            'gender' => 'required',
            'goal' => 'required|in:gain,loss,maintain',
            'age' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'target_weight' => 'required|numeric',
        ]);

        // Menghitung nilai BMI
        $height_in_meters = $request->height / 100;
        $bmi = $request->weight / ($height_in_meters ** 2);

        // Menghitung rekomendasi berat badan
        $height_in_cm = $request->height;
        $gender = $request->gender;
        $ideal_weight = $this->getIdealWeight($height_in_cm, $gender);
        $recommended_weight = $ideal_weight + ($request->target_weight - $ideal_weight) * 0.75;

        // Menghitung rekomendasi kalori harian
        $goal = $request->goal;
        $age = $request->age;
        $daily_calories = $this->getDailyCalories($gender, $age, $height_in_cm, $recommended_weight, $goal);

        // klasifikasi BMI
        $bmi_classification = $this->bmiClassification($bmi);

        if ($bmi < 18.5 && $goal == 'loss') {
            $warning = "Warning: Your BMI indicates that you are underweight. It may not be healthy to try to gain weight at this time.";
        } elseif ($bmi >= 18.5 && $bmi < 25 && ($goal == 'loss' || $goal == 'gain')) {
            $warning = "Warning: Your BMI indicates that you are in the normal weight range. It may not be healthy to try to lose or gain weight at this time.";
        } elseif ($bmi >= 25 && $bmi < 30 && $goal == 'loss') {
            $warning = "Warning: Your BMI indicates that you are overweight. It is recommended that you focus on losing weight rather than gaining muscle mass at this time.";
        } elseif ($bmi >= 30 && $goal == 'loss') {
            $warning = "Warning: Your BMI indicates that you are obese. It is strongly recommended that you focus on losing weight for the sake of your health.";
        } else {
            $warning = "";
        }

        // Mencari program yang cocok berdasarkan BMI
        $programs = Program::where('program_type', $goal)->first();

        // Memasukkan hasil survei dan hasil nilai BMI ke dalam model user
        // $user = new User();
        $user = User::find($auth->id);

        $user->goal = $request->goal;
        $user->gender = $request->gender;
        $user->age = $request->age;
        $user->height = $request->height;
        $user->weight = $request->weight;
        $user->target_weight = $request->target_weight;
        $user->bmi = $bmi;
        $user->recommend_calories = $daily_calories;


        // $user->programs()->sync($programs);
        $user->save();


        // Menyimpan hasil ke my program
        $myProgram = new MyProgram();

        $myProgram->user_id = $auth->id;
        $myProgram->program_id = $programs->id;
        $myProgram->status = 'on-going';
        $myProgram->join_date = date('Y-m-d');
        $myProgram->end_date = date('Y-m-d', strtotime('+14 days'));

        $myProgram->save();

        // Algorithm
        // 1.get all program mission
        // 2.generate my_mission until end_date
        $program = Program::find($programs->id);
        // get all mission
        $missions = Mission::where('program_id', $program->id)->get();

        // generate my_mission
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('+14 days'));
        $currentDate = $startDate;

        while ($currentDate <= $endDate) {
            // my nutrition
            $myNutrion = new MyNutrion();
            $myNutrion->program_id = $program->id;
            $myNutrion->user_id = $auth->id;

            $myNutrion->date = $currentDate;
            $myNutrion->targetCalories = $daily_calories;
            $myNutrion->activityCalories = 0;
            $myNutrion->intakeCalories = 0;
            $myNutrion->fat = 0;
            $myNutrion->carbohydrate = 0;
            $myNutrion->protein = 0;
            $myNutrion->akg = 0;
            $myNutrion->calorieLeft = $myNutrion->targetCalories - $myNutrion->activityCalories + $myNutrion->intakeCalories;;

            $myNutrion->save();

            foreach ($missions as $mission) {
                $myMission = new MyMission();
                $myMission->mission_id = $mission->id;
                $myMission->user_id = $auth->id;
                $myMission->status = 'on-going';
                if ($mission->name == 'Catat Aktivitas Makanan') {
                    $myMission->target = $daily_calories;
                    $myMission->type_target = 'cal';
                } else if ($mission->name == 'Catat Aktivitas Olahraga') {
                    $myMission->target = 300;
                    $myMission->type_target = 'cal';
                } else if ($mission->name == 'Catat Aktivitas Lari/ Jalan') {
                    $myMission->target = 2000;
                    $myMission->type_target = 'langkah';
                } else if ($mission->name == 'Catat asupan Minum') {
                    $myMission->target = 8;
                    $myMission->type_target = 'gelas';
                } else if ($mission->name == 'Catat berat  badan') {
                    $myMission->target = 0;
                    $myMission->type_target = 'kg';
                } else if ($mission->name == 'Check Kesehatan anda') {
                    $myMission->target = 0;
                    $myMission->type_target = 'bpm';
                }
                $myMission->current = 0;
                $myMission->date = $currentDate;
                $myMission->save();
            }
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }


        // code






        // Mengembalikan response dalam format JSON
        return response()->json([
            'message' => 'Success',
            'data' => [
                'bmi' => $bmi,
                'ideal_weight' => $ideal_weight,
                'recommended_weight' => $recommended_weight,
                'daily_calories' => $daily_calories,
                'bmi_classification' => $bmi_classification,
                'warning' => $warning,
                'programs' => $programs,
            ]
        ], 200);
    }

    private function getIdealWeight($height_in_cm, $gender)
    {
        // Menghitung ideal weight berdasarkan tinggi dan jenis kelamin
        if ($gender == 'male') {
            return (0.9 * $height_in_cm - 100) * 0.9;
        } else {
            return (0.85 * $height_in_cm - 100) * 0.9;
        }
    }

    private
    function getDailyCalories($gender, $age, $height_in_cm, $weight, $goal)
    {
        // Menghitung rekomendasi kalori harian berdasarkan jenis kelamin, umur, tinggi, berat badan, dan tujuan
        if ($gender == 'male') {
            $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height_in_cm) - (5.677 * $age);
        } else {
            $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height_in_cm) - (4.330 * $age);
        }

        if ($goal == 'gain') {
            return $bmr * 1.2 + 500;
        } else if ($goal == 'loss') {
            return $bmr * 1.2 - 500;
        } else {
            return $bmr;
        }
    }

    private function bmiClassification($bmi)
    {
        // Mengklasifikasikan nilai BMI
        if ($bmi < 18.5) {
            $bmi_classification = 'Underweight';
        } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
            $bmi_classification = 'Normal';
        } elseif ($bmi >= 25 && $bmi <= 29.9) {
            $bmi_classification = 'Overweight';
        } else {
            $bmi_classification = 'Obesity';
        }

        return $bmi_classification;
    }

    public function findSuitableProgram($bmi)
    {
        $programs = Program::all();
        $suitablePrograms = [];

        foreach ($programs as $program) {
            $averageBMI = ($program->bmi_min + $program->bmi_max) / 2;

            if ($bmi > $averageBMI) {
                if ($bmi <= $program->bmi_max) {
                    $suitablePrograms[] = $program;
                }
            }
        }

        return $suitablePrograms;
    }


    public function getMyPrograms(Request $request)
    {
        $auth = auth()->user();

        $status = $request->query('status'); // ambil nilai parameter status dari URL

        $query = MyProgram::where('user_id', $auth->id);

        // jika parameter status ada dan nilainya valid, tambahkan query untuk mencari program dengan status yang dipilih
        if ($status && in_array($status, ['on-going', 'cancel', 'finish'])) {
            $query->where('status', $status);
        }

        $myPrograms = $query->get();

        return response()->json([
            'message' => 'Success',
            'data' => $myPrograms
        ], 200);
    }


    // EXIT PROGRAM

    public function exitProgram(Request $request)
    {

        $auth = $request->user();

        $myProgram = MyProgram::where('user_id', $auth->id)
            ->where('program_id', $request->program_id)
            ->whereIn('status', ['on-going'])
            ->firstOrFail();

        $myProgram->status = 'cancel';
        $myProgram->end_date = now();

        $myProgram->save();

        return response()->json([
            'message' => 'Success',
            'data' => $myProgram
        ], 200);
    }

    public function getDailyUserData(Request $request)
    {
        $auth = auth()->user();
        // Validasi request parameter
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d'
        ]);

        // Jika validasi gagal, kembalikan pesan error
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        // hitung total kalori

        // Ambil data MyNutrion dan MyMission dari database berdasarkan parameter date
        $myNutrion = MyNutrion::select('date', 'targetCalories', 'calorieLeft', 'activityCalories', 'carbohydrate', 'protein', 'fat', 'intakeCalories')
            ->where('user_id', $auth->id)
            ->where('date', $request->input('date'))
            ->first();
        $allNutrionData =  $myNutrion->protein + $myNutrion->carbohydrate + $myNutrion->fat;
        $akg = (  $myNutrion->intakeCalories /  $auth->recommend_calories) * 100;
        // hitung persentase protein, karbohidrat, dan lemak terhadap total kalori
        $akg_percentange =
        round($akg, 2);

        if($allNutrionData > 0) {
            $proteinPercentage = $myNutrion->protein / $allNutrionData;
            $carbPercentage = $myNutrion->carbohydrate /
            $allNutrionData;
            $fatPercentage = $myNutrion->fat /
            $allNutrionData;
        }else{
            $proteinPercentage = 0;
            $carbPercentage = 0;
            $fatPercentage = 0;
        }


        $myMissions = MyMission::where('user_id', auth()->id())
            ->where('date', $request->input('date'))
            ->with('mission')
            ->get()
            ->map(function ($myMission) {
            $percentage_success = 0;

            if ($myMission->type_target == 'cal' or $myMission->type_target == 'langkah' or $myMission->type_target == 'gelas') {
                $percentage_success = ($myMission->current / $myMission->target) * 100;
            }

            if ($myMission->status == 'finish') {
                $percentage_success = 100;
            }

            if ($percentage_success > 100) {
                $percentage_success = 100;
            }
                return [
                    'name' => $myMission->mission->name,
                    'description' => $myMission->mission->description,
                    'icon' => $myMission->mission->icon,
                    'color_theme' => $myMission->mission->color_Theme,
                    'point' => $myMission->mission->point,
                    'target' => $myMission->target,
                    'current' => $myMission->current,
                    'type_target' => $myMission->type_target,
                    'status' => $myMission->status,
                    'date' => $myMission->date,
                   'percentange_success' => $percentage_success,
                ];
            });



        // Jika tidak ada data yang ditemukan, kembalikan pesan not found
        if (!$myNutrion || !$myMissions) {
            return response()->json(['error' => 'Data not found'], 404);
        }
        $totalMissions = count($myMissions);
        $totalMissionsUnfinished = $myMissions->where('status', 'on-going')->count();
        $totalMissionsFinished = $myMissions->where('status', 'finished')->count();
        // Kembalikan data dalam format JSON
        return response()->json([
            'message' => 'Success',
            'data' => [
                "my_nutrion" => [
                    "date" => $myNutrion->date,
                    "targetCalories" => $myNutrion->targetCalories,
                    "calorieLeft" => $myNutrion->calorieLeft,
                    "activityCalories" => $myNutrion->activityCalories,
                    "carbohydrate" => $myNutrion->carbohydrate,
                    "protein" => $myNutrion->protein,
                    "fat" => $myNutrion->fat,
                    "intakeCalories" => $myNutrion->intakeCalories,
                    "proteinPercentage" => $proteinPercentage,
                    "carbPercentage" => $carbPercentage,
                    "fatPercentage" => $fatPercentage,
                    "akg" => $akg_percentange
                ],
                'total_missions' => $totalMissions,
                'total_missions_unfinished' => $totalMissionsUnfinished,
                'total_missions_finished' => $totalMissionsFinished,
                'my_missions' => $myMissions
            ]
        ], 200);
    }

    //  drink mission
    public function storeDrink(Request $request)
    {
        $auth = auth()->user();

        $mission = Mission::where('name', "Catat asupan Minum")->first();

        // check if mission is exist
        if (!$mission) {
            return response()->json(['error' => 'Data not found'], 404);
        }



       $myMission = MyMission::where('user_id', $auth->id)
            ->where('mission_id', $mission->id)
            ->where('date', date('Y-m-d'))
            ->first();

        if (!$myMission) {
            return response()->json(['error' => 'MyMission not found'], 404);
        }

        // store to my_drink_activity
        $myDrinkActivity = new MyDrinkActivity();
        $myDrinkActivity->user_id = $auth->id;
        $myDrinkActivity->my_mission_id = $myMission->id;
        $myDrinkActivity->value = 1;
        $myDrinkActivity->date = date('Y-m-d');
        $myDrinkActivity->save();








        $myMission->current += 1;


        if($myMission->current >= $myMission->target){
            $myMission->status = 'finish';
            $myMission->save();
        }

        $myMission->save();


        return response()->json([
            'message' => 'Success',

        ], 200);





    }

    // get drink history by date
    public function getUserDrinks(Request $request)
    {
        $auth = auth()->user();
        $date = $request->input('date', date('Y-m-d'));
        $days = $request->input('days', 7); // default to 7 days if not specified

        $endDate = date('Y-m-d', strtotime($date));
        $startDate = date('Y-m-d', strtotime("-$days day", strtotime($endDate)));




        $myDrinkActivities = MyDrinkActivity::where('user_id', $auth->id)
            ->whereBetween('date', [$startDate, $endDate])->select('date', 'value')
            ->select('date', 'value')
            ->get();



        return response()->json([
            'message' => 'Success',
            'data' => $myDrinkActivities
        ], 200);


    }

    // store weight track
    public function storeWeightTrackData(Request $request)
    {
        $auth = auth()->user();

        // validator request
        $validator = Validator::make($request->all(), [
            'weight' => 'required|numeric',
        ]);


        // check if validator is failed
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $mission = Mission::where('name', 'like', '%catat berat badan%')->first();

        // check if mission is exist
        if (!$mission) {
            return response()->json(['error' => 'Data not found'], 404);
        }



        $myMission = MyMission::where('user_id', $auth->id)
            ->where('mission_id', $mission->id)
            ->where('date', date('Y-m-d'))
            ->first();

        if (!$myMission) {
            return response()->json(['error' => 'MyMission not found'], 404);
        }

        // store to my_drink_activity
        $myDrinkActivity = new MyWeightTrackActivity();
        $myDrinkActivity->user_id = $auth->id;
        $myDrinkActivity->my_mission_id = $myMission->id;
        $myDrinkActivity->value = $request->weight;
        $myDrinkActivity->date = date('Y-m-d');
        $myDrinkActivity->save();








        $myMission->current =  $request->weight;


        if ($myMission->current >= $myMission->target) {
            $myMission->status = 'finish';
            $myMission->save();
        }

        $myMission->save();


        return response()->json([
            'message' => 'Success',

        ], 200);

    }
    // get weight track
    public function getUserWeightTrackData(Request $request)
    {
        $auth = auth()->user();
        $date = $request->input('date', date('Y-m-d'));
        $days = $request->input('days', 7); // default to 7 days if not specified

        $endDate = date('Y-m-d', strtotime($date));
        $startDate = date('Y-m-d', strtotime("-$days day", strtotime($endDate)));

        $myWeightTrack = MyWeightTrackActivity::where('user_id', $auth->id)
            ->whereBetween('date', [$startDate, $endDate])->select('date', 'value')
            ->get();

        return response()->json([
            'message' => 'Success',
            'data' => $myWeightTrack
        ], 200);


    }


    // store running acivity like storeDrink() function
    public function storeStepTrackData(Request $request)
    {
        $auth = auth()->user();

        // validator request
        $validator = Validator::make($request->all(), [
            'step' => 'required|numeric',
        ]);


        // check if validator is failed
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $mission = Mission::where('name', 'like', '%Catat Aktivitas Lari/ Jalan%')->first();

        // check if mission is exist
        if (!$mission) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $myMission = MyMission::where('user_id', $auth->id)
            ->where('mission_id', $mission->id)
            ->where('date', date('Y-m-d'))
            ->first();

        if (!$myMission) {
            return response()->json(['error' => 'MyMission not found'], 404);
        }

        // store to my_drink_activity
        $myRunningActivity = new MyRunningActivity();
        $myRunningActivity->user_id = $auth->id;
        $myRunningActivity->my_mission_id = $myMission->id;
        $myRunningActivity->value = $request->step;
        $myRunningActivity->date = date('Y-m-d');
        $myRunningActivity->save();








        $myMission->current +=  $request->step;


        if ($myMission->current >= $myMission->target) {
            $myMission->status = 'finish';
            $myMission->save();
        }

        $myMission->save();


        return response()->json([
            'message' => 'Success',

        ], 200);







    }

    // get running activity
    public function getUserStepTrackData(Request $request)
    {
        $auth = auth()->user();
        $date = $request->input('date', date('Y-m-d'));
        $days = $request->input('days', 7); // default to 7 days if not specified

        $endDate = date('Y-m-d', strtotime($date));
        $startDate = date('Y-m-d', strtotime("-$days day", strtotime($endDate)));

        $myRunningActivity = MyRunningActivity::where('user_id', $auth->id)
            ->whereBetween('date', [$startDate, $endDate])->select('date', 'value')
            ->get();

        return response()->json([
            'message' => 'Success',
            'data' => $myRunningActivity
        ], 200);

    }






}
