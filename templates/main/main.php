<?php include __DIR__ . '/../header.php'; ?>

<main>
	<div class="container">
        <div class="row">
            <div class="col l12">

            	<h4>Список задач</h4>

            	<?php if (!empty($message)): ?>
	                <div class = "success-message"><?= $message ?></div>
	            <?php endif; ?>

				<?php include __DIR__ . '/sortFilter.php'; ?>

				<table class="highlight tasks">
					<thead>
						<tr>
							<th>Имя автора</th>
							<th>Email автора</th>
							<th>Текст задачи</th>
							<th>Статус</th>
							<?php if (!empty($user)) { ?>
								<th>Действия</th>
							<?php } ?>
						</tr>		
					</thead>
					<tbody>
						<?php foreach ($tasks as $task): ?>
							<tr>
							    <td><?= $task->getName() ?></td>
							    <td><?= $task->getEmail() ?></td>
							    <td>
							    	<?= $task->getText() ?>
							    	<?php if ($task->getEdited()): ?>
							    		<br /><span>(Отредактировано администратором)</span>
							    	<?php endif; ?>
							    </td>
							    <td><?= $task->getStatus() ?></td>
							    <?php if (!empty($user)) { ?> 
							        <td><a href="/tasks/<?= $task->getId() ?>/edit">Редактировать</a> | <a href="/tasks/<?= $task->getId() ?>/delete">Удалить</a></td>
							    <?php } ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<a class="waves-effect waves-light btn blue lighten-1" href="/tasks/add"><i class="material-icons left">add</i>Добавить задание</a>

				<?php include __DIR__ . '/pagination.php'; ?>

			</div>
		</div>
	</div>
</main>

<?php include __DIR__ . '/../footer.php'; ?>