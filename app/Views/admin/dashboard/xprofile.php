<?= $this->extend("layout/main"); ?>

<?= $this->section("content"); ?>
<style type="text/css">
    a {
        padding-left: 5px;
        padding-right: 5px;
        margin-left: 5px;
        margin-right: 5px;
    }

    .pagination li.active {
        background: deepskyblue;
        color: white;
    }

    .pagination li.active a {
        color: white;
        text-decoration: none;
    }
</style>
<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <h1 class="page-title fw-semibold fs-18 mb-0"><?= ucwords($title); ?></h1>
    <div class="ms-md-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript: void(0)" class="text-danger" onclick="history.back(-1);"><i class="fas fa-circle-left"></i> Back</a></li>
                <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="/admin/profile"><?= ucwords($title); ?></a>
                </li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header Close -->

<!-- Start::row-1 -->
<div class="row">
    <div class="col-xxl-4 col-xl-12">
        <div class="card custom-card overflow-hidden">
            <div class="card-body p-0">
                <div class="d-sm-flex align-items-top p-4 border-bottom border-block-end-dashed main-profile-cover">
                    <div>
                        <span class="avatar avatar-xxl avatar-rounded <?= (strtolower($user['access_status']) == 'active') ? 'online' : 'offline'; ?> me-3">
                            <?php if (file_exists('uploads/users/' . $user['photo']) && $user['photo']) : ?>
                                <img src="/uploads/users/<?= $user['photo']; ?>" alt="Photo" />
                            <?php else : ?>
                                <img src="/assets/dist/img/nophoto.jpg" alt="Photo" />
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="flex-fill main-profile-info">
                        <div class="d-flex align-items-center justify-content-between">
                            <!-- username -->
                            <h6 class="fw-semibold mb-1 text-fixed-white">
                                <?= $user['name']; ?>
                            </h6>
                            <button class="btn btn-light btn-wave" onclick="change_password()">
                                <i class="ri-lock-password-line me-1 align-middle d-inline-block"></i> Password
                            </button>
                        </div>
                        <!-- position -->
                        <p class="mb-1 text-muted text-fixed-white op-7">
                            <?= $user['account_type']; ?>
                        </p>
                        <!-- department && position -->
                        <p class="fs-12 text-fixed-white mb-4 op-5">
                            <span class="me-3">
                                <i class="ri-building-line me-1 align-middle"></i>
                                <?= $user['department_name']; ?>
                            </span>
                            <span>
                                <i class="la la-user-tag me-1 align-middle"></i>
                                <?= $user['position']; ?>
                            </span>
                        </p>
                        <!-- log && activity -->
                        <div class="d-flex mb-0">
                            <div class="me-4">
                                <p class="fw-bold fs-20 text-fixed-white text-shadow mb-0">
                                    <?= $logsCount; ?>
                                </p>
                                <p class="mb-0 fs-11 op-5 text-fixed-white" id="userLogin">Logins</p>
                            </div>
                            <div class="me-4">
                                <p class="fw-bold fs-20 text-fixed-white text-shadow mb-0">
                                    <?= $activityCount; ?>
                                </p>
                                <p class="mb-0 fs-11 op-5 text-fixed-white">Activity</p>
                            </div>
                            <div class="me-4">
                                <p class="fw-bold fs-20 text-fixed-white text-shadow mb-0">
                                    <?= $user['staffID']; ?>
                                </p>
                                <p class="mb-0 fs-11 op-5 text-fixed-white">ID</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- contact -->
                <div class="p-4 border-bottom border-block-end-dashed">
                    <p class="fs-15 mb-2 me-4 fw-semibold">Contact Information :</p>
                    <div class="text-muted">
                        <p class="mb-2">
                            <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                                <i class="ri-mail-line align-middle fs-14"></i>
                            </span>
                            <?= $user['email']; ?>
                        </p>
                        <p class="mb-2">
                            <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                                <i class="ri-phone-line align-middle fs-14"></i>
                            </span>
                            <?= $user['mobile']; ?>
                        </p>
                        <p class="mb-2">
                            <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                                <i class="ri-phone-line align-middle fs-14"></i>
                            </span>
                            <?= $user['alternate_mobile']; ?>
                        </p>
                        <p class="mb-0">
                            <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                                <i class="ri-map-pin-line align-middle fs-14"></i>
                            </span>
                            <?= $user['address']; ?>
                        </p>
                    </div>
                </div>
                <!-- bio -->
                <div class="p-4 border-bottom border-block-end-dashed d-flex">
                    <div class="p-4 border-bottom border-block-end-dashed">
                        <p class="fs-15 mb-2 me-4 fw-semibold">Bio Information :</p>
                        <div class="text-muted">
                            <p class="mb-2">
                                <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                                    <i class="bi bi-gender-ambiguous align-middle fs-14"></i>
                                </span>
                                <?= $user['gender']; ?>
                            </p>
                            <p class="mb-2">
                                <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                                    <i class="la la-user-friends align-middle fs-14"></i>
                                </span>
                                <?= $user['marital_status']; ?>
                            </p>
                            <p class="mb-2">
                                <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                                    <i class="ti ti-building-church align-middle fs-14"></i>
                                </span>
                                <?= $user['religion']; ?>
                            </p>
                            <p class="mb-2">
                                <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                                    <i class="ri-calendar-check-fill align-middle fs-14"></i>
                                </span>
                                <?= $user['date_of_birth']; ?>
                            </p>
                            <p class="mb-2">
                                <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                                    <i class="bx bx-world align-middle fs-14"></i>
                                </span>
                                <?= $user['nationality']; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- staff status -->
                <div class="p-4 border-bottom border-block-end-dashed">
                    <p class="fs-15 mb-2 me-4 fw-semibold">Staff Information :</p>
                    <div class="text-muted">
                        <p class="mb-2">
                            <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                                <i class="la la-user-tag align-middle fs-14"></i>
                            </span>
                            <?= $user['account_type']; ?>
                        </p>
                        <p class="mb-2">
                            <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                                <i class="ri-git-branch-line align-middle fs-14"></i>
                            </span>
                            <?= $user['branch_name']; ?>
                        </p>
                        <p class="mb-2">
                            <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted">
                                <i class="la la-user-clock align-middle fs-14"></i>
                            </span>
                            <?= $user['appointment_type']; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-8 col-xl-12">
        <div class="row">
            <div class="col-xl-12">
                <div class="card border border-warning custom-card">
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                            <div>
                                <!-- page head -->
                                <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity-tab-pane" type="button" role="tab" aria-controls="activity-tab-pane" aria-selected="true">
                                            <i class="ri-gift-line me-1 align-middle d-inline-block"></i>Activity
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="logins-tab" data-bs-toggle="tab" data-bs-target="#logins-tab-pane" type="button" role="tab" aria-controls="logins-tab-pane" aria-selected="false">
                                            <i class="ri-bill-line me-1 align-middle d-inline-block"></i>Logins
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions-tab-pane" type="button" role="tab" aria-controls="permissions-tab-pane" aria-selected="false">
                                            <i class="la la-user-check me-1 align-middle d-inline-block"></i>Permissions
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <!-- <div>
                                <p class="fw-semibold mb-2">Profile 60% completed - <a href="javscript:void(0);" class="text-primary fs-12">Finish now</a></p>
                                <div class="progress progress-xs progress-animate">
                                    <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"></div>
                                </div>
                            </div> -->
                        </div>
                        <div class="p-3">
                            <div class="tab-content" id="myTabContent">
                                <!-- activity tab -->
                                <div class="tab-pane show active fade p-0 border-0" id="activity-tab-pane" role="tabpanel" aria-labelledby="activity-tab" tabindex="0">
                                    <ul class="list-group">
                                        <?php if (count($activities) > 0) :
                                            foreach ($activities as $activity) :
                                                // activity color
                                                switch (strtolower($activity['action'])) {
                                                    case 'create':
                                                        $color = 'bg-success-transparent';
                                                        $icon = '<i class="fas fa-plus text-success"></i>';
                                                        break;
                                                    case 'import':
                                                        $color = 'bg-success-transparent';
                                                        $icon = '<i class="fas fa-file-import text-success"></i>';
                                                        break;
                                                    case 'upload':
                                                        $color = 'bg-success-transparent';
                                                        $icon = '<i class="fas fa-file-upload text-success"></i>';
                                                        break;
                                                    case 'update':
                                                        $color = 'bg-primary-transparent';
                                                        $icon = '<i class="fas fa-edit text-primary"></i>';
                                                        break;
                                                    case 'delete':
                                                        $color = 'bg-pink-transparent';
                                                        $icon = '<i class="la la-trash tx-danger"></i>';
                                                        break;
                                                    case 'bulk-delete':
                                                        $color = 'bg-danger-transparent';
                                                        $icon = '<i class="la la-trash-o tx-danger"></i>';
                                                    default:
                                                        $color = 'bg-dark-transparent';
                                                        $icon = '<i class="bi bi-pin text-info"></i>';
                                                        break;
                                                }
                                                // activity link
                                                switch (strtolower($activity['module'])) {
                                                    case 'menus':
                                                        $goTO = '
                                                            <a href="/admin/menus/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to menu"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'branches':
                                                        $goTO = '
                                                            <a href="/admin/branch/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to branch"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'departments':
                                                        $goTO = '
                                                            <a href="/admin/department/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to department"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'positions':
                                                        $goTO = '
                                                            <a href="/admin/position/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to position"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'staff':
                                                        $goTO = '
                                                            <a href="/admin/staff/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to staff"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'clients':
                                                        $goTO = '
                                                            <a href="/admin/client/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to client"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'categories':
                                                        $goTO = '
                                                            <a href="/admin/category/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to category"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'subcategories':
                                                        $goTO = '
                                                            <a href="/admin/subcategory/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to subcategory"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'particulars':
                                                        $goTO = '
                                                            <a href="/admin/particular/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to particular"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'transactions':
                                                        $goTO = '
                                                            <a href="/admin/transaction/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to subcategory"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'products':
                                                        $goTO = '
                                                            <a href="/admin/product/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to product"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'applications':
                                                        $goTO = '
                                                            <a href="/admin/application/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to application"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'disbursements':
                                                        $goTO = '
                                                            <a href="/admin/disbursement/info/' . $activity['referrer_id'] . '" class="text-secondary" title="go to disbursement"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    case 'settings':
                                                        $goTO = '
                                                            <a href="/admin/settings/setting" class="text-secondary" title="go to settings"><u>#' . ucfirst($activity['module']) . '</u></a>
                                                        ';
                                                        break;
                                                    default:
                                                        $goTO = '
                                                            <a href="javascript: void(0)" class="text-secondary" title="go to activity"><u>#' . $activity['referrer_id'] . '</u></a>
                                                        ';
                                                        break;
                                                }
                                        ?>
                                                <li class="list-group-item">
                                                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                        <div class="rounded border">
                                                            <div class="p-3 d-flex align-items-top flex-wrap">
                                                                <div class="me-2">
                                                                    <span class="avatar avatar-sm avatar-rounded">
                                                                        <?= $icon ?>
                                                                    </span>
                                                                </div>
                                                                <div class="flex-fill">
                                                                    <p class="mb-1 fw-semibold lh-1">
                                                                        <?= $goTO; ?>
                                                                    </p>
                                                                    <p class="fs-11 mb-2 text-muted"><?= date('d, M y', strtotime($activity['created_at'])) ?></p>
                                                                    <p class="fs-12 text-muted mb-3">
                                                                        <?= $activity['description'] ?>👌
                                                                    </p>
                                                                    <div class="d-flex align-items-center justify-content-between mb-md-0 mb-2">
                                                                        <div>
                                                                            <div class="btn-list">
                                                                                <button class="btn btn-info-light btn-sm btn-wave">
                                                                                    <i class="ri-timer-line">
                                                                                        <?= date('H:i:s', strtotime($activity['created_at'])) ?>
                                                                                    </i>
                                                                                </button>
                                                                                <button class="btn btn-primary-light btn-sm btn-wave">
                                                                                    Activity: <?= $activity['action'] ?>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach;
                                        else : ?>
                                            <li class="list-group-item">
                                                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                    <div class="rounded border">
                                                        <div class="p-3 d-flex align-items-top flex-wrap">
                                                            <div class="me-2">
                                                                <span class="avatar avatar-sm bg-primary-transparent avatar-rounded profile-timeline-avatar">
                                                                    N/A
                                                                </span>
                                                            </div>
                                                                <div class="flex-fill">
                                                                    <p class="mb-2">
                                                                        <b>You</b> No <b>Activity</b> Found <a class="text-secondary" href="javascript:void(0);"><u>#</u></a>.<span class="float-end fs-11 text-muted">__, __ __ - 00:00</span>
                                                                    </p>
                                                                    <p class="profile-activity-media mb-0">
                                                                        You do not have any activity found.
                                                                    </p>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                    <div>
                                        <?= $activityPager->links(); ?>
                                    </div>
                                </div>
                                <!-- login logs tab -->
                                <div class="tab-pane fade p-0 border-0" id="logins-tab-pane" role="tabpanel" aria-labelledby="logins-tab" tabindex="0">
                                    <ul class="list-group">
                                        <?php if (count($logs) > 0) :
                                            foreach ($logs as $log) : ?>
                                                <li class="list-group-item">
                                                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                        <div class="rounded border">
                                                            <div class="p-3 d-flex align-items-top flex-wrap">
                                                                <div class="me-2">
                                                                    <span class="avatar avatar-sm avatar-rounded">
                                                                        <i class="bi bi-pin \\\\\\\\\\\\\\\\\\\\\\\\text-info"></i>
                                                                    </span>
                                                                </div>
                                                                <div class="flex-fill">
                                                                    <p class="mb-1 fw-semibold lh-1">
                                                                        <i class="ri-computer-line"></i>, <?= $log['operating_system'] ?> using <?= $log['browser'] ?>
                                                                    </p>
                                                                    <p class="fs-11 mb-2 text-muted"><?= date('d, M y', strtotime($log['login_at'])) ?></p>
                                                                    <p class="fs-12 text-muted mb-3">
                                                                        IP Address: <?= $log['ip_address'] ?>👌<br />
                                                                        Location: <?= $log['location'] ?>👌
                                                                    </p>
                                                                    <p class="fs-12 text-muted mb-0">Info: <?= $log['loginfo'] ?>.</p>
                                                                    <div class="d-flex align-items-center justify-content-between mb-md-0 mb-2">
                                                                        <div>
                                                                            <div class="btn-list">
                                                                                <button class="btn btn-sm <?= ($log['status'] == 'online') ? 'btn-success-light' : 'btn-danger-light' ?> b  btn-wave">
                                                                                    <i class="la la-question"> </i> <?= $log['status'] ?>
                                                                                </button>
                                                                                <button class="btn btn-info-light btn-sm btn-wave">
                                                                                    <i class="bx bx-log-in"></i> <?= date('H:i:s', strtotime($log['login_at'])) ?>
                                                                                </button>
                                                                                <button class="btn btn-primary-light btn-sm btn-wave">
                                                                                    <i class="bx bx-log-out"></i> <?= date('H:i:s', strtotime($log['logout_at'])) ?>
                                                                                </button>
                                                                                <button class="btn btn-sm btn-success-light btn-wave">
                                                                                    <i class="ri-timer-line"> </i> <?= $log['duration'] ?>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach;
                                        else : ?>
                                            <li class="list-group-item">
                                                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                    <div class="rounded border">
                                                        <div class="p-3 d-flex align-items-top flex-wrap">
                                                            <div class="me-2">
                                                                <span class="avatar avatar-sm avatar-rounded">
                                                                    <i class="bi bi-pin text-info"></i>
                                                                </span>
                                                            </div>
                                                            <div class="flex-fill">
                                                                <p class="mb-1 fw-semibold lh-1">You</p>
                                                                <p class="fs-11 mb-2 text-muted">00, 00 - 00:00</p>
                                                                <p class="fs-12 text-muted mb-3">No Login Acitivity found👌</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                        <div class="float-right">
                                            <?= $logsPager->links(); ?>
                                        </div>
                                    </ul>
                                </div>
                                <!-- permissions tab -->
                                <div class="tab-pane fade p-0 border-0" id="permissions-tab-pane" role="tabpanel" aria-labelledby="permissions-tab" tabindex="0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive" id="permissions">
                                                <table class="table text-nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Menu</th>
                                                            <th scope="col">Create</th>
                                                            <th scope="col">Import</th>
                                                            <th scope="col">View</th>
                                                            <th scope="col">Edit</th>
                                                            <th scope="col">Delete</th>
                                                            <th scope="col">Bulk-Del</th>
                                                            <th scope="col">Export</th>
                                                            <!-- <th scope="col">All</th> -->
                                                        </tr>
                                                    <tbody id="permissions"></tbody>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End::row-1 -->

