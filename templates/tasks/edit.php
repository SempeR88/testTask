<?php include __DIR__ . '/../header.php'; ?>

<main>
    <div class="container">
        <div class="row">
            <div class="col l12">

            <h4>Редактирование задания</h4>

            <?php if(!empty($error)): ?>
                <div class = "error-message"><?= $error ?></div>
            <?php endif; ?>

            <form action="/tasks/<?= $task->getId() ?>/edit" method="post">
                <div class="input-field col s6">
                    <label for="name">Имя автора</label>
                    <input type="text" name="name" id="name" value="<?= $_POST['name'] ?? $task->getName() ?>" size="50" readonly>
                </div>
                <div class="input-field col s6">
                    <label for="email">Email автора</label>
                    <input type="text" name="email" id="email" value="<?= $_POST['email'] ?? $task->getEmail() ?>" size="50" readonly>
                </div>
                <div class="input-field col s12">
                    <label for="text">Текст задания</label>
                    <textarea class="materialize-textarea" name="text" id="text" rows="5" cols="80"><?= $_POST['text'] ?? $task->getText() ?></textarea>
                </div>
                <div class="input-field col s3">
                    <select name="status" id="status">
                        <?php foreach($statusOptions as $option): ?>
                            <?php 
                                $selected = '';
                                if ($task->getStatus() == $option[1]) {
                                    $selected = 'selected ';
                                }
                            ?>
                        <option <?= $selected ?>value="<?= $option[0] ?>"><?= $option[1] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-field col s9">
                    <i style="margin-top: 5px;" class="material-icons left">edit</i><input class="waves-effect waves-light btn blue lighten-1" type="submit" value="Обновить">
                </div>
            </form>

            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../footer.php'; ?>