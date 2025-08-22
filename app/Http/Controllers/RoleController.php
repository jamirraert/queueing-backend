<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * @return {Lists of Roles}
     */
    public function index()
    {
       try {
        $roles = Role::all();
        return $this->jsonResponse([
            'message' => 'Roles fetched successfully',
            'data' => $roles->toArray()
        ], 200);
       } catch (Exception $e) {
        return $this->jsonResponse([
            'success' => false,
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
       }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        try {
            $role = DB::transaction(fn () => Role::create([
                'name' => $validated['name']
            ]));

            return $this->jsonResponse([
                'message' => 'Successfully created role.',
                'data' => $role->toArray()
            ], 201);

        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong.' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $role = DB::transaction(function() use ($id) {
                $role = Role::findOrFail($id);

                if(!$role) {
                   throw new \Exception('Role not found.', 404);
                }

                return $role;
            });

            return $this->jsonResponse([
                'message' => 'Successfully fetch role',
                'data' => $role->toArray()
            ], 200);
        } catch (Exception $e) {
            $status = $e->getCode() === 404 ? 404 : 500;
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong.' . $e->getMessage()
            ], $status);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string'
        ]);

        try {
            $role = DB::transaction(function() use ($validated, $id) {
                $role = Role::find($id);

                if(!$role) {
                   throw new \Exception('Role not found', 404);
                }

                $role->update($validated);
                $role->save();

                return $role;
            });
            
            return $this->jsonResponse([
                'message' => 'successfully updated role.',
                'data' => $role->toArray()
            ], 200);
        } catch (Exception $e) {
            $status = $e->getCode() === 404 ? 404 : 500;
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong' . $e->getMessage()
            ], $status);
        }
    }

    public function destroy($id)
    {
        try {
            $role = DB::transaction(function () use ($id) {
                $role = Role::find($id);

                if(!$role) {
                    throw new \Exception('Role not found', 404);
                }

                $role->delete();
            });

            return $this->jsonResponse([
                'message' => 'Successfully deleted role.',
                'data' => $role->toArray()
            ], 200);
        } catch (Exception $e) {
            $status = $e->getCode() === 404 ? 404 : 500;
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong., ' . $e->getMessage()
            ], $status);
        }
    }
}
