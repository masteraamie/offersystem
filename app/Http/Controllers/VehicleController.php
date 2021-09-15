<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleController extends Controller
{

    public function home()
    {
        return view('vehicles');
    }

    public function index()
    {
        return response(Vehicle::all()->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vehicle =  new Vehicle();
        $vehicle->model = $request->model;
        $vehicle->price = $request->price;
        $vehicle->save();

        return response($vehicle->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::find($id);
        $vehicle->model = $request->model;
        $vehicle->price = $request->price;
        $vehicle->save();

        return response($vehicle->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Vehicle::destroy($id);
        return response(null, Response::HTTP_OK);
    }
}
