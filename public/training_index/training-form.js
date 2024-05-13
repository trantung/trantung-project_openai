function createOnchangeHandler(select) {
    return function() {
        console.log("hello");
        var radioDiv = document.createElement("div");
        radioDiv.className = "radio";
        var weightDiv = "";
        if (this.value === "4") {
            // console.log($(select).closest('.radio').parent().find('#weight_number').length)
            if ($(select).closest('.radio').parent().find('#weight_number').length === 0) {
                weightDiv = createWeightNumberDiv();
                weightDiv.style.display = "block";
                radioDiv.appendChild(weightDiv);
            } else {
                $(select).closest('.radio').parent().find('#weight_number').css("display", "block")
            }
        } else {
            $(select).closest('.radio').parent().find('#weight_number').css("display", "none")
        }
        $(select).closest('.radio')[0].appendChild(radioDiv);
    };
}

function clickRemoveBtn(btn) {
    return function() {
        var parentModelContent = btn.closest('.model_content');
        var modelContents = document.querySelectorAll('.model_content');
        console.log(modelContents.length);

        if (parentModelContent && modelContents.length > 1) {
            parentModelContent.remove();
            updateCharacterCount();
        } else {
            alert("At least one model content must remain.");
        }
    };
}

function updateCharacterCount() {
    const inputQuestions = document.querySelectorAll('textarea[name="question_model[]"]');
    const inputAnswers = document.querySelectorAll('textarea[name="answer_model[]"]');
    const display = document.getElementById('counter-display');
    const displayBottom = document.getElementById('counter-display-bottom');
    let totalCharacters = 0;
    inputQuestions.forEach(input => totalCharacters += input.value.length);
    inputAnswers.forEach(input => totalCharacters += input.value.length);
    display.innerHTML = totalCharacters + "/10000";
    displayBottom.innerHTML = totalCharacters + "/10000";
    let alertShown = false;
    if (totalCharacters > 10000 && !alertShown) {
        alert('Limit 10000 characters');
        $('#save_model').prop('disabled', true);
        alertShown = true;
    } else {
        $('#save_model').prop('disabled', false);
        alertShown = false;
    }
    console.log(totalCharacters);
}

function getLastModelIndex() {
    var modelContents = document.querySelectorAll('.model_content');

    if (modelContents.length === 0) {
        return 0;
    }

    var lastModelId = modelContents[modelContents.length - 1].id;

    var lastIndex = parseInt(lastModelId.replace('model_content', ''));

    return lastIndex;
}

function createQuestionButton() {
    var roundCount = document.querySelectorAll('.model_content').length;
    var lastModelIndex = getLastModelIndex();
    // console.log(lastModelIndex)
    lastModelIndex++;
    var originalDiv = document.querySelector('.model_content');
    var clonedDiv = originalDiv.cloneNode(true);
    clonedDiv.id = 'model_content' + lastModelIndex;
    var inputs = clonedDiv.querySelectorAll('textarea');

    inputs.forEach(function(input, index) {
        input.value = '';
        input.id = input.id + '_' + lastModelIndex; // C?p nh?t id c?a input
        input.name = input.name.replace(/\d+/g, lastModelIndex); // C?p nh?t name c?a input
    });

    var roundHeading = clonedDiv.querySelector('h4');
    roundHeading.textContent = 'Config ' + lastModelIndex;

    clonedDiv.style.marginTop = '10px';

    var clonedRemoveQuestionBtn = clonedDiv.querySelectorAll('.remove_question');
    clonedRemoveQuestionBtn.forEach(function(btn) {
        btn.addEventListener('click', clickRemoveBtn(btn), false);
    });

    var clonedSelects = clonedDiv.querySelectorAll('select');
    clonedSelects.forEach(function(select) {
        select.addEventListener('change', createOnchangeHandler(select), false);
    });

    var formGroup = document.querySelector('.main_config');
    formGroup.appendChild(clonedDiv);

    const counter = (() => {
        const inputQuestions = document.querySelectorAll('textarea[name="question_model[]"]');
        const inputAnswers = document.querySelectorAll('textarea[name="answer_model[]"]');
        const display = document.getElementById('counter-display');
        const displayBottom = document.getElementById('counter-display-bottom');
        let alertShown = false;
        const changeEvent = () => {
            let totalCharacters = 0;
            inputQuestions.forEach(input => totalCharacters += input.value.length);
            inputAnswers.forEach(input => totalCharacters += input.value.length);
            // display.innerHTML = totalCharacters;
            display.innerHTML = totalCharacters + "/10000";
            displayBottom.innerHTML = totalCharacters + "/10000";
            if (totalCharacters > 10000 && !alertShown) {
                alert('Limit 10000 characters');
                $('#save_model').prop('disabled', true);
                alertShown = true;
            } else if (totalCharacters <= 10000) {
                $('#save_model').prop('disabled', false);
                alertShown = false;
            }
            console.log(totalCharacters);
        };

        const countEvent = () => {
            inputQuestions.forEach(input => input.addEventListener('input', changeEvent));
            inputAnswers.forEach(input => input.addEventListener('input', changeEvent));
        };

        const init = () => {
            countEvent();
        };

        return {
            init: init
        };

    })();
    counter.init();

    // updateCharacterCount();
}

