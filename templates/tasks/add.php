    <?php include __DIR__ . '/../header.php'; ?>

<main>
    <div class="container">
        <div class="row">
            <div class="col l12">

            <h4>Создание нового задания</h4>

            <?php if(!empty($error)): ?>
                <div class = "error-message"><?= $error ?></div>
            <?php endif; ?>

            <form action="/tasks/add" method="post">
                <div class="input-field col s6">
                    <label for="name">Имя автора</label>
                    <input type="text" name="name" id="name" value="<?= $_POST['name'] ?? '' ?>" size="50">
                </div>
                <div class="input-field col s6">
                    <label for="email">Email автора</label>
                    <input type="text" name="email" id="email" value="<?= $_POST['email'] ?? '' ?>" size="50">
                </div>
                <div class="input-field col s12">
                    <label for="text">Текст задания</label>
                    <textarea class="materialize-textarea" name="text" id="text" rows="5" cols="80"><?= $_POST['text'] ?? '' ?></textarea>
                </div>
                <div class="input-field col s12">
                    <i style="margin-top: 5px;" class="material-icons left">create</i><input class="waves-effect waves-light btn blue lighten-1" type="submit" value="Создать">
                </div>
            </form>

            </div>
        </div>
    </div>
</main>


<?php include __DIR__ . '/../footer.php'; ?>