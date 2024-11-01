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
<div class="container-fluid">
    <div class="card profile-overview profile-overview-wide">
        <div class="card-body d-flex">
            <div class="clearfix">
                <div class="d-inline-block position-relative me-sm-4 me-3 mb-3 mb-lg-0">
                    <?php if (file_exists('uploads/users/' . $user['photo']) && $user['photo']) : ?>
                        <img src="/uploads/users/<?= $user['photo']; ?>" class="rounded-4 profile-avatar" alt="Photo" />
                    <?php else : ?>
                        <img src="/assets/dist/img/nophoto.jpg" class="rounded-4 profile-avatar" alt="Photo" />
                    <?php endif; ?>
                    <span class="fa fa-circle border border-3 border-white text-<?= (strtolower($user['access_status']) == 'active') ? 'success' : 'danger'; ?> position-absolute bottom-0 end-0 rounded-circle"></span>
                </div>
            </div>
            <div class="clearfix d-xl-flex flex-grow-1">
                <div class="clearfix pe-md-5">
                    <h3 class="fw-semibold mb-1"><?= $user['name']; ?></h3>
                    <ul class="d-flex flex-wrap fs-6 align-items-center">
                        <li class="me-3 d-inline-flex align-items-center"><i class="las la-user me-1 fs-18"></i><?= $user['account_type']; ?></li>
                        <li class="me-3 d-inline-flex align-items-center"><i class="las la-map-marker me-1 fs-18"></i><?= $user['address']; ?></li>
                        <li class="me-3 d-inline-flex align-items-center"><i class="las la-envelope me-1 fs-18"></i><?= $user['email']; ?></li>
                    </ul>

                    <ul class="d-flex flex-wrap fs-6 align-items-center mt-2">
                        <li class="me-3 d-inline-flex align-items-center"><i class="las la-phone me-1 fs-18"></i><?= $user['mobile']; ?></li>
                        <li class="me-3 d-inline-flex align-items-center"><i class="las la-tags me-1 fs-18"></i><?= $user['department_name']; ?></li>
                        <li class="me-3 d-inline-flex align-items-center"><i class="las la-tag me-1 fs-18"></i><?= $user['position']; ?></li>
                    </ul>
                    <div class="d-md-flex d-none flex-wrap">
                        <div class="border outline-dashed rounded p-2 d-flex align-items-center me-3 mt-3">
                            <div class="avatar avatar-md bg-primary-light text-primary rounded d-flex align-items-center justify-content-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9849 15.3462C8.11731 15.3462 4.81445 15.931 4.81445 18.2729C4.81445 20.6148 8.09636 21.2205 11.9849 21.2205C15.8525 21.2205 19.1545 20.6348 19.1545 18.2938C19.1545 15.9529 15.8735 15.3462 11.9849 15.3462Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9849 12.0059C14.523 12.0059 16.5801 9.94779 16.5801 7.40969C16.5801 4.8716 14.523 2.81445 11.9849 2.81445C9.44679 2.81445 7.3887 4.8716 7.3887 7.40969C7.38013 9.93922 9.42394 11.9973 11.9525 12.0059H11.9849Z" stroke="var(--primary)" stroke-width="1.42857" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="clearfix ms-2">
                                <h3 class="mb-0 fw-semibold lh-1"><?= $user['staffID']; ?></h3>
                                <span class="fs-14">Staff ID</span>
                            </div>
                        </div>
                        <div class="border outline-dashed rounded p-2 d-flex align-items-center me-3 mt-3">
                            <div class="avatar avatar-md bg-primary-light text-primary rounded d-flex align-items-center justify-content-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.9951 16.6768V14.1398" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M18.19 5.3302C19.88 5.3302 21.24 6.7002 21.24 8.3902V11.8302C18.78 13.2702 15.53 14.1402 11.99 14.1402C8.45 14.1402 5.21 13.2702 2.75 11.8302V8.3802C2.75 6.6902 4.12 5.3302 5.81 5.3302H18.19Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M15.4951 5.32582V4.95982C15.4951 3.73982 14.5051 2.74982 13.2851 2.74982H10.7051C9.48512 2.74982 8.49512 3.73982 8.49512 4.95982V5.32582" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M2.77441 15.483L2.96341 17.992C3.09141 19.683 4.50041 20.99 6.19541 20.99H17.7944C19.4894 20.99 20.8984 19.683 21.0264 17.992L21.2154 15.483" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="clearfix ms-2">
                                <h3 class="mb-0 fw-semibold lh-1"><?= $activityCount; ?></h3>
                                <span class="fs-14">Total Activities</span>
                            </div>
                        </div>
                        <div class="border outline-dashed rounded p-2 d-flex align-items-center me-3 mt-3">
                            <div class="avatar avatar-md bg-primary-light text-primary rounded d-flex align-items-center justify-content-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M21.25 16.3341V7.66512C21.25 4.64512 19.111 2.75012 16.084 2.75012H7.916C4.889 2.75012 2.75 4.63512 2.75 7.66512L2.75 16.3341C2.75 19.3641 4.889 21.2501 7.916 21.2501H16.084C19.111 21.2501 21.25 19.3641 21.25 16.3341Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.0861 12.0001H7.91406" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12.3223 8.25211L16.0863 12.0001L12.3223 15.7481" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="clearfix ms-2">
                                <h3 class="mb-0 fw-semibold lh-1"><?= $logsCount; ?></h3>
                                <span class="fs-14">Total Logins</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix mt-3 mt-xl-0 ms-auto d-flex flex-column col-xl-3">
                    <div class="clearfix mb-3 text-xl-end">
                        <a href="javascript:void(0);" id="fAuthBtn" class="btn <?= (strtolower($user['2fa']) == 'false') ? 'btn-info' : 'btn-danger'; ?> ms-2" onclick="two_factorAuth('<?= $user['2fa'] ?>')"><?= (strtolower($user['2fa']) == 'false') ? 'Enable 2-FA' : 'Disable 2-FA'; ?></a>
                        <a href="javascript:void(0);" class="btn btn-primary ms-2" onclick="change_password()">Change Password</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer py-0 d-flex flex-wrap justify-content-between align-items-center px-0">
            <div class="card-footer py-0 d-flex flex-wrap justify-content-between align-items-center px-0">
                <ul class="nav nav-underline nav-underline-primary nav-underline-text-dark nav-underline-gap-x-0" id="tabMyProfileBottom" role="tablist">
                    <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="activityBtn" class="nav-link py-3 border-3 text-dark active" data-bs-toggle="tab" data-bs-target="#activity-tab" type="button" role="tab" aria-controls="activity-tab" aria-selected="true">Activity</a>
                    </li>
                    <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="loginsBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#logins-tab" type="button" role="tab" aria-controls="logins-tab" aria-selected="false">Login</a>

                    </li>
                    <li class="nav-item ms-1" id="" role="presentation">
                        <a href="javascript:void(0)" id="permissionsBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#permissions-tab" type="button" role="tab" aria-controls="permissions-tab" aria-selected="false">Permission</a>
                    </li>
                    <li class="nav-item ms-1" role="presentation">
                        <a href="javascript:void(0)" id="overviewBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#overview-tab" type="button" role="tab" aria-controls="overview-tab" aria-selected="true">Overview</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="tab-content" id="tabContentMyProfileBottom">
        <div class="row">
            <div class="col-xxl-12 col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <!-- activies -->
                            <div class="tab-pane fade show active" id="activity-tab" role="tabpanel" aria-labelledby="activity-tab" tabindex="0">
                                <div class="widget-timeline-icons pb-3">
                                    <ul class="timeline">
                                        <div class="card-footer py-0 d-flex flex-wrap justify-content-between align-items-center px-0">
                                            <div class="card-footer py-0 d-flex flex-wrap justify-content-between align-items-center px-0">
                                                <ul class="nav nav-underline nav-underline-primary nav-underline-text-dark nav-underline-gap-x-0" id="tabMyProfileBottom" role="tablist">
                                                    <li class="nav-item ms-1" role="presentation">
                                                        <a href="javascript:void(0)" id="activityBtn" class="nav-link py-3 border-3 text-dark active" data-bs-toggle="tab" data-bs-target="#activity-tab" type="button" role="tab" aria-controls="activity-tab" aria-selected="true">Activity</a>
                                                    </li>
                                                    <li class="nav-item ms-1" role="presentation">
                                                        <a href="javascript:void(0)" id="loginsBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#logins-tab" type="button" role="tab" aria-controls="logins-tab" aria-selected="false">Login</a>

                                                    </li>
                                                    <li class="nav-item ms-1" id="" role="presentation">
                                                        <a href="javascript:void(0)" id="permissionsBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#permissions-tab" type="button" role="tab" aria-controls="permissions-tab" aria-selected="false">Permission</a>
                                                    </li>
                                                    <li class="nav-item ms-1" role="presentation">
                                                        <a href="javascript:void(0)" id="overviewBtn" class="nav-link py-3 border-3 text-dark" data-bs-toggle="tab" data-bs-target="#overview-tab" type="button" role="tab" aria-controls="overview-tab" aria-selected="true">Overview</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                </div>

                                <div class="tab-content" id="tabContentMyProfileBottom">
                                    <div class="row">
                                        <div class="col-xxl-12 col-xl-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="tab-content" id="myTabContent">
                                                        <!-- activies -->
                                                        <div class="tab-pane fade show active" id="activity-tab" role="tabpanel" aria-labelledby="activity-tab" tabindex="0">
                                                            <div class="widget-timeline-icons pb-3">
                                                                <ul class="timeline">
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
                                                                                    $icon = '<i class="la la-trash text-danger"></i>';
                                                                                    $icon = '<i class="la la-trash text-danger"></i>';
                                                                                    break;
                                                                                case 'bulk-delete':
                                                                                    $color = 'bg-danger-transparent';
                                                                                    $icon = '<i class="la la-trash-alt text-danger"></i>';
                                                                                    break;
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
                                                                            <li>
                                                                                <div class="timeline-media">
                                                                                    <?= $icon ?>
                                                                                </div>
                                                                                <div class="timeline-panel">
                                                                                    <div class="clearfix">
                                                                                        <span class="text-secondary fs-14 fw-semibold"><?= $activity['action'] ?></span>
                                                                                        <span class="fs-14 d-block">On <?= date('d M, Y H:i:s e', strtotime($activity['created_at'])) ?> by <span class="text-primary"><?= $user['name']; ?></span></span>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-lg-10">
                                                                                            <div class="alert alert-primary border-primary outline-dashed py-3 px-4 d-flex align-items-center mb-0 mt-3">
                                                                                                <i class="la la-quote-left text-primary fs-30 align-self-start"></i>
                                                                                                <div class="mx-3">
                                                                                                    <h6 class="fs-14 fw-semibold mb-1">
                                                                                                        <?= $activity['location'] . ' IP: <span class="text-primary">' . $activity['ip_address'] . '</span> - ' . $activity['operating_system'] ?>
                                                                                                    </h6>
                                                                                                    <p class="fs-14 mb-0 text-dark">
                                                                                                        <?= $activity['description'] ?>👌.
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        <?php endforeach;
                                                                    else : ?>
                                                                        <li>
                                                                            <div class="timeline-media">
                                                                                <i class="las la-user"></i>
                                                                            </div>
                                                                            <div class="timeline-panel">
                                                                                <div class="clearfix">
                                                                                    <span class="text-secondary fs-14 fw-semibold">No activity log found.</span>
                                                                                    <span class="fs-14 d-block">00:00 00 by <span class="text-primary"><?= $user['name']; ?></span></span>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                            <div class="float-right">
                                                                <?= $activityPager->links(); ?>
                                                            </div>
                                                        </div>
                                                        <!-- logins -->
                                                        <div class="tab-pane fade" id="logins-tab" role="tabpanel" aria-labelledby="logins-tab" tabindex="0">
                                                            <div class="widget-timeline-icons pb-3">
                                                                <ul class="timeline">
                                                                    <?php if (count($logs) > 0) :
                                                                        foreach ($logs as $log) : ?>
                                                                            <li>
                                                                                <div class="timeline-media">
                                                                                    <i class="fa fa-circle-notch text-<?= (strtolower($log['status']) == 'online') ? 'success' : 'secondary' ?>"></i>
                                                                                </div>
                                                                                <div class="timeline-panel">
                                                                                    <div class="clearfix">
                                                                                        <span class="text-secondary fs-14 fw-semibold">
                                                                                            Logged in: <?= '<span class="text-primary">' . date('d M, y H:i:s e', strtotime($log['login_at'])) . '</span>. Logged out: <span class="text-primary">' . date('d M, y H:i:s e', strtotime($log['logout_at'])) . '</span>, using ' . $log['browser'] ?>
                                                                                        </span>
                                                                                        <span class="fs-14 d-block">Duration
                                                                                            <span class="text-primary">
                                                                                                <?= $log['duration'] ?>
                                                                                            </span>
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-lg-10">
                                                                                            <div class="alert alert-primary border-primary outline-dashed py-3 px-4 d-flex align-items-center mb-0 mt-3">
                                                                                                <i class="la la-door-<?= (strtolower($log['status']) == 'online') ? 'open' : 'closed' ?> text-<?= (strtolower($log['status']) == 'online') ? 'success' : 'secondary' ?> fs-30 align-self-start"></i>
                                                                                                <div class="mx-3">
                                                                                                    <h6 class="fs-14 fw-semibold mb-1">
                                                                                                        <?= $log['location'] . ' IP: <span class="text-primary">' . $log['ip_address'] . '</span> - ' . $log['operating_system'] ?>
                                                                                                    </h6>
                                                                                                    <p class="fs-14 mb-0 text-dark">
                                                                                                        <?= $log['loginfo'] ?>.
                                                                                                        <br>
                                                                                                        <?= 'Latitude ' . $log['latitude'] . ', longitude  ' . $log['longitude']  ?>.
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        <?php endforeach;
                                                                    else : ?>
                                                                        <li>
                                                                            <div class="timeline-media">
                                                                                <i class="las la-credit-card"></i>
                                                                            </div>
                                                                            <div class="timeline-panel">
                                                                                <div class="clearfix">
                                                                                    <span class="text-secondary fs-14 fw-semibold"></span>
                                                                                    <span class="fs-14 d-block"> <span class="text-primary"></span></span>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-lg-8">
                                                                                        <div class="alert alert-primary border-primary outline-dashed py-3 px-4 d-flex align-items-center mb-0 mt-3">
                                                                                            <i class="fa-solid fa-door-open text-primary fs-30 align-self-start"></i>
                                                                                            <div class="mx-3">
                                                                                                <h6 class="fs-14 fw-semibold mb-1">No Logins</h6>
                                                                                                <p class="fs-14 mb-0 text-dark">No Previous logins found for your account.</p>
                                                                                            </div>
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
                                                        </div>
                                                        <!-- permissions -->
                                                        <div class="tab-pane fade" id="permissions-tab" role="permissions-tab" aria-labelledby="all-tab3" tabindex="0">
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
                                                        <!-- overview -->
                                                        <div class="tab-pane fade" id="overview-tab" role="tabpanel" aria-labelledby="overview-tab" tabindex="0">
                                                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                                                <input type="hidden" readonly value="" name="id" />
                                                                <div class="form-body">
                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12 mb-3">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Name</label>
                                                                                <div class="col-md-12">
                                                                                    <input name="staff_name" placeholder="Name" class="form-control" type="text" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Gender</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" class="form-control" name="gender" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Nationality</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" name="nationality" placeholder="Nationality" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Marital Status</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" class="form-control" name="marital_status" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Religion</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" class="form-control" name="religion" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Phone Number</label>
                                                                                <div class="col-md-12">
                                                                                    <input name="mobile" placeholder="Phone Number" class="form-control" type="text" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Phone Number 2</label>
                                                                                <div class="col-md-12">
                                                                                    <input name="alternate_mobile" placeholder="Phone Number 2" class="form-control" type="text" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Email</label>
                                                                                <div class="col-md-12">
                                                                                    <input name="email" placeholder="example@mail.com" class="form-control" type="email" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Staff ID</label>
                                                                                <div class="col-md-12">
                                                                                    <input name="staffID" placeholder="Staff ID" class="form-control" type="text" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Department</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" name="department" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Position</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" name="position_id" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <div class="col-md-4">
                                                                            <label class="control-label fw-bold col-md-12">ID Photo(Front)</label>
                                                                            <div class="form-group" id="id-previewFront">
                                                                                <div class="col-md-12">
                                                                                    (No photo)
                                                                                    <span class="help-block"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="row">
                                                                                <div class="col-md-12 mb-3">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label fw-bold col-md-12">ID Type</label>
                                                                                        <div class="col-md-12">
                                                                                            <input name="id_type" placeholder="ID Type" class="form-control" type="text" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 mb-3">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label fw-bold col-md-12">ID Number</label>
                                                                                        <div class="col-md-12">
                                                                                            <input name="id_number" placeholder="ID number" class="form-control" type="text" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label fw-bold col-md-12">ID Expiry</label>
                                                                                        <div class="col-md-12">
                                                                                            <input name="id_expiry" placeholder="ID Expiry Date" class="form-control" type="text" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label class="control-label fw-bold col-md-12">ID Photo(Back)</label>
                                                                            <div class="form-group" id="id-previewBack">
                                                                                <div class="col-md-12">
                                                                                    (No photo)
                                                                                    <span class="help-block"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Address</label>
                                                                                <div class="col-md-12">
                                                                                    <textarea name="address" placeholder="address" class="form-control" readonly></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Qualifications</label>
                                                                                <div class="col-md-12">
                                                                                    <textarea name="qualifications" placeholder="qualifications" class="form-control" readonly></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Branch</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" name="branch_id" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Salary [<?= $settings['currency']; ?>]</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" class="form-control" name="salary_scale" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Appointment</label>
                                                                                <div class="col-md-12">
                                                                                    <input name="appointment_type" placeholder="appointment type" class="form-control" type="text" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <div class="col-md-4 mb-3">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Bank Name</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 mb-3">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Bank Branch</label>
                                                                                <div class="col-md-12">
                                                                                    <input type="text" class="form-control" name="bank_branch" placeholder="Bank Branch" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="control-label fw-bold col-md-12">Bank Account No.</label>
                                                                                <div class="col-md-12">
                                                                                    <input name="bank_account" class="form-control" type="text" placeholder="Bank Account No." readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-8">
                                                                            <div class="row mb-3">
                                                                                <div class="col-md-6 mb-3">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label fw-bold col-md-12">
                                                                                            Registration Date
                                                                                        </label>
                                                                                        <div class="col-md-12">
                                                                                            <input name="view_reg_date" placeholder="Registration Date" class="form-control" type="text" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 mb-3">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label fw-bold col-md-12">Admin D.O.B</label>
                                                                                        <div class="col-md-12">
                                                                                            <input name="date_of_birth" class="form-control" type="date" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label fw-bold col-md-12">Created Date</label>
                                                                                        <div class="col-md-12">
                                                                                            <input name="created_at" placeholder="created at" class="form-control" type="text" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label fw-bold col-md-12">Last Updated At</label>
                                                                                        <div class="col-md-12">
                                                                                            <input name="updated_at" placeholder="updated at" class="form-control" type="text" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 text-center">
                                                                            <label class="control-label fw-bold col-md-12">Signature</label>
                                                                            <div class="form-group" id="signature-preview">
                                                                                <div class="col-md-12">
                                                                                    (No Sign)
                                                                                    <span class="help-block"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
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
                                        <input type="password" name="currentpassword" class="form-control form-control-lg" id="currentpassword" placeholder="current password" autocomplete="off">
                                        <button class="btn btn-light" type="button" onclick="createpassword('currentpassword',this)" id="btncurrentpassword"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <span class="help-block error-msg text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="newpassword" class="form-label text-default">New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="newpassword" class="form-control form-control-lg" id="newpassword" placeholder="new password" autocomplete="off">
                                        <button class="btn btn-light" type="button" onclick="createpassword('newpassword',this)" id="btnnewpassword"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <span class="help-block error-msg text-danger"></span>
                                </div>
                            </div>
                            <div class="col-xl-12 mb-2">
                                <div class="form-group">
                                    <label for="confirmpassword" class="form-label text-default">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" name="confirmpassword" class="form-control form-control-lg" id="confirmpassword" placeholder="confirm password" autocomplete="off">
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
    const fAuth = "<?= $user['2fa']; ?>";
    const staff_id = <?= $user['staff_id']; ?>;
    const permissions = <?= json_encode(unserialize($user['permissions'])); ?>;
</script>
<script src="/assets/scripts/main/permissions.js"></script>
<script src="/assets/scripts/profile.js"></script>

<?= $this->endSection() ?>