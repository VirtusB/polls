<?php

if (!isset($params['slug'])) {
    echo <<<HTML
        <h3>Poll slug missing</h3>
HTML;

    return;
}

?>

<script src="/js/echarts.min.js"></script>

<div class="section no-pad-bot">
<!--    <div class="container">-->
    <div class="">
        <br><br>
        <h1 class="header center orange-text" id="poll-question"></h1>

        <br><br>

        <div class="row center">
            <div class="col s9" style="height: 600px; margin-bottom: 70px;">
                <div id="poll-chart" style="height: 600px; width: 95vw; max-width: 100%; margin: 0 auto !important;"></div>
            </div>
            <div class="col s3">
                <h5 class="vote-msg">Click to vote</h5>

                <ul id="options-list">

                </ul>
            </div>
        </div>
        <br><br>
    </div>
</div>

<script src="/js/poll.js"></script>