<?php

namespace App\Models\Seo;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $fillable = [
        'locale',
        'meta_title',
        'meta_description'
    ];

    public function metable()
    {
        return $this->morphTo();
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'meta_keyword');
    }

    public static function store(Request $request, $model)
    {
        foreach ($request->input('metas', []) as $locale => $metaData) {

            $meta = $model->metas()->updateOrCreate(
                ['locale' => $locale],
                [
                    'meta_title' => $metaData['meta_title'] ?? null,
                    'meta_description' => $metaData['meta_description'] ?? null
                ]
            );


            $keywordIds = [];
            foreach ($metaData['meta_keywords'] as $word) {
                $word = trim($word);
                if ($word) {
                    $keyword = Keyword::firstOrCreate(['keyword' => $word]);
                    $keywordIds[] = $keyword->id;
                }
            }

            $meta->keywords()->sync($keywordIds);
        }
    }
}
