<?php
$uri = service('uri')->getSegments();
$edit = in_array('edit', $uri);
$addhome = in_array('addhome', $uri);
?>

<?= $this->extend('dashboard/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>
        
        <!-- Object Detail Information -->
        <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Reservation Package</h4>
                        <div class="col-auto">
                            <a href="<?= base_url('dashboard/package/edit'); ?>/<?= esc($detail['package_id']); ?>" class="btn btn-outline-primary"><i class="fa-solid fa-pencil me-3"></i>Edit Package</a>
                        </div>
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
                                            <td><?= esc(date('l, j F Y H:i:s', strtotime($check_out))); ?></td>
                                        </tr>
                                        <tr>
                                        <td class="fw-bold">Min Capacity</td>
                                            <td><?= esc($data_package['min_capacity']); ?> orang</td>
                                        </tr>
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
                                                $jumlah_package = floor($detail['total_people']/$data_package['min_capacity']);
                                                $tambahan =$detail['total_people']%$data_package['min_capacity'];

                                                if($tambahan!=0){
                                                    if ($tambahan <5){
                                                        $order= $jumlah_package+0.5;
                                                    } else {
                                                        $order= $jumlah_package+1;
                                                    }
                                                } else {
                                                    $order= $jumlah_package;
                                                }
                                                $total_price_package = $order*$data_package['price'];
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
                                <p class="fw-bold">Reservation Note </p>
                                <p><?= esc($detail['note']);?></p>
                                
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

        <?php if($dayhome>0): ?>
            <div class="col-md-6 col-12" >
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center"><?= $title; ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="col-auto ">
                            <br>
                            <div class="btn-group float-right" role="group">
                                <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#unitHomestayModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> Add Unit Homestay</button>
                            </div>
                        </div>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Homestay</th>
                                    <th>Capacity</th>
                                    <th>Price</th>
                                    <th>Actions</th>
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
                    </div>
                </div>

            </div>
        <?php endif;  ?>

            <!-- payment -->
            <div class="col-md-12 col-12" >
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Payment</h4>
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
                                            <?php if($detail['status']==null && $detail['confirmation_date']==null && $detail['account_refund']==null): ?>    
                                                <i class="fa fa-clock btn-sm btn-secondary btn-circle"></i> Waiting</td>
                                            <?php elseif($detail['status']==1 && $detail['cancel']==1  && $detail['account_refund']==null): ?>    
                                                <i class="fa fa-cancel btn-sm btn-secondary btn-circle"></i> Cancel</td>
                                            <?php elseif($detail['status']==1 && $detail['cancel']==1  && $detail['account_refund']!=null): ?>    
                                                <i class="fa fa-cancel btn-sm btn-secondary btn-circle"></i> Cancel and Refund</td>
                                            <?php elseif($detail['status']==1 && $detail['cancel']!=1): ?>    
                                                <i class="fa fa-check btn-sm btn-success btn-circle"></i> Accepted</td>
                                            <?php elseif($detail['status']==0): ?>    
                                                <i class="fa fa-times btn-sm btn-danger btn-circle"></i> Rejected</td>
                                            <?php endif; ?>                                      
                                    </tr>
                                    <tr>
                                        <?php if($detail['status']==null && $detail['confirmation_date']==null): ?> 
                                            <td> Confirmation Reservation: </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <form class="form form-vertical" action="<?= base_url('dashboard/detailreservation/saveconfirm/').$detail['id']; ?>" method="post" enctype="multipart/form-data">
                                                <div class="form-body">
                                                    <div class="col-md-5 col-12">
                                                        <div class="form-group mb-2">
                                                            <label>
                                                            <input type="radio" name="status" value="'0'" required>
                                                            <i class="fa fa-times"></i> Rejected
                                                            </label>
                                                            <label>
                                                            <input type="radio" name="status" value="1" required>
                                                            <i class="fa fa-check"></i> Accepted
                                                            </label>
                                                        </div>
                                                        <div class="form-group mb-2">
                                                            <label for="feedback" class="mb-2">Feedback</label>
                                                            <textarea class="form-control" id="feedback" name="feedback" placeholder="Isikan tanggapan terhadap reservasi" required rows="4"><?= ($edit) ? $data['feedback'] : old('feedback'); ?></textarea>
                                                        </div>
                                                        <div col="col-md-5 col-12">
                                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>   
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
                                            <td> Feedback :
                                                 <?= esc($detail['feedback']); ?></td> 
                                        <?php endif; ?>   
                                    </tr>
                                    <tr><td><br></td></tr>
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
                                    <!-- upload proof deposit -->
                                    <td class="col-md-5 col-12">
                                        <?php if ($detail['proof_of_deposit']!=null): ?>
                                            <div class="col-md-5 col-12">
                                                <div class="form-group">
                                                    <div class="text-md-start mb-3" id="deposit-container">
                                                        <div class="row gallery" data-bs-toggle="modal" data-bs-target="#galleryModal">
                                                                <b>Proof of Deposit</b>
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
                                        <?php endif; ?>
                                        <?php 
                                            $dateTime = new DateTime('now'); // Waktu sekarang
                                            $datenow = $dateTime->format('Y-m-d H:i:s'); 
                                        ?>
                                        <?php if($detail['status']=='1' && $detail['proof_of_deposit']==null && $detail['cancel']!=1 && $datenow<$batas_dp): ?>
                                            <p class="btn btn-sm btn-primary">Batas pembayaran deposit : <?= esc(date('l, j F Y H:i:s', strtotime($batas_dp)));  ?></p>
                                            <br>
                                            <u><b>Countdown</b></u>
                                            <br><i>Upload bukti pembayaran sebelum batas waktu, jika batas waktu habis, maka reservasi otomatis di cancel</i>
                                            <h5 id="countdown"></h5>
                                            <script>
                                                // Set tanggal target countdown (dalam timestamp UNIX)
                                                var targetDate = <?php echo strtotime($batas_dp); ?>;

                                                // Fungsi untuk memperbarui countdown setiap detik
                                                function updateCountdown() {
                                                    var currentDate = Math.floor(Date.now() / 1000);
                                                    var remainingSeconds = targetDate - currentDate;

                                                    if (remainingSeconds <= 0 && document.hasFocus()) {
                                                    // if (remainingSeconds <= 0) {
                                                        document.getElementById('countdown').innerHTML = "Sorry, the deposit payment time for the reservation has expired";
                                                        clearInterval(countdownInterval);
                                                        
                                                              // Lakukan reload halaman setelah countdown habis
                                                        setTimeout(function() {
                                                            location.reload();
                                                        }, 9000); // Reload halaman setelah 3 detik
                                                        
                                                             // Lakukan submit form otomatis
                                                        document.querySelector('#cancelform').submit();
                                                    } else {
                                                        var days = Math.floor(remainingSeconds / (24 * 60 * 60));
                                                        var hours = Math.floor((remainingSeconds % (24 * 60 * 60)) / (60 * 60));
                                                        var minutes = Math.floor((remainingSeconds % (60 * 60)) / 60);
                                                        var seconds = remainingSeconds % 60;

                                                        document.getElementById('countdown').innerHTML = days + " hari " + hours + " jam " + minutes + " menit " + seconds + " detik";
                                                    }
                                                }

                                                var countdownInterval = setInterval(updateCountdown, 1000);
                                            </script>
                                            
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

                                            <br>
                                            <p class="btn btn-secondary btn-sm"><i><b>Do you want to cancel? Cancel reservation can be made maximal H-3 check_in</b></i></p>
                                            <form class="form form-vertical" id="cancelform" action="<?= base_url('web/detailreservation/savecancel/').$detail['id']; ?>" method="post" enctype="multipart/form-data">
                                                <div class="form-body">
                                                    <div class="col-md-5 col-12">
                                                        <div class="form-group mb-2">
                                                            <label>
                                                            <input type="radio" name="cancel" value="1" required>
                                                            <i class="fa fa-check"></i> Yes
                                                            </label>
                                                        </div>
                                                        <div col="col-md-5 col-12">
                                                            <button type="submit" class="btn btn-secondary me-1 mb-1">Cancel Reservation</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php elseif ($detail['status']==1 && $detail['proof_of_deposit']==null && $detail['cancel']!=1 && $datenow>$batas_dp ): ?>
                                            <p class="btn btn-danger btn-sm"><i><b>Upps Sorry, the deposit payment time for the reservation has expired</b></i></p>
                                            <br>
                                            <p class="btn btn-secondary btn-sm"><i><b>Do you want to cancel? Cancel reservation can be made maximal H-3 check_in</b></i></p>
                                            <form class="form hidden form-vertical" id="cancelform" action="<?= base_url('web/detailreservation/savecancel/').$detail['id']; ?>" method="post" enctype="multipart/form-data">
                                                <div class="form-body">
                                                    <div class="col-md-5 col-12">
                                                        <div class="form-group mb-2">
                                                            <label>
                                                            <input type="radio" name="cancel" value="1" required>
                                                            <i class="fa fa-check"></i> Yes
                                                            </label>
                                                        </div>
                                                        <div col="col-md-5 col-12">
                                                            <button type="submit" class="btn btn-secondary me-1 mb-1">Cancel Reservation</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <script>
                                                window.onload = function() {
                                                    document.querySelector('#cancelform').submit();
                                                };
                                            </script>
                                        <?php endif; ?>
                                    </td>

                                    <!-- upload proof payment -->
                                    <td class="col-md-5 col-12">
                                        <?php if ($detail['status']=='1' && $detail['cancel']!='1'):
                                            if($detail['proof_of_deposit']!=null): 
                                                if($detail['proof_of_payment']==null): ?>                                        
                                                    <form class="form form-vertical" action="<?= base_url('web/reservation/uploadfullpayment/').$detail['id']; ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                                        <div class="form-body">
                                                        <div class="col-md-5 col-12">
                                                                    <div class="form-group mb-4">
                                                                        <label for="proof_of_payment" class="form-label">  Proof of Full Payment</label>
                                                                        <input class="form-control" accept="image/*" type="file" name="proof_of_payment" id="proof_of_payment" required>
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