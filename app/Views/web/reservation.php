<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">

    <?php if(session()->has('success')) : ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session('success') ?>',
            });
        </script>
    <?php endif; ?>

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
                                                <td><?= esc($item['name']);?></td>
                                                <td><?= date('d F Y, H:i:s', strtotime($item['request_date'])); ?></td>
                                                <td><?= date('d F Y, H:i:s', strtotime($item['check_in'])); ?></td>
                                                <td>
                                                    <?php $date = date('Y-m-d H:i');?>
                                                    <?php if($item['status']==null && $item['confirmation_date']==null && $item['account_refund']==null ): ?>    
                                                        <a href="#" class="btn-sm btn-secondary float-center"><i class="fa-solid fa-clock"></i></a>
                                                        <i>Menunggu konfirmasi</i>
                                                    <?php elseif($item['status']==null && $item['confirmation_date']!=null && $item['account_refund']==null ): ?>    
                                                        <a href="#" class="btn-sm btn-secondary float-center"><i class="fa-solid fa-cancel"></i></a>
                                                        <i>Cancel</i>
                                                    <?php elseif($item['status']==null && $item['confirmation_date']!=null && $item['account_refund']!=null ): ?>    
                                                        <a href="#" class="btn-sm btn-secondary float-center"><i class="fa-solid fa-cancel"></i></a>
                                                        <i>Cancel & refund</i>
                                                    <?php elseif($item['status']==1): ?>    
                                                        <a href="#" class="btn-sm btn-success float-center"><i class="fa-solid fa-check"></i></a>
                                                        <?php if($item['proof_of_deposit']==null) :?>
                                                            <i>Silakan bayar deposit</i>
                                                        <?php elseif($item['proof_of_payment']==null): ?>
                                                            <i>Sisa pembayaran belum</i>
                                                        <?php elseif($item['proof_of_payment']!=null ):  ?>
                                                            <?php if($item['review']==null): ?>
                                                                <i>Belum direview</i>
                                                            <?php else: ?>
                                                                <i>Done</i>
                                                            <?php endif; ?>        
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
                                                        <?php if($item['status']==null): ?>  
                                                            <form action="<?= base_url('web/reservation/delete/').$item['id']; ?>" method="post" class="d-inline">
                                                                <?= csrf_field(); ?>
                                                                <input type="hidden" name="id" value="<?= esc($item['id']); ?>">
                                                                <input type="hidden" name="package_id" value="<?= esc($item['package_id']); ?>">
                                                                <input type="hidden" name="user_id" value="<?= esc($item['user_id']); ?>">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <button type="submit" class="btn btn-danger" onclick="return confirm('apakah anda yakin akan menghapus?');"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                            </form>

                                                        <?php else: ?>
                                                            <button type="submit" class="btn btn-secondary" onclick="return alert('Data ini tidak dapat dihapus');"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                        <?php endif ?>
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