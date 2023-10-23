<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class=" row">
    <div class="col-md-12 col-12">
        <div class="row">
            <!-- List Object -->
            <div class="col-12" id="list-rg-col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">List Package</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive overflow-auto" id="table-user">
                            <table class="table table-hover mb-0 table-lg">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Capacity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-data">
                                    <?php if (isset($data)) : ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($data as $item) : ?>
                                            <tr>
                                                <td><?= esc($i); ?></td>
                                                <td><?= esc($item['name']); ?></td>
                                                <td><?= esc($item['type_name']); ?></td>
                                                <td><?= 'Rp ' . number_format(esc($item['price']), 0, ',', '.'); ?></td>
                                                <td><?= esc($item['min_capacity']); ?> orang</td>
                                                <td>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-outline-primary mx-1" href="<?=base_url('web/package/').$item['id']; ?>">
                                                        <i class="fa-solid fa-circle-info"></i>
                                                    </a>
                                                </td>
                                                <?php $i++ ?>
                                            </tr>
                                        <?php endforeach; ?>
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