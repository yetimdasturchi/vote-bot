<div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Nominatsiya</th>
                <th>Ishtirokchi</th>
                <th class="text-center">Holat</th>
                <th class="text-center">Vaqt</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $x = 1;
                foreach ($votes as $vote) {
                    switch ( $vote['check_status'] ) {
                        case '1':
                            $check_status = "<span class=\"status-badge-success\"></span> Tekshirilgan";
                        break;

                        case '2':
                            $check_status = "<span class=\"status-badge-danger\"></span> Bekor qilingan";
                        break;
                        
                        default:
                            $check_status = "<span class=\"status-badge-process\"></span> Jarayonda";
                        break;
                    }

                    $check_status = "<small>{$check_status}</small>";
            ?>
            <tr>
                <th class="text-center" scope="row"><?php echo $x;?></th>
                <td><?php echo $vote['nomination_name'];?></td>
                <td><?php echo $vote['member_name'];?></td>
                <td class="text-center"><?php echo $check_status;?></td>
                <td class="text-center"><small><?php echo date("d.m.Y (H:i)", $vote['date']);?></small></td>
            </tr>
            <?php
                    $x++;
                }
            ?>
        </tbody>
    </table>
</div>