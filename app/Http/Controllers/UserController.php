<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function survey(Request $request)
    {

        // Check auth
        $auth = auth()->user();

        if(!$auth) {
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

        // $user->programs()->sync($programs);
        $user->save();

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


}
