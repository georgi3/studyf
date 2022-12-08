function createNewAssessment(){
    const modal = document.getElementById("newAssessmentModal");
    const closeBtn = document.getElementById("closeNewAssessmentModal");

    modal.style.display = "block";
    closeBtn.onclick = function() {
        modal.style.display = "none";}
}

function addMCQ(){
    const questionContainer = document.createElement('div');
    const deleteButton = document.createElement('button');
    const questionHeaderDiv = createHeader()
    const questionAnswersDiv = document.createElement('div');
    const answerLetters = ['a', 'b', 'c', 'd', 'e', 'f'];
    const numberOfOptions = document.getElementById('newQuestionMcqNumber').value;

    questionContainer.className = 'questionContainer';
    deleteButton.className = 'deleteContentBtn';
    deleteButton.setAttribute('onclick', 'deleteParentSection(this);');
    questionContainer.appendChild(deleteButton);
    questionContainer.appendChild(questionHeaderDiv);

    questionAnswersDiv.className = 'questionAnswers';
    for (let i = 0; i < numberOfOptions; i++){
        let sign = answerLetters[i];
        let questionOption = createQuestionOption(sign);
        questionAnswersDiv.appendChild(questionOption);
    }
    questionContainer.appendChild(questionAnswersDiv);
    const questionSection = document.querySelector('.questionsContainer');
    questionSection.appendChild(questionContainer);
}

function createHeader(){
    const questionCount = document.getElementsByClassName('questionAnswers').length + 1;
    const questionHeaderDiv = document.createElement('div');
    const questionMarkInputDiv = document.createElement('div');
    const questionMarkLabel = document.createElement('label');
    const questionMarkInput = document.createElement('input');
    const questionDiv = document.createElement('div');
    const questionTextArea = document.createElement('textarea');
    // Mark input + question div
    questionHeaderDiv.classname = 'questionHeader';
    questionMarkInputDiv.className = 'questionMarkInputDiv';
    questionMarkLabel.setAttribute('for', `q${questionCount}_marks`);
    questionMarkLabel.textContent = `Question ${questionCount} Marks: `;
    questionMarkInput.type = 'number';
    questionMarkInput.className = 'questionMarkInput';
    questionMarkInput.setAttribute('min', '1');
    // questionMarkInput.setAttribute('value', '1');
    questionMarkInput.setAttribute('onchange', "checkTotalMarks(this);");
    questionMarkInput.id = `q${questionCount}_marks`;

    // Question div section
    questionDiv.className = 'questionInput';
    questionTextArea.name = `q${questionCount}`;
    questionTextArea.id = `q${questionCount}`;
    questionTextArea.placeholder = 'Enter the question...'

    questionMarkInputDiv.appendChild(questionMarkLabel);
    questionMarkInputDiv.appendChild(questionMarkInput);
    questionDiv.appendChild(questionTextArea);
    questionHeaderDiv.appendChild(questionMarkInputDiv);
    questionHeaderDiv.appendChild(questionDiv);
    return questionHeaderDiv;
}
function createQuestionOption(sign){
    const questionCount = document.getElementsByClassName('questionAnswers').length + 1;
    const questionDiv = document.createElement('div');
    const questionInput = document.createElement('input');
    const questionAnswer = document.createElement('p');

    questionDiv.className = 'questionAnswer';
    questionInput.type = 'radio';
    questionInput.id = `q${questionCount}_${sign}`;
    questionInput.name = `q${questionCount}mcq`;
    questionInput.value = `q${questionCount}a_${sign}`;
    questionInput.setAttribute('required', 'true');
    questionAnswer.id = `q${questionCount}a_${sign}`;
    questionAnswer.setAttribute('contenteditable', 'true');
    questionAnswer.textContent = `Answer ${sign}...`
    questionDiv.appendChild(questionInput);
    questionDiv.appendChild(questionAnswer);
    return questionDiv;
}

function checkTotalMarks(element){ // called on change of the mark Input
    let markInputs = document.getElementsByClassName('questionMarkInput');
    let totalAllowedMarks = parseInt(document.getElementById('totalAllowedMarks').textContent);
    let totalSetMarks = 0;
    for (let i = 0; i < markInputs.length; i++){
        totalSetMarks += parseInt(markInputs[i].value);
    }
    let leftoverMarks = totalAllowedMarks - totalSetMarks;
    if (totalSetMarks > totalAllowedMarks){
        let resetValue = parseInt(element.value) + leftoverMarks;
        element.setAttribute('min', '');
        element.value =`${resetValue}`;
    }
}

function fadeAway(element){
    let fadeEffect = setInterval(function () {
        if (!element.style.opacity) {
            element.style.opacity = '1';
        }
        if (element.style.opacity > 0) {
            element.style.opacity -= 0.01;
        } else {
            clearInterval(fadeEffect);
            element.style.visibility = 'hidden';
            element.style.opacity = '1';
        }
    }, 50);
}

