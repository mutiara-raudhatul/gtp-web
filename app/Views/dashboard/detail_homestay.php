<?= $this->extend('dashboard/layouts/main'); ?>

<?= $this->section('content') ?>
<style>
    .rating {
        display: inline-block;
        font-size: 25px;
    }
    
    .rating {
        color: orange;
    }

    .rating2 {
        display: inline-block;
        font-size: 25px;
    }
    
    .rating2 {
        color: grey;
    }
</style>
<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>

        <!-- Object Detail Information -->
        <div class="col-md-7 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title text-center">Homestay Information</h4>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('dashboard/homestay'); ?>/<?= esc($data['id']); ?>/edit" class="btn btn-primary float-end"><i class="fa-solid fa-pencil me-3"></i>Edit</a>
                        </div>
                    </div>
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
                                        <h5 class="card-title"> <?= esc($item['name_type']); ?> <?= esc($item['nama_unit']); ?> <?= esc($item['unit_number']); ?></h5>
                                        <div class="rating text-center ">
                                            <?php foreach($rating as $dt => $rate): ?>
                                                <?php if(empty($rate['rating'])): ?>
                                                    <i name="rating" class="far fa-star"></i>
                                                    <i name="rating" class="far fa-star"></i>
                                                    <i name="rating" class="far fa-star"></i>
                                                    <i name="rating" class="far fa-star"></i>
                                                    <i name="rating" class="far fa-star"></i>
                                                <?php endif; ?>   
                                            <?php endforeach; ?> 

                                            <?php foreach($rating as $rate): ?>
                                                <?php if($rate['unit_number']==$item['unit_number'] && $rate['homestay_id']==$item['homestay_id'] && $rate['unit_type']==$item['unit_type']): ?>
                                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                        <?php if ($i <= $rate['rating']) : ?>
                                                            <i name="rating" class="fas fa-star"></i>
                                                        <?php else: ?>
                                                            <i name="rating" class="far fa-star"></i>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <br><br>
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
                                        <?php foreach ($review as $dt) : ?>
                                            <?php foreach ($dt as $value => $d) : ?>
                                                <?php if($d['unit_number']==$item['unit_number'] && $d['homestay_id']==$item['homestay_id'] && $d['unit_type']==$item['unit_type']): ?>
                                                    <p class="d-inline-flex gap-1">
                                                        <button class="btn btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                            <i class="fa fa-comments"></i>
                                                        </button>
                                                    </p>
                                                    <div class="collapse" id="collapseExample">
                                                        <div class="card card-body">
                                                            <strong>@<?= esc($d['username']) ?></strong>
                                                            <div>Rating  :
                                                                <div class="rating2 text-center ">
                                                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                                        <?php if ($i <= $d['rating']) : ?>
                                                                            <i name="rating2" class="fas fa-star"></i>
                                                                        <?php else: ?>
                                                                            <i name="rating2" class="far fa-star"></i>
                                                                        <?php endif; ?>
                                                                    <?php endfor; ?>
                                                                </div>
                                                            </div>
                                                            <div>Review  : <?= esc($d['review']) ?></div>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
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

                <?= $this->include('web/layouts/map-body'); ?>
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