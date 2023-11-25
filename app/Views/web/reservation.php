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
                                <a href="<?= base_url('/web/package'); ?>" class="btn btn-secondary float-right">
                                    <i class="fa-solid fa-plus me-3"></i>Custom Package for Booking
                                </a>
                                <br>
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
                                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-outline-primary mx-1" href="<?=base_url('web/detailreservation/').$item['id']; ?>">
                                                            <i class="fa-solid fa-circle-info"></i>
                                                        </a>
                                                        <a type="button" class="btn icon btn-outline-success mx-1" title="History" data-bs-toggle="modal" data-bs-target="#historyModal<?=esc($item['id'])?>" data-bs-whatever="@getbootstrap">
                                                            <i class="fa-solid fa-history"></i>
                                                        </a>
                                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Review" class="btn icon btn-outline-info mx-1" href="<?=base_url('web/detailreservation/review/').$item['id']; ?>">
                                                            <i class="fa-solid fa-comments"></i>
                                                        </a>
                                                    </div>
                                                    <?php if($item['status']==null): ?>  
                                                            <form action="<?= base_url('web/reservation/delete/').$item['id']; ?>" method="post" class="d-inline">
                                                                <?= csrf_field(); ?>
                                                                <input type="hidden" name="id" value="<?= esc($item['id']); ?>">
                                                                <input type="hidden" name="package_id" value="<?= esc($item['package_id']); ?>">
                                                                <input type="hidden" name="user_id" value="<?= esc($item['user_id']); ?>">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <button type="submit" class="btn icon btn-outline-danger" onclick="return confirm('apakah anda yakin akan menghapus?');"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                            </form>

                                                        <?php else: ?>
                                                            <button type="submit" class="btn icon btn-outline-secondary" onclick="return alert('Data ini tidak dapat dihapus');"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                        <?php endif ?>
                                                     <!-- Modal Detail -->
                                                    <div class="modal fade" id="historyModal<?=esc($item['id'])?>" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
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
                                                                                    <table class="col-12">
                                                                                        <tbody>
                                                                                            <?php 
                                                                                                $dateTime = new DateTime('now'); // Waktu sekarang
                                                                                                $datenow = $dateTime->format('Y-m-d H:i:s'); 
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td> Status  </td>
                                                                                                <td> : 
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
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <?php if($item['status']=='1' || $item['status']=='0'): ?> 
                                                                                                    <td><i class="fa fa-level-down" aria-hidden="true"></i> Confirmation Date</td>
                                                                                                    <td> : <?= esc(date('l, j F Y H:i:s', strtotime($item['confirmation_date']))); ?> (by adm<?= esc($item['admin_confirm']); ?>)</td> 
                                                                                                <?php endif; ?>   
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <?php if($item['status']=='1' || $item['status']=='0'): ?>  
                                                                                                    <td> Feedback admin about reservation</td>
                                                                                                    <td> : <?= esc($item['feedback']); ?> (by adm<?= esc($item['admin_confirm']); ?>)</td> 
                                                                                                <?php endif; ?>   
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <?php if($item['proof_of_deposit']!=null ): ?> 
                                                                                                    <td><i class="fa fa-level-down" aria-hidden="true"></i> Deposit Payment
                                                                                                            <td>
                                                                                                                : <?= esc(date('l, j F Y H:i:s', strtotime($item['deposit_date']))); ?> (by <?= esc(user()->username); ?>)
                                                                                                            </td>
                                                                                                            
                                                                                                    </td>
                                                                                                <?php endif; ?>  
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <?php if($item['proof_of_deposit']!=null): ?>
                                                                                                    <td>Status Deposit Payment
                                                                                                        <td>
                                                                                                            : 
                                                                                                            <?php if($item['deposit_check']==null ): ?> 
                                                                                                            We will check your proof of deposit 
                                                                                                            <?php elseif($item['deposit_check']==1 ): ?> 
                                                                                                            Thank you. The proof of deposit is correct
                                                                                                            <?php elseif($item['deposit_check']==0 ): ?> 
                                                                                                            Sorry. The proof of deposit is incorrect
                                                                                                            <?php endif; ?> 
                                                                                                            (by adm<?= esc($item['admin_confirm']); ?>)
                                                                                                        </td>
                                                                                                    </td>
                                                                                                <?php endif; ?>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <?php if($item['proof_of_payment']!=null): ?> 
                                                                                                    <td><i class="fa fa-level-down" aria-hidden="true"></i> Full Payment Reservation 
                                                                                                        <td>
                                                                                                            : <?= esc(date('l, j F Y H:i:s', strtotime($item['payment_date']))); ?> (by <?= esc(user()->username); ?>)
                                                                                                        </td>
                                                                                                    </td>                                                
                                                                                                <?php endif; ?>   
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <?php if($item['proof_of_payment']!=null): ?>
                                                                                                    <td>Status FullPayment
                                                                                                        <td>
                                                                                                            : 
                                                                                                            <?php if($item['payment_check']==null ): ?> 
                                                                                                            We will check your proof of payment 
                                                                                                            <?php elseif($item['payment_check']==1 ): ?> 
                                                                                                            Thank you. The proof of payment is correct
                                                                                                            <?php elseif($item['payment_check']==0 ): ?> 
                                                                                                            Sorry. The proof of payment is incorrect
                                                                                                            <?php endif; ?> 
                                                                                                            (by adm<?= esc($item['admin_confirm']); ?>)
                                                                                                        </td>
                                                                                                    </td>
                                                                                                <?php endif; ?>  
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <?php if($item['cancel_date']!=null): ?> 
                                                                                                    <td><i class="fa fa-level-down" aria-hidden="true"></i> Cancel Reservation 
                                                                                                        <td>
                                                                                                            : <?= esc(date('l, j F Y H:i:s', strtotime($item['cancel_date']))); ?> (by <?= esc(user()->username); ?>)
                                                                                                        </td>
                                                                                                    </td>                                                
                                                                                                <?php endif; ?>   
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <?php if($item['refund_date']!=null): ?> 
                                                                                                    <td><i class="fa fa-level-down" aria-hidden="true"></i> Refund Reservation 
                                                                                                        <td>
                                                                                                            : <?= esc(date('l, j F Y H:i:s', strtotime($item['refund_date']))); ?> (by adm<?= esc($item['admin_refund']); ?>)
                                                                                                        </td>
                                                                                                    </td>                                                
                                                                                                <?php endif; ?>   
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <?php if($item['refund_date']!=null): ?>
                                                                                                    <td>Status Refund
                                                                                                        <td>
                                                                                                            : 
                                                                                                            <?php if($item['refund_check']==null ): ?> 
                                                                                                            You must check the proof of refund 
                                                                                                            <?php elseif($item['refund_check']==1 ): ?> 
                                                                                                            Thank you. The proof of refund is correct
                                                                                                            <?php elseif($item['refund_check']==0 ): ?> 
                                                                                                            Sorry. The proof of refund is incorrect
                                                                                                            <?php endif; ?> 
                                                                                                            (by <?= esc(user()->username); ?>)
                                                                                                        </td>
                                                                                                    </td>
                                                                                                <?php endif; ?> 
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <?php if($item['review']!=null): ?> 
                                                                                                    <td><i class="fa fa-level-down" aria-hidden="true"></i> Reservation 
                                                                                                        <td>
                                                                                                            : You have finished your tour. Thank you for your review. See you on the next tour
                                                                                                        </td>
                                                                                                    </td>       
                                                                                                <?php elseif( $item['review']==null && $item['status']==1 && $item['cancel']==0): ?> 
                                                                                                    <td> Reservation 
                                                                                                        <td>
                                                                                                            : You have finished your tour. Please give your review.                                            </td>
                                                                                                    </td>                                          
                                                                                                <?php endif; ?>   
                                                                                            </tr>
                                                                                        </tbody>
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
<?= $this->endSection() ?>