<?php

// ConstructionController.php

   namespace App\Http\Controllers;

   use App\Models\Construction;
   use Illuminate\Http\Request;

   class ConstructionController extends Controller
   {
       
    public function show($id)
{
    $userConstruction = auth()->user()->construction; // ログインユーザーのconstruction値を取得

    switch ($userConstruction) {
        case '気虚':
            $id = 1;
            break;
        case '気滞':
            $id = 2;
            break;
        case '血虚':
            $id = 3;
            break;
        case '瘀血':
            $id = 4;
            break;
        case '水滞':
            $id = 5;
            break;
        default:
            $id = null;
            break;
    }

    if ($id !== null) {
        $construction = Construction::find($id);

        if ($construction) {
            dd($construction);
            return view('kiketsusui.construction', compact('construction'));
        } else {
            return response()->view('errors.construction_not_found', [], 404); // constructionが見つからない場合のエラー表示
        }
    } else {
        return response()->view('errors.invalid_construction', [], 400); // 不正なconstruction値の場合のエラー表示
    }
}
}
