<?php

namespace App\Controllers\Api\Nexen;

use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\Api\V1\ApiController;
use CodeIgniter\RESTful\ResourceController;

class Blogs extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Dashboard';
        $this->title = 'Blogs';
    }

    public function blogs()
    {
        return $this->sendResponse('Blog categories are generated successfully.', [
            'categories' => $this->categories()
        ]);
    }

    public function categories()
    {
        $categories = $this->blog
            ->select([
                'name', 'slug', 'description', 'image', 'blog_id'
            ])
            ->where(['status' => 'Approved'])->orderBy('id', 'asc')->findAll();
        if ($categories) {
            $data = [];
            foreach ($categories as $category) {
                if ($category['blog_id'] === null) {
                    $data['categories'][] = $category;
                } else {
                    $data['subcategories'][] = $category;
                }
            }
            return $data;
        } else {
            return [];
        }
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $posts = $this->post
            ->select([
                'posts.id', 'posts.title', 'posts.slug', 'posts.image', 'posts.intro', 'posts.content',
                'posts.status', 'posts.views', 'posts.created_at', 'posts.updated_at', 'posts.blog_id',
                'blogs.name as category', 'blogs.slug as categorySlug', 'blogs.status as categoryStatus',
                'users.name', 'users.photo'
            ])
            ->join('blogs', 'blogs.id = posts.blog_id', 'left')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->where([
                'posts.status' => 'Approved',
                'posts.deleted_at' => null
            ])
            ->orderBy('posts.id', 'desc')
            ->findAll();

        return $this->sendResponse('Blog posts are generated successfully.', [
            'categories' => $this->categories(),
            'posts' => $posts
        ]);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($slug = null)
    {
        $post = $this->post
            ->select([
                'posts.id', 'posts.title', 'posts.slug', 'posts.image', 'posts.intro', 'posts.content',
                'posts.status', 'posts.views', 'posts.created_at', 'posts.updated_at', 'posts.blog_id',
                'blogs.name as category', 'blogs.slug as categorySlug', 'blogs.status as categoryStatus',
                'users.name', 'users.photo'
            ])
            ->join('blogs', 'blogs.id = posts.blog_id', 'left')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->where([
                'posts.slug' => $slug,
                'posts.status' => 'Approved',
                'posts.deleted_at' => null
            ])
            ->orderBy('posts.id', 'desc')
            ->find();
        # Check the blog existence by blog post slug
        if ($post) {
            # post exist
            return $this->sendResponse('Single blog post are generated successfully.', [
                'post' => $post,
                'categories' => $this->categories()
            ]);
        } else {
            # no blog post exist
            return $this->sendError('The requested ' . $this->title . ' resource could not be found!', [
                'errorCode' => 'NotFound',
            ]);
        }
    }

    public function category($slug = null)
    {
        $post = $this->post
            ->select([
                'posts.id', 'posts.title', 'posts.slug', 'posts.image', 'posts.intro', 'posts.content',
                'posts.status', 'posts.views', 'posts.created_at', 'posts.updated_at', 'posts.blog_id',
                'blogs.name as category', 'blogs.slug as categorySlug', 'blogs.status as categoryStatus',
                'users.name', 'users.photo'
            ])
            ->join('blogs', 'blogs.id = posts.blog_id', 'left')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->where([
                'blogs.slug' => $slug,
                'blogs.status' => 'Approved',
                'blogs.deleted_at' => null
            ])
            ->orderBy('posts.id', 'desc')
            ->findAll();
        # Check the blog existence by blog post slug
        if ($post) {
            # post exist
            return $this->sendResponse('Blog posts by category are generated successfully.', [
                'categories' => $this->categories(),
                'post' => $post
            ]);
        } else {
            # no blog post exist
            return $this->sendError('The requested ' . $this->title . ' resource could not be found!', [
                'errorCode' => 'NotFound',
            ]);
        }
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        $rules = [
            'search' => 'required|min_length[2]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return $this->sendError('Validation Error.', $this->validator->getErrors(), 200);
        }

        $search = $this->request->getVar('search');

        $posts = $this->post
            ->select([
                'posts.id', 'posts.title', 'posts.slug', 'posts.image', 'posts.intro', 'posts.content',
                'posts.status', 'posts.views', 'posts.created_at', 'posts.updated_at', 'posts.blog_id',
                'blogs.name as category', 'blogs.slug as categorySlug', 'blogs.status as categoryStatus',
                'users.name', 'users.photo'
            ])
            ->join('blogs', 'blogs.id = posts.blog_id', 'left')
            ->join('users', 'users.id = posts.user_id', 'left')
            // ->where([
            //     'posts.status' => 'Approved',
            // ])
            ->like(
                'posts.title',
                $search
            )
            ->orLike(
                'posts.status',
                $search
            )
            ->orLike(
                'posts.intro',
                $search
            )
            ->orLike(
                'posts.content',
                $search
            )
            ->orLike(
                'posts.slug',
                $search
            )
            ->orLike(
                'blogs.name',
                $search,
                'both'
            )
            ->orLike(
                'blogs.status',
                $search
            )
            ->orLike(
                'blogs.slug',
                $search,
                'both'
            )
            ->orLike(
                'users.name',
                $search,
                'both'
            )
            ->orderBy('posts.id', 'desc')
            ->findAll();

        return $this->sendResponse('Blog posts by search are generated successfully.', [
            'categories' => $this->categories(),
            'posts' => $posts
        ]);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        //
    }
}
