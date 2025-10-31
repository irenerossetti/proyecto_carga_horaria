<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicPeriodController extends Controller
{
    protected function ensureAdmin()
    {
        $user = auth()->user();
        if (!$user || ! (
            ($user->hasRole('ADMIN') || $user->hasRole('administrador'))
            || (property_exists($user, 'is_admin') ? $user->is_admin : ($user->is_admin ?? false))
        )) {
            abort(response()->json(['message' => 'Forbidden'], 403));
        }
    }

    public function index()
    {
        $this->ensureAdmin();
        return response()->json(AcademicPeriod::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $period = AcademicPeriod::create(array_merge($data, ['status' => 'draft']));

        return response()->json($period, 201);
    }

    public function activate($id)
    {
        $this->ensureAdmin();

        return DB::transaction(function () use ($id) {
            // close any currently active period
            AcademicPeriod::where('status', 'active')->update(['status' => 'closed']);

            $period = AcademicPeriod::findOrFail($id);
            $period->status = 'active';
            $period->save();

            return response()->json(['message' => 'Period activated', 'period' => $period]);
        });
    }

    public function close($id)
    {
        $this->ensureAdmin();

        $period = AcademicPeriod::findOrFail($id);
        $period->status = 'closed';
        $period->save();

        return response()->json(['message' => 'Period closed', 'period' => $period]);
    }

    public function update(Request $request, $id)
    {
        $this->ensureAdmin();

        $period = AcademicPeriod::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:draft,active,closed',
        ]);

        // validate date order if both provided
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
                return response()->json(['message' => 'start_date must be before or equal to end_date'], 422);
            }
        }

        // If status is set to active, reuse activation logic to close others
        if (isset($data['status']) && $data['status'] === 'active') {
            return DB::transaction(function () use ($period, $data) {
                AcademicPeriod::where('status', 'active')->update(['status' => 'closed']);

                $period->fill($data);
                $period->status = 'active';
                $period->save();

                return response()->json(['message' => 'Period updated and activated', 'period' => $period]);
            });
        }

        $period->fill($data);
        $period->save();

        return response()->json(['message' => 'Period updated', 'period' => $period]);
    }

    public function destroy($id)
    {
        $this->ensureAdmin();

        $period = AcademicPeriod::findOrFail($id);
        $period->delete();

        return response()->json(['message' => 'Period deleted', 'id' => $id]);
    }
}
