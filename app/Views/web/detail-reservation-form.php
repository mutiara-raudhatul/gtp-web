<?php
$uri = service('uri')->getSegments();
$edit = in_array('edit', $uri);
$addhome = in_array('addhome', $uri);
?>

<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>
                <?php if(session()->has('warning')) : ?>
                    <script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Wait!',
                            text: '<?= session('warning') ?>',
                        });
                    </script>
                <?php endif; ?>
                <?php if(session()->has('success')) : ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '<?= session('success') ?>',
                        });
                    </script>
                <?php endif; ?>
                <?php if(session()->has('failed')) : ?>
                    <script>
                        Swal.fire({
                            icon: 'danger',
                            title: 'Oops!',
                            text: '<?= session('failed') ?>',
                        });
                    </script>
                <?php endif; ?>

        <!-- Object Detail Information -->
        <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Reservation Package</h4>
                    </div>
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Package Name</td>
                                            <td><?= esc($data_package['name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Type</td>
                                            <td><?= esc($data_package['type_name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Request Date</td>
                                            <?php $request_date = strtotime($detail['request_date']); ?>
                                            <td><?= esc(date('l, j F Y H:i:s', $request_date)); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Check In</td>
                                            <?php $check_in = strtotime($detail['check_in']); ?>
                                            <td><?= esc(date('l, j F Y H:i:s', $check_in)); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Check Out</td>
                                            <?php $check_out = strtotime($detail['check_out']); ?>
                                            <td><?= esc(date('l, j F Y H:i:s', $check_out)); ?></td>
                                        </tr>
                                        <tr>
                                        <td class="fw-bold">Total People</td>
                                            <td><?= esc($detail['total_people']); ?> orang</td>
                                         </tr>
                                        <tr>
                                            <td class="fw-bold">Price</td>
                                            <td><?= 'Rp ' . number_format(esc($data_package['price']), 0, ',', '.'); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Total Price Package</td>
                                            <?php 
                                                $jumlah_package = ceil($detail['total_people']/$data_package['min_capacity']);
                                                $total_price_package = $jumlah_package*$data_package['price'];
                                            ?>
                                            <td><?= 'Rp ' . number_format(esc($total_price_package), 0, ',', '.'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="fw-bold">Description </p>
                                <p><?= esc($data_package['description']);?></p>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="fw-bold">Service Include <br>
                                <?php foreach ($serviceinclude as $ls) : ?>
                                    <li><?= esc($ls['name']);?></li>
                                <?php endforeach; ?>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="fw-bold">Service Exclude</p>
                                <?php foreach ($serviceexclude as $ls) : ?>
                                    <li><?= esc($ls['name']);?></li>
                                <?php endforeach; ?>
                                <br> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="fw-bold">Activity</p>
                                <?php foreach ($day as $d) : ?>
                                    <b>Day <?= esc($d['day']);?></b><br> 
                                    <?php foreach ($activity as $ac) : ?>
                                        <?php if($d['day']==$ac['day']): ?>
                                            <?= esc($ac['activity']);?>. <?= esc($ac['name']);?> : <?= esc($ac['description']);?> <br>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                                <br> 
                            </div>
                        </div>

                    </div>
                </div>
        </div>

            <div class="col-md-6 col-12" >
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center"><?= $title; ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if($addhome && $detail['status']==null): ?>
                        <div class="col-auto ">
                            <br>
                            <div class="btn-group float-right" role="group">
                                <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#unitHomestayModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> Add Unit Homestay</button>
                            </div>
                        </div>
                        <?php endif; ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Homestay</th>
                                    <th>Capacity</th>
                                    <th>Price</th>
                                    <?php if($addhome && $detail['status']==null): ?>
                                    <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>

                            <?php if (isset($booking)) : ?> 
                                <?php $i = 1; ?>                     
                                <?php foreach ($booking as $dtb) : ?>
                                    <tr>
                                        <td><?= esc($i++); ?></td>
                                        <td>[<?= esc($dtb['name']); ?>] <?= esc($dtb['name_type']); ?> <?= esc($dtb['unit_number']); ?> <?= esc($dtb['nama_unit']); ?></td>
                                        <td><?= esc($dtb['capacity']); ?></td>
                                        <td><?= esc($dtb['price']); ?></td>
                                        <?php if($addhome): ?>
                                        <td> 
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <form action="<?= base_url('web/detailreservation/deleteunit/').$dtb['date']; ?>" method="post" class="d-inline">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="date" value="<?= esc($dtb['date']); ?>">
                                                    <input type="hidden" name="homestay_id" value="<?= esc($dtb['homestay_id']); ?>">
                                                    <input type="hidden" name="unit_type" value="<?= esc($dtb['unit_type']); ?>">
                                                    <input type="hidden" name="unit_number" value="<?= esc($dtb['unit_number']); ?>">
                                                    <input type="hidden" name="reservation_id" value="<?= esc($dtb['reservation_id']); ?>">
                                                    <input type="hidden" name="description" value="<?= esc($dtb['description']); ?>">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-sm" onclick="return confirm('apakah anda yakin akan menghapus?');"><i class="fa fa-remove" aria-hidden="true"></i></button>
                                                </form>
                                            </div>
                                        </td> 
                                        <?php endif; ?>
                                    </tr>              
                                <?php endforeach; ?>
                            <?php endif; ?>
                                    <tr>
                                        <td>Total Price Homestay </td>
                                        <td>:   <?= 'Rp' . number_format(esc($price_home), 0, ',', '.'); ?></td>

                                    </tr>
                            </tbody>
                        </table>

                    <!-- modal add unit homestay -->
                        <div class="modal fade" id="unitHomestayModal" tabindex="-1" aria-labelledby="unitHomestayModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="unitHomestayModalLabel">Unit Homestay</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="row g-3" action="<?= base_url('web/detailreservation/create'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="card-header">
                                            <?php @csrf_field(); ?>
                                            <div class="row g-4">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="reservation_id">Reservation</label>
                                                        <input type="text" class="form-control" id="reservation_id" name="reservation_id" readonly value="<?= esc($detail['id']) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="pk_unit">Unit Homestay</label>
                                                    <select class="form-select" name="pk_unit" required>
                                                        <?php foreach ($list_unit as $item => $keyy) : ?>
                                                            <option value="<?= esc($keyy['homestay_id']); ?>-<?= esc($keyy['unit_type']); ?>-<?= esc($keyy['unit_number']); ?>"> 
                                                                [<?= esc($keyy['name']); ?>] <?= esc($keyy['name_type']); ?> <?= esc($keyy['unit_number']); ?> <?= esc($keyy['nama_unit']); ?>
                                                            </option>                                                                
                                                        <?php endforeach; ?>                                                    
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                        <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                        <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    <!-- end modal add unit homestay -->


                        <div class="col-auto">
                            <a href="<?= base_url('/web/reservation'); ?>" class="btn btn-outline-success float-end"><i class="fa-solid fa-check me-3"></i>Done</a>
                        </div>
                    </div>
                </div>

            </div>


            <!-- payment -->
            <div class="col-md-12 col-12" >
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Payment</h4>
                        <?php if (in_groups(['admin'])) : ?>
                            <div class="col-auto">
                                <a href="<?= base_url('dashboard/detailreservation/confirm'); ?>/<?= esc($detail['id']); ?>" class="btn btn-primary"><i class="fa-solid fa-envelope me-3"></i>Confirmation</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div>
                            <table class="col-12">
                                <tbody>
                                    <tr>
                                        <td><b>Total Reservation</b></td>
                                        <td><b>:   <?= 'Rp' . number_format(esc($detail['total_price']), 0, ',', '.'); ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><b>Deposit Reservation</b></td>
                                        <td><b>:   <?= 'Rp' . number_format(esc($detail['deposit']), 0, ',', '.'); ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><hr> </td>
                                        <td><hr> </td>
                                    </tr>

                                    <tr>
                                        <td> Status  : 
                                        <?php if($detail['status']==null): ?>    
                                                <i class="fa fa-clock btn-sm btn-secondary btn-circle"></i> Waiting</td>
                                            <?php elseif($detail['status']==1): ?>    
                                                <i class="fa fa-check btn-sm btn-success btn-circle"></i> Accepted</td>
                                            <?php elseif($detail['status']==0): ?>    
                                                <i class="fa fa-times btn-sm btn-danger btn-circle"></i> Rejected</td>
                                            <?php endif; ?>                                      
                                    </tr>
    
                                    <tr>
                                        <?php if($detail['status']=='1' || $detail['status']=='0'): ?> 
                                            <td> Confirmation Date :
                                                 <?= esc(date('l, j F Y H:i:s', strtotime($detail['confirmation_date']))); ?></td> 
                                        <?php endif; ?>   
                                    </tr>
                                    <tr>
                                        <?php if($detail['status']=='1' || $detail['status']=='0'): ?> 
                                            <td> Comment :
                                                 <?= esc($detail['comment']); ?></td> 
                                        <?php endif; ?>   
                                    </tr>
                                    <tr>
                                        <td><br><hr></td>
                                        <td><br><hr></td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <?php if($detail['status']=='1'): ?> 
                                            <p> Pembayaran melalui 
                                                <ul>
                                                    <li>Bank Syariah Mandiri (BSI) - Kode 451</li>
                                                    <li>Nomor rekening:  73492379</li>
                                                    <li>Atas nama: Green Talao Park</li>
                                                </ul>
                                            </p>
                                        <?php endif; ?>  
                                        </td> 
                                    </tr>
                                    <tr>
                                        <?php if($detail['proof_of_deposit']!=null): ?> 
                                            <td>Deposit Payment
                                            : <?= esc(date('l, j F Y H:i:s', strtotime($detail['deposit_date']))); ?><td>
                                        <?php endif; ?>   

                                        <?php if($detail['proof_of_deposit']!=null): ?> 
                                            <?php if($detail['proof_of_payment']!=null): ?> 
                                                <td>Full Payment Reservation
                                                : <?= esc(date('l, j F Y H:i:s', strtotime($detail['payment_date']))); ?></td>                                                
                                            <?php endif; ?>   
                                        <?php endif; ?>   
                                    </tr>
                                </tbody>
                            </table>
                            
                            <table class="col-12">
                                <tbody>
                                <tr>
                                    <!-- upload proof payment -->

                                    <td class="col-md-5 col-12">
                                        <?php if ($detail['status']=='1'):
                                            if($detail['proof_of_deposit']==null): ?>
                                            <form class="form form-vertical" action="<?= base_url('web/reservation/uploaddeposit/').$detail['id']; ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                                <div class="form-body">
                                                <div class="col-md-5 col-12">
                                                        <div class="form-group mb-4">
                                                                <label for="proof_of_deposit" class="form-label">Proof of Deposit</label>
                                                                <input class="form-control" accept="image/*" type="file" name="proof_of_deposit" id="proof_of_deposit" required>
                                                        </div>
                                                        </div>
                                                        <div col="col-md-5 col-12">
                                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php else: ?>
                                                <div class="col-md-5 col-12">
                                                    <div class="form-group">
                                                        <div class="text-md-start mb-3" id="deposit-container">
                                                                <div class="row gallery" data-bs-toggle="modal" data-bs-target="#galleryModal">
                                                                        <img class="w-100 active" src="<?= base_url('media/photos/deposit/'); ?><?= $detail['proof_of_deposit'] ?>" data-bs-target="#Gallerycarousel" />
                                                                </div>
                                                            <!-- modal deposit-->
                                                            <div class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="galleryModalTitle" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="galleryModalTitle">
                                                                                Proof of Deposit
                                                                            </h5>
                                                                            
                                                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                                <i data-feather="x"></i>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div id="Gallerycarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                                                                                <div class="carousel-indicators">
                                                                                        <button type="button" data-bs-target="#Gallerycarousel" data-bs-slide-to="<?= esc($i=1); ?>" class="<?= ($i == 0) ? 'active' : ''; ?>"></button>
                                                                                </div>
                                                                                <div class="carousel-inner">
                                                                                    <?php $i = 0; ?>
                                                                                        <div class="carousel-item<?= ($i == 0) ? ' active' : ''; ?>">
                                                                                            <img class="d-block w-100" src="<?= base_url('media/photos/deposit/'); ?><?= $detail['proof_of_deposit'] ?>">
                                                                                        </div>
                                                                                </div>
                                                                                <a class="carousel-control-prev" href="#Gallerycarousel" role="button" type="button" data-bs-slide="prev">
                                                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                                </a>
                                                                                <a class="carousel-control-next" href="#Gallerycarousel" role="button" data-bs-slide="next">
                                                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                                </a>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                                Close
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; 
                                        endif; ?>
                                    </td>

                                    <!-- upload proof payment -->
                                    <td class="col-md-5 col-12">
                                        <?php if ($detail['status']=='1'):
                                            if($detail['proof_of_deposit']!=null): 
                                                if($detail['proof_of_payment']==null): ?>                                        
                                                    <form class="form form-vertical" action="<?= base_url('web/reservation/uploadfullpayment/').$detail['id']; ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                                        <div class="form-body">
                                                        <div class="col-md-5 col-12">
                                                                    <div class="form-group mb-4">
                                                                        <label for="proof_of_payment" class="form-label">  Proof of Full Payment</label>
                                                                        <input class="form-control" accept="image/*" type="file" name="proof_of_payment" id="proof_of_payment">
                                                                    </div>
                                                                </div>
                                                                <div col="col-md-5 col-12">
                                                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                        <?php else: ?>
                                            <div class="col-md-5 col-12">
                                                <div class="form-group">
                                                    <div class="text-md-start mb-3" id="deposit-container">
                                                            <div class="row gallery" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                                                    <img class="w-100 active" src="<?= base_url('media/photos/fullpayment/'); ?><?= $detail['proof_of_payment'] ?>" data-bs-target="#Gallerycarousel" />
                                                            </div>
                                                        <!-- modal payment -->
                                                        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalTitle" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="gpaymentModalTitle">
                                                                            Proof of Payment
                                                                        </h5>
                                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                            <i data-feather="x"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div id="Gallerycarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                                                                            <div class="carousel-indicators">
                                                                                    <button type="button" data-bs-target="#Gallerycarousel" data-bs-slide-to="<?= esc($i=1); ?>" class="<?= ($i == 0) ? 'active' : ''; ?>"></button>
                                                                            </div>
                                                                            <div class="carousel-inner">
                                                                                <?php $i = 0; ?>
                                                                                    <div class="carousel-item<?= ($i == 0) ? ' active' : ''; ?>">
                                                                                        <img class="d-block w-100" src="<?= base_url('media/photos/fullpayment/'); ?><?= $detail['proof_of_payment'] ?>">
                                                                                    </div>
                                                                            </div>
                                                                            <a class="carousel-control-prev" href="#Gallerycarousel" role="button" type="button" data-bs-slide="prev">
                                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                            </a>
                                                                            <a class="carousel-control-next" href="#Gallerycarousel" role="button" data-bs-slide="next">
                                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                            </a>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                            Close
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        <?php 
                                            endif;
                                            endif;
                                            endif; ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
    </div>            

</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="<?= base_url('assets/js/extensions/form-element-select.js'); ?>"></script>

<?= $this->endSection() ?>