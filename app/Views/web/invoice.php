<style>
    p, span, table { font-size: 12px}
    table { width: 100%; border: 1px solid #dee2e6; }
    table#tb-item tr th, table#tb-item tr td {
        border:1px solid #000
    }
</style>

<p style="font-size:18pt;text-align:right">INVOICE</p>
<span>Kepada Yth.</span><br/>
<table cellpadding="0" >
    <tr>
        <th width="10%">Name</th>
            <?php if (!empty(user()->fullname)) : ?>
                <th width="40%">: <strong><?= user()->fullname; ?></strong></th>
            <?php else : ?>
                <th width="40%">: <strong>@<?= user()->username; ?></strong></th>
            <?php endif; ?>
        <th width="12%">No.Invoice</th>
        <th width="40%">: <strong><?= esc($detail['id']); ?></strong></th>
    </tr>
    <tr>
        <th width="10%">Addres</th>
            <?php if (!empty(user()->address)) : ?>
                <th width="40%">: <strong><?= user()->address; ?></strong></th>
            <?php else : ?>
                <th width="40%">: <i>(profil is incomplete)</i></th>
            <?php endif; ?>
        <th width="12%">Request at</th>
        <?php $request_date = strtotime($detail['request_date']); ?>
        <th width="60%">: <strong><?= esc(date('l, j F Y H:i:s', $request_date)); ?></strong></th>
    </tr>
    <tr>
        <th width="10%">Telp</th>
            <?php if (!empty(user()->phone)) : ?>
                <th width="40%">: <strong><?= user()->phone; ?></strong></th>
            <?php else : ?>
                <th width="40%">: <i>(profil is incomplete)</i></th>
            <?php endif; ?>
    </tr>
</table>
<p></p>
<table id="tb-item" cellpadding="4" >
    <tr style="background-color:#a9a9a9">
        <th width="35%" style="height: 20px"><strong>Name Package</strong></th>
        <th width="12%" style="height: 20px"><strong>Capacity</strong></th>
        <th width="10%" style="height: 20px"><strong>Total People</strong></th>
        <th width="8%" style="height: 20px;text-align:center"><strong>Qty</strong></th>
        <th width="13%" style="height: 20px"><strong>Package Price</strong></th>
        <th width="18%" style="height: 20px"><strong>Total Price</strong></th>
    </tr>
    <tr>
        <td style="height: 20px">Package <?= esc($detail['name']); ?></td>
        <td style="height: 20px;text-align:center"><?= esc($data_package['min_capacity']); ?></td>
        <td style="height: 20px;"><?= esc($detail['total_people']); ?></td>
        <?php 
            $min=$data_package['min_capacity'];
            $people=$detail['total_people'];
            $qty=ceil($people/$min); 
            $price_package=$data_package['price'];
            $tot_price_package=$qty*$price_package;
        ?>
        <td style="height: 20px;text-align:center"><?= $qty; ?></td>
        <td style="height: 20px;text-align:right"><?= 'Rp'.number_format(esc($data_package['price']), 0, ',', '.'); ?></td>
        <td style="height: 20px;text-align:right"><?= 'Rp'.number_format(esc($tot_price_package), 0, ',', '.'); ?></td>
    </tr>
    <tr><td colspan="6"></td></tr>
    <tr style="background-color:#a9a9a9">
        <th width="35%" style="height: 20px"><strong>Name Homestay</strong></th>
        <th width="30%" style="height: 20px"><strong>Unit</strong></th>
        <th width="13%" style="height: 20px"><strong>Capacity</strong></th>
        <th width="18%" style="height: 20px"><strong>Unit Price</strong></th>
    </tr>
    <?php if (isset($booking)) : ?> 
        <?php foreach ($booking as $dtb) : ?>
            <tr>
                <td><?= esc($dtb['name']); ?></td>
                <td><?= esc($dtb['name_type']); ?> <?= esc($dtb['unit_number']); ?> <?= esc($dtb['nama_unit']); ?></td>
                <td style="height: 20px;text-align:center"><?= esc($dtb['capacity']); ?></td>
                <td style="height: 20px;text-align:right"><?= 'Rp' . number_format(esc($dtb['price']), 0, ',', '.'); ?></td>
            </tr>              
        <?php endforeach; ?>
    <?php endif; ?>
    <tr style="border:1px solid #000">
        <td colspan="3" style="height: 20px"><strong>Grand Total</strong></td>
        <td style="height: 20px;text-align:right"><strong><?= 'Rp' . number_format(esc($detail['total_price']), 0, ',', '.'); ?></strong></td>
    </tr>
    <tr style="border:1px solid #000">
        <td colspan="3" style="height: 20px"><strong>Deposit</strong></td>
        <td style="height: 20px;text-align:right"><strong><?= 'Rp' . number_format(esc($detail['deposit']), 0, ',', '.'); ?></strong></td>
    </tr>
</table>

<p>Terbilang: Satu Juta Lima Ratus Ribu Rupiah</p>
<p><u>TRANSFER VIA</u></p>
<p>BSI: IDR<br/>A/C : 78280389<br/>A/N : Desa Wisata Green Talao Park</p>


<table cellpadding="0" >
    <tr>
        <?php $deposit_date = strtotime($detail['deposit_date']); ?>
        <?php $fullpayment_date = strtotime($detail['payment_date']); ?>

        <th width="25%">Deposit Payment </th>
            <?php if (!empty($detail['proof_of_deposit'])) : ?>
                <th width="70%">: Complete on <strong><?= esc(date('l, j F Y H:i:s', $deposit_date)); ?></strong></th>
            <?php else : ?>
                <th width="70%">: <i>Incomplete</i></th>
            <?php endif; ?>
    </tr>
    <tr>
        <th width="25%">Full Payment  </th>
            <?php if (!empty($detail['proof_of_deposit'])) : ?>
                <th width="70%">: Complete on <strong><?= esc(date('l, j F Y H:i:s', $fullpayment_date)); ?></strong></th>
            <?php else : ?>
                <th width="70%">: <i>Incomplete</i></th>
            <?php endif; ?>
    </tr>
</table>

<p>&nbsp;</p>
<table cellpadding="4" >
    <tr>
        <td width="50%" style="height: 20px;text-align:center">
            <p>&nbsp;</p>
        </td>
        <td width="50%" style="height: 20px;text-align:center">
            <p>Malang, 28 Sept 2021</p>
            <p>Hormat kami,</p>
            <p></p>
            <p></p>
            <p></p>
            <p>sobatcoding.com</p>
        </td>
    </tr>
</table>