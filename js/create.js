var optionIndex = 3;
var optionsContainer = document.getElementById('options-container');

var events = ['click', 'change'];
events.forEach(function(event) {
    document.addEventListener(event, function(e){
        if(e.target && e.target.id.includes('option-input')) {
            if (validateOptions()) {
                optionIndex++;
                var newInput = generateOptionInput(optionIndex);
                optionsContainer.insertAdjacentHTML('beforeend', newInput);
            }
        }
    });
});

function generateOptionInput(index) {
    return `
    <li>
        <input id="option-input${index}" type="text" placeholder="Option">
    </li>
    `;
}

function validateOptions() {
    var optionElements = document.querySelectorAll('*[id^="option-input"]');
    var countEmpty = 0;
    Array.from(optionElements).forEach(function(optionElement) {
        if (optionElement.value === '') {
            countEmpty++;
        }
    });
    return countEmpty === 0;
}

function createPoll() {
    let pollQuestion = document.getElementById('poll-question').value;

    if (pollQuestion === '') {
        alert("Poll question can't be empty");
        return;
    }

    let options = [];

    var optionElements = document.querySelectorAll('*[id^="option-input"]');
    Array.from(optionElements).forEach(function(optionElement) {
        if (optionElement.value !== '') {
            options.push(optionElement.value);
        }
    });

    $.post(location.origin, { create_poll: '1', poll_question: pollQuestion, options: options }, (data) => {
        if (data) {
            location.pathname = '/poll/' + data.slug;
        } else {
            alert("Poll couldn't be created");
        }
    });
}