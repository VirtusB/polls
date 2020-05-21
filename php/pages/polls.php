<?php

$polls = getAllPolls();

?>

<div class="section no-pad-bot">
    <div class="container">
        <br><br>
        <h1 class="header center orange-text">All Polls</h1>

        <div class="row center">
            <ul>
                <?php foreach ($polls as $poll): ?>
                    <li>
                        <a class="big-link" href="<?= 'poll/' . $poll['slug'] ?>"><?= $poll['question'] ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <br><br>
    </div>
</div>



