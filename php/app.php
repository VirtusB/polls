<?php

const SALT = 'd24bfd8c';
const INVALID_DATA = 400;
const SUCCESS_OK = 200;

try {
    $DB = new PDO('sqlite:' . __DIR__ . '/poll.db');
} catch(PDOException $e) {
    print 'Exception : '.$e->getMessage();
}

function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

function unixToDateTime($unix) {
    return date('m-d-Y H:i:s', $unix);
}

function outputResult($status, $result) {
    ob_get_clean();
    ob_start();
    header('Content-Type: application/json');

    http_response_code($status);

    echo json_encode($result);

    die();
}

function getAllPolls() {
    global $DB;

    $polls = $DB->query('SELECT * FROM polls ORDER BY created DESC', PDO::FETCH_ASSOC)->fetchAll();

    foreach ($polls as $key => $poll) {
        $polls[$key]['options'] = getVotesAndOptionsForPoll($poll['id']);
    }

    return $polls;
}

function getPollBySlug($slug) {
    global $DB;

    $slug = escape($slug);

    $poll = $DB->query("SELECT * FROM polls WHERE slug = '$slug'")->fetch(PDO::FETCH_ASSOC);

    $poll['options'] = getVotesAndOptionsForPoll($poll['id']);

    return $poll;
}

function addVote($pollID, $optionID) {
    global $DB;

    return (bool) $DB->exec("INSERT INTO votes (poll_id, option_id) VALUES ($pollID, $optionID)");
}

function removeVote($pollID, $optionID) {
    global $DB;

    return (bool) $DB->exec("DELETE FROM votes WHERE ROWID = (SELECT ROWID FROM votes WHERE poll_id = $pollID AND option_id = $optionID LIMIT 1)");
}

function createPoll($pollQuestion, $options) {
    global $DB;

    $slug = generatePollSlug();

    $pollQuestion = escape($pollQuestion);

    $insertPoll = $DB->exec("INSERT INTO polls (question, slug) VALUES ('$pollQuestion', '$slug')");

    if ($insertPoll !== 1) {
        return false;
    }

    $insertedPollID = $DB->lastInsertId();

    foreach ($options as $option) {
        $cleanOption = escape($option);

        $insertOption = $DB->exec("INSERT INTO poll_options (poll_id, option_text) VALUES ($insertedPollID, '$cleanOption')");

        if ($insertOption !== 1) {
            return false;
        }
    }

    return getPollBySlug($slug);
}

function getVotesAndOptionsForPoll($pollID) {
    $options = getOptionsForPoll($pollID);

    foreach ($options as $key => $option) {
        $options[$key]['vote_count'] = getVotesForOption($option['id'], $pollID);
    }

    return $options;
}

function getVotesForOption($optionID, $pollID) {
    global $DB;

    return (int) $DB->query("SELECT COUNT(*) as votes FROM votes WHERE poll_id = $pollID AND option_id = $optionID", PDO::FETCH_COLUMN, 0)->fetch();
}

function getOptionsForPoll($pollID) {
    global $DB;

    return $DB->query("SELECT * FROM poll_options WHERE poll_id = $pollID", PDO::FETCH_ASSOC)->fetchAll();
}

function generatePollSlug() {
    global $DB;

    $slug = bin2hex(random_bytes(8));

    $pollExists = $DB->query("SELECT * FROM polls WHERE slug = '$slug' LIMIT 1", PDO::FETCH_ASSOC)->fetchAll();

    if (count($pollExists) !== 0) {
        return generatePollSlug();
    }

    return $slug;
}

