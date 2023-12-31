<div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Referal</th>
                <th>Foydalanuvchi</th>
                <th class="text-center">Ovozlar</th>
                <th class="text-center">Vaqt</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $x = 0;
                foreach ($referrals as $row) {
                    if ( !empty( $row['u_first_name'] ) ) {
                        $u_name = $row['u_first_name'];               
                    }

                    if ( !empty( $row['u_last_name'] ) ) {
                        $u_name .= $row['u_last_name'];
                    }

                    if ( !empty( $row['u_username'] ) ) {
                        $u_name .= " ( @{$row['u_username']} )";
                    }
                    
                    $u_name = trim( $u_name );

                    if ( mb_strlen( $u_name, "UTF-8" ) > 52 ) {
                        $u_name = "Excepted";
                    }

                    $u_name = !empty( $u_name ) ? $u_name : $row['chat_id'];

                    if ( !empty( $row['o_first_name'] ) ) {
                        $o_name = $row['o_first_name'];               
                    }

                    if ( !empty( $row['o_last_name'] ) ) {
                        $o_name .= $row['o_last_name'];
                    }

                    if ( !empty( $row['o_username'] ) ) {
                        $o_name .= " ( @{$row['o_username']} )";
                    }
                    
                    $o_name = trim( $o_name );

                    if ( mb_strlen( $o_name, "UTF-8" ) > 52 ) {
                        $o_name = "Excepted";
                    }

                    $o_name = !empty( $o_name ) ? $o_name : $row['owner_id'];

                    $date = date('d.m.Y (H:i)', $row['date']);
                $x++;
            ?>
            <tr>
                <th class="text-center" scope="row"><?php echo $x;?></th>
                <td><?php echo $o_name;?></td>
                <td><?php echo $u_name;?></td>
                <td><small><span class="status-badge-process"></span> <?php echo $row['unchecked'];?> / <span class="status-badge-danger"></span>  <?php echo $row['ignored'];?> / <span class="status-badge-success"></span> <?php echo $row['checked'];?></small></td>
                <td class="text-center"><small><?php echo $date;?></small></td>
            </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</div>