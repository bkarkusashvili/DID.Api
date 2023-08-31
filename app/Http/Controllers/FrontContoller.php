<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Favorite;
use App\Models\Site;
use App\Models\Social;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FrontContoller extends Controller
{
    public function getAll()
    {
        return response()->json(auth()->user()->sites()->with('template.categories')->get());
    }

    public function getAllSite()
    {
        return response()->json(auth()->user()->sites()->with('template', 'category')->get());
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

    public function getTemplate()
    {
        $list = Category::whereNull('category_id')->with('children.templates')->get();

        return $list;
    }

    public function favorite(Request $request)
    {
        $request->validate([
            'category' => 'required|int|exists:categories,id',
            'template' => 'required|int|exists:templates,id',
        ]);

        $userId = auth()->user()->id;

        $favorite = Favorite::where('user_id', $userId)
            ->where('category_id', $request->input('category'))
            ->where('template_id', $request->input('template'))
            ->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            Favorite::create([
                'user_id' => $userId,
                'category_id' => $request->input('category'),
                'template_id' => $request->input('template'),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function createSite(Request $request)
    {
        $data = $request->validate([
            'template' => 'required|int|exists:templates,id',
            'category' => 'required|int|exists:categories,id'
        ]);

        return Site::create([
            'user_id' => auth()->user()->id,
            'template_id' => $data['template'],
            'category_id' => $data['category'],
            'status' => 'draft',
        ]);
    }

    public function getSite(Site $site)
    {
        if ($site->user_id !== auth()->user()->id) {
            abort(403);
        }

        return $site;
    }

    public function updateSite(Request $request, Site $site)
    {
        $data = $request->validate([
            'data.keywords' => 'nullable|string',
            'data.websiteType' => 'nullable|int|min:1|max:3',
            'data.logo' => 'nullable|image',
            'data.socialMedia' => 'nullable|string',
            'data.businessImages' => 'nullable|image',
            'data.brand' => 'nullable|string',
        ]);
    
        if ($request->hasFile('data.logo')) {
            if ($site->data && isset($site->data['logo']) && $site->data['logo']) {
                Storage::disk('public')->delete($site->data['logo']);
            }
    
            $data['logo'] = Storage::disk('public')->put('site/logo', $data['data.logo']);
        }
    
        if ($request->hasFile('data.businessImages')) {
            if ($site->data && isset($site->data['businessImages']) && $site->data['businessImages']) {
                Storage::disk('public')->delete($site->data['businessImages']);
            }
    
            $data['businessImages'] = Storage::disk('public')->put('site/businessImages', $data['data.businessImages']);
        }
    
        $site->update([
            'data' => array_merge($site->data ?? [], $data),
            'subscription_product_id' => $request->input('subscription_product_id'),
            'status' => 'pending',
        ]);
    
        return $site;
    }
    
    public function updateSiteStatus(Request $request, Site $site)
    {
        // Check if the current status is 'pending' before updating
        if ($site->status === 'pending') {
            // Change the status to 'active'
            $site->update([
                'status' => 'active',
            ]);
        }
    
        return $site;
    }
    
    public function deleteSite(Site $site)
    {
        if ($site->user_id !== auth()->user()->id) {
            abort(403);
        }

        if ($site->data && isset($site->data['logo']) && $site->data['logo']) {
            Storage::disk('public')->delete($site->data['logo']);
        }

        if ($site->data && isset($site->data['businessImages']) && $site->data['businessImages']) {
            Storage::disk('public')->delete($site->data['businessImages']);
        }

        return $site->delete();
    }
}
