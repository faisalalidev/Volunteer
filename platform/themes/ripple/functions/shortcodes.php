<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Shortcode\Compilers\Shortcode;
use Botble\Theme\Supports\ThemeSupport;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

app()->booted(function () {
    ThemeSupport::registerGoogleMapsShortcode();
    ThemeSupport::registerYoutubeShortcode();

    if (is_plugin_active('blog')) {
        add_shortcode('featured-posts', __('Featured posts'), __('Featured posts'), function () {
            return Theme::partial('shortcodes.featured-posts');
        });

        shortcode()->setAdminConfig('featured-posts', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.featured-posts-admin-config', compact('attributes', 'content'));
        });

        add_shortcode('recent-posts', __('Recent posts'), __('Recent posts'), function (Shortcode $shortcode) {
            return Theme::partial('shortcodes.recent-posts', ['title' => $shortcode->title]);
        });

        shortcode()->setAdminConfig('recent-posts', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.recent-posts-admin-config', compact('attributes', 'content'));
        });

        add_shortcode(
            'featured-categories-posts',
            __('Featured categories posts'),
            __('Featured categories posts'),
            function (Shortcode $shortcode) {
                $with = [
                    'slugable',
                    'posts' => function (BelongsToMany $query) {
                        $query
                            ->where('status', BaseStatusEnum::PUBLISHED)
                            ->orderBy('created_at', 'DESC');
                    },
                    'posts.slugable',
                ];

                if (is_plugin_active('language-advanced')) {
                    $with[] = 'posts.translations';
                }

                $posts = collect();

                if ($shortcode->category_id) {
                    $with['posts'] = function (BelongsToMany $query) {
                        $query
                            ->where('status', BaseStatusEnum::PUBLISHED)
                            ->orderBy('created_at', 'DESC')
                            ->take(6);
                    };

                    $category = app(CategoryInterface::class)
                        ->getModel()
                        ->with($with)
                        ->where([
                            'status' => BaseStatusEnum::PUBLISHED,
                            'id' => $shortcode->category_id,
                        ])
                        ->select([
                            'id',
                            'name',
                            'description',
                            'icon',
                        ])
                        ->first();

                    if ($category) {
                        $posts = $category->posts;
                    } else {
                        $posts = collect();
                    }
                } else {
                    $categories = get_featured_categories(2, $with);

                    foreach ($categories as $category) {
                        $posts = $posts->merge($category->posts->take(3));
                    }

                    $posts = $posts->sortByDesc('created_at');
                }

                return Theme::partial(
                    'shortcodes.featured-categories-posts',
                    ['title' => $shortcode->title, 'posts' => $posts]
                );
            }
        );

        shortcode()->setAdminConfig('featured-categories-posts', function (array $attributes) {
            $categories = app(CategoryInterface::class)->pluck('name', 'id', ['status' => BaseStatusEnum::PUBLISHED]);

            return Theme::partial(
                'shortcodes.featured-categories-posts-admin-config',
                compact('attributes', 'categories')
            );
        });
    }

    if (is_plugin_active('gallery')) {
        add_shortcode('all-galleries', __('All Galleries'), __('All Galleries'), function (Shortcode $shortcode) {
            return Theme::partial('shortcodes.all-galleries', ['limit' => $shortcode->limit]);
        });

        shortcode()->setAdminConfig('all-galleries', function (array $attributes, ?string $content) {
            return Theme::partial('shortcodes.all-galleries-admin-config', compact('attributes', 'content'));
        });
    }
});
