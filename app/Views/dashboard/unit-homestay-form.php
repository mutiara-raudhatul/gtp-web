<?php
$uri = service('uri')->getSegments();
$edit = in_array('edit', $uri);
?>

<?= $this->extend('dashboard/layouts/main'); ?>

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


        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center"><?= $title; ?> <?= esc($data['name']) ?></h4>
                </div>
                <div class="card-body" >

                <!-- button modal -->
                    <div class="col-auto ">
                        <br>
                        <div class="btn-group float-right" role="group">
                            <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#unitHomestayModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> Unit Homestay</button>
                            <button type="button" class="btn btn-outline-info " data-bs-toggle="modal" data-bs-target="#facilityUnitModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> Facility Unit</button>
                            <button type="button" class="btn btn-outline-secondary " data-bs-toggle="modal" data-bs-target="#facilityModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> Facility</button>
                        </div>
                        <div class="btn-group float-end" role="group">
                            <a href="<?= base_url('dashboard/homestay'); ?>" class="btn btn-outline-success"><i class="fa fa-check"></i> Done</a>
                        </div>
                    </div>
                    <br>
                <!-- end button modal -->

                <!-- modal add unit homestay -->
                    <div class="modal fade" id="unitHomestayModal" tabindex="-1" aria-labelledby="unitHomestayModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="unitHomestayModalLabel">Unit Homestay</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form class="row g-3" action="<?= base_url('dashboard/unithomestay/createunit') . '/' . $homestay_id; ?>" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="card-header">
                                        <?php @csrf_field(); ?>
                                        <h5 class="card-title">Homestay <?= esc($data['name']) ?></h5>
                                        <div class="row g-4">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="homestay">Homestay</label>
                                                    <input type="text" class="form-control" id="homestay" name="homestay" placeholder="HOxxx" disabled value="<?= esc($data['id']) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label for="nama_unit">Unit Name</label>
                                                    <input type="text" class="form-control" id="nama_unit" name="nama_unit">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="capacity">Capacity</label>
                                                    <input type="number" class="form-control" id="capacity" name="capacity" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label for="price">Price</label>
                                                    <input type="number" class="form-control" id="price" name="price">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea name="description" id="description" class="form-control" cols="30" rows="10"></textarea>
                                                </div>
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
                
                <!-- modal add facility unit homestay -->
                    <div class="col-sm-2 float-end">
                        <div class="modal fade" id="facilityUnitModal" tabindex="-1" aria-labelledby="facilityUnitModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="facilityUnitModalLabel">Facility Unit</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="row g-3" action="<?= base_url('dashboard/unithomestay/createfacilityunit/').$data['id']; ?>" method="post" >
                                        <div class="modal-body">
                                            <div class="card-header">
                                                <?php @csrf_field(); ?>
                                                    <div class="row g-4">
                                                        <div class="col-md-12">
                                                            <label for="unit_homestay">Unit Homestay</label>
                                                            <select class="form-select" name="unit_homestay">
                                                                    <option selected disabled>Select Unit</option>
                                                                <?php foreach ($unit as $item => $keyy) : ?>
                                                                    <option value="<?= esc($keyy['id']); ?>">Unit <?= esc($keyy['nama_unit']); ?></option>                                                                
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div><br>
                                                    <div class="row g-4">
                                                        <div class="col-md-12">
                                                            <input hidden type="text" class="form-control" id="package" name="package" placeholder="Pxxxxx" disabled value="">
                                                            <label for="facility_unit_id">Facility Unit</label>
                                                            <select class="form-select" name="facility_unit_id">
                                                                    <option selected disabled>Select Facility</option>
                                                                    <?php foreach ($facility_unit as $t) : ?>
                                                                        <?php if ($edit) : ?>
                                                                            <option value="<?= esc($t['id']); ?>" <?= (esc($data['facility_unit_id']) == esc($t['id'])) ? 'selected' : ''; ?>><?= esc($t['type_name']); ?></option>
                                                                        <?php else : ?>
                                                                            <option value="<?= esc($t['id']); ?>"><?= esc($t['name']); ?></option>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div><br>
                                                    <div class="row g-4">
                                                        <div class="col-md-12">
                                                            <label for="description_facility">Description</label>
                                                            <input type="text" class="form-control" id="description_facility" name="description_facility">
                                                        </div>
                                                    </div><br>
                                            </div>
                                            <div class="modal-footer">
                                                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                                <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                                <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- end modal add facility unit homestay -->

                <!-- modal add data facility -->
                    <div class="modal fade" id="facilityModal" tabindex="-1" aria-labelledby="facilityModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="facilityModalLabel">Data Facility</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="row g-3" action="<?= ($edit) ? base_url('dashboard/unithomestay/createfacility'). '/' . $data['id'] : base_url('dashboard/unithomestay/createfacility'). '/' . $homestay_id; ?>" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="card-header">
                                            <?php @csrf_field(); ?>
                                            <div class="row g-4">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="facility_name">Facility Name</label>
                                                        <input type="text" class="form-control" id="facility_name" name="facility_name">
                                                    </div>
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
                <!-- end modal add data facility -->

                    <?php if (session()->getFlashdata('pesan')) : ?>
                        <div class="alert alert-success col-sm-12 mx-auto" role="alert">
                            <?= session()->getFlashdata('pesan'); ?>
                        </div>
                    <?php endif;  ?>

                    <?php if (isset($unit)) : ?>                      
                        <div class="row sm-4">
                            <?php foreach ($unit as $itemunit) : ?>
                                <div class="col-sm-6">
                                    <div class="card border border-primary-subtle p-2 mb-2">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= esc($itemunit['nama_unit']); ?></h5>
                                            <p class="card-text">
                                                Price : <?= 'Rp ' . number_format(esc($itemunit['price']), 0, ',', '.'); ?> <br>
                                                Capacity : <?= esc($itemunit['capacity']); ?> orang <br>
                                                <?= esc($itemunit['description']); ?>
                                            </p>

                                            <p class="card-text">Facility :</p>
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Facility</th>
                                                        <th>Description</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (isset($facility)) : ?> 
                                                    <?php foreach ($facility as $dt_fc) : ?>
                                                        <?php $i = 1; ?>                     
                                                        <?php foreach ($dt_fc as $dt) : ?>
                                                            <?php if ($dt['unit_homestay_id']==$itemunit['id']): ?>                                                                
                                                                <tr>
                                                                    <td><?= esc($i++); ?></td>
                                                                    <td><?= esc($dt['name']); ?></td>
                                                                    <td><?= esc($dt['description']); ?></td>
                                                                    <td>
                                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                                            <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap"><i class="fa fa-pencil" aria-hidden="true"></i></button>                                                            
                                                                            <form action="<?= base_url('dashboard/unithomestay/deletefacilityunit/').$dt['unit_homestay_id']; ?>" method="post" class="d-inline">
                                                                                <?= csrf_field(); ?>
                                                                                <input type="hidden" name="unit_homestay_id" value="<?= esc($dt['unit_homestay_id']); ?>">
                                                                                <input type="hidden" name="facility_unit_id" value="<?= esc($dt['facility_unit_id']); ?>">
                                                                                <input type="hidden" name="description" value="<?= esc($dt['description']); ?>">
                                                                                <input type="hidden" name="_method" value="DELETE">
                                                                                <button type="submit" class="btn btn-sm" onclick="return confirm('apakah anda yakin akan menghapus?');"><i class="fa fa-remove" aria-hidden="true"></i></button>
                                                                            </form>
                                                                        </div>
                                                                    </td> 
                                                                </tr>              
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                </tbody>
                                            </table>
                                            
                                            <div class="btn-group float-end" role="group" aria-label="Basic example">
                                                <button type="button" id="editButton" class="btn btn btn-outline-warning btn-sm" data-bs-toggle="modal"  data-bs-whatever="@getbootstrap"><i class="material-icons">&#xE254;</i></button>                                                            
                                                

                                                <!-- Modal -->
                                                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="unitHomestayModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        <span class="close">&times;</span>
                                                        <h2>Edit Data</h2>
                                                        <form id="editForm" class="row g-3" action="<?= base_url('dashboard/unithomestay/editunit') . '/' . $itemunit['id']; ?>" method="post" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <div class="card-header">
                                                                    <?php @csrf_field(); ?>
                                                                    <h5 class="card-title">Edit Unit <?= esc($itemunit['nama_unit']); ?></h5>
                                                                    <div class="row g-4">
                                                                        <div class="col-md-5">
                                                                            <div class="form-group">
                                                                                <label for="homestay">Homestay</label>
                                                                                <input type="text" class="form-control" id="editHomestay" name="editHomestay" placeholder="HOxxx" disabled value="<?= esc($data['id']) ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <div class="form-group">
                                                                                <label for="nama_unit">Unit Name</label>
                                                                                <input type="text" class="form-control" id="editNama_unit" name="editNama_unit" value="<?= esc($itemunit['nama_unit']); ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row g-4">
                                                                        <div class="col-md-5">
                                                                            <div class="form-group">
                                                                                <label for="capacity">Capacity</label>
                                                                                <input type="number" class="form-control" id="editCapacity" name="editCapacity" value="<?= esc($itemunit['capacity']); ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <div class="form-group">
                                                                                <label for="price">Price</label>
                                                                                <input type="number" class="form-control" id="editPrice" name="editPrice" value="<?= esc($itemunit['price']); ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row g-4">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="description">Description</label>
                                                                                <textarea name="editDescription" id="editDescription" class="form-control" cols="30" rows="10"><?= esc($itemunit['description']); ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                                                <button type="submit" value="simpan" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                                                <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                                                            </div>
                                                        </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <script>    
                                                    // Ambil elemen-elemen HTML yang diperlukan
                                                    const editButton = document.getElementById('editButton');
                                                    const editModal = document.getElementById('editModal');
                                                    const closeButton = document.getElementsByClassName('close')[0];
                                                    const editForm = document.getElementById('editForm');
                                                    const namaSpan = document.getElementById('capacity');
                                                    const umurSpan = document.getElementById('price');
                                                    const editNamaInput = document.getElementById('editCapacity');
                                                    const editUmurInput = document.getElementById('editPrice');

                                                    // Tampilkan modal saat tombol "Edit" ditekan
                                                    editButton.addEventListener('click', () => {
                                                        editModal.style.display = 'block';
                                                        editNamaInput.value = namaSpan.textContent;
                                                        editUmurInput.value = umurSpan.textContent;
                                                    });

                                                    // Tutup modal saat tombol close ditekan
                                                    closeButton.addEventListener('click', () => {
                                                        editModal.style.display = 'none';
                                                    });

                                                    // Tutup modal jika pengguna mengklik di luar modal
                                                    window.addEventListener('click', (event) => {
                                                        if (event.target === editModal) {
                                                            editModal.style.display = 'none';
                                                        }
                                                    });

                                                    // Mengirimkan data yang diedit
                                                    editForm.addEventListener('submit', (event) => {
                                                        event.preventDefault();
                                                        namaSpan.textContent = editNamaInput.value;
                                                        umurSpan.textContent = editUmurInput.value;
                                                        editModal.style.display = 'none';
                                                    });
                                                </script>
                                                
                                                <form action="<?= base_url('dashboard/unithomestay/delete/').$itemunit['id']; ?>" method="post" class="d-inline">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="homestay_id" value="<?= esc($itemunit['homestay_id']); ?>">
                                                    <input type="hidden" name="unit_homestay_id" value="<?= esc($itemunit['id']); ?>">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('apakah anda yakin akan menghapus?');"><i class="material-icons">&#xE872;</i></button>
                                                </form>
                                            </div>                                        
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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

