<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Thematique;
use App\Http\Requests\Api\ThematiqueCreateRequest;
use App\Http\Requests\Api\ThematiqueUpdateRequest;
use App\Http\Requests\Api\ThematiqueDeleteRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\Api\ThematiqueUploadRequest;
use Illuminate\Support\Str;

class ThematiqueController extends Controller
{
    public function index()
    {
        return QueryBuilder::for(Thematique::class)
            ->allowedFilters([
                'name',
            ])
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show($slugOrId)
    {
        $thematique = (is_numeric($slugOrId))
            ? Thematique::where('id', $slugOrId)->firstOrFail()
            : Thematique::where('slug', $slugOrId)->firstOrFail();

        return $thematique;
    }

    public function store(ThematiqueCreateRequest $request)
    {
        $thematique = Thematique::create($request->validated());

        return $thematique;
    }

    public function update(ThematiqueUpdateRequest $request, Thematique $thematique)
    {
        $thematique->update($request->validated());

        return $thematique;
    }

    public function upload(ThematiqueUploadRequest $request, Thematique $thematique)
    {

        // Delete previous file
        if ($media = $thematique->getFirstMedia('thematiques')) {
            $media->delete();
        }

        $data = $request->all();
        $extension = $request->file('image')->guessExtension();
        $name = Str::random(30);

        $cropSettings = json_decode($data['cropSettings']);
        if (!empty($cropSettings)) {
            $stringCropSettings = implode(",", [
                $cropSettings->width,
                $cropSettings->height,
                $cropSettings->x,
                $cropSettings->y
            ]);
        } else {
            $pathName = $request->file('image')->getPathname();
            $infos = getimagesize($pathName);
            $stringCropSettings = implode(",", [
                $infos[0],
                $infos[1],
                0,
                0
            ]);
        }

        $thematique
            ->addMedia($request->file('image'))
            ->usingName($name)
            ->usingFileName($name . '.' . $extension)
            ->withManipulations([
                'large' => ['manualCrop' => $stringCropSettings],
                'thumb' => ['manualCrop' => $stringCropSettings]
            ])
            ->toMediaCollection('thematiques');

        return $thematique;
    }

    public function uploadDelete(ThematiqueDeleteRequest $request, Thematique $thematique)
    {
        if ($media = $thematique->getFirstMedia('thematiques')) {
            $media->delete();
        }
    }

    public function delete(ThematiqueDeleteRequest $request, Thematique $thematique)
    {
        return (string) $thematique->delete();
    }
}