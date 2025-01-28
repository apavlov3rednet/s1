class Form {
    constructor() {
        this.obForm = document.getElementById('voteForm');
        this.arStars = this.obForm.querySelectorAll('.stars input');
        /*
        div - поиск по имени тега
        .class - класс
        #id - идентификатор
        [for=COMFORT] - поиск по атрубуту
        #id.class
        .class:not 
        */
        this.arFields = this.obForm.querySelectorAll('textarea');
        this.obNEC = this.obForm.querySelector('#NOT_EQUALE');
        this.obNET = this.obForm.querySelector('#NOT_EQUALE_TEXT');
        this.obFileField = this.obForm.querySelector('[type=file]');
        this.arChecklist = this.obForm.querySelectorAll('.checklist li');
        this.dropArea = this.obForm.querySelector('.file-upload');
        this.uploadUrl = "/upload/voteform";
        this.obStatusText = this.obForm.querySelector('.status-text');

        this.Init();
        this.DragNDrop();
    }

    Init() {
        //Переключатель показа скрытого поля
        this.obNEC.addEventListener('change', () => {
            this.obNET.classList.toggle('hidden');
        });

        //Установка оценки
        this.arStars.forEach(item => {
            item.addEventListener('change', () => {
                let check = this.obForm.querySelector('[data-name=marks]');

                if(!check.classList.contains('active'))
                    check.classList.add('active');

                this.Count();
            });
        });

        //Переключатель полей
        this.arFields.forEach(item => {
            item.addEventListener('keyup', () => {
                if(item.value !== '') {
                    this.arChecklist.forEach(el => {
                        if(item.name === el.dataset.name) {
                            el.classList.add('active');
                        }
                    });
                }
                else {
                    this.arChecklist.forEach(el => {
                        if(item.name === el.dataset.name) {
                            el.classList.remove('active');
                        }
                    });
                }

                this.Count();
            });
        });

        this.obFileField.addEventListener('change', () => {
            let check = this.obForm.querySelector('[data-name=files]');

            if(this.obFileField.value !== '') {
                check.classList.add('active');
            }
            else {
                check.classList.remove('active');
            }

            this.Count();
        });
    }

    DragNDrop() {
        let check = this.obForm.querySelector('[data-name=files]');
        let self = this;

        ["dragover", "drop"].forEach(function(event) {
            document.addEventListener(event, function(evt) {
              evt.preventDefault();
              return false;
            });
        });

        self.dropArea.addEventListener("dragenter", function() {
            self.dropArea.classList.add("_active");
        });
          
        self.dropArea.addEventListener("dragleave", function() {
            self.dropArea.classList.remove("_active");
        });

        self.dropArea.addEventListener('drop', event => {
            self.dropArea.classList.remove("_active");
            const file = event.dataTransfer?.files[0];
            if (!file) {
                return
            }

            self.obFileField.files = event.dataTransfer.files;
            self.processingUploadFile(file, check);
        });
    }

    processingUploadFile(file, check) {
        let self = this;
        if(file) {
            const dropZoneData = new FormData();
            const xhr = new XMLHttpRequest();

            let span = document.createElement('span');
            span.classList.add('uploadfile');
            span.textContent = file.name;

            self.dropArea.append(span);

            xhr.open("POST", self.uploadUrl, true);
            xhr.send(dropZoneData);

            xhr.onload = function () {
                if (xhr.status == 200) {
                    self.setStatus("Всё загружено");
                    check.classList.add('active');
                    self.Count();
                } else {
                    self.setStatus("Oшибка загрузки")
                }
                //HTMLElement.style.display = "none"
            }
        }
    }

    setStatus(text) {
        this.obStatusText.textContent = text;
        // innerText
        // innerHTML
        // textContent
    }

    Count() {
        let count = this.obForm.querySelectorAll('li.active').length * 20;
        let ob = this.obForm.querySelector('.progressbar');
        ob.removeAttribute('class');

        ob.classList.add('progressbar');
        ob.classList.add('size-' + count);
    }


}

new Form();