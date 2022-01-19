window.addEventListener('load', () => {
    const allLists: NodeListOf<HTMLDListElement> = document.querySelectorAll("[data-list-id]");
    for (const list of allLists) {
        addDeleteBtn(list);
    }

    const btnsDelete: NodeListOf<HTMLButtonElement> = document.querySelectorAll("[data-btn-delete-id]");
    if (btnsDelete) {
        for (const btnDeleteElement of btnsDelete) {
            addDeleteBtnEvent(btnDeleteElement);
        }
    }

    const btnsAdd = document.querySelectorAll("[data-btn-add-id]");
    for (const btn of btnsAdd) {
        btn.addEventListener('click', () => {
            let selector: string = '[data-list-id=\'' + btn.getAttribute('data-btn-add-id') + '\']';
            let list: HTMLElement = document.querySelector(selector);
            let counter: number = list.children.length;
            let newCountry: string = list.getAttribute('data-prototype');
            newCountry = newCountry.replace(/__name__/g, counter.toString());
            const dataBtnDeleteValue: string = 'data-btn-delete-id="' + list.getAttribute("id") + '_' + counter.toString() + '"';
            const deleteBtnHtml: string = '<div class="col-1"><button class="btn btn-danger" type="button"' + dataBtnDeleteValue +'><i class="fas fa-trash-alt"></i></button></div>';
            newCountry = newCountry.replace('<div class="mb-3">',  '<div class="col-11">');
            newCountry = newCountry + deleteBtnHtml;
            let newElem: HTMLDivElement = document.createElement("div");
            newElem.classList.add('row');
            newElem.classList.add('mb-3');
            newElem.setAttribute('data-form-delete-id', list.getAttribute("id") + '_' + counter.toString())
            counter++;
            newElem.innerHTML = newCountry;
            list.appendChild(newElem);

            let newDeleteBtn: HTMLButtonElement = document.querySelector('[' + dataBtnDeleteValue + ']');
            if(newDeleteBtn){
                addDeleteBtnEvent(newDeleteBtn);
            }
        })
    }
})

function addDeleteBtnEvent(btnDelete: HTMLButtonElement) {
    btnDelete.addEventListener('click', () => {
        let elementToDelete: HTMLElement = document.querySelector('[data-form-delete-id="' + btnDelete.getAttribute("data-btn-delete-id") + '"]');
        elementToDelete.remove();
    })
}

function addDeleteBtn(list: HTMLDListElement){
    let counter: number = list.children.length;
    let iteration: number = 0;
    for (const element of list.children ) {
        element.classList.add('row');
        element.setAttribute('data-form-delete-id', list.getAttribute("id") + '_' + iteration);

        let col11: HTMLElement = document.createElement('div');
        col11.classList.add('col-11');
        col11.innerHTML = element.innerHTML;

        const dataBtnDeleteValue: string = 'data-btn-delete-id="' + list.getAttribute("id") + '_' + iteration + '"';
        const deleteBtnHtml: string = '<div class="col-1"><button class="btn btn-danger" type="button"' + dataBtnDeleteValue +'><i class="fas fa-trash-alt"></i></button></div>';

        let col1: HTMLElement = document.createElement('div');
        col1.classList.add('col-1');
        col1.innerHTML = deleteBtnHtml;

        element.innerHTML = '';
        element.appendChild(col11);
        element.appendChild(col1);

        iteration++;

    }

}