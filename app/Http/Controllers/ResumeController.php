<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use App\Models\Ticket;


class ResumeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $today = Carbon::today();

        $cigars = DB::table('tickets')
            ->join('products', 'tickets.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(amount_of_cigars) as total_cigars')
            )
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
            ])
            ->groupBy('product_id')
            ->orderBy('total_cigars', 'desc')
            ->limit(3)
            ->get();

        $employees_production = DB::table('tickets_employees')
            ->join('tickets', 'tickets_employees.ticket_id', '=', 'tickets.id')
            ->join('employees', 'tickets_employees.employee_id', '=', 'employees.id')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->selectRaw('full_name, sum(amount_of_cigars) as total_cigars')
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
                ['positions.name', 'like', 'ROLER%']
            ])
            ->groupBy('employee_id')
            ->orderBy('total_cigars', 'desc')
            ->limit(3)
            ->get();

        $total_production = DB::table('tickets')
            ->select(DB::raw('SUM(amount_of_cigars) as total_production'))
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
            ])
            ->first();

        $total_botados = DB::table('tickets')
            ->select(DB::raw('SUM(botados) total_botados'))
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
            ])
            ->first();

        $botados_employees = DB::table('tickets_employees')
            ->select(DB::raw('full_name, positions.name as position, SUM(botados) AS total_botados'))
            ->join('tickets', 'tickets_employees.ticket_id', '=', 'tickets.id')
            ->join('employees', 'tickets_employees.employee_id', '=',  'employees.id')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->where('positions.name', 'like', 'ROLER%')
            ->groupBy('employees.id')
            ->orderBy('total_botados', 'desc')
            ->limit(3)
            ->get();

        $total_defects = DB::table('tickets')
            ->selectRaw(
                'SUM(trancados) total_trancados,
                SUM(pelados) total_pelados,
                SUM(botados) total_botados'
            )
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
            ])
            ->first();

        // dd($total_defects);

        return Inertia::render('Resume/Home', [
            'cigars' => $cigars,
            'employees_production' => $employees_production,
            'total_production' => $total_production,
            'total_botados' => $total_botados,
            'botados_employees' => $botados_employees,
            'total_defects' => $total_defects,

        ]);
    }

    public function CigarProduction()
    {
        $today = Carbon::today();

        $total_production = DB::table('tickets')
            ->select(DB::raw('SUM(amount_of_cigars) as total_production'))
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
            ])
            ->first();

        $cigars = DB::table('tickets')
            ->join('products', 'tickets.product_id', '=', 'products.id')
            ->join('vitolas', 'vitolas.id', '=', 'products.vitola_id')
            ->select(
                'products.name as product',
                'vitolas.name as vitola',
                DB::raw('SUM(amount_of_cigars) as total_cigars')
            )
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
            ])
            ->groupBy('product_id')
            ->orderBy('total_cigars', 'desc')
            ->get();

        return Inertia::render('Resume/CigarProduction', [
            'cigars' => $cigars,
            'total_production' => $total_production,
            'filters' => Request::all('search', 'trashed')
        ]);
    }

    public function ShowCigarProduction(HttpRequest $request)
    {
        $today = Carbon::today();
        $status = "";

        if ($request->fecha == $today->format('Y-m-d')) {
            $status = "en bodega";
        } else {
            $status = "finalizado";
        }

        $cigars = DB::table('tickets')
            ->join('products', 'tickets.product_id', '=', 'products.id')
            ->join('vitolas', 'vitolas.id', '=', 'products.vitola_id')
            ->select(
                'products.name as product',
                'vitolas.name as vitola',
                DB::raw('SUM(amount_of_cigars) as total_cigars')
            )
            ->where([
                ['tickets.created_at', 'like',  $request->fecha . '%'],
                ['status', $status],
            ])
            ->groupBy('product_id')
            ->orderBy('total_cigars', 'desc')
            ->get();


        $total_production = DB::table('tickets')
            ->select(DB::raw('SUM(amount_of_cigars) as total_production'))
            ->where([
                ['tickets.created_at', 'like',  $request->fecha . '%'],
                ['status', $status],
            ])
            ->first();

        return redirect()->back()->with(['aditionalData' => $cigars, 'aditionalData2' => $total_production, 'message' => 'Actualizada']);
    }
    //Botados///////////
    public function GetBotados()
    {
        $today = Carbon::today();

        $total_botados = DB::table('tickets')
            ->select(DB::raw('SUM(botados) total_botados'))
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
            ])
            ->first();

        $botados_employees = DB::table('tickets_employees')
            ->select(DB::raw('full_name, positions.name as position, employee_code ,SUM(botados) AS total_botados'))
            ->join('tickets', 'tickets_employees.ticket_id', '=', 'tickets.id')
            ->join('employees', 'tickets_employees.employee_id', '=',  'employees.id')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['positions.name', 'like', 'ROLER%'],
            ])
            ->groupBy('employees.id')
            ->orderBy('total_botados', 'desc')
            //->limit(3)
            ->get();

        return Inertia::render('Resume/ThrownIndex', [
            'total_botados' => $total_botados,
            'botados_employees' => $botados_employees,
            'filters' => Request::all('search', 'trashed')
        ]);
    }

    public function ShowCigarBotados(HttpRequest $request)
    {
        $today = Carbon::today();
        $status = "";

        if ($request->fecha == $today->format('Y-m-d')) {
            $status = "en bodega";
        } else {
            $status = "finalizado";
        }

        $total_botados = DB::table('tickets')
            ->select(DB::raw('SUM(botados) total_botados'))
            ->where([
                ['tickets.created_at', 'like',  $request->fecha . '%'],
                ['status', $status],
            ])
            ->first();

        $botados_employees = DB::table('tickets_employees')
            ->select(DB::raw('full_name, positions.name as position, employee_code, SUM(botados) AS total_botados'))
            ->join('tickets', 'tickets_employees.ticket_id', '=', 'tickets.id')
            ->join('employees', 'tickets_employees.employee_id', '=',  'employees.id')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->where([
                ['tickets.created_at', 'like',  $request->fecha . '%'],
                ['positions.name', 'like', 'ROLER%'],
                ['status', $status],
            ])
            ->groupBy('employees.id')
            ->orderBy('total_botados', 'desc')
            //->limit(3)
            ->get();


        return redirect()->back()->with(['aditionalData' => $botados_employees, 'aditionalData2' => $total_botados, 'message' => 'Actualizada']);
    }

    ////Produccion por empleados

    public function GetProductionByEmployee()
    {
        $today = Carbon::today();

        $employees_production = DB::table('tickets_employees')
            ->join('tickets', 'tickets_employees.ticket_id', '=', 'tickets.id')
            ->join('employees', 'tickets_employees.employee_id', '=', 'employees.id')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->selectRaw('full_name, employee_code,  sum(amount_of_cigars) as total_cigars')
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
                ['positions.name', 'like', 'ROLER%']
            ])
            ->groupBy('employee_id')
            ->orderBy('total_cigars', 'desc')
            ->get();

        $total_production = DB::table('tickets')
            ->select(DB::raw('SUM(amount_of_cigars) as total_production'))
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
            ])
            ->first();

        return Inertia::render('Resume/ProductionByEmployeeIndex', [
            'employees_production' => $employees_production,
            'total_production' => $total_production,
            'filters' => Request::all('search', 'trashed')
        ]);
    }

    public function ShowProductionByEmployee(HttpRequest $request)
    {
        $today = Carbon::today();
        $status = "";

        if ($request->fecha == $today->format('Y-m-d')) {
            $status = "en bodega";
        } else {
            $status = "finalizado";
        }

        $employees_production = DB::table('tickets_employees')
            ->join('tickets', 'tickets_employees.ticket_id', '=', 'tickets.id')
            ->join('employees', 'tickets_employees.employee_id', '=', 'employees.id')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->selectRaw('full_name, employee_code, sum(amount_of_cigars) as total_cigars')
            ->where([
                ['tickets.created_at', 'like',  $request->fecha . '%'],
                ['status', $status],
                ['positions.name', 'like', 'ROLER%']
            ])
            ->groupBy('employee_id')
            ->orderBy('total_cigars', 'desc')
            ->get();

        $total_production = DB::table('tickets')
            ->select(DB::raw('SUM(amount_of_cigars) as total_production'))
            ->where([
                ['tickets.created_at', 'like',  $request->fecha . '%'],
                ['status', $status],
            ])
            ->first();

        return redirect()->back()->with(['aditionalData' => $employees_production, 'aditionalData2' => $total_production, 'message' => 'Actualizada']);
    }
    ////defectos

    public function GetDefects()
    {
        $today = Carbon::today();

        $total_defects = DB::table('tickets')
            ->selectRaw(
                'SUM(trancados) total_trancados,
         SUM(pelados) total_pelados,
         SUM(botados) total_botados'
            )
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
            ])
            ->get();


        return Inertia::render('Resume/DefectsIndex', [
            'total_defects' => $total_defects,
            'filters' => Request::all('search', 'trashed')
        ]);
    }

    public function ShowDefects(HttpRequest $request)
    {
        $today = Carbon::today();
        $status = "";

        if ($request->fecha == $today->format('Y-m-d')) {
            $status = "en bodega";
        } else {
            $status = "finalizado";
        }

        $total_defects = DB::table('tickets')
            ->selectRaw(
                'SUM(trancados) total_trancados,
                SUM(pelados) total_pelados,
                SUM(botados) total_botados'
            )
            ->where([
                ['tickets.created_at', 'like',  $request->fecha . '%'],
                ['status', $status],
            ])
            ->get();


        return redirect()->back()->with(['aditionalData' => $total_defects, 'message' => 'Actualizada']);
    }

    ////Cierre diario

    public function CierreDiario()
    {
        $today = Carbon::today();

        $en_bodega = DB::table('tickets_employees')
            ->select(DB::raw('full_name, positions.name as position, tickets.amount_of_cigars, vitolas.name'))
            ->join('tickets', 'tickets_employees.ticket_id', '=', 'tickets.id')
            ->join('products', 'tickets.product_id', '=', 'products.id')
            ->join('vitolas', 'products.vitola_id', '=', 'vitolas.id')
            ->join('employees', 'tickets_employees.employee_id', '=',  'employees.id')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->where([
                ['positions.name', 'like', 'ROLER%'],
                ['status', 'en bodega'],
                ['tickets.created_at', 'like',   $today->format('Y-m-d')  . '%'],
            ])
            //->groupBy('employees.id')
            //->orderBy('total_botados', 'desc')
            //->limit(3)
            ->get();

        $total_creados = DB::table('tickets')
            ->select(DB::raw('COUNT(id) AS total_creados'))
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
            ])
            ->first();

        $pesado = DB::table('tickets')
            ->select(DB::raw('COUNT(id) AS pesados'))
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'pesado'],
            ])
            ->first();

        $solo_creados = DB::table('tickets')
            ->select(DB::raw('COUNT(id) AS solo_creados'))
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'creado'],
            ])
            ->first();

        $cancelados = DB::table('tickets')
            ->select(DB::raw('COUNT(id) AS cancelados'))
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'cancelado'],
            ])
            ->first();

        $bodega = DB::table('tickets')
            ->select(DB::raw('COUNT(id) AS bodega'))
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
            ])
            ->first();

        $total_production = DB::table('tickets')
            ->select(DB::raw('SUM(amount_of_cigars) as total_production'))
            ->where([
                ['tickets.created_at', 'like',  $today->format('Y-m-d') . '%'],
                ['status', 'en bodega'],
            ])
            ->first();


        return Inertia::render('Resume/DailyCloseIndex', [
            'daily_production' => [
                'producidos' => $en_bodega,
                'total_creados' => $total_creados->total_creados,
                'pesado' => $pesado->pesados, 'solo_creados' => $solo_creados->solo_creados,
                'cancelados' => $cancelados->cancelados,
                'en_bodega' => $bodega->bodega,
                'total_produccion' => $total_production->total_production
            ],

            'filters' => Request::all('search', 'trashed')
        ]);
    }

    public function ShowCierreDiario(HttpRequest $request)
    {

        $en_bodega = DB::table('tickets_employees')
            ->select(DB::raw('full_name, positions.name as position, tickets.amount_of_cigars, vitolas.name'))
            ->join('tickets', 'tickets_employees.ticket_id', '=', 'tickets.id')
            ->join('products', 'tickets.product_id', '=', 'products.id')
            ->join('vitolas', 'products.vitola_id', '=', 'vitolas.id')
            ->join('employees', 'tickets_employees.employee_id', '=',  'employees.id')
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            ->where([
                ['positions.name', 'like', 'ROLER%'],
                ['status', 'finalizado'],
                ['tickets.created_at', 'like',   $request->fecha  . '%'],
            ])
            //->groupBy('employees.id')
            //->orderBy('total_botados', 'desc')
            //->limit(3)
            ->get();

        $total_creados = DB::table('tickets')
            ->select(DB::raw('COUNT(id) AS total_creados'))
            ->where([
                ['tickets.created_at', 'like',  $request->fecha . '%'],
            ])
            ->first();

        $pesado = DB::table('tickets')
            ->select(DB::raw('COUNT(id) AS pesados'))
            ->where([
                ['tickets.created_at', 'like',  $request->fecha . '%'],
                ['status', 'pesado'],
            ])
            ->first();

        $solo_creados = DB::table('tickets')
            ->select(DB::raw('COUNT(id) AS solo_creados'))
            ->where([
                ['tickets.created_at', 'like', $request->fecha . '%'],
                ['status', 'creado'],
            ])
            ->first();

        $cancelados = DB::table('tickets')
            ->select(DB::raw('COUNT(id) AS cancelados'))
            ->where([
                ['tickets.created_at', 'like',  $request->fecha . '%'],
                ['status', 'cancelado'],
            ])
            ->first();

        $bodega = DB::table('tickets')
            ->select(DB::raw('COUNT(id) AS bodega'))
            ->where([
                ['tickets.created_at', 'like',  $request->fecha . '%'],
                ['status', 'en bodega'],
            ])
            ->first();

        $total_production = DB::table('tickets')
            ->select(DB::raw('SUM(amount_of_cigars) as total_production'))
            ->where([
                ['tickets.created_at', 'like', $request->fecha . '%'],
                ['status', 'en bodega'],
            ])
            ->first();

        $produccion_dia = Ticket::where('created_at', 'like', $request . '%')->get();

        print_r($produccion_dia);


        return redirect()->back()->with(['aditionalData' => [
            'producidos' => $en_bodega,
            'total_creados' => $total_creados->total_creados,
            'pesado' => $pesado->pesados, 'solo_creados' => $solo_creados->solo_creados,
            'cancelados' => $cancelados->cancelados,
            'en_bodega' => $bodega->bodega,
            'total_produccion' => $total_production->total_production
        ], 'message' => 'Actualizado']);
    }

    public function MakeCierreDiario(HttpRequest $request)
    {

        $produccion_dia = Ticket::where('created_at', 'like', '%' . $request->fecha . '%')->get();


        foreach ($produccion_dia as $produccion) {
            if ($produccion->status == 'en bodega') {
                $produccion->status = 'finalizado';
                $produccion->update();
            } else {
                $produccion->status = 'cancelado';
                $produccion->update();
            }
        }

        return redirect()->back()->with('message', 'Cierre exitoso');
        // $ticket->update($request->validated());
        // foreach($produccion_dia as produ){

        // }
    }
}