function validateNumberOfQuestion(displayErrorAtTheBottom=false){
    const allowedNumberOfQuestions = document.getElementById("numberOfQuestion").value;
    const currentNumberOfQuestion = document.getElementsByClassName('questionContainer').length;
    const error = document.getElementById("tooltipError");
    const bottomError = document.getElementById('questionCountError');

    if(currentNumberOfQuestion < allowedNumberOfQuestions){
        addMCQ();
        if (displayErrorAtTheBottom){
            bottomError.style.visibility = 'visible';
            fadeAway(bottomError);
        }
        return false;
    }else{
        if(!displayErrorAtTheBottom){
            error.style.visibility = 'visible';
            fadeAway(error);
        }
        return true;
    }
}

function validateMarkDistribution(displayError=true){  // to be called by save button
    let markInputs = document.getElementsByClassName('questionMarkInput');
    let totalAllowedMarks = parseInt(document.getElementById('totalAllowedMarks').textContent);
    const error = document.getElementById('markDistributionError');
    let totalSetMarks = 0;
    for (let i = 0; i < markInputs.length; i++){
        totalSetMarks += parseInt(markInputs[i].value);
    }
    if (totalSetMarks !== totalAllowedMarks){
        if (displayError){
            error.style.visibility = 'visible';
            fadeAway(error);
        }
        return false;
    }
    return true;
}
function getElementByXpath(path) {
    return document.evaluate(path, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
}

function writeAssessment(){
    const queryUrl = new URLSearchParams(window.location.search);
    const assessmentId = queryUrl.get('assessmentId');
    const assessmentTitle = document.getElementById('assessmentTitle');
    const questionContainer = document.getElementsByClassName("questionContainer");
    let assessmentQuestions = [];

    for (let i = 0; i < questionContainer.length; i++) {
        let questionMarks = getElementByXpath(`/html/body/div[3]/form/div/div[3]/div[1]/div[${i+1}]/div[1]/div[1]/input`);
        let question = getElementByXpath(`/html/body/div[3]/form/div/div[3]/div[1]/div[${i+1}]/div[1]/div[2]/textarea`);
        let questionAnswersDiv = questionContainer[i].querySelectorAll(":scope > .questionAnswers")[0];
        let questionAnswers = questionAnswersDiv.querySelectorAll(":scope > .questionAnswer");
        let numberOfDigits = (i + 1).toString().length;
        let correctAnswer = document.querySelector(`input[name='q${question.id.slice(-numberOfDigits)}mcq']:checked`).value;
        let answersArr = [];
        for (let a = 0; a < questionAnswers.length; a++) {
            let questionCurrAnswer = questionAnswers[a];
            let answerInfo = {
                "answer": questionCurrAnswer.children[1].textContent,
                "answerId": questionCurrAnswer.children[1].id
            }
            answersArr.push(answerInfo);
        }
        let questionInfo = {
            "questionNumber": i+1,                  // D
            "question": question.value,             // D
            "questionMarks": questionMarks.value,   // D
            "correctAnswer": correctAnswer,         // N
            "answers": answersArr                   // N
        }
        assessmentQuestions.push(questionInfo);
    }

    let assessment = {
        "assessmentId": assessmentId,
        "assessmentTitle": assessmentTitle.textContent,
        "assessmentQuestions": assessmentQuestions
    }
    // console.log(assessment)
    const response = fetch('/soen_proj/saveCourseAssessment.php', {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(assessment),
    })
    return response
}

function validateAssessmentForm(){
    if (!validateNumberOfQuestion(true)){
        // raise questionCountError
        return false;
    }else if (!validateMarkDistribution(true)){
        // make sure each question has at least 1 mark
        // raise markDistributionError
        return false
    }else{
        // submit the form, write to json
        const queryString = window.location.search;
        const queryUrl = new URLSearchParams(queryString);
        writeAssessment();
        // window.location.replace(`/soen_proj/teacherAssessments.php?courseCode=${queryUrl.get('courseCode')}`);
        return true;
    }
}

// dealing with test submission
function submittedAssessment(){
    const queryUrl = new URLSearchParams(window.location.search);
    const assessmentId = queryUrl.get('assessmentId');
    const questionContainer = document.getElementsByClassName("questionContainer");
    const username = document.getElementById("username").value;
    let answeredQuestions = [];

    for (let i = 0; i < questionContainer.length; i++) {
        let questionMarks = getElementByXpath(`/html/body/div[3]/form/div/div[3]/div[1]/div[${i+1}]/div[1]/div[1]/h2/span`);
        let chosenAnswer = document.querySelector(`input[name='q${i+1}mcq']:checked`);
        let questionInfo = {
            "questionNumber": i+1,
            "questionMarks": questionMarks.textContent,
            "chosenAnswer": chosenAnswer.value,
        }
        answeredQuestions.push(questionInfo);
    }
    //
    let assessment = {
        "assessmentId": assessmentId,
        "username": username,
        "assessmentQuestions": answeredQuestions,
    }
    // console.log(assessment)
    const response = fetch('/soen_proj/checkAssessmentAnswers.php', {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(assessment),
    })
    // window.location.replace(`/soen_proj/studentDashboard.php`);
    return response
}

