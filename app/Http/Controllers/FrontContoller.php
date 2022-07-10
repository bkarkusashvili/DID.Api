<?php

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FrontContoller extends Controller
{
    public function getAll()
    {
        return response()->json([]);
    }

    public function createOrEdit(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'type' => 'nullable|integer',
            'text' => 'nullable|string',
            'photoText' => 'nullable|string',
            'comment' => 'nullable|string',
            'suggestedPhoto' => 'nullable|string',

            'keywords' => 'nullable|array',
            'keywords.*' => 'nullable|string',

            'photos' => 'nullable|array',
            'photos.*' => 'nullable|image',

            'materials' => 'nullable|array',
            'materials.*' => 'nullable|image',
        ]);

        $id = $request->query('id');

        $social = '';

        $photos = $data['photos'];
        $materials = $data['materials'];

        if ($data['photos']) {
            $photos = array();
            collect($data['photos'])->each(function (UploadedFile $file) use ($photos) {
                array_push($photos, Storage::disk('public')->put('social/photos', $file));
            });
            dd($photos);
        }

        if ($id) {
            $social = Social::findOrFail($id);
            if ($social->user_id !== auth()->user()->id) {
                abort(403);
            }

            $social->update($data);
        } else {
            $social = Social::create($data);
        }

        return response($social, 201);
    }

    public function generateText(Request $request)
    {
        $request->validate([
            'keywords' => 'required|array',
            'keywords.*' => 'required|string',
        ]);

        $response = Http::contentType('application/json')
            ->withHeaders(['Authorization' => 'Bearer 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'])
            ->post('https://api.openai.com/v1/completions', [
                "model" => "text-ada-001",
                "prompt" => implode(' ', $request->input('keywords')),
                "temperature" => 0,
                "max_tokens" => 300,
                "top_p" => 1,
                "frequency_penalty" => 0.5,
                "presence_penalty" => 0
            ]);


        $text = json_decode($response->body())->choices[0]->text;

        $text = preg_replace("/^(?:\\n)+/", '', $text);

        return response($text);
    }

    public function generateImage(Request $request)
    {
        $request->validate([
            'keywords' => 'required|array',
            'keywords.*' => 'required|string',
        ]);

        $response = Http::contentType('application/json')
            ->withHeaders(['Authorization' => '563492ad6f9170000100000162d1c6dc404b4ec993b4b74fc2a264e2'])
            ->get('https://api.pexels.com/v1/search', [
                "query" => implode(' ', $request->input('keywords')),
            ]);

        return response(json_decode($response->body())->photos);
    }
}
