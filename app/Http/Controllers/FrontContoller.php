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
        $socials = auth()->user()->socials;

        return response()->json($socials);
    }

    public function getSocial(Request $request, int $id)
    {
        return Social::findOrFail($id);
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

        if (isset($data['photos'])) {
            $data['photos'] = collect($data['photos'])->map(function (UploadedFile $file) {
                return Storage::disk('public')->put('social/photos', $file);
            });
        }

        if (isset($data['materials'])) {
            $data['materials'] = collect($data['materials'])->map(function (UploadedFile $file) {
                return Storage::disk('public')->put('social/materials', $file);
            });
        }

        $userId = auth()->user()->id;
        $data['user_id'] = $userId;

        $id = $request->query('id');

        $social = '';

        if ($id) {
            $social = Social::findOrFail($id);
            if ($social->user_id !== $userId) {
                abort(403);
            }

            $social->update($data);
        } else {
            $social = Social::create($data);
        }

        return response($social, 201);
    }

    public function deleteItem(int $id)
    {
        $social = Social::findOrFail($id);

        if ($social->user_id !== auth()->user()->id) {
            abort(403);
        }

        return $social->delete();
    }

    public function generateText(Request $request)
    {
        $request->validate([
            'keywords' => 'required|array',
            'keywords.*' => 'required|string',
        ]);

        $response = Http::contentType('application/json')
            ->withHeaders(['Authorization' => 'Bearer ' . env('OPENAI_KEY')])
            ->post('https://api.openai.com/v1/completions', [
                "model" => "text-davinci-003",
                "prompt" => implode(' ', $request->input('keywords')),
                "temperature" => 0.8,
                "max_tokens" => 2000,
                "top_p" => 1,
                "frequency_penalty" => 0,
                "presence_penalty" => 0.6,
                "stop" => [" Human:", " AI:"],
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
            ->withHeaders(['Authorization' => env('PEXELS_KEY')])
            ->get('https://api.pexels.com/v1/search', [
                "query" => implode(' ', $request->input('keywords')),
            ]);

        return response(json_decode($response->body())->photos);
    }
}
