<?php

namespace App\Controllers\Nexen\Blog;

use Hermawan\DataTables\DataTable;
use App\Controllers\MasterController;
use CodeIgniter\HTTP\ResponseInterface;

class Categories extends MasterController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Dashboard';
        $this->title = 'Blog Categories';
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        return view('nexen/blogs/categories', [
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
        $blog = $this->blog
            ->select([
                'blogs.name', 'blogs.status', 'blogs.description', 'blogs.created_at',
                'blogs.updated_at', 'blogs.id', 'blogs.blog_id',
                'c.name as type'
            ])
            ->join('blogs c', 'c.id = blogs.blog_id', 'left')
            ->find($id);
        $blogOLD = $this->blog->find($id);
        if ($blog) {
            return $this->respond(($blog));
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
        $where = ['blogs.deleted_at' => Null];
        $blogs = $this->blog
            ->select('blogs.name, blogs.status, blogs.description, blogs.created_at, 
            blogs.updated_at, blogs.id, c.name as type')
            ->join('blogs c', 'c.id = blogs.blog_id', 'left')
            ->orderBy('blogs.id', 'asc')
            ->where($where);
        return DataTable::of($blogs)
            ->add('checkbox', function ($blog) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $blog->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('action', function ($blog) {
                if (strtolower($blog->status) == 'approved') {
                    $text = "info";
                } else {
                    $text = "danger";
                }
                // show buttons based on user permissions
                $actions = '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">';
                if (($this->userPermissions == 'all') || (in_array('view' . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {
                    $actions .= '<li><a href="javascript:void(0)" onclick="viewBlogCategory(' . "'" . $blog->id . "'" . ')" title="View Category" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Category</a></li>';
                }
                if (($this->userPermissions == 'all') || (in_array('update' . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <li><a href="javascript:void(0)" onclick="editBlogCategory(' . "'" . $blog->id . "'" . ')" title="Edit Category" class="dropdown-item update' . $this->title . '"><i class="fas fa-edit text-info"></i> Edit Category</a></li>';
                }
                if (($this->userPermissions == 'all') || (in_array('delete' . ucwords(str_replace(' ', '', $this->title)), $this->userPermissions))) {
                    $actions .= '<div class="dropdown-divider"></div>
                                <li><a href="javascript:void(0)" onclick="deleteCategory(' . "'" . $blog->id . "'" . ',' . "'" . $blog->name . "'" . ')" title="Delete Category" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Category</a></li>';
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
        $this->_validateBlog('add');
        $name = $this->request->getVar('name');
        $data = [
            'name' => trim($name),
            'slug' => trim(url_title(strtolower($name), '-', true)),
            'description' => trim($this->request->getVar('description')),
            'blog_id' => ($this->request->getVar('blog_id')) ? $this->request->getVar('blog_id') : null,
            'status' => trim($this->request->getVar('status')),
            'user_id' => $this->userRow['id'],
            'account_id' => $this->userRow['account_id'],
        ];
        $insert = $this->blog->insert($data);
        $this->saveUserActivity([
            'user_id' => $this->userRow['id'],
            'account_id' => $this->userRow['account_id'],
            'action' => 'create',
            'description' => ('Blog Category: ' . $data['name']),
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
        $this->_validateBlog('update');
        $name = $this->request->getVar('name');
        $data = [
            'name' => trim($name),
            'slug' => trim(url_title(strtolower($name), '-', true)),
            'description' => trim($this->request->getVar('description')),
            'blog_id' => ($this->request->getVar('blog_id')) ? $this->request->getVar('blog_id') : null,
            'status' => trim($this->request->getVar('status')),
        ];
        $update = $this->blog->update($id, $data);
        if ($update) {
            # code...
            $this->saveUserActivity([
                'user_id' => $this->userRow['id'],
                'account_id' => $this->userRow['account_id'],
                'action' => 'update',
                'description' => ('Blog Category: ' . $data['name']),
                'module' => $this->menu,
                'referrer_id' => $id,
                'title' => $this->title,
            ]);
        } else {
            $response = [
                'status' => 500,
                'error' => 'Update Failed',
                'messages' => 'Updating ' . $this->title . ' record failed, try again later!',
            ];
            return $this->respond($response);
            exit;
        }
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
            $data = $this->blog->find($id);
            if ($data) {
                $delete = $this->blog->delete($id);
                if ($delete) {
                    $this->saveUserActivity([
                        'user_id' => $this->userRow['id'],
                        'account_id' => $this->userRow['account_id'],
                        'action' => 'delete',
                        'description' => ('Blog Category: ' . $data['name']),
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
                $data = $this->blog->find($id);
                if ($data) {
                    $delete = $this->blog->delete($id);
                    if ($delete) {
                        # Activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'account_id' => $this->userRow['account_id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', ' . $data['name']),
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

    private function _validateBlog($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $blog = $this->blog->find($this->request->getVar('id'));

        if ($this->request->getVar('name') == '') {
            $data['inputerror'][] = 'name';
            $data['error_string'][] = 'Category Name is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('name'))) {
            $name = $this->request->getVar('name');
            if ($this->settings->validateName($name) == TRUE) {
                if (strlen(trim($name)) < 5) {
                    $data['inputerror'][] = 'name';
                    $data['error_string'][] = 'Minimum 5 letters required [' . strlen($name) . ']';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($this->request->getVar('name')) == FALSE) {
                $data['inputerror'][] = 'name';
                $data['error_string'][] = 'Valid Category Name is required';
                $data['status'] = FALSE;
            }
            if ($method == "add") {
                # check category name existence
                $nameCounter = $this->blog
                    ->where(['name' => $this->request->getVar('name')])->countAllResults();
                if ($nameCounter) {
                    $data['inputerror'][] = 'name';
                    $data['error_string'][] = $this->request->getVar('name') . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if (
                $method == "update" &&
                $blog['name'] != $this->request->getVar('name')
            ) {
                # check branch name existence
                $blog = $this->blog
                    ->where(['name' => $this->request->getVar('name')])->countAllResults();
                if ($blog) {
                    $data['inputerror'][] = 'name';
                    $data['error_string'][] = $this->request->getVar('name') . ' already added';
                    $data['status'] = FALSE;
                }
            }
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
        if ($this->request->getVar('status') == '') {
            $data['inputerror'][] = 'status';
            $data['error_string'][] = 'Status is required';
            $data['status'] = FALSE;
        }
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
