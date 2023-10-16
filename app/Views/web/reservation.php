<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class=" row">
    <div class="col-md-12">
        <div class="row">
            <!-- List Reservation -->
            <div class="col-12" id="list-rg-col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">List Reservation</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-auto">
                            <a href="<?= current_url(); ?>/new" class="btn btn-primary float-end"><i class="fa-solid fa-plus me-3"></i>New Reservation</a>
                        </div>
                        <br><br>
                        <div class="table-responsive overflow-auto" id="table-user">
                            <script>
                                clearMarker();
                                clearRadius();
                                clearRoute();
                            </script>
                            <table class="table table-hover mb-0 table-lg">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Package Name</th>
                                        <th>Request Date</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-data">
                                    <?php if (isset($data)) : ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($data as $item) :?>
                                            <tr>
                                                <td><?= esc($i); ?></td>
                                                <td><?= esc($item['name']); ?></td>
                                                <td><?= date('d F Y, h:i:s A', strtotime($item['request_date'])); ?></td>
                                                <td><?= date('d F Y, h:i:s A', strtotime($item['check_in'])); ?></td>
                                                <td><?= date('d F Y, h:i:s A', strtotime($item['check_out'])); ?></td>
                                                <td><?= esc($item['status']); ?></td>
                                                <td>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-outline-primary mx-1" href="<?=base_url('web/detailreservation/').$item['id']; ?>">
                                                        <i class="fa-solid fa-circle-info"></i>
                                                    </a>
                                                </td>
                                                <?php $i++ ?>
                                            </tr>
                                        <?php endforeach; ?>
                                        <script>
                                            boundToObject();
                                        </script>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<?= $this->endSection() ?>