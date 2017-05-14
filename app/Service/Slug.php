<?php

namespace App\Service;

class Slug {
    /**
     * @type \Illuminate\Database\Eloquent\Model
     */
    private $model;


    /**
     * @param void
     */
    public function __construct ($model) {
        $this->model = $model;
    }

    /**
     * Instantiate Slug class
     * 
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return static
     */
    public static function for ($model) {
        return new static($model);
    }

    /**
     * @param string $valueToSlug
     * @return string
     */
    public function generate($valueToSlug)
    {
        $newSlug = str_slug($valueToSlug);

        if($latestSlug = $this->selectLatestSlugs($newSlug)->first())
            $newSlug = $this->transform($latestSlug, $newSlug);
        
        return $newSlug;
    }

    /**
     * @param string $slug
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function selectLatestSlugs ($slug) {
        return $this->model->where('slug', 'rlike', "^{$slug}(-[0-9]*)?$")
            ->where('id', '<>', $this->model->id)
            ->latest('id')
            ->pluck('slug');
    }

    /**
     * @param string $latestSlug
     * @param string $newSlug
     * @return string   
     */
    private function transform ($latestSlug, $newSlug) {
        return $newSlug . '-' . ($this->parse($latestSlug) + 1); 
    }

    /**
     * @param string $slug
     * @return int 
     */
    private function parse ($slug) {
        $sections = explode('-', $slug);

        $count = end($sections);
        
        return intval($count);
    }
}