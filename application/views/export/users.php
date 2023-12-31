<table>
	<thead>
		<tr>
			<th data-f-bold="true">ID</th>
			<th data-f-bold="true">Chat id</th>
			<th data-f-bold="true">Foydalanuvchi nomi</th>
			<th data-f-bold="true">Ism</th>
			<th data-f-bold="true">Familiya</th>
			<th data-f-bold="true">Telefon raqam</th>
			<th data-f-bold="true">Registered</th>
			<th data-f-bold="true">Oxirgi harakat</th>
			<th data-f-bold="true">Til</th>
			<?php
				if ( !empty( $xfields ) ) {
					foreach ($xfields as $field) {
						echo "<th data-f-bold=\"true\">".$field['name']."</th>";
					}
				}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($users as $user) {
		?>
			<tr>
				<td><?php echo $user['real_id'];?></td>
				<td><?php echo $user['chat_id'];?></td>
				<td><?php echo $user['username'];?></td>
				<td><?php echo $user['first_name'];?></td>
				<td><?php echo $user['last_name'];?></td>
				<td><?php echo $user['phone'];?></td>
				<td><?php echo date($GLOBALS['system_config']['dateformat'], $user['registered']);?></td>
				<td><?php echo date($GLOBALS['system_config']['dateformat'], $user['last_action']);?></td>
				<td><?php echo $user['language'];?></td>
				<?php
					if ( !empty( $xfields ) ) {
						foreach ($xfields as $field) {
							if ( array_key_exists($field['xfield'], $user) ) {
								echo "<td>".$user[$field['xfield']]."</td>";
							}
						}
					}
				?>
			</tr>
		<?php
			}
		?>
	</tbody>
</table>