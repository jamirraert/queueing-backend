<?php

namespace App\Http\Controllers;

use App\Models\Teller;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PDO;
use Ramsey\Uuid\Type\Integer;

class TellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $tellers = DB::transaction(fn () => Teller::with(['user','counter', 'service'])->get());

            return $this->jsonResponse([
                'message' => 'Successfully fetching data.',
                'data' => $tellers->toArray(),
            ], 200);

        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            return $this->jsonResponse();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $today = Carbon::now();
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::unique('tellers', 'user_id')->where(function ($query) use ($today) {
                    $query->whereDate('create_at', $today);
                })
            ],
            'counter_id' => [
                'required',
                'integer',
                Rule::unique('tellers', 'counter_id')->where(function ($query) use ($today) {
                    $query->whereDate('created_at', $today);
                }),
            ],
            'service_id' => [
                'required',
                'integer',
                Rule::unique('tellers', 'service_id')->where(function ($query) use ($today) {
                    $query->whereDate('create_at', $today);
                })
            ]
        ]);

        try {

            $teller = DB::transaction(fn () => Teller::create($validated));

            return $this->jsonResponse([
                'message' => 'Successfully created teller.',
                'data' => $teller->toArray()
            ], 201);

        } catch (Exception $e) {
            $this->setErrorMessage($e->getMessage());
            return $this->jsonResponse();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $teller = DB::transaction(function() use ($id) {
                $teller = Teller::where('id', $id)
                    ->whereDate('created_at' , Carbon::today())
                    ->with(['user', 'counter', 'service'])->first();

                if(!$teller) {
                    throw new ModelNotFoundException('Teller not found. ', 404);
                }

                return $teller;
            });

            return $this->jsonResponse([
                'message' => 'Successfull fetching data.',
                'data' => $teller->toArray()
            ]);

        } catch (ModelNotFoundException $e) {
            $this->setErrorMessage(
                $e->getMessage(),
                $e->getCode()
            );
            return $this->jsonResponse();
        } catch (Exception $e) {
            $this->setErrorMessage(
                $e->getMessage(),
                $e->getCode()
            );
            return $this->jsonResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'user_id' => ['nullable','integer','exists:users,id'],
            'counter_id' => ['nullable','integer','exists:counters,id'],
            'service_id' => ['nullable', 'integer', 'exists:services,id']
        ]);

        try {

            $teller = DB::transaction(function() use ($id, $validated) {
                $teller = Teller::where('id', $id)
                    ->whereDate('created_at', Carbon::today())
                    ->first();

                if(!$teller || !$id) {
                    throw new \Exception('Teller not found.', 404);
                }

                $teller->update($validated);
                $teller->save();

                return $teller;
            });

            return $this->jsonResponse([
                'message' => 'Successfully updated teller.',
                'data' => $teller->toArray()
            ], 200);

        } catch (Exception $e) {
            $this->setErrorMessage(
                $e->getMessage(),
                $e->getCode()
            );
            return $this->jsonResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $teller = DB::transaction(function () use ($id) {
                $teller = Teller::find($id);

                if(!$teller) {
                    throw new \Exception('Teller not found.', 404);
                }

                return $teller;
            });

            return $this->jsonResponse([
                'message' => 'Successfully deleted teller.',
                'data'=> $teller->toArray()
            ]);
        } catch (\Exception $e) {
            $this->setErrorMessage(
                $e->getMessage(),
                $e->getCode()
            );
            return $this->jsonResponse();
        }
    }
}
