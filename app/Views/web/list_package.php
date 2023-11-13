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
                        <div class="col-auto">
                            <?php if (logged_in()) : ?> <!-- Assuming `logged_in()` is a function that checks if the user is logged in -->
                                <form class="form form-vertical" id="customForm" action="<?= base_url('/web/detailreservation/addcustom'); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                    <?= csrf_field(); ?>
                                    <button type="submit" class="btn btn-secondary float-right"><i class="fa-solid fa-plus me-3"></i>Custom Package for Booking</button>
                                    <br>
                                </form>
                            <?php else : ?>
                                <button type="button" class="btn btn-secondary float-right" onclick="redirectToLogin()"><i class="fa-solid fa-plus me-3"></i>Custom Package for Booking</button>
                                <script>
                                    function redirectToLogin() {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'You are not logged in',
                                            text: 'Please log in to proceed.',
                                            confirmButtonText: 'OK',
                                        }).then(() => {
                                            // Optionally, redirect to the login page
                                            window.location.href = '<?= base_url('/login'); ?>';
                                        });
                                    }
                                </script>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-12" id="list-rg-col">
                            <?php if (isset($data)) : ?>
                                <?php foreach ($data as $item) : ?>
                                    <div class="card mb-3">
                                        <div class="row g-0">
                                        <div class="col-md-4" style="width: 250px; height: 250px; overflow: hidden;">
                                            <img src="<?= base_url('media/photos/package/' . esc($item['gallery'])); ?>" class="img-fluid rounded-start" alt="Gallery Image" style="object-fit: cover; width: 100%; height: 100%;">
                                        </div>

                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?= esc($item['name']); ?></h5>
                                                    <p class="card-text"><?= esc($item['type_name']); ?></p>
                                                    <p class="card-text"><?= 'Rp ' . number_format(esc($item['price']), 0, ',', '.'); ?></p>
                                                    <p class="card-text"><?= esc($item['min_capacity']); ?> orang</p>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-outline-primary" href="<?= base_url('web/package/') . $item['id']; ?>">
                                                        <i class="fa-solid fa-circle-info"></i> More Info
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
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