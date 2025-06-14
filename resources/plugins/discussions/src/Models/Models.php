<?php

namespace Wave\Plugins\Discussions\Models;

class Models
{
    /**
     * Map for the models.
     *
     * @var array
     */
    protected static $models = [];

    /**
     * Set the model to be used for categories.
     *
     * @param string $model
     *
     * @return void
     */
    public static function setCategoryModel($model)
    {
        static::$models[Category::class] = $model;
    }

    /**
     * Set the model to be used for discussions.
     *
     * @param string $model
     *
     * @return void
     */
    public static function setDiscussionModel($model)
    {
        static::$models[Discussion::class] = $model;
    }

    /**
     * Set the model to be used for posts.
     *
     * @param string $model
     *
     * @return void
     */
    public static function setPostModel($model)
    {
        static::$models[Post::class] = $model;
    }

    /**
     * Get an instance of the category model.
     *
     * @param array $attributes
     *
     * @return \Wave\Plugins\Discussions\Models\Category
     */
    public static function category(array $attributes = [])
    {
        return self::makeModel(Category::class, $attributes);
    }

    /**
     * Get an instance of the discussion model.
     *
     * @param array $attributes
     *
     * @return \Wave\Plugins\Discussions\Models\Discussion
     */
    public static function discussion(array $attributes = [])
    {
        return self::makeModel(Discussion::class, $attributes);
    }

    /**
     * Get an instance of the post model.
     *
     * @param array $attributes
     *
     * @return \Wave\Plugins\Discussions\Models\Post
     */
    public static function post(array $attributes = [])
    {
        return self::makeModel(Post::class, $attributes);
    }

    /**
     * Get an instance of the given model.
     *
     * @param string $model
     * @param array  $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected static function makeModel($model, $attributes)
    {
        $model = self::className($model);

        return new $model($attributes);
    }

    /**
     * Get the correct class model.
     *
     * @param string $model
     *
     * @return string
     */
    public static function className($model)
    {
        if (isset(static::$models[$model])) {
            $model = static::$models[$model];
        }

        return $model;
    }
}
