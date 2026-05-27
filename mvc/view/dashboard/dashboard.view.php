<?php if ( ! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php require_once view('dashboard/header.dashboard'); ?>
<?php require_once view('dashboard/top.dashboard'); ?>
            
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 rounded-3 shadow-sm bg-primary text-white">
                                <div class="card-body d-flex align-items-center justify-content-between p-4">
                                    <div>
                                        <h3 class="fw-bold mb-1">150</h3>
                                        <p class="mb-0 text-white-50">New Orders</p>
                                    </div>
                                    <i class="bi bi-bag-plus fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 rounded-3 shadow-sm bg-success text-white">
                                <div class="card-body d-flex align-items-center justify-content-between p-4">
                                    <div>
                                        <h3 class="fw-bold mb-1">53%</h3>
                                        <p class="mb-0 text-white-50">Bounce Rate</p>
                                    </div>
                                    <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 rounded-3 shadow-sm bg-warning text-dark">
                                <div class="card-body d-flex align-items-center justify-content-between p-4">
                                    <div>
                                        <h3 class="fw-bold mb-1">44</h3>
                                        <p class="mb-0 text-dark-50">User Registrations</p>
                                    </div>
                                    <i class="bi bi-person-plus fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 rounded-3 shadow-sm bg-danger text-white">
                                <div class="card-body d-flex align-items-center justify-content-between p-4">
                                    <div>
                                        <h3 class="fw-bold mb-1">65</h3>
                                        <p class="mb-0 text-white-50">Unique Visitors</p>
                                    </div>
                                    <i class="bi bi-pie-chart fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-12 col-xl-8">
                            <div class="card border-0 rounded-3 shadow-sm">
                                <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between py-3">
                                    <h5 class="mb-0 fw-bold">Latest Transactions</h5>
                                    <span class="badge bg-info-subtle text-info p-2 rounded">Live Updates</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Product</th>
                                                <th>Status</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#OR9842</td>
                                                <td>iPhone 15 Pro</td>
                                                <td><span class="badge bg-success">Completed</span></td>
                                                <td>$999.00</td>
                                            </tr>
                                            <tr>
                                                <td>#OR9841</td>
                                                <td>Logitech MX Master 3S</td>
                                                <td><span class="badge bg-warning">Pending</span></td>
                                                <td>$99.00</td>
                                            </tr>
                                            <tr>
                                                <td>#OR9840</td>
                                                <td>Dell UltraSharp 32"</td>
                                                <td><span class="badge bg-danger">Canceled</span></td>
                                                <td>$649.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-xl-4">
                            <div class="card border-0 rounded-3 shadow-sm">
                                <div class="card-header bg-transparent border-bottom py-3">
                                    <h5 class="mb-0 fw-bold">System Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1 small">
                                            <span>CPU Usage</span>
                                            <span class="fw-bold">40%</span>
                                        </div>
                                        <div class="progress progress-sm" style="height: 6px;">
                                            <div class="progress-bar bg-primary" style="width: 40%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1 small">
                                            <span>Memory Usage</span>
                                            <span class="fw-bold">75%</span>
                                        </div>
                                        <div class="progress progress-sm" style="height: 6px;">
                                            <div class="progress-bar bg-warning" style="width: 75%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between mb-1 small">
                                            <span>Disk Space</span>
                                            <span class="fw-bold">90%</span>
                                        </div>
                                        <div class="progress progress-sm" style="height: 6px;">
                                            <div class="progress-bar bg-danger" style="width: 90%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php require_once view('dashboard/bottom.dashboard'); ?>

    <?php require_once view('dashboard/footer.dashboard'); ?>
</body>
</html>