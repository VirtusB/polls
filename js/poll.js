let slug = location.pathname.split('/')[2];
var firstRun = true;

var fetchPollInterval;
var hidden, visibilityChange;
if (typeof document.hidden !== "undefined") {
    hidden = "hidden";
    visibilityChange = "visibilitychange";
} else if (typeof document.msHidden !== "undefined") {
    hidden = "msHidden";
    visibilityChange = "msvisibilitychange";
} else if (typeof document.webkitHidden !== "undefined") {
    hidden = "webkitHidden";
    visibilityChange = "webkitvisibilitychange";
}

function handleVisibilityChange() {
    if (document[hidden]) {
        clearInterval(fetchPollInterval);
    } else {
        fetchPollInterval = setInterval(fetchPoll, 3000);
    }
}

function fetchPoll() {
    $.post(location.origin, { fetch_poll: '1', slug: slug }, (data) => {
        if (firstRun) {
            $('#poll-question').text(data.question);
            insertVoteOptions(data);
            insertPieChart(data);
            firstRun = false;
        } else {
            insertPieChart(data);
        }
    });
}

function insertVoteOptions(poll) {
    let options = poll.options;
    let optionList = document.getElementById('options-list');

    options.forEach(option => {
        let html;

        if (hasVotedOnOptionID(option.id)) {
            html = `
                <li onclick="removeVote(this)" data-option-id="${option.id}" class="vote-option voted">${option.option_text}</li>
                `;
        } else {
            html = `
                <li onclick="addVote(this)" data-option-id="${option.id}" class="vote-option">${option.option_text}</li>
                `;
        }

        optionList.insertAdjacentHTML('beforeend', html);
    });
}

function hasVotedOnOptionID(optionID) {
    let votes = JSON.parse(localStorage.getItem('votes'));
    optionID = parseInt(optionID);

    if (!votes) {
        return false;
    }

    return votes.some(v => v !== null && v.slug === slug && v.option_id === optionID);
}

function removeVoteFromLocalStorage(optionID) {
    let votes = JSON.parse(localStorage.getItem('votes'));
    optionID = parseInt(optionID);

    if (!votes) {
        return false;
    }

    let idx = votes.findIndex(v => v !== null && v.slug === slug && v.option_id === optionID);
    delete votes[idx];
    localStorage.setItem('votes', JSON.stringify(votes));
}

function addVoteToLocalStorage(optionID) {
    let votes = JSON.parse(localStorage.getItem('votes'));
    optionID = parseInt(optionID);

    if (!votes) {
        votes = [];
    }

    let vote = {'slug': slug, 'option_id': optionID};
    votes.push(vote);

    localStorage.setItem('votes', JSON.stringify(votes));
}

function addVote(el) {
    let optionID = el.getAttribute('data-option-id');

    $.post(location.origin, { add_vote: '1', slug: slug, option_id: optionID }, (data) => {
        console.log(data);

        if (data) {
            fetchPoll();
            addVoteToLocalStorage(optionID);
            el.setAttribute('onclick', 'removeVote(this)');
            el.classList.add('voted');
        } else {
            alert('Could not add your vote');
        }
    });
}

function removeVote(el) {
    let optionID = el.getAttribute('data-option-id');

    $.post(location.origin, { remove_vote: '1', slug: slug, option_id: optionID }, (data) => {
        console.log(data);

        if (data) {
            fetchPoll();
            removeVoteFromLocalStorage(optionID);
            el.setAttribute('onclick', 'addVote(this)');
            el.classList.remove('voted');
        } else {
            alert('Could not remove your vote');
        }
    });
}

fetchPoll();

document.addEventListener(visibilityChange, handleVisibilityChange, false);
fetchPollInterval = setInterval(fetchPoll, 3000);

function insertPieChart(poll) {
    let option_text = [];

    let options = poll.options;

    let data = [];

    for (var i in options) {
        if (options.hasOwnProperty(i)) {
            option_text.push(options[i].option_text);

            data.push({value: options[i].vote_count, name: options[i].option_text})
        }
    }

    let pieChart = echarts.init(document.getElementById('poll-chart'));

    let conf = {
        tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b}: {c} ({d}%)'
        },
        legend: {
            orient: 'vertical',
            left: 10,
            data: option_text
        },
        series: [
            {
                name: 'Votes',
                type: 'pie',
                radius: ['50%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    show: false,
                    position: 'center'
                },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: '30',
                        fontWeight: 'bold'
                    }
                },
                labelLine: {
                    show: false
                },
                data: data
            }
        ]
    };

    pieChart.setOption(conf);

    var resizeListener = void 0;
    $(window).on('resize', function () {
        clearTimeout(resizeListener);
        resizeListener = setTimeout(pieChart.resize(), 1000);
    });
}