<!-- <script>
    $('#datepicker_start').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '-3d'
    });
    $('#datepicker_end').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '-3d'
    });
</script> -->
<script>
    // const myModal = document.getElementById('videoModal');
    // const videoSrc = document.getElementById('video-play').getAttribute('data-src');

    // myModal.addEventListener('shown.bs.modal', () => {
    //     // console.log(videoSrc);
    //     document.getElementById('video').setAttribute('src', videoSrc);
    // });
    // myModal.addEventListener('hide.bs.modal', () => {
    //     document.getElementById('video').setAttribute('src', '');
    // });

</script>
<script>
    FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginImageResize,
        FilePondPluginMediaPreview,
    );

    // Get a reference to the file input element
    const photo = document.querySelector('input[id="gallery"]');
    const video = document.querySelector('input[id="video"]');

    // Create a FilePond instance
    const pond = FilePond.create(photo, {
        imageResizeTargetHeight: 720,
        imageResizeUpscale: false,
        credits: false,
    });
    const vidPond = FilePond.create(video, {
        credits: false,
    })

    <?php if ($edit && count($data['gallery']) > 0) : ?>
        pond.addFiles(
            <?php foreach ($data['gallery'] as $g) : ?> `<?= base_url('media/photos/homestay/' . $g); ?>`,
            <?php endforeach; ?>
        );
    <?php endif; ?>
    pond.setOptions({
        server: '/upload/photo'
    });

