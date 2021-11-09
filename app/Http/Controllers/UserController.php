<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UpdatePasswordNotification;
use App\Jobs\ProcessSendNewPasswordEmail;
use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Request as Request2;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{

    public function index()
    {
        return Inertia::render('Users/UserIndex',
            [
                'filters' => Request2::all('search', 'trashed'),
                'users' => User::filter(Request2::only('search', 'trashed'))
                    ->with('permissions')
                    ->paginate(5)
                    ->appends(Request2::all()),
                'permissions' => Permission::all('id', 'name'),
            ]);

    }

    public function create()
    {
        return Inertia::render('Users/CreateUser', compact('user'));
    }

    public function store(UserStoreRequest $request)
    {
        $request->validated();

        $longitud = 8;
        $password = substr(md5(rand()), 0, $longitud);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($password),
            'temporal_password' => $password,
            'status' => $request['status'],
        ]);

        //tarea que se ejecuta en segundo plano -- revisar la documentacion para usarlo en produccion;
        $this->dispatch(new ProcessSendNewPasswordEmail($user));

        return back()->with(["message" => "Usuario Creado con Éxito"]);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $request->validated();

        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->status;

        try {
            $user->save();

            return back()->with(["message" => "Usuario Actualizado con Éxito"]);

        } catch (\Illuminate\Database\QueryException$ex) {
            return back()->with(["message" => "Hubo un problema"]);
        }

    }

    public function destroy(User $user)
    {
        $user->delete();
    }

    public function assignPermissions(Request $request, User $user)
    {

        $request->validate([
            'permissions' => 'required',
        ]);

        $user->syncPermissions($request->permissions);

        return back()->with(["message" => "¡Permisos asignados con éxito!"]);
    }

}
