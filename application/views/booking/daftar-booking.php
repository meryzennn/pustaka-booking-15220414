<div class="container">
    <center>
        <div class="table-responsive full-width">
            <table class="table table-bordered table-striped table-hover" id="table-datatable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>ID Booking</th>
                        <th>Tanggal Booking</th>
                        <th>ID User</th>
                        <th>Aksi</th>
                        <th>Denda / Buku / Hari</th>
                        <th>Lama Pinjam</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($pinjam as $p) {
                    ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td>
                            <a class="btn btn-link" href="<?= base_url('pinjam/bookingDetail/' . $p['id_booking']); ?>">
                                <?= $p['id_booking']; ?>
                            </a>
                        </td>
                        <td><?= $p['tgl_booking']; ?></td>
                        <td><?= $p['id_user']; ?></td>
                        <td nowrap>
                            <form action="<?= base_url('pinjam/pinjamAct/' . $p['id_booking']); ?>" method="post">
                                <button type="submit" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-fw fa-cart-plus"></i> Pinjam
                                </button>
                        </td>
                        <td>
                            <input class="form-check-user rounded-sm" style="width:100px" type="text" name="denda" id="denda" value="5000">
                            <?= form_error('denda'); ?>
                        </td>
                        <td>
                            <input class="form-check-user rounded-sm" style="width:100px" type="text" name="lama" id="lama" value="3">
                            <?= form_error('lama'); ?>
                        </td>
                            </form>
                    </tr>
                    <?php
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div align="center">
            <a href="<?= base_url('pinjam/daftarBooking'); ?>" class="btn btn-link">
                <i class="fas fa-fw fa-refresh"></i> Refresh
            </a>
        </div>
    </center>
</div>
