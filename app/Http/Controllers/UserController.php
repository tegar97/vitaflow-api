<?php

namespace App\Http\Controllers;

use App\Models\exerciseType;
use App\Models\Food;
use App\Models\FoodActivityTracking;
use App\Models\HealthTrackActivity;
use App\Models\Mission;
use App\Models\MyDrinkActivity;
use App\Models\MyMission;
use App\Models\MyNutrion;
use App\Models\MyProgram;
use App\Models\MyRunningActivity;
use App\Models\MyWeightTrackActivity;
use App\Models\payment as ModelsPayment;
use App\Models\Program;
use App\Models\sportTrackingActivity;
use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
                if (strtolower(trim($mission->name)) == 'catat aktivitas makanan') {
                    $myMission->target = $daily_calories;
                    $myMission->type_target = 'cal';
                } else if (strtolower(trim($mission->name)) == 'catat aktivitas olahraga') {
                    $myMission->target = 300;
                    $myMission->type_target = 'cal';
                } else if (strtolower(trim($mission->name)) == 'catat aktivitas lari/ jalan') {
                    $myMission->target = 2000;
                    $myMission->type_target = 'langkah';
                } else if (strtolower(trim($mission->name)) == 'catat asupan minum') {
                    $myMission->target = 8;
                    $myMission->type_target = 'gelas';
                } else if (strtolower(trim($mission->name)) == 'catat berat badan') {
                    $myMission->target = 0;
                    $myMission->type_target = 'kg';
                } else if (strtolower(trim($mission->name)) == 'check kesehatan anda') {
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
        $akg = ($myNutrion->intakeCalories /  $auth->recommend_calories) * 100;
        // hitung persentase protein, karbohidrat, dan lemak terhadap total kalori
        $akg_percentange =
            round($akg, 2);

        if ($allNutrionData > 0) {
            $proteinPercentage = $myNutrion->protein / $allNutrionData;
            $carbPercentage = $myNutrion->carbohydrate /
                $allNutrionData;
            $fatPercentage = $myNutrion->fat /
                $allNutrionData;
        } else {
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

        // get the user's total drink value for today



        // add the new drink value to the total
        $lastDrinkActivity = MyDrinkActivity::where('user_id', $auth->id)
            ->where('my_mission_id', $myMission->id)
            ->where('date', date('Y-m-d'))
            ->orderBy('id', 'desc')
            ->first();

        if ($lastDrinkActivity == null) {
            // Jika $lastDrinkActivity kosong, maka berikan nilai default 0
            $value = 0;
        } else {
            $value = $lastDrinkActivity->value;
        }

        // store the new drink activity
        $myDrinkActivity = new MyDrinkActivity();
        $myDrinkActivity->user_id = $auth->id;
        $myDrinkActivity->my_mission_id = $myMission->id;
        $myDrinkActivity->value  = $value + 1;
        $myDrinkActivity->date = date('Y-m-d H:i:s');
        $myDrinkActivity->save();









        $myMission->current += 1;


        if ($myMission->current >= $myMission->target) {
            if ($myMission->status != 'finish') { // tambahkan pengecekan ini
                $myMission->status = 'finish';
                $myMission->save();

                // find user and add point
                $user = User::find($auth->id);
                $user->point += $mission->point;
                $user->save();
            }
        }


        $myMission->save();

        $myDrinkList = MyDrinkActivity::where('user_id', $auth->id)
            ->where('my_mission_id', $myMission->id)
            ->where('date', date('Y-m-d'))
            ->orderBy('id', 'desc')
            ->get();


        return response()->json([
            'message' => 'Success',
            'data' =>  $myDrinkList

        ], 200);
    }

    // get drink history by date
    public function getUserDrinks(Request $request)
    {
        $auth = auth()->user();
        $date = $request->date;
        // $days = $request->input('days', 7); // default to 7 days if not specified

        // $endDate = date('Y-m-d', strtotime($date));
        // $startDate = date('Y-m-d', strtotime("-$days day", strtotime($endDate)));




        $myDrinkActivities = MyDrinkActivity::where('user_id', $auth->id)
            ->where('date', $date)->select('date', 'value')
            ->select('date', 'value', 'created_at')
            ->orderBy('value', 'desc')
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
        $myWeighTrackData = new MyWeightTrackActivity();
        $myWeighTrackData->user_id = $auth->id;
        $myWeighTrackData->my_mission_id = $myMission->id;
        $myWeighTrackData->value = $request->weight;
        $myWeighTrackData->date = date('Y-m-d');
        $myWeighTrackData->save();








        $myMission->current =  $request->weight;


        if ($myMission->current >= $myMission->target) {
            if ($myMission->status != 'finish') { // tambahkan pengecekan ini
                $myMission->status = 'finish';
                $myMission->save();

                // find user and add point
                $user = User::find($auth->id);
                $user->point += $mission->point;
                $user->save();
            }
        }


        $myMission->save();

        $myWeighTrackData = MyWeightTrackActivity::where('user_id', $auth->id)
            ->where('my_mission_id', $myMission->id)
            ->where('date', date('Y-m-d'))
            ->orderBy('id', 'desc')
            ->get();


        return response()->json([
            'message' => 'Success',
            'data' =>  $myWeighTrackData

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
            ->whereBetween('date', [$startDate, $endDate])->select('date', 'value', 'created_at')
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
            if ($myMission->status != 'finish') { // tambahkan pengecekan ini
                $myMission->status = 'finish';
                $myMission->save();

                // find user and add point
                $user = User::find($auth->id);
                $user->point += $mission->point;
                $user->save();
            }
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

    public function storeHealthTrackData(Request $request)
    {
        $auth = auth()->user();

        // validator request
        $validator = Validator::make($request->all(), [
            'bpm' => 'required|numeric',
        ]);


        // check if validator is failed
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $mission = Mission::where('name', 'like', '%Check Kesehatan anda%')->first();

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
        $myHealthTrack = new HealthTrackActivity();
        $myHealthTrack->user_id = $auth->id;
        $myHealthTrack->my_mission_id = $myMission->id;
        $myHealthTrack->value = $request->bpm;
        $myHealthTrack->date = date('Y-m-d');
        $myHealthTrack->save();

        $myHealthTrack =
        HealthTrackActivity::where('user_id', $auth->id)
            ->where('date', Date("Y-m-d"))
            ->select('date', 'value', 'created_at')
            ->orderBy('value', 'desc')
            ->get();








        $myMission->current =  $request->bpm;



        $myMission->status = 'finish';




        $myMission->save();

        // find user and add point
        $user = User::find($auth->id);
        $user->point += $mission->point;
        $user->save();



        return response()->json([
            'message' => 'Success',
            'data' => $myHealthTrack

        ], 200);
    }

    public function getUserHealthTrackData(Request $request)
    {
        $auth = auth()->user();
        $date = $request->input('date', date('Y-m-d'));
        $days = $request->input('days', 7); // default to 7 days if not specified

        $endDate = date('Y-m-d', strtotime($date));
        $startDate = date('Y-m-d', strtotime("-$days day", strtotime($endDate)));

        $healthActivities = HealthTrackActivity::where('user_id', $auth->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('date', 'value' , 'created_at')
            ->orderBy('date', 'desc')
            ->get();

        $healthData = [];
        $totalBpm = 0;

        foreach ($healthActivities as $activity) {
            $bpm = $activity->value;
            $healthData[] = [
                'date' => $activity->date,
                'value' => $bpm
            ];
            $totalBpm += $bpm;
        }

        $count = count($healthData);
        $avgBpm = ($count > 0) ? $totalBpm / $count : 0;

        return response()->json([
            'message' => 'Success',
            'data' => [
                'health_data' => $healthActivities,
                'chart' => $healthData,
                'average_bpm' => $avgBpm

            ],
        ], 200);
    }

    public function storeExerciseTrackData(Request $request)
    {
        $auth = auth()->user();

        $validator = Validator::make($request->all(), [
            'exercises.*.exercise_type_id' => 'required|exists:exercise_types,id',
            'exercises.*.duration' => 'required|integer|min:1'
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $mission = Mission::where('name', 'like', '%Catat Aktivitas Olahraga%')->first();

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

        $exercises = $request->exercises;
        $totalBurnedCalories = 0;

        foreach ($exercises as $exercise) {
            $exercise_id = $exercise['exercise_type_id'];
            $duration = $exercise['duration'];

            $exercise = exerciseType::find($exercise_id);
            $burnedCalories = ($exercise->calories_burned_estimate * $duration) / 60; // convert to calories burned based on duration

            $totalBurnedCalories += $burnedCalories;

            $myExerciseTrack = new sportTrackingActivity();
            $myExerciseTrack->user_id = $auth->id;
            $myExerciseTrack->my_mission_id = $myMission->id;
            $myExerciseTrack->exercise_type_id = $exercise_id;
            $myExerciseTrack->duration = $duration;
            $myExerciseTrack->burn_calories = $burnedCalories;
            $myExerciseTrack->date = date('Y-m-d');
            $myExerciseTrack->save();
        }

        $myMission->current += $totalBurnedCalories;

        // add to mynutrion activityCalori

        $userNutrion = MyNutrion::where('user_id', $auth->id)->where('date', date('Y-m-d'))->first();
        $userNutrion->activityCalories += $totalBurnedCalories;
        // add calorieleft
        $userNutrion->calorieLeft = $userNutrion->calorieLeft =  $totalBurnedCalories;
        $userNutrion->save();
        if ($myMission->current >= $myMission->target) {
            if ($myMission->status != 'finish') { // tambahkan pengecekan ini
                $myMission->status = 'finish';
                $myMission->save();

                // find user and add point
                $user = User::find($auth->id);
                $user->point += $mission->point;
                $user->save();
            }
        }

        return response()->json([
            'message' => 'Success',
        ], 200);
    }

    public function getSportCaloriBurn()
    {
        $auth = auth()->user();
        $today = date('Y-m-d');

        $totalBurnedCalories = sportTrackingActivity::where('user_id', $auth->id)
            ->where('date', $today)
            ->sum('burn_calories');




        return response()->json([
            'message' => 'Success',
            'data' => $totalBurnedCalories
        ], 200);
    }
    public function getUseSportAcivityData(Request $request)
    {
        $auth = auth()->user();
        $date = $request->input('date', date('Y-m-d'));
        $days = $request->input('days', 7); // default to 7 days if not specified

        $endDate = date('Y-m-d', strtotime($date));
        $startDate = date('Y-m-d', strtotime("-$days day", strtotime($endDate)));

        $mySportActivity = sportTrackingActivity::with('exerciseType')->where('user_id', $auth->id)
            ->whereBetween('date', [$startDate, $endDate])->select('*')
            ->get();



        return response()->json([
            'message' => 'Success',
            'data' => $mySportActivity
        ], 200);
    }


    // food activity
    public function storeFoodTrackData(Request $request)
    {
        $auth = auth()->user();

        $validator = Validator::make($request->all(), [

            'foods.*.food_id' => 'required|exists:food,id',
            'foods.*.calorie_intake' => 'required|integer|min:1',
            'foods.*.carbohydrate_intake' => 'required|integer|min:1',
            'foods.*.protein_intake' => 'required|integer|min:1',
            'foods.*.fat_intake' => 'required|integer|min:1',
            'foods.*.size' => 'required|integer|min:1',
            'foods.*.unit' => 'required|string',
        ]);



        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $mission = Mission::where('name', 'like', '%Catat Aktivitas Makanan%')->first();

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


        $foods = $request->foods;
        $totalCaloriesIntake = 0;
        $totalCarboIntake = 0;
        $totalProteinIntake = 0;
        $totalFatIntake = 0;


        foreach ($foods as $food) {
            $food_id = $food['food_id'];
            $calorie_intake = $food['calorie_intake'];
            $carbohydrate_intake = $food['carbohydrate_intake'];
            $protein_intake = $food['protein_intake'];
            $fat_intake = $food['fat_intake'];
            $size = $food['size'];
            $unit = $food['unit'];

            $food = Food::find($food_id);

            $totalCaloriesIntake += $calorie_intake;
            $totalCarboIntake += $carbohydrate_intake;
            $totalProteinIntake += $protein_intake;
            $totalFatIntake += $fat_intake;


            $foodActivityTrack = new FoodActivityTracking();
            $foodActivityTrack->user_id = $auth->id;
            $foodActivityTrack->my_mission_id = $myMission->id;
            $foodActivityTrack->food_id = $food_id;
            $foodActivityTrack->calorie_intake = $calorie_intake;
            $foodActivityTrack->carbohydrate_intake = $carbohydrate_intake;
            $foodActivityTrack->protein_intake = $protein_intake;
            $foodActivityTrack->fat_intake = $fat_intake;
            $foodActivityTrack->size = $size;
            $foodActivityTrack->unit = $unit;
            $foodActivityTrack->meal_type = $request->meal_type;
            $foodActivityTrack->date = date('Y-m-d');
            $foodActivityTrack->save();
        }

        $myMission->current += $totalCaloriesIntake;

        $userNutrion = MyNutrion::where('user_id', $auth->id)->where('date', date('Y-m-d'))->first();

        // STORE total cal intake
        $userNutrion->intakeCalories += $totalCaloriesIntake;
        $userNutrion->fat += $totalCarboIntake;
        $userNutrion->carbohydrate += $totalProteinIntake;
        $userNutrion->protein += $totalFatIntake;

        // update calori left
        $userNutrion->calorieLeft = $userNutrion->targetCalories - $userNutrion->intakeCalories;
        $userNutrion->save();






        if ($myMission->current >= $myMission->target) {
            if ($myMission->status != 'finish') { // tambahkan pengecekan ini
                $myMission->status = 'finish';
                $myMission->save();

                // find user and add point
                $user = User::find($auth->id);
                $user->point += $mission->point;
                $user->save();
            }
        }

        $myMission->save();

        return response()->json([
            'message' => 'Success',
        ], 200);
    }

    public function getUserFoodTrackData(Request $request)
    {
        $date = $request->date; // tanggal yang diinput user

        if ($date == null) {
            $date = date('Y-m-d');
        }
        $auth = auth()->user(); // user yang sedang login

        $foods = DB::table('food_activity_trackings')
            ->select('meal_type', 'food.food_name', 'calorie_intake', 'carbohydrate_intake', 'protein_intake', 'fat_intake', 'size', 'unit')
            ->join('food', 'food_activity_trackings.food_id', '=', 'food.id')
            ->where('food_activity_trackings.user_id', $auth->id)
            ->where('food_activity_trackings.date', $date)
            ->get();

        // get my nutrion
        $myNutrion = MyNutrion::where('user_id', $auth->id)->where('date', $date)->first();

        $carboProteinFatSum = $myNutrion->carbohydrate + $myNutrion->protein + $myNutrion->fat;
        $carboPercent = $proteinPercent = $fatPercent = 0;
        if ($carboProteinFatSum > 0) {
            $carboPercent = ($myNutrion->carbohydrate / $carboProteinFatSum) * 100;
            $proteinPercent = ($myNutrion->protein / $carboProteinFatSum) * 100;
            $fatPercent = ($myNutrion->fat / $carboProteinFatSum) * 100;
        }

        $nutrion = [
            'calorieLeft' => $myNutrion->calorieLeft,
            'carbohydrate' => $myNutrion->carbohydrate,
            'protein' => $myNutrion->protein,
            'fat' => $myNutrion->fat,
            'intakeCalories' => $myNutrion->intakeCalories,
            'targetCalories' => $myNutrion->targetCalories,
            'calorieLeftPercentage' => ($myNutrion->calorieLeft / $myNutrion->targetCalories) * 100,
            'intakeCaloriesPercentage' => ($myNutrion->intakeCalories / $myNutrion->targetCalories) * 100,
            'carbohydratePercentage' => $carboPercent,
            'proteinPercentage' => $proteinPercent,
            'fatPercentage' => $fatPercent,
        ];



        return response()->json([
            'message' => 'Success',
            'data' => [
                'foods' => $foods,
                'nutrion' => $nutrion,

            ],

        ], 200);
    }

    public function vitabot(Request $request)
    {

        // Ambil inputan teks dari request

        $clientRequest = $request->input('text');
        $message =
    "If the user greets me, I will respond by saying 'Hello! I am Vitabot, your personal health assistant. How can I assist you today?' and only mention their greeting.

If the user is talking about food, I will only provide responses about healthy food choices and will not provide information about exercise.

If the user is talking about exercise, I will only provide responses about exercise and will not provide information about food.

I will limit my responses according to the topic being discussed by the user and will not mix up the topics.

For food-related inquiries, I will provide nutritional information and suggestions for healthy food choices.

Example Food:
Name:
Ingredients:
Nutritional Information:
Request Type:


If the user asks about a fitness-related question, I will provide the necessary information regarding the type of exercise that is being asked in the following format:

EXERCISE_NAME:
HOW_TO:
REPS:
SETS:
REQUEST_TYPE:
YOUTUBE_KEYWORD:

For exercise-related Request Type : REQUEST_EXERCISE_INFO
For Definition-related Request Type : REQUEST_DEFINITION
Exercise-related keyword : 'exercise', 'workout', 'fitness', 'lifting', 'running', and related words
Definition-related keyword : 'definition', 'what is', 'describe', 'explain'
For exercise-related use value Request Type : REQUEST_EXERCISE_INFO and for definition-related use value Request Type : REQUEST_DEFINITION

translate to indonesian language (dont translate the format  )
I hope these rules are easy to understand and will help me provide better assistance to users with their health and fitness inquiries.

" . $clientRequest;




        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY')
        ])->post(env('OPENAI_URL'), [
            "model" => "gpt-3.5-turbo",
            'messages' => [
                [
                    "role" => "user",
                    "content" => $message
                ]
            ]
        ]);

        $text = $response->getBody()->getContents();

        $text = json_decode($text, true);

        $message_content =
        $text['choices'][0]['message']['content'];
        $role = $text['choices'][0]['message']['role'];





        return response()->json([
            'message' => 'Success',
            'data' => [
                'message' => $message_content,
                'role' => $role,

            ],
        ], 200);



    }

    public function activateTrialPremium()
    {
        $auth = auth()->user();

        $user = User::find($auth->id);


        if (!$user->is_premium) { // Jika user belum premium
            $user->is_premium = true; // Aktifkan premium
            $user->premium_expires_at = Carbon::now()->addDays(7); // Berikan durasi trial 7 hari
            $user->save(); // Simpan perubahan

            return response()->json(['message' => 'Trial premium activated successfully.'], 200);
        }

        return response()->json(['message' => 'User is already premium.'], 400);
    }

    public function activePremiumBca(Request $request){

        \Midtrans\Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_ISPRODUCTION');
        \Midtrans\Config::$is3ds = (bool) env('IS3DS');


        $auth = auth()->user();

        $user = User::find($auth->id);


        $plan_type = $request->plan_type;

        if($plan_type == 'monthly'){
            $transaction_details = array(
                'order_id'    => time(),
                'gross_amount'  => 30000
            );; // Simpan perubahan

            $items = array(
                array(
                    'id'       => 'vita_premium_monthly',
                    'price'    => 30000,
                    'quantity' => 1,
                    'name'     => 'Vita premium 1 month'
                ),

            );

        }else{
            $transaction_details = array(
                'order_id'    => time(),
                'gross_amount'  => 299999
            );; // Simpan perubahan

            $items = array(
                array(
                    'id'       => 'vita_premium_yearly',
                    'price'    => 299999,
                    'quantity' => 1,
                    'name'     => 'Vita premium 1 year'
                ),

            );
            // Simpan perubahan
        }


        $name = explode(' ', $user['name']);
        if (count($name) > 1) {
            $customer_details = array(
                'first_name'       => $name[0],
                'last_name'        => $name[1],
                'email'            => $user['email'],
                'phone'            => "08214124",

            );
        } else {
            $customer_details = array(
                'first_name'       => $name[0],
                'email'            => $user['email'],
                'phone'            => "08124124",

            );
        }

        $transaction = array(
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $items,
            'payment_type' => 'bank_transfer',
            'bank_transfer' => array(
                'bank' => 'bca',

            )
        );




        $response = \Midtrans\CoreApi::charge($transaction);
        $expire = Carbon::now('Asia/Jakarta')->addDays(1)->timestamp;
        $dateString = Carbon::now('Asia/Jakarta')->addDays(1);
        ModelsPayment::create([
            'user_id' => $user['id'],
            'midtrans_order_id' => $response->order_id,
            'amount' => $response->gross_amount,
            'payment_url' => $response->transaction_id,
            'expire_time_unix' => $expire,
            'expire_time_str' => $dateString,
            'payment_status' => 2,
            'snap_url' => '-',
            'service_name' => "bca",
        ]);




        return response()->json([
            'message' => 'Success',
            'data' => [
                'gross_amount' => $response->gross_amount,
                'transaction_id' => $response->transaction_id,
                'payment_type' => $response->payment_type,
                'bank' => $response->va_numbers[0]->bank,
                'va_number' => $response->va_numbers[0]->va_number,
                'expire_time_unix' => $expire,
                'expire_time_str' => $dateString,
                'service_name' => "bca",


            ]
        ], 200);

    }

    public function webHookHandler(Request $request)
    {
        $data = $request->all();

        $signatureKey = $data['signature_key'];
        $orderId = $data['order_id'];
        $statusCode = $data['status_code'];
        $grossAmount = $data['gross_amount'];
        $serverKey = env('MIDTRANS_SERVER_KEY');


        $mySignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $transactioStatus = $data['transaction_status'];
        $type = $data['payment_type'];
        $fraudStatus = $data['fraud_status'];
        if ($signatureKey !== $mySignatureKey) {
            return  response()->json([
                'message' => 'Invalid signature',
                'data' => $data
            ], 400);
        };
        $payment = ModelsPayment::where('midtrans_order_id', $orderId)->first();

        if (!$payment) {
            return  response()->json([
                'message' => 'Payment not found',
                'data' => $data
            ], 400);
        }


        if ($payment->payment_status === 1) {
            return response()->json([
                'message' => 'Payment already processed',
                'data' => $data
            ], 400);
        }

        if ($transactioStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                // TODO set transaction status on your database to 'challenge'
                $payment->payment_status = 3;
                $payment->payment_status_str = 'challenge';

                // and response with 200 OK
            } else if ($fraudStatus == 'accept') {
                // TODO set transaction status on your database to 'success'
                // and response with 200 OK
                $payment->payment_status = 1;
                $payment->payment_status_str = 'settlement';
            }
        } else if ($transactioStatus == 'settlement') {
            // TODO set transaction status on your database to 'success'

            $buyers = User::where('id', $payment->user_id)->first();

            if($grossAmount == 30000) {
                $buyers->update([
                    'is_premium' => 1,
                    'expired_premium' => Carbon::now()->addDays(30)
                ]);
            }else{
                $buyers->update([
                    'is_premium' => 1,
                    'premium_expires_at' => Carbon::now()->addDays(365)
                ]);
            }

            $payment->payment_status = 1;
            $payment->payment_status_str = 'settlement';



            // and response with 200 OK
        } else if (
            $transactioStatus == 'cancel' ||
            $transactioStatus == 'deny' ||
            $transactioStatus == 'expire'
        ) {
            // TODO set transaction status on your database to 'failure'
            $payment->payment_status = 3;
            $payment->payment_status_str = 'cancel';






            // and response with 200 OK
        } else if ($transactioStatus == 'pending') {
            // TODO set transaction status on your database to 'pending' / waiting payment
            $payment->payment_status = 2;
            $payment->payment_status_str = 'pending';

            // foreach ($order as $o) {
            //     $value = $order['amount'] + $order['shipping_amount'];
            //     $email = $user

            //     foreach ($o['orderItem'] as $item) {

            //     }
            // };



            // and response with 200 OK
        }

        $payment->save();




    }

    // validasi pembayaran

    public function paymentValidation( Request $request) {

       $payment = ModelsPayment::where('payment_url', $request->transaction_id)->first();


        return response()->json([
            'message' => 'Success',
            'data' =>  [
                "gross_amount" => $payment->amount,
                "transaction_id" => $payment->payment_url,
                "payment_type" => $payment->payment_type,
                "bank" => $payment->service_name,
                "va_number" => $payment->payment_url,
                "expire_time_unix" => $payment->expire_time_unix,
                "expire_time_str" => $payment->expire_time_str,
                "service_name" => $payment->service_name,
                "payment_status" => $payment->payment_status,

            ]
        ], 200);




    }


}
