<?= $this->extend("layout/dashboard") ?>

<?= $this->section("content") ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        Hello <a href="/admin/profile" class="font-italic"><?= session()->get('name') ?></a>!
                    </h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="javascript: void(0)" class="text-danger" onclick="history.back(-1);"><i class="fas fa-circle-left"></i> Back</a></li>
                        <li class="breadcrumb-item active"><?= ucfirst($title) ?></li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php
            if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
                    <i class="fas fa-check-double"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php elseif (session()->getFlashdata('failed')) : ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= session()->getFlashdata('failed') ?>
                </div>
            <?php endif; ?>
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 class="text-white"><?= $clients ?></h3>
                            <h6 class="text-white text-bold">Clients</h6>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="/admin/clients/client" class="small-box-footer text-white">
                            View More <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 class="text-white"><?= $staff ?></h3>
                            <h6 class="text-white text-bold">Staff</h6>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="small-box-footer btn-group">
                            <span class="btn btn-tool dropdown-toggle text-white" data-toggle="dropdown">
                                View More
                            </span>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-center" role="menu">
                                <a href="/admin/staff/administrator" class="dropdown-item">
                                    <i class="nav-icon fas fa-user-tie"></i>
                                    Administrators
                                </a>
                                <a class="dropdown-divider"></a>
                                <a href="/admin/staff/employee" class="dropdown-item">
                                    <i class="nav-icon fas fa-user-tag"></i> Employees
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="small-box bg-success">
                    <div class="inner">
                        <h3 class="text-white"><?= $applications ?></h3>
                        <h6 class="text-white text-bold">Applications</h6>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clipboard"></i>
                    </div>
                    <a href="/admin/loans/application" class="small-box-footer text-white">
                        View More <i class="fas fa-arrow-circle-right"></i>
                    </a>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 class="text-white"><?= $disbursements ?></h3>
                        <h6 class="text-white text-bold">Disbursements</h6>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-trend-up"></i>
                    </div>
                    <a href="/admin/loans/disbursement" class="small-box-footer text-white">
                        View More <i class="fas fa-arrow-circle-right"></i>
                    </a>
                    </div>
                </div>
            </div>
            <!-- chart -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Transactions Report</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <p class="text-center">
                                        <strong>
                                            Transactions: 01 Jan, <?= date('Y') ?> - <?= date('d M, Y') ?>
                                        </strong>
                                    </p>
                                    <div class="chart">
                                        <canvas id="lineChart" height="250" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-center">
                                        <strong>Transaction Totals</strong>
                                    </p>
                                    <!-- Total Financing -->
                                    <div class="progress-group">
                                        Total Financing
                                        <span class="float-right">
                                            <b><?= $financing; ?></b>/<?= $totalEntries; ?>
                                        </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: <?= ($totalEntries != 0) ? round((($financing/$totalEntries)*100)) : 0; ?>%;"></div>
                                        </div>
                                    </div><br>
                                    <!-- Total Expenses -->
                                    <div class="progress-group">
                                        Total Expenses
                                        <span class="float-right">
                                            <b><?= $expenses; ?></b>/<?= $totalEntries; ?>
                                        </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-danger" style="width: <?= ($totalEntries != 0) ? round((($expenses/$totalEntries)*100)) : 0; ?>%;"></div>
                                        </div>
                                    </div><br>
                                    <!-- Total Transfer -->
                                    <div class="progress-group">
                                        <span class="progress-text">Total Transfer</span>
                                        <span class="float-right">
                                            <b><?= $transfers; ?></b>/<?= $totalEntries; ?>
                                        </span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-info" style="width: <?= ($totalEntries != 0) ? round((($transfers/$totalEntries)*100)) : "0" ?>%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <!-- Total Financing -->
                                <div class="col-4">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-success">
                                            <i class="fas fa-cash-register"></i>
                                            <?= ($totalEntries != 0) ? round((($financing/$totalEntries)*100)) : 0; ?>%
                                        </span>
                                        <h5 class="description-header">
                                            <?= $settings['currency']; ?>. <?= number_format($financingAmt) ?>
                                        </h5>
                                        <span class="description-text">TOTAL FINANCING</span>
                                    </div>
                                </div>
                                <!-- Total Expenses -->
                                <div class="col-4">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-danger">
                                            <i class="fas fa-money-bill-1-wave"></i>
                                            <?= ($totalEntries != 0) ? round((($expenses/$totalEntries)*100)) : 0 ?>%
                                    </span>
                                        <h5 class="description-header">
                                            <?= $settings['currency']; ?>. <?= number_format($expenseAmt) ?>
                                        </h5>
                                        <span class="description-text">TOTAL EXPENSES</span>
                                    </div>
                                </div>
                                <!-- Total Transfer -->
                                <div class="col-4">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-info">
                                            <i class="fas fa-money-bill-transfer"></i>
                                            <?= ($totalEntries != 0) ? round((($transfers/$totalEntries)*100)) : 0; ?>%
                                        </span>
                                        <h5 class="description-header">
                                            <?= $settings['currency']; ?>. <?= number_format($transferAmt) ?>
                                        </h5>
                                        <span class="description-text">TOTAL TRANSFERS</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main row -->
            <div class="row">
                <!-- LIVE CHART -->
                <div class="col-md-4">
                    <!-- DIRECT CHAT -->
                    <div class="card direct-chat direct-chat-warning">
                        <div class="card-header">
                            <h3 class="card-title">Direct Chat</h3>

                            <div class="card-tools">
                                <span title="3 New Messages" class="badge badge-warning">3</span>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                                    <i class="fas fa-comments"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Conversations are loaded here -->
                            <div class="direct-chat-messages">
                                <!-- Message. Default to the left -->
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">Alexander Pierce</span>
                                        <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="/assets/dist/img/user1-128x128.jpg" alt="message user image" />
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        Is this template really for free? That's unbelievable!
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->

                                <!-- Message to the right -->
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-right">Sarah Bullock</span>
                                        <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="/assets/dist/img/user3-128x128.jpg" alt="message user image" />
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        You better believe it!
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->

                                <!-- Message. Default to the left -->
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">Alexander Pierce</span>
                                        <span class="direct-chat-timestamp float-right">23 Jan 5:37 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="/assets/dist/img/user1-128x128.jpg" alt="message user image" />
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        Working with AdminLTE on a great new app! Wanna join?
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->

                                <!-- Message to the right -->
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-right">Sarah Bullock</span>
                                        <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="/assets/dist/img/user3-128x128.jpg" alt="message user image" />
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        I would love to.
                                    </div>
                                    <!-- /.direct-chat-text -->
                                </div>
                                <!-- /.direct-chat-msg -->
                            </div>
                            <!--/.direct-chat-messages-->

                            <!-- Contacts are loaded here -->
                            <div class="direct-chat-contacts">
                                <ul class="contacts-list">
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="/assets/dist/img/user1-128x128.jpg" alt="User Avatar" />

                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Count Dracula
                                                    <small class="contacts-list-date float-right">2/28/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">How have you been? I was...</span>
                                            </div>
                                            <!-- /.contacts-list-info -->
                                        </a>
                                    </li>
                                    <!-- End Contact Item -->
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="/assets/dist/img/user7-128x128.jpg" alt="User Avatar" />

                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Sarah Doe
                                                    <small class="contacts-list-date float-right">2/23/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">I will be waiting for...</span>
                                            </div>
                                            <!-- /.contacts-list-info -->
                                        </a>
                                    </li>
                                    <!-- End Contact Item -->
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="/assets/dist/img/user3-128x128.jpg" alt="User Avatar" />

                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Nadia Jolie
                                                    <small class="contacts-list-date float-right">2/20/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">I'll call you back at...</span>
                                            </div>
                                            <!-- /.contacts-list-info -->
                                        </a>
                                    </li>
                                    <!-- End Contact Item -->
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="/assets/dist/img/user5-128x128.jpg" alt="User Avatar" />

                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Nora S. Vans
                                                    <small class="contacts-list-date float-right">2/10/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">Where is your new...</span>
                                            </div>
                                            <!-- /.contacts-list-info -->
                                        </a>
                                    </li>
                                    <!-- End Contact Item -->
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="/assets/dist/img/user6-128x128.jpg" alt="User Avatar" />

                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    John K.
                                                    <small class="contacts-list-date float-right">1/27/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">Can I take a look at...</span>
                                            </div>
                                            <!-- /.contacts-list-info -->
                                        </a>
                                    </li>
                                    <!-- End Contact Item -->
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="/assets/dist/img/user8-128x128.jpg" alt="User Avatar" />

                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Kenneth M.
                                                    <small class="contacts-list-date float-right">1/4/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">Never mind I found...</span>
                                            </div>
                                            <!-- /.contacts-list-info -->
                                        </a>
                                    </li>
                                    <!-- End Contact Item -->
                                </ul>
                                <!-- /.contacts-list -->
                            </div>
                            <!-- /.direct-chat-pane -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <form action="#" method="post">
                                <div class="input-group">
                                    <input type="text" name="message" placeholder="Type Message ..." class="form-control" />
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-warning">Send</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-footer-->
                    </div>
                    <!--/.direct-chat -->
                </div>
                <!-- BRANCHES LIST -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Branches</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                <?php if(count($branches) > 0):
                                    foreach($branches as $branch):
                                ?>
                                <li class="item">
                                    <div class="product-img">
                                        <img src="/assets/dist/img/boxed-bg.jpg" alt="Product Image" class="img-size-50" />
                                    </div>
                                    <div class="product-info">
                                        <a href="/admin/branch/info/<?= $branch['id']; ?>" class="product-title" title="Go to branch">
                                            <?= $branch['branch_name']; ?> <span class="badge badge-warning float-right"><?= $branch['branch_code']; ?></span>
                                        </a>
                                        <span class="product-description">
                                            <?= $branch['branch_address']; ?>
                                        </span>
                                    </div>
                                </li>
                                <?php endforeach; else: ?>
                                    <li class="item">
                                        <div class="product-img">
                                            <img src="/assets/dist/img/boxed-bg.jpg" alt="Product Image" class="img-size-50" />
                                        </div>
                                        <div class="product-info">
                                            <a href="javascript: void(0)" class="product-title" title="No branch">
                                                No Branch <span class="badge badge-warning float-right">0.00%</span>
                                            </a>
                                            <span class="product-description">
                                                No Branch Added
                                            </span>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="card-footer text-center">
                            <a href="/admin/company/branch" class="uppercase">View More</a>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTS LIST -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Loan Products</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                <?php if(count($products) > 0):
                                    foreach($products as $product):
                                ?>
                                <li class="item">
                                    <div class="product-img">
                                        <img src="/assets/dist/img/boxed-bg.jpg" alt="Product Image" class="img-size-50" />
                                    </div>
                                    <div class="product-info">
                                        <a href="/admin/product/info/<?= $product['id']; ?>" class="product-title" title="Go to product">
                                            <?= $product['product_name']; ?> <span class="badge badge-warning float-right"><?= $product['interest_rate']; ?>%</span>
                                        </a>
                                        <span class="product-description">
                                            <?= $product['repayment_freq']; ?>
                                        </span>
                                    </div>
                                </li>
                                <?php endforeach; else: ?>
                                    <li class="item">
                                        <div class="product-img">
                                            <img src="/assets/dist/img/boxed-bg.jpg" alt="Product Image" class="img-size-50" />
                                        </div>
                                        <div class="product-info">
                                            <a href="javascript: void(0)" class="product-title" title="No product">
                                                No Product <span class="badge badge-warning float-right">0.00%</span>
                                            </a>
                                            <span class="product-description">
                                                No Product Added
                                            </span>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="card-footer text-center">
                            <a href="/admin/loans/product" class="uppercase">View More</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- TABLE: Latest Transactions -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header border-transparent">
                            <h3 class="card-title">Your Latest Transactions</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table tabls-striped table-hover m-0">
                                    <thead>
                                        <tr>
                                            <th>S/N<u>o</u></th>
                                            <th>Type</th>
                                            <th>Particular</th>
                                            <th>Acc. Type</th>
                                            <th>Payment</th>
                                            <th>Amount [<?= $settings['currency']; ?>]</th>
                                            <th>Ref ID</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="entries">
                                        <?php if(count($entries) > 0):
                                            $no = 0;
                                            foreach ($entries as $entry):
                                                $no++;
                                        ?>
                                        <tr>
                                            <td><?=$no ?></td>
                                            <td><?= ucfirst($entry['entry_menu']); ?></td>
                                            <td><?= $entry['particular_name']; ?></td>
                                            <td><?= $entry['module']; ?></td>
                                            <td><?= $entry['payment_method']; ?></td>
                                            <td><?= number_format($entry['amount']); ?></td>
                                            <td>
                                                <a href="/admin/transaction/info/<?= $entry['ref_id']; ?>" class="font-italic" title="go to transaction">
                                                    <?= $entry['ref_id']; ?>
                                                </a>
                                            </td>
                                            <td><?= $entry['date']; ?></td>
                                            <td><?= $entry['status']; ?></td>
                                        </tr>
                                        <?php endforeach; else: ?>
                                            <tr><th class="text-center" colspan="6">No Transactions Found</th></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <span class="nav-item float-right dropdown">
                                <a href="javascript: void(0)" class="nav-link btn btn-sm btn-info" data-toggle="dropdown" title="View Transactions">
                                   View More <i class="fas fa-arrow-circle-down"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-center text-center">
                                    <a href="/admin/transactions/view-transactions/financing" class="dropdown-item text-"  title="Financing Transactions">
                                    Financing <i class="nav-icon fas fa-cash-register"></i>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="/admin/transactions/view-transactions/expense" class="dropdown-item text-" title="Expenses Transactions">
                                    Expenses <i class="nav-icon fas fa-money-bill-1-wave"></i>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="/admin/transactions/view-transactions/transfer" class="dropdown-item text-" title="Transfer Transactions">
                                        Transfers <i class="nav-icon fas fa-money-bill-transfer"></i>
                                    </a>
                                </div>
                            </span>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="/assets/dist/js/charts/dashboard.js"></script>
<?= $this->endSection() ?>