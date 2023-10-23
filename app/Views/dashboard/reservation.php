<?php
$uri = service('uri')->getSegments();
$users = in_array('users', $uri);
?>

<?= $this->extend('dashboard/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class=" row">
    <div class="col-md-12">
        <div class="row">
            <!-- List Reservation -->
            <div class="col-12" id="list-rg-col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center"><?= esc($title); ?></h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover dt-head-center" id="table-manage">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Costumer</th>
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
                                                <td><?= esc($item['username']); ?></td>
                                                <td><?= esc($item['name']); ?></td>
                                                <td><?= date('d F Y, h:i:s A', strtotime($item['request_date'])); ?></td>
                                                <td><?= date('d F Y, h:i:s A', strtotime($item['check_in'])); ?></td>
                                                <td><?= date('d F Y, h:i:s A', strtotime($item['check_out'])); ?></td>
                                                <td>
                                                    <?php $date = date('Y-m-d H:i');
                                                           if($item['status']==null): ?>    
                                                        <a href="#" class="btn-sm btn-secondary float-center"><i class="fa-solid fa-clock"></i></a>
                                                        <i>Menunggu konfirmasi</i>
                                                    <?php elseif($item['status']==1): ?>    
                                                        <a href="#" class="btn-sm btn-success float-center"><i class="fa-solid fa-check"></i></a>
                                                        <?php if($item['proof_of_deposit']==null) :?>
                                                            <i>Belum bayar deposit</i>
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
<script>
    $(document).ready(function() {
        $('#table-manage').DataTable({
            columnDefs: [{
                targets: ['_all'],
                className: 'dt-head-center'
            }],
            lengthMenu: [5, 10, 20, 50, 100]
        });
    });
</script>
<?= $this->endSection() ?>