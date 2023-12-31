<div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Savol</th>
                <th class="text-center">Yuborish vaqti</th>
                <th class="text-center">Javob berilgan</th>
                <th class="text-center">Holat</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $x = 1;
                foreach ($questions as $row) {
                    $q_status = "";

                    if ( !empty( $row['uz_name'] ) ) {
                        $question = $row['uz_name'];
                    }else if ( !empty( $row['uzc_name'] ) ) {
                        $question = $row['uzc_name'];
                    }else if ( !empty( $row['ru_name'] ) ) {
                        $question = $row['ru_name'];
                    }else if ( !empty( $row['en_name'] ) ) {
                        $question = $row['en_name'];
                    }else{
                        $question = "<code>-</code>";
                    }

                    if ( $row['expire'] <= time() && $row['answered'] == '0' ) {
                        $q_status = "<span class=\"status-badge-danger\"></span> Javob bermagan";
                    }

                    if ( $row['answered'] > 0 ) {
                        $answered = date('d.m.Y (H:i)', $row['answered']);
                        $q_status = "<span class=\"status-badge-success\"></span> Javob bergan";
                    }else{
                        $answered = "<code>-</code>";
                    }

                    if ( empty( $q_status ) ) {
                        $q_status = "<span class=\"status-badge-process\"></span> Jarayonda";
                    }

                    $send_date = date('d.m.Y (H:i)', $row['send_date']);

                    if ( $row['sended'] == '1' && $row['tg_status'] == '2' ) {
                        $send_date = '<i class="bx bx-block text-danger"></i> ' . $send_date;
                    }else if ( $row['sended'] == '1' && $row['tg_status'] == '0' ) {
                        $send_date = '<i class="bx bx-check-double text-success"></i> ' . $send_date;
                    }else if ( $row['sended'] == '0' && $row['tg_status'] == '0' ) {
                        $send_date = '<i class="bx bx-time-five text-info"></i> ' . $send_date;
                    }

                    $send_date = "<small>{$send_date}<small>";
                    $answered = "<small>{$answered}<small>";
                    $q_status = "<small>{$q_status}<small>";
            ?>
            <tr>
                <th class="text-center" scope="row"><?php echo $x;?></th>
                <td><?php echo $question;?></td>
                <td><?php echo $send_date;?></td>
                <td class="text-center"><?php echo $answered;?></td>
                <td class="text-center"><?php echo $q_status;?></td>
            </tr>
            <?php
                    $x++;
                }
            ?>
        </tbody>
    </table>
</div>