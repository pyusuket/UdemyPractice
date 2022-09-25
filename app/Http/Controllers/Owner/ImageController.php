<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;
use App\Models\Owner;
use Illuminate\Support\Facades\Storage;


class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {
                $id = $request->route()->parameter('image');
                if(!is_null($id)){
                    $ImageOwnerId = Image::findOrFail($id)->owner->id;
                    $ImageId = (int)$imagesOwnerId;
                    if($imageId !== Auth::id()){
                    abort(404);
                }
                }
            return $next($request);
        });
    }

    public function index()
    {
        $images = Image::where('owner_id', Auth::id())->orderBy('updated_at','desc')->paginate(20);

        return view('owner.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        $imageFiles = $request->file('files'); //配列でファイルを取得
            if(!is_null($imageFiles)){
            foreach($imageFiles as $imageFile){ // それぞれ処理
                Storage::putFile('public/shops', $imageFile);
                Image::create([
                'owner_id' => Auth::id(),
                'filename' => $imageFile
            ]);
            }
            }
        return redirect()->route('owner.images.index')->with(['message' , '画像登録を更新しました。','status' => 'info']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
