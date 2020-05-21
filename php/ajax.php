<?php

if (isset($_POST['fetch_poll'])) {
    $slug = $_POST['slug'];

    $poll = getPollBySlug($slug);

    outputResult(200, $poll);
}

if (isset($_POST['create_poll'])) {
    $pollQuestion = $_POST['poll_question'];
    $options = $_POST['options'];

    $poll = createPoll($pollQuestion, $options);

    outputResult(200, $poll);
}

if (isset($_POST['add_vote'])) {
    $slug = $_POST['slug'];
    $optionID = $_POST['option_id'];

    $poll = getPollBySlug($slug);

    $res = addVote($poll['id'], $optionID);

    outputResult(200, $res);
}

if (isset($_POST['remove_vote'])) {
    $slug = $_POST['slug'];
    $optionID = $_POST['option_id'];

    $poll = getPollBySlug($slug);

    $res = removeVote($poll['id'], $optionID);

    outputResult(200, $res);
}