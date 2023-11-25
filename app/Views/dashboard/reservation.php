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
                                        <th>ID</th>
                                        <th>Costumer</th>
                                        <th>Package Name</th>
                                        <th>Request Date</th>
                                        <th>Check In</th>
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
                                                <td><?= esc($item['id']); ?></td>
                                                <td><?= esc($item['username']); ?></td>
                                                <td><?= esc($item['name']); ?></td>
                                                <td><?= date('d F Y, h:i:s A', strtotime($item['request_date'])); ?></td>
                                                <td><?= date('d F Y, h:i:s A', strtotime($item['check_in'])); ?></td>
                                                <td>
                                                    <?php $date = date('Y-m-d H:i');?>
                                                    <?php if($item['status']==null ): ?>    
                                                        <?php if($item['custom']=='1' ): ?>
                                                            <?php if($item['response']==null ): ?>
                                                                <a href="#" class="btn-sm btn-warning float-center"><i>Negotiate</i></a>
                                                            <?php elseif($item['response']!=null ): ?>
                                                                <a href="#" class="btn-sm btn-warning float-center"><i>Waiting</i></a>
                                                            <?php endif; ?>
                                                        <?php elseif($item['custom']!='1' ): ?>
                                                            <a href="#" class="btn-sm btn-warning float-center"><i>Waiting</i></a>
                                                        <?php endif; ?>
                                                    <?php elseif($item['status']=='1' ): ?>    
                                                        <?php if($item['cancel']=='0'): ?>
                                                            <?php if($item['proof_of_deposit']==null) :?>
                                                                <a href="#" class="btn-sm btn-info float-center"><i>Pay deposit</i></a>
                                                        
                                                            <?php elseif($item['proof_of_deposit']!=null && $item['proof_of_payment']==null): ?>
                                                                <a href="#" class="btn-sm btn-info float-center"><i>Pay in full</i></a>
                                                            
                                                            <?php elseif($item['proof_of_deposit']!=null && $item['proof_of_payment']!=null ):  ?>
                                                                <?php if($item['review']==null): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><i>Unreviewed</i></a>
                                                            
                                                                <?php else: ?>
                                                                    <a href="#" class="btn-sm btn-success float-center"><i>Done</i></a>
                                                                
                                                                <?php endif; ?>        
                                                            <?php endif; ?>
                                                        <?php elseif($item['cancel']=='1'): ?>
                                                            <?php if($item['account_refund']==null): ?>
                                                                <a href="#" class="btn-sm btn-secondary float-center"><i>Cancel</i></a>

                                                            <?php elseif($item['account_refund']!=null && $item['proof_refund']==null): ?>
                                                                <a href="#" class="btn-sm btn-secondary float-center"><i>Cancel & refund</i></a>

                                                            <?php elseif($item['account_refund']!=null && $item['proof_refund']!=null): ?>
                                                                <a href="#" class="btn-sm btn-danger float-center"><i>Refund</i></a>

                                                            <?php endif; ?>

                                                        <?php endif; ?>

                                                    <?php elseif($item['status']==0): ?>    
                                                        <a href="#" class="btn-sm btn-danger float-center"><i>Rejected</i></a>
                                                    
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Button Group">
                                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-outline-primary mx-1" href="<?=base_url('dashboard/detailreservation/confirm/').$item['id']; ?>">
                                                            <i class="fa-solid fa-circle-info"></i>
                                                        </a>
                                                        <a type="button" class="btn icon btn-outline-success mx-1" title="History" data-bs-toggle="modal" data-bs-target="#historyModal<?=esc($item['id'])?>" data-bs-whatever="@getbootstrap">
                                                            <i class="fa-solid fa-history"></i>
                                                        </a>
                                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Review" class="btn icon btn-outline-info mx-1" href="<?=base_url('dashboard/detailreservation/review/').$item['id']; ?>">
                                                            <i class="fa-solid fa-comments"></i>
                                                        </a>
                                                    </div>
                                                     <!-- Modal Detail -->
                                                    <div class="modal fade" id="historyModal<?=esc($item['id'])?>" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-md">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="historyModalLabel">History Reservation</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body" id="historyContent">
                                                                    <div class="col-12  order-md-first order-last">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <br>
                                                                                    <table>
                                                                                        <tr>
                                                                                            <td>Request </td>
                                                                                            <td>: <?= date('d F Y, h:i:s A', strtotime($item['request_date'])); ?></td>
                                                                                        </tr>
                                                                                        <?php if($item['status']!=null): ?>
                                                                                            <tr>
                                                                                                <?php if($item['status']==1): ?>
                                                                                                    <td>Accepted </td>
                                                                                                <?php elseif ($item['status']==0): ?>
                                                                                                    <td>Rejected </td>
                                                                                                <?php endif; ?>
                                                                                                <td>: <?= date('d F Y, h:i:s A', strtotime($item['confirmation_date'])); ?> (by adm<?= esc($item['admin_confirm']); ?>)</td>
                                                                                            </tr>
                                                                                        <?php endif; ?>
                                                                                        <?php if($item['proof_of_deposit']!=null): ?>
                                                                                            <tr>
                                                                                                <td>Deposit </td>
                                                                                                <td>: <?= date('d F Y, h:i:s A', strtotime($item['deposit_date'])); ?></td>
                                                                                            </tr>
                                                                                        <?php endif; ?>
                                                                                        <?php if($item['proof_of_payment']!=null): ?>
                                                                                            <tr>
                                                                                                <td>Full Payment </td>
                                                                                                <td>: <?= date('d F Y, h:i:s A', strtotime($item['payment_date'])); ?></td>
                                                                                            <tr>
                                                                                        <?php endif; ?>
                                                                                        <?php if($item['cancel']==1): ?>
                                                                                            <tr>
                                                                                                <td>Cancel </td>
                                                                                                <td>: <?= date('d F Y, h:i:s A', strtotime($item['cancel_date'])); ?></td>
                                                                                            <tr>
                                                                                        <?php endif; ?>
                                                                                        <?php if($item['proof_refund']!=null): ?>
                                                                                            <tr>
                                                                                                <td>Refund </td>
                                                                                                <td>: <?= date('d F Y, h:i:s A', strtotime($item['refund_date'])); ?> (by adm<?= esc($item['admin_refund']); ?>)</td>
                                                                                            <tr>
                                                                                        <?php endif; ?>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Tambahkan footer modal jika diperlukan -->
                                                            </div>
                                                        </div>
                                                    </div>
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