var deleteQuestionButton = document.querySelectorAll('.remove_question');
deleteQuestionButton.forEach(function(btn) {
    btn.addEventListener('click', function() {
        var parentModelContent = btn.closest('.model_content');
        var modelContents = document.querySelectorAll('.model_content');
        if (parentModelContent && modelContents.length > 1) {
            parentModelContent.remove();
            updateCharacterCount();
        } else {
            alert("At least one model content must remain.");
        }
    });
});

document.querySelector('.add_quickly').addEventListener('click', function() {
    for (var i = 0; i < 10; i++) {
        createQuestionButton();
    }
});

(() => {
    const counter = (() => {
        const inputQuestions = document.querySelectorAll('textarea[name="question_model[]"]');
        const inputAnswers = document.querySelectorAll('textarea[name="answer_model[]"]');
        const display = document.getElementById('counter-display');
        const displayBottom = document.getElementById('counter-display-bottom');
        let alertShown = false;
        const changeEvent = () => {
            let totalCharacters = 0;
            inputQuestions.forEach(input => totalCharacters += input.value.length);
            inputAnswers.forEach(input => totalCharacters += input.value.length);
            display.innerHTML = totalCharacters + "/10000";
            displayBottom.innerHTML = totalCharacters + "/10000";
            if (totalCharacters > 10000 && !alertShown) {
                alert('Limit 10000 characters');
                $('#save_model').prop('disabled', true);
                alertShown = true;
            } else if (totalCharacters <= 10000) {
                $('#save_model').prop('disabled', false);
                alertShown = false;
            }
            console.log(totalCharacters);
        };

        const countEvent = () => {
            inputQuestions.forEach(input => input.addEventListener('input', changeEvent));
            inputAnswers.forEach(input => input.addEventListener('input', changeEvent));
        };

        const init = () => {
            countEvent();
        };

        return {
            init: init
        };

    })();
    counter.init();
})();

function autoExpandTextarea(textarea) {
    const tmpTextarea = document.createElement('textarea');
    tmpTextarea.style.width = textarea.offsetWidth + 'px';
    tmpTextarea.style.padding = textarea.style.padding;
    tmpTextarea.style.border = textarea.style.border;
    tmpTextarea.style.fontSize = window.getComputedStyle(textarea).fontSize;
    tmpTextarea.style.fontFamily = window.getComputedStyle(textarea).fontFamily;
    tmpTextarea.style.wordWrap = 'break-word';
    tmpTextarea.style.visibility = 'hidden';
    tmpTextarea.style.position = 'absolute';
    tmpTextarea.style.whiteSpace = 'pre-wrap';
    tmpTextarea.value = textarea.value;

    document.body.appendChild(tmpTextarea);

    textarea.style.height = tmpTextarea.scrollHeight + 'px';

    document.body.removeChild(tmpTextarea);
}

function ensureModelContents(dataLength) {
    const modelContents = document.querySelectorAll('.model_content');
    const existingModelsWithValues = Array.from(modelContents).filter(modelContent => {
        const questionTextArea = modelContent.querySelector('textarea[name="question_model[]"]');
        const answerTextArea = modelContent.querySelector('textarea[name="answer_model[]"]');
        return questionTextArea.value.trim() !== '' || answerTextArea.value.trim() !== '';
    });

    let modelContentNeeded = dataLength;
    let modelContentReality = dataLength;

    console.log(existingModelsWithValues.length, modelContents.length);

    if (existingModelsWithValues.length < modelContents.length) {
        modelContentNeeded = modelContents.length - existingModelsWithValues.length;
        modelContentReality = dataLength - modelContentNeeded;
    }

    console.log('modelNeed:' + modelContentNeeded);
    console.log('modelContentReality:' + modelContentReality);

    for (let i = 0; i < modelContentReality; i++) {
        createQuestionButton();
    }

    // if (existingModelsWithValues.length > 0) {
    //     for (let i = 0; i < dataLength; i++) {
    //         createQuestionButton();
    //     }
    // } else {
    //     for (let i = 0; i < dataLength; i++) {
    //         createQuestionButton();
    //     }
    // }
}


function setDataToTextAreas(data) {
    ensureModelContents(data.length);

    const modelContents = document.querySelectorAll('.model_content');
    let dataIndex = 0;

    modelContents.forEach((modelContent) => {
        const questionTextArea = modelContent.querySelector('textarea[name="question_model[]"]:not([value])');
        const answerTextArea = modelContent.querySelector('textarea[name="answer_model[]"]:not([value])');

        if (questionTextArea && answerTextArea && questionTextArea.value.trim() === '' && answerTextArea.value.trim() === '') {
            const messages = data[dataIndex].messages;

            questionTextArea.value = messages.find(message => message.role === 'user').content;
            answerTextArea.value = messages.find(message => message.role === 'assistant').content;

            autoExpandTextarea(questionTextArea);
            autoExpandTextarea(answerTextArea);

            dataIndex++;
        }
    });

    updateCharacterCount(); // G?i updateCharacterCount() sau khi v�ng l?p d� ho�n th�nh
    console.log(dataIndex);
}
