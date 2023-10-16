<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>

        <!-- Object Detail Information -->
        <div class="col-md-7 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Homestay Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">Name</td>
                                        <td><?= esc($data['name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Address</td>
                                        <td><?= esc($data['address']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Contact Person</td>
                                        <td><?= esc($data['contact_person']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="fw-bold">Description</p>
                            <p><?= esc($data['description']);
                                ?></p>
                        </div>
                    </div>
                </div>

            </div>

            <div>
                <div class="card-header">
                    <h4 class="card-title text-center">Homestay Unit</h4>
                </div>
                
                <?php if (isset($unit)) : ?>                      
                    <div class="row sm-8">
                        <?php foreach ($unit as $item) : ?>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= esc($item['nama_unit']); ?></h5>
                                        <p class="card-text">
                                            Price : <?= 'Rp ' . number_format(esc($item['price']), 0, ',', '.'); ?> <br>
                                            Capacity : <?= esc($item['capacity']); ?> orang
                                        </p>
                                        <p class="card-text">Facility :
                                            <?php if (isset($facility)) : ?>                      
                                                <?php foreach ($facility as $dt_fc) : ?>
                                                    <?php foreach ($dt_fc as $dt) : ?>
                                                        <?php if ($dt['unit_homestay_id']==$item['id']): ?>
                                                        <li>
                                                             <?= esc($dt['name']); ?> (<?= esc($dt['description']); ?>)
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </p>
                                        <p class="card-text"><?= esc($item['description']); ?></p>
                                        <a href="#" class="btn btn-primary">Booking</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-5 col-12">
            <!-- Object Location on Map -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Google Maps</h5>
                </div>

                <?= $this->include('web/layouts/map-body-4'); ?>
                <script>
                    initMap(<?= esc($data['lat']); ?>, <?= esc($data['lng']); ?>)
                </script>
                <script>
                    objectMarker("<?= esc($data['id']); ?>", <?= esc($data['lat']); ?>, <?= esc($data['lng']); ?>);
                </script>
            </div>

            <!-- Object Media -->
            <?= $this->include('web/layouts/our_gallery'); ?>

        </div>
    </div>
</section>

<?= $this->endSection() ?>