<?php
$uri = service('uri')->getSegments();
$edit = in_array('edit', $uri);
?>

<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('styles') ?>
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.css">
<link rel="stylesheet" href="<?= base_url('assets/css/pages/form-element-select.css'); ?>">
<style>
    .filepond--root {
        width: 100%;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>

        <!-- Object Detail Information -->
        <div class="col-md-6 col-12">
            <form class="form form-vertical" id="reservationForm" action="<?= ($edit) ? base_url('web/reservation/update') . '/' . $detail['id'] : base_url('web/reservation/create'); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Package</h4>
                    </div>
                    
                    <div class="card-body">
                        <?= csrf_field();  ?>
                        <div class="form-group">
                            <label for="package">Package</label>
                            <select id="package" name="package" class="form-control" required>
                                <option value="" selected>Select Package</option>
                                <?php foreach ($data as $item => $keyy) : ?>
                                    <?php if($edit): 
                                        if($detail['package_id']==$keyy['id']): ?>
                                            <option selected data-price="<?= esc($keyy['price']); ?>" data-day="<?= esc($keyy['days']); ?>" data-capacity="<?= esc($keyy['min_capacity']); ?>" value="<?= esc($detail['package_id']); ?>" ><?= esc($keyy['name']); ?></option>                                                                
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <option data-price="<?= esc($keyy['price']); ?>" data-day="<?= esc($keyy['days']); ?>" data-capacity="<?= esc($keyy['min_capacity']); ?>" value="<?= esc($keyy['id']); ?>" ><?= esc($keyy['name']); ?></option>                                                                
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            
                            </select>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-7">
                                <label for="check_in">Check-in</label>
                                <input type="date" id="check_in" name="check_in" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label for="check_in"></label>
                                <input type="time" id="time_check_in" name="time_check_in" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-7">
                                <label for="check_out">Check-out</label>
                                <input readonly type="date" id="check_out" name="check_out" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label for="check_out"></label>
                                <input readonly type="time" id="time_check_out" name="time_check_out" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="total_people">Total People</label>
                            <input type="number" id="total_people" name="total_people" value="<?= ($edit) ? $detail['total_people'] : old('total_people'); ?>" class="form-control" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="item">Package Order</label>
                            <input type="number" id="item" name="item" class="form-control" min="1" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price Package</label>
                            <input type="text" id="price" name="price" class="form-control" value="<?= ($edit) ? $keyy['price'] : old('price'); ?>" readonly>
                        </div>
                         <div class="form-group">
                            <label for="total_price">Total Price Package</label>
                            <input type="text" id="total_price" name="total_price" class="form-control" value="<?= ($edit) ? $detail['total_price'] : old('total_price'); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="deposit">Deposit</label>
                            <input type="text" id="deposit" name="deposit" class="form-control" value="<?= ($edit) ? $detail['deposit'] : old('deposit'); ?>" readonly>
                        </div>
                        <!-- <div class="form-group" id="homestayGroup" style="display: none;">
                            <input type="checkbox" id="agreeCheckbox" name="agreeCheckbox" required>
                            <label for="agreeCheckbox"><i>Untuk paket wisata ini akan diarahkan memesan homestay</i></label>
                        </div> -->
                        <?php if(!$edit): ?>
                            <div class="float-end">
                                <button type="submit" class="btn btn-primary">Booking</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            
            </form>
        </div>

        <?php if($edit): ?>
            <div class="col-md-6 col-12" id="homestayGroup" >
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
                                <?php foreach ($booking as $dtb) : ?>
                                    <?php $i = 1; ?>                     
                                    <tr>
                                        <td><?= esc($i++); ?></td>
                                        <td>[<?= esc($dtb['name']); ?>] <?= esc($dtb['name_type']); ?> <?= esc($dtb['unit_number']); ?> <?= esc($dtb['nama_unit']); ?></td>
                                        <td><?= esc($dtb['capacity']); ?></td>
                                        <td><?= esc($dtb['price']); ?></td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <form action="<?= base_url('web/detailreservation/delete/').$dtb['date']; ?>" method="post" class="d-inline">
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
                            </tbody>
                        </table>

                        <div>
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Total Price Homestay </td>
                                        <td>:   <?= 'Rp' . number_format(esc($price_home), 0, ',', '.'); ?></td>

                                    </tr>
                                    <tr>
                                        <td><hr> </td>
                                        <td><hr> </td>
                                    </tr>
                                    <tr>
                                        <td><b>Total Reservation</b></td>
                                        <td><b>:   <?= 'Rp' . number_format(esc($detail['total_price']), 0, ',', '.'); ?></b></td>
                                    </tr>
                                    <tr>
                                        <td><b>Deposit Reservation</b></td>
                                        <td><b>:   <?= 'Rp' . number_format(esc($detail['deposit']), 0, ',', '.'); ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


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
                                                        <option value="" selected>Select Unit Homestay</option>
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
        <?php endif; ?>
    </div>

<!--!!!!!!!!!!!! ini homestaynya bisa muncul button, muncul pas untuk edit aja !!!!!!!!!!!-->
            
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
        $(document).ready(function () {
            $('#package').change(function () {
                calculateTotalPrice();
            });

            $('#total_people').change(function () {
                calculateTotalPrice();
            });

            $('#check_in, #time_check_in').change(function () {
                calculateCheckOut();
            });

            $('#unit_homestay').change(function () {
                calculateHomestayTotalPrice();
            });

            $('#addUnitButton').click(function () {
                // Implement your logic for adding homestay units here
            });

            calculateTotalPrice();
            calculateCheckOut();
        });

        function calculateTotalPrice() {
            const package = $('#package option:selected');
            const price = parseFloat(package.data('price'));
            const capacity = parseInt(package.data('capacity'));
            const totalPeople = parseInt($('#total_people').val());
            $('#price').val(price);
            const numberOfPackages = Math.ceil(totalPeople/capacity);
            $('#item').val(numberOfPackages);

            if (totalPeople > capacity) {
                const numberOfPackages = Math.ceil(totalPeople/capacity);
                const totalPrice = price * numberOfPackages;
                $('#total_price').val(totalPrice);
                const deposit = totalPrice * 0.5;
                $('#deposit').val(deposit);
            } else {
                $('#total_price').val(price);
                const deposit = price * 0.5;
                $('#deposit').val(deposit);
            }

            // Show or hide the homestay unit selection based on the package day value
            const day = parseInt(package.data('day'));
            if (day > 1) {
                $('#homestayGroup').show();
            } else {
                $('#homestayGroup').hide();
            }
        }

        function calculateCheckOut() {
            const checkInDate = $('#check_in').val();
            const checkInTime = $('#time_check_in').val();
            const package = $('#package option:selected');
            const day = parseInt(package.data('day'));
            if (checkInDate && checkInTime) {
                const checkInDateTime = new Date(checkInDate + ' ' + checkInTime);
                const checkOutDateTime = new Date(checkInDateTime);
                checkOutDateTime.setDate(checkOutDateTime.getDate() + day);
                const checkOutDate = checkOutDateTime.toISOString().split('T')[0];
                const checkOutTime = checkOutDateTime.toTimeString().split(' ')[0];
                $('#check_out').val(checkOutDate);
                $('#time_check_out').val(checkOutTime);
            } else {
                $('#check_out').val('');
                $('#time_check_out').val('');
            }
        }

    </script>

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


