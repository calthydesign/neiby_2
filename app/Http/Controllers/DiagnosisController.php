<?php
//app/Http/Controllers/DiagnosisController.php
namespace App\Http\Controllers;

use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; //この2行を追加！
use Illuminate\Support\Facades\Auth;      //この2行を追加！
use App\Models\User;

class DiagnosisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $user = Auth::user();
    
    $latestDiagnosis = Diagnosis::where('user_id', $user->id)
                                ->latest('id')
                                ->first();

    $diagnosisData = $latestDiagnosis ? [
        'date' => $latestDiagnosis->created_at,
        'kikyo_count' => $latestDiagnosis->kikyo_count,
        'kekyo_count' => $latestDiagnosis->kekyo_count,
        'kitai_count' => $latestDiagnosis->kitai_count,
        'oketsu_count' => $latestDiagnosis->oketsu_count,
        'suitai_count' => $latestDiagnosis->suitai_count,
    ] : null;

    return view('diagnoses', compact('diagnosisData'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    \Log::info('Received request data:', $request->all());

    try {
        $data = $request->validate([
            'counts' => 'required|array',
            'counts.kekkyo' => 'required|integer',
            'counts.kikyo' => 'required|integer',
            'counts.kitai' => 'required|integer',
            'counts.oketsu' => 'required|integer',
            'counts.suitai' => 'required|integer',
            'result' => 'required|string'
        ]);

        $user = Auth::user();
        $user->construction = $data['result'];
        $user->save();

        $diagnosis = new Diagnosis();
        $diagnosis->user_id = $user->id;
        $diagnosis->result = $data['result'];
        $diagnosis->kikyo_count = $data['counts']['kikyo'];
        $diagnosis->kekkyo_count = $data['counts']['kekkyo'];
        $diagnosis->kitai_count = $data['counts']['kitai'];
        $diagnosis->oketsu_count = $data['counts']['oketsu'];
        $diagnosis->suitai_count = $data['counts']['suitai'];
        $diagnosis->save();

        \Log::info('Diagnosis saved successfully:', $diagnosis->toArray());

        return response()->json(['success' => true, 'message' => '診断結果が保存されました。']);
    } catch (\Exception $e) {
        \Log::error('Error saving diagnosis:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Diagnosis $diagnosis)
    {
        //
    }

    
    
    public function showDiagnosis()
    {
        //グラフのデータ取得（現在ログインしているユーザーの最新の診断データを取得）
        $user = Auth::user();
        
        $latestDiagnosis = Diagnosis::where('user_id', $user->id)
                                    ->latest('id')
                                    ->first();
    
        $diagnosisData = [
            'kikyo_count' => $latestDiagnosis->kikyo_count ?? null,
            'kekyo_count' => $latestDiagnosis->kekyo_count ?? null,
            'kitai_count' => $latestDiagnosis->kitai_count ?? null,
            'oketsu_count' => $latestDiagnosis->oketsu_count ?? null,
            'suitai_count' => $latestDiagnosis->suitai_count ?? null,
        ];
    
        return view('diagnosis.show', compact('diagnosisData'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diagnosis $diagnosis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diagnosis $diagnosis)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diagnosis $diagnosis)
    {
        //
    }
    
}

