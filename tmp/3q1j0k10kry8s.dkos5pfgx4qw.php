<div class="container py-5">
	<div class="row">
		<div class="col-md-12">
			<h1><?= ($i18n_users) ?></h1>	
			<table class="table table-hover table-striped table-bordered" id="list">
				<thead>
					<tr>
						<th scope="col">username</th>
						<th scope="col">usertype</th>
						<th scope="col">created_at</th>
						<th scope="col">updated_at</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach (($users?:[]) as $user): ?>
					<tr onclick="window.document.location='admin/users/<?= ($user['id']) ?>';" style="cursor: pointer;">
						<td><?= ($user['username']) ?></td>
						<td><?php if ($user['user_type']===100): ?><em>Administrator</em>
						<?php else: ?><?php if ($user['user_type']===10): ?><em>Superuser</em><?php else: ?>Standard User<?php endif; ?><?php endif; ?></td>
						<td><?= ($user['created_at']) ?></td>
						<td><?= ($user['updated_at']) ?></td>
					</tr>	
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

