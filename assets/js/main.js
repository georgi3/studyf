function displaySelectedFileName(file) {
    let fileName = file.files[0].name;
    let grandParent = file.parentElement.parentElement;
    const span = document.createElement('span');
    span.className = 'selectedFile';
    span.appendChild(document.createTextNode(fileName));
    grandParent.querySelector('.sectionFileArea').appendChild(span);
    writeCourseContent();
}

function addLinkField(sectionCount){  // not used at the moment
    let linkCount = document.getElementsByClassName('sectionLink');
    const linkDiv = document.createElement('div');
    const hyperTextInput = document.createElement('input');
    const linkInput = document.createElement('input');
    const linkAreaDiv = document.getElementById('sectionLinkArea'+sectionCount);
    linkDiv.className = 'sectionLink';
    hyperTextInput.type = 'text';
    hyperTextInput.placeholder = 'Link Text...';
    hyperTextInput.id = 'linkInputHT' + sectionCount;// + linkCount;
    hyperTextInput.className = 'linkInput';
    hyperTextInput.setAttribute('onfocusout', 'writeCourseContent()');
    linkInput.type = 'url';
    linkInput.placeholder = 'https://example.com';
    linkInput.className = 'linkInput';
    linkInput.id = 'linkInputURL' + sectionCount;// + linkCount;
    linkInput.setAttribute('onfocusout', 'writeCourseContent()');
    linkDiv.appendChild(hyperTextInput);
    linkDiv.appendChild(linkInput);
    linkAreaDiv.appendChild(linkDiv);
}
function addNewLinkFieldIfFilled(){}  // if to be implemented, links' id must be changed!!!

function addContentSection() {
    const outerDiv = document.createElement('div');
    const deleteButton = document.createElement('button');
    const titleInput = document.createElement('input');
    const textAreaDiv = document.createElement('div');
    const linkAreaDiv = document.createElement('div');

    const linkDiv = document.createElement('div');
    const hyperTextInput = document.createElement('input');
    const linkInput = document.createElement('input');

    const fileAreaDiv = document.createElement('div');
    const label = document.createElement('label');
    const fileInput = document.createElement('input');
    let sectionCount = document.getElementsByClassName('section').length;

    outerDiv.className = 'section';
    outerDiv.id = 'contentSection' + sectionCount;
    deleteButton.className = 'deleteContentBtn';
    deleteButton.setAttribute('onclick', 'deleteParentSection(this);')
    outerDiv.appendChild(deleteButton);
    titleInput.type = 'text';
    titleInput.placeholder = 'Section ' + (sectionCount + 1);
    titleInput.setAttribute('onfocusout', 'writeCourseContent()');
    titleInput.id = 'titleInputID' + sectionCount;
    titleInput.className = 'titleInput'
    outerDiv.appendChild(titleInput);
    textAreaDiv.className = 'custom-textarea';
    textAreaDiv.setAttribute('onfocusout', 'writeCourseContent()');
    textAreaDiv.setAttribute('contenteditable', true);
    textAreaDiv.id = 'textInputID' + sectionCount;
    outerDiv.appendChild(textAreaDiv);
    textAreaDiv.appendChild(document.createTextNode('Section text...'));
    linkAreaDiv.className = 'sectionLinksArea';
    linkAreaDiv.id = 'sectionLinkArea' + sectionCount;

    linkDiv.className = 'sectionLink';
    hyperTextInput.type = 'text';
    hyperTextInput.placeholder = 'Link Text...';
    hyperTextInput.id = 'linkInputHT' + sectionCount;
    hyperTextInput.className = 'linkInput';
    hyperTextInput.setAttribute('onfocusout', 'writeCourseContent()');
    linkInput.type = 'url';
    linkInput.placeholder = 'https://example.com';
    linkInput.className = 'linkInput';
    linkInput.id = 'linkInputURL' + sectionCount;
    linkInput.setAttribute('onfocusout', 'writeCourseContent()');
    linkDiv.appendChild(hyperTextInput);
    linkDiv.appendChild(linkInput);
    linkAreaDiv.appendChild(linkDiv);

    outerDiv.appendChild(linkAreaDiv);

    fileAreaDiv.className = 'sectionFileArea';
    label.setAttribute('for', 'fileInputID'+sectionCount);
    label.appendChild(document.createTextNode('Upload File'));
    label.className = 'custom-file-upload';
    fileAreaDiv.appendChild(label);
    fileInput.type = 'file';
    fileInput.id = 'fileInputID' + sectionCount;
    fileInput.name = '';
    fileInput.setAttribute('onchange', 'displaySelectedFileName(this);');
    fileInput.setAttribute('multiple', '');
    fileAreaDiv.appendChild(fileInput);
    outerDiv.appendChild(fileAreaDiv);
    document.querySelector('.course-content').appendChild(outerDiv);
}
function deleteParentSection(elem){
    const sectionToDelete = elem.parentElement;
    sectionToDelete.remove();
    writeCourseContent();
}

function writeCourseContent(){
    console.log('overwriting file');
    let courseTitle = document.getElementById('courseTitle').textContent;
    let courseDesc = document.getElementById('courseDescription').textContent;
    let courseCode = document.getElementById('courseCodeInput').value;
    let courseContentElem = document.querySelector('.course-content');

    let courseContent = [];
    for (let i=0; i < courseContentElem.children.length; i++){
        let sectionId = courseContentElem.children[i].id.substring(14);

        let titleInput = document.getElementById('titleInputID'+sectionId).value;
        let textInput = document.getElementById('textInputID'+sectionId).textContent;
        let linkInputHT = document.getElementById('linkInputHT'+sectionId).value;
        let linkInputURL = document.getElementById('linkInputURL'+sectionId).value;
        let filesInput = document.getElementById('fileInputID'+sectionId).files;
        let section = {
            'sectionTitle': titleInput,
            'sectionText': textInput,
            'sectionFiles': filesInput,
            'sectionLinkHT': linkInputHT,
            'sectionLinkURL': linkInputURL,
        }
        courseContent.push(section)
    }

    let courseData = {
        'courseTitle': courseTitle,
        'courseCode': courseCode,
        'courseDesc': courseDesc,
        'courseContent': courseContent,
    }

    // const response = fetch('/soen_proj/saveCourseContent.php', {
    //         method: 'POST', // *GET, POST, PUT, DELETE, etc.
    //         mode: 'cors', // no-cors, *cors, same-origin property body
    //         body: courseData,
    //         cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
    //         credentials: 'same-origin', // include, *same-origin, omit
    //         headers: {
    //             'Content-Type': 'application/json'
    //             // 'Content-Type': 'application/x-www-form-urlencoded',
    //         },
    //         redirect: 'follow', // manual, *follow, error
    //         referrerPolicy: 'no-referrer', // no-r
    //         })
    const response = fetch('/soen_proj/saveCourseContent.php', {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(courseData),
    })
    return response
}

function createNewCourse(){
 const modal = document.getElementById("newCourseModal");
 // const addCourseBtn = document.getElementById("createNewCourse");
 const closeBtn = document.getElementById("closeCreateNewCourseModal");
 modal.style.display = "block";
 closeBtn.onclick = function() {
        modal.style.display = "none";}
}