</script>

<script>
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	var actions = $("table td:last-child").html();
	// Append table with add row form on add new button click
    $(".add-new").click(function(){
		$(this).attr("disabled", "disabled");
		var index = $("table tbody tr:last-child").index();
        var row = '<tr>' +
            '<td><input type="text" class="form-control" name="activity" id="activity"></td>' +
            '<td><input type="text" class="form-control" name="activity_type" id="activity_type"></td>' +
            '<td><input type="text" class="form-control" name="object" id="object"></td>' +
            '<td><input type="text" class="form-control" name="description" id="description"></td>' +
			'<td>' + actions + '</td>' +
        '</tr>';
    	$("table").append(row);		
		$("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
        $('[data-toggle="tooltip"]').tooltip();
    });
	// Add row on add button click
	$(document).on("click", ".add", function(){
		var empty = false;
		var input = $(this).parents("tr").find('input[type="text"]');
        input.each(function(){
			if(!$(this).val()){
				$(this).addClass("error");
				empty = true;
			} else{
                $(this).removeClass("error");
            }
		});
		$(this).parents("tr").find(".error").first().focus();
		if(!empty){
			input.each(function(){
				$(this).parent("td").html($(this).val());
			});			
			$(this).parents("tr").find(".add, .edit").toggle();
			$(".add-new").removeAttr("disabled");
		}		
    });
	// Edit row on edit button click
	$(document).on("click", ".edit", function(){		
        $(this).parents("tr").find("td:not(:last-child)").each(function(){
			$(this).html('<input type="text" class="form-control" value="' + $(this).text() + '">');
		});		
		$(this).parents("tr").find(".add, .edit").toggle();
		$(".add-new").attr("disabled", "disabled");
    });
	// Delete row on delete button click
	$(document).on("click", ".delete", function(){
        $(this).parents("tr").remove();
		$(".add-new").removeAttr("disabled");
    });
});
</script>

<?= $this->endSection() ?>