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
                        <div class="row">

                            <div class="col-auto">
                                <a href="<?= current_url(); ?>/new" class="btn btn-primary float-right"><i class="fa-solid fa-plus me-3"></i>New Reservation</a>
                            </div>
                            <div class="col-auto">
                                <form class="form form-vertical" id="customForm" action="<?= base_url('/web/detailreservation/addcustom'); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                    <?= csrf_field();  ?>
                                        <button type="submit" class="btn btn-secondary float-right"><i class="fa-solid fa-plus me-3"></i>Custom Package for Booking</button>
                                    <br>
                                </form>
                            </div>
                        </div>
                        <br><br>
                        <div class="table-responsive">
                            <table class="table table-hover dt-head-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Package Name</th>
                                        <th>Request Date</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        <th>Review</th>
                                    </tr>
                                </thead>
                                <tbody id="table-data">
                                    <?php if (isset($data)) : ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($data as $item) :?>
                                            <tr>
                                                <td><?= esc($i); ?></td>
                                                <td><?= esc($item['name']); ?></td>
                                                <td><?= date('d F Y, H:i:s', strtotime($item['request_date'])); ?></td>
                                                <td><?= date('d F Y, H:i:s', strtotime($item['check_in'])); ?></td>
                                                <td><?= date('d F Y, H:i:s', strtotime($item['check_out'])); ?></td>
                                                <td>
                                                    <?php $date = date('Y-m-d H:i');
                                                           if($item['status']==null): ?>    
                                                        <a href="#" class="btn-sm btn-secondary float-center"><i class="fa-solid fa-clock"></i></a>
                                                        <i>Menunggu konfirmasi</i>
                                                    <?php elseif($item['status']==1): ?>    
                                                        <a href="#" class="btn-sm btn-success float-center"><i class="fa-solid fa-check"></i></a>
                                                        <?php if($item['proof_of_deposit']==null) :?>
                                                            <i>Silakan bayar deposit</i>
                                                        <?php elseif($item['proof_of_payment']==null): ?>
                                                            <i>Sisa pembayaran belum</i>
                                                        <?php elseif($item['proof_of_payment']!=null && $item['check_out']<$date ):  ?>
                                                               <i>Done</i>
                                                        <?php endif; ?>
                                                    <?php elseif($item['status']==0): ?>    
                                                        <a href="#" class="btn-sm btn-danger float-center"><i class="fa-solid fa-times"></i></a>
                                                        <i>Reservasi ditolak</i>
                                                    <?php endif; ?>  
                                                </td>
                                                <td>
                                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-outline-primary mx-1" href="<?=base_url('web/detailreservation/').$item['id']; ?>">
                                                            <i class="fa-solid fa-circle-info"></i>
                                                        </a>
                                                </td>
                                                <td>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Review" class="btn icon btn-outline-info mx-1" href="<?=base_url('web/detailreservation/review/').$item['id']; ?>">
                                                        <i class="fa-solid fa-comments"></i>
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