<div class="modal fade" id="passwordModal" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="password_form" class="form-horizontal" autocomplete="off">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Hello <?= $user['name']; ?>!</p>
                    <input type="hidden" readonly value="" name="id" />
                    <input type="hidden" readonly value="" name="menu" />
                    <div class="form-body">
                        <div class="row gy-3">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="currentpassword" class="form-label text-default">Current Password</label>
                                    <div class="input-group">
                                        <input type="password" name="currentpassword" class="form-control form-control-lg" id="currentpassword" placeholder="current password">
                                        <button class="btn btn-light" type="button" onclick="createpassword('currentpassword',this)" id="btncurrentpassword"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <span class="help-block error-msg text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="newpassword" class="form-label text-default">New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="newpassword" class="form-control form-control-lg" id="newpassword" placeholder="new password">
                                        <button class="btn btn-light" type="button" onclick="createpassword('newpassword',this)" id="btnnewpassword"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <span class="help-block error-msg text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-2">
                                <div class="form-group">
                                    <label for="confirmpassword" class="form-label text-default">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" name="confirmpassword" class="form-control form-control-lg" id="confirmpassword" placeholder="confirm password">
                                        <button class="btn btn-light" type="button" onclick="createpassword('confirmpassword',this)" id="btn-confirmpassword"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <span class="help-block error-msg text-danger"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" onclick="save_newPassword()" class="btn btn-outline-success" id="btnReset">Reset</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section("scripts") ?>
<script type="text/javascript">
    const userId = <?= $user['id']; ?>;
    const permissions = <?= json_encode(unserialize($user['permissions'])); ?>;
</script>
<script src="/assets/scripts/main/permissions.js"></script>
<script src="/assets/scripts/profile.js"></script>

<?= $this->endSection() ?>