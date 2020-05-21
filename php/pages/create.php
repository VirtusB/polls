<div class="section no-pad-bot">
    <div class="container">
        <br><br>

        <label for="poll-question">Poll Question</label>
        <input id="poll-question" type="text" placeholder="Eg. what is your favorite food?">

        <div class="row center">
            <ul id="options-container">
                <li>
                    <input id="option-input1" type="text" placeholder="Option">
                </li>
                <li>
                    <input id="option-input2" type="text" placeholder="Option">
                </li>
                <li>
                    <input id="option-input3" type="text" placeholder="Option">
                </li>
            </ul>

            <button onclick="createPoll()" class="btn">Create Poll</button>
        </div>
        <br><br>
    </div>
</div>

<script src="/js/create.js"></script>