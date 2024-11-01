<?php

namespace App\Controllers\Nexen\Blog;

use Hermawan\DataTables\DataTable;
use App\Controllers\MasterController;
use CodeIgniter\HTTP\ResponseInterface;

class Blogs extends MasterController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Dashboard';
        $this->title = 'Blogs';
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        return view('nexen/blogs/index', [
            'title' => $this->title,
            'menu' => $this->menu,
            'settings' => $this->settingsRow,
            'user' => $this->userRow,
            'userMenu' => $this->load_menu(),
        ]);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $post = $this->post
            ->select([
                'posts.*', 'blogs.name as category', 'users.name'
            ])
            ->join('blogs', 'blogs.id = posts.blog_id', 'left')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->find($id);
        if ($post) {
            return $this->respond(($post));
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requested ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
        }
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        $posts = $this->post
            ->select([
                'posts.image', 'posts.title', 'posts.intro', 'posts.status', 'posts.created_at',
                'posts.id', 'posts.blog_id', 'blogs.name as category',
                'users.name'
            ])
            ->join('blogs', 'blogs.id = posts.blog_id', 'left')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->where(['posts.deleted_at' => null]);

        return DataTable::of($posts)
            ->add('checkbox', function ($post) {
                return '
                <div class="text-center"><input type="checkbox" class="data-check text-center" value="' . $post->id . '">
                </div>
                ';
            })
            ->addNumbering('no') # it will return data output with numbering on first column
            ->add('image', function ($post) {
                # check whether the image exist
                if (file_exists("uploads/posts/" . $post->image) && $post->image) {
                    # display the current image
                    $image = '
                    <a href="javascript:void(0)" onclick="view_post_image(' . "'" . $post->id . "'" . ')"><img src="' . base_url('uploads/posts/' . $post->image) . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                } else {
                    # display the default image
                    $image = '
                    <a href="javascript:void(0)" onclick="view_post_image(' . "'" . $post->id . "'" . ')"><img src="' . base_url('assets/dist/img/noimage.jpg') . '" style="width:40px;height:40px;" class="img-circle" /></a>
                    ';
                }

                return '<div class="products">
                ' . $image . '
                    <div>
                        <h6>' . strtoupper($post->title) . '</h6>
                        <span>' . $post->category . '</span>	
                    </div>	
                </div>';
            })
            ->add('action', function ($post) {
                switch (strtolower($post->status)) {
                    case 'approved':
                        $text = 'success';
                        break;
                    case 'pending':
                        $text = 'primary';
                        break;
                    case 'reviewed':
                        $text = 'secondary';
                        break;
                    default:
                        $text = 'danger';
                        break;
                }

                $actions = '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">';
                if (($this->userPermissions == 'all') || (in_array('view' . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {
                    $actions .= '
                    <a href="javascript:void(0)" class="dropdown-item" onclick="viewBlogPost(' . "'" . $post->id . "'" . ')"><i class="fas fa-eye text-success"></i> View Post</a>
                    ';
                }
                if (($this->userPermissions == 'all') || (in_array('update' . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {
                    $actions .= '<div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" class="dropdown-item" onclick="editBlogPost(' . "'" . $post->id . "'" . ')"><i class="fas fa-edit text-info"></i> Edit Post</a>
                    ';
                }
                if (($this->userPermissions == 'all') || (in_array('delete' . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {
                    $actions .= '<div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="deleteBlogPost(' . "'" . $post->id . "'" . ', ' . "'" . $post->title . "'" . ')"><i class="fas fa-trash text-danger"></i> Delete Post
                        </a>';
                }
                $actions .= ' 
                </div>
                </div>';
                return $actions;
            })
            ->toJson(true);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $operation = strtolower($this->request->getVar('operation'));
        $this->_validateBlogPost($operation);
        $title = $this->request->getVar('title');
        $data = [
            'title' => trim($title),
            'slug' => trim(url_title(strtolower($title), '-', true)),
            'intro' => trim($this->request->getVar('intro')),
            'content' => trim($this->request->getVar('content')),
            'blog_id' => ($this->request->getVar('blog_id')) ? $this->request->getVar('blog_id') : null,
            'status' => trim($this->request->getVar('status')),
            'user_id' => $this->userRow['id'],
            'account_id' => $this->userRow['account_id'],
        ];
        # Check the operation method
        if ($operation == "create") {
            # check whether the image has been uploaded
            if (!empty($_FILES['image']['name'])) {
                $fileName = $this->doUploadAttachment("image", "posts");
                $data['image'] = $fileName;
            }
            $insert = $this->post->insert($data);
        } else {
            # Update the blog post
            $post_id = $this->request->getVar('id');
            # check whether the image has been uploaded
            if (!empty($_FILES['image']['name'])) {
                $fileName = $this->doUploadAttachment("image", "posts");
                # get user information
                $post = $this->post->find($post_id);
                if (file_exists("uploads/posts/" . $post['image']) && $post['image']) {
                    # delete or remove the previous image
                    unlink("uploads/posts/" . $post['image']);
                }
                # save the new image
                $data['image'] = $fileName;
            }
            # update user information
            $save = $this->post->update($post_id, $data);
            $insert = $post_id;
        }

        $this->saveUserActivity([
            'user_id' => $this->userRow['id'],
            'account_id' => $this->userRow['account_id'],
            'action' => $operation,
            'description' => ('Blog Post: ' . $data['title']),
            'module' => $this->menu,
            'referrer_id' => $insert,
            'title' => $this->title,
        ]);
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
        if (($this->userPermissions == 'all') || (in_array('delete' . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {
            $data = $this->post->find($id);
            if ($data) {
                $delete = $this->post->delete($id);
                if ($delete) {
                    $this->saveUserActivity([
                        'user_id' => $this->userRow['id'],
                        'account_id' => $this->userRow['account_id'],
                        'action' => 'delete',
                        'description' => ('Blog Post: ' . $data['title']),
                        'module' => $this->menu,
                        'referrer_id' => $id,
                        'title' => $this->title,
                    ]);
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Delete Failed',
                        'messages' => 'Deleting ' . $this->title . ' record failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'The requested ' . $this->title . ' resource could not be found!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . ' record!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    public function bulkDelete()
    {
        if (($this->userPermissions == 'all') || (in_array('bulkDelete' . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {
            $list_id = $this->request->getVar('id');
            foreach ($list_id as $id) {
                $data = $this->post->find($id);
                if ($data) {
                    $delete = $this->post->delete($id);
                    if ($delete) {
                        # Activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'account_id' => $this->userRow['account_id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', ' . $data['title']),
                            'module' => $this->menu,
                            'referrer_id' => $id,
                        ];
                        $activity = $this->insertActivityLog($activityData);
                    } else {
                        $response = [
                            'status' => 500,
                            'error' => 'Delete Failed',
                            'messages' => 'Deleting ' . $this->title . ' record(s) failed, try again later!',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'The requested ' . $this->title . ' resource could not be found!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            }
            if ($activity) {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' Deleted Successfully',
                ];
                return $this->respond($response);
                exit;
            } else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' Deleted Successfully. loggingFailed'
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . ' records!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    private function _validateBlogPost($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $post = $this->post->find($this->request->getVar('id'));

        if ($this->request->getVar('blog_id') == '') {
            $data['inputerror'][] = 'blog_id';
            $data['error_string'][] = 'Category is required';
            $data['status'] = FALSE;
        }

        if ($this->request->getVar('title') == '') {
            $data['inputerror'][] = 'title';
            $data['error_string'][] = 'Title name is required';
            $data['status'] = FALSE;
        }

        if (!empty($this->request->getVar('title'))) {
            $name = $this->request->getVar('title');
            if ($this->settings->validateName($name) == TRUE) {
                if (strlen(trim($name)) < 5) {
                    $data['inputerror'][] = 'title';
                    $data['error_string'][] = 'Minimum 5 letters required [' . strlen($name) . ']';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($this->request->getVar('title')) == FALSE) {
                $data['inputerror'][] = 'title';
                $data['error_string'][] = 'Valid Title is required';
                $data['status'] = FALSE;
            }
            if ($method == "create") {
                # check category name existence
                $counter = $this->post
                    ->where(['title' => $this->request->getVar('title')])->countAllResults();
                if ($counter) {
                    $data['inputerror'][] = 'title';
                    $data['error_string'][] = $this->request->getVar('title') . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if (
                $method == "update" &&
                $post['title'] != $this->request->getVar('title')
            ) {
                # check branch name existence
                $post = $this->post
                    ->where(['title' => $this->request->getVar('title')])->countAllResults();
                if ($post) {
                    $data['inputerror'][] = 'title';
                    $data['error_string'][] = $this->request->getVar('title') . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }

        if ($this->request->getVar('title') == '') {
            $data['inputerror'][] = 'title';
            $data['error_string'][] = 'Title name is required';
            $data['status'] = FALSE;
        }

        if (!empty($this->request->getVar('description'))) {
            if ($this->settings->validateAddress($this->request->getVar('description')) == TRUE) {
                if (strlen(trim($this->request->getVar('description'))) < 4) {
                    $data['inputerror'][] = 'description';
                    $data['error_string'][] = 'Description is too short';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateAddress($this->request->getVar('description')) == FALSE) {
                $data['inputerror'][] = 'description';
                $data['error_string'][] = 'Valid Description is required';
                $data['status'] = FALSE;
            }
        }

        if ($this->request->getVar('content') == '') {
            $data['inputerror'][] = 'content';
            $data['error_string'][] = 'Content is required';
            $data['status'] = FALSE;
        }

        if ($this->request->getVar('intro') == '') {
            $data['inputerror'][] = 'intro';
            $data['error_string'][] = 'Intro is required';
            $data['status'] = FALSE;
        }

        if ($this->request->getVar('status') == '') {
            $data['inputerror'][] = 'status';
            $data['error_string'][] = 'Status is required';
            $data['status'] = FALSE;
        }

        if (empty($_FILES['image']['name']) && $method == "create") {
            # Please browse for the file to be uploaded
            $data['inputerror'][] = 'image';
            $data['error_string'][] = 'Upload Error: Image is required!';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
