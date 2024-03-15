<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function all(Request $request)
    {

        return response()->json([
            'message' => 'Cars Returned',
            'data' => Car::all(),
        ]);
    }

    public function find(int $id)
    {

        return response()->json([
            'message' => 'Cars Returned',
            'data' => Car::find($id),
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'model' => 'required|max:40|string',
            'year' => 'required|integer|max:2024',
            'mileage' => 'integer',
            'cute' => 'boolean',
        ]);

        $car = new Car();
        $car->model = $request->model;
        $car->year = $request->year;
        $car->mileage = $request->mileage;
        $car->cute = $request->cute;

        $car->save();

        if (! $car->save()) {
            return response()->json(['message' => 'Car not Created'], 500);
        }

        return response()->json(['message' => 'Car Created'], 201);
    }

    public function update(int $id, Request $request)
    {
        $car = Car::find($id);
        if (! $car) {
            return response()->json(['message' => 'Invalid ID']);
        }

        $request->validate([
            'model' => 'required|max:40|string',
            'year' => 'required|integer|max:2024',
            'mileage' => 'integer',
            'cute' => 'boolean',
        ]);

        $car->model = $request->model;
        $car->year = $request->year;
        $car->mileage = $request->mileage;
        $car->cute = $request->cute;

        $car->save();

        if (! $car->save()) {
            return response()->json(['message' => 'Car not updated'], 500);
        }

        return response()->json(['message' => 'Car Updated']);
    }

    public function delete(int $id)
    {
        $car = Car::find($id);
        if (! $car) {
            return response()->json(['message' => 'Invalid ID']);
        }

        $car->delete();

        return response()->json(['message' => 'Car Deleted']);
    }
}
