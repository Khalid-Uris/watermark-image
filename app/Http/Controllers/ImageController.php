<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use ProtoneMedia\LaravelFFMpeg\Filters\WatermarkFactory;

class ImageController extends Controller
{

    public function ImageCreate() {
        return view('create');
    }

    // public function ImageStore(Request $request) {
    //     // return "hello";
    //     // return $request;

    //     if ($request->hasFile('image')) {
    //         $image=$request->file('image');
    //         $file_name=time().'.'.$image->getClientOriginalExtension();
    //         $image_resize=Image::make($image->getRealPath());
    //         $image_resize->resize(400,400);

    //         // create a new Image instance for inserting
    //         $watermark = Image::make(public_path('watermark.png'))->opacity(50);
    //         $image_resize->insert($watermark, 'center');
    //         $image_resize->save('photo/'.$file_name);
    //     }
    //     return back()->with('success','done');
    // }

    public function ImageStore(Request $request) {
        // return "hello";
        // return $request;

        if ($request->hasFile('image')) {
            $image=$request->file('image');
            $file_name=time().'.'.$image->getClientOriginalExtension();
            $image_resize=Image::make($image->getRealPath());
            $image_resize->resize(400,400);

            if (!is_null($request->watermark)) {
                 // create a new Image instance for inserting
            // $watermark = Image::make($request->watermark)->opacity(50);
            $watermark = Image::make($request->watermark);
            $watermark->resize(100,100);
            $image_resize->insert($watermark, 'bottom-right', 10, 10);
            }

            $image_resize->save('photo/'.$file_name);
        }
        return back()->with('success','done');
    }


    public function index()
    {
        return view('imageUpload');
    }

    public function store(Request $request)
    {
        //  return 1;
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('image')) {
            $image = Image::make($request->file('image'));
            $image->insert(public_path('images/1698622197-2p.png'), 'bottom-right', 5, 5);

            /**
             * Main Image Upload on Folder Code
             */
            $imageName = time().'-'.$request->file('image')->getClientOriginalName();
            $destinationPath = public_path('images/');
            $image->save($destinationPath.$imageName);


           // $img = Image::make(public_path('images/main.png'));
            /* insert watermark at bottom-right corner with 10px offset */
            //$img->insert(public_path('images/logo.png'), 'bottom-right', 10, 10);
           // $img->save(public_path('images/main-new.png'));

            /**
             * Generate Thumbnail Image Upload on Folder Code
             */
            // $destinationPathThumbnail = public_path('images/thumbnail/');
            // $image->resize(100,100);
            // $image->save($destinationPathThumbnail.$imageName);

            /**
             * Write Code for Image Upload Here,
             *
             * $upload = new Images();
             * $upload->file = $imageName;
             * $upload->save();
            */

            return back()
                ->with('success','Image Upload successful')
                ->with('imageName',$imageName);
        }

        return back();
    }
    public function watermakeVideo()  {

    $videoPath = public_path('videos/testing.mp4');
    $imagePath = public_path('image/thumbnail/1698622197-2p.png');


    FFMpeg::fromDisk('local')
    ->open($videoPath)
    ->addWatermark(function(WatermarkFactory $watermark) use ($imagePath) {
        $watermark->fromDisk('local')
            ->open($imagePath)
            ->right(25)
            ->bottom(25);
    });

}

}
