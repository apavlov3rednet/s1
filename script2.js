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

        this.Init();
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

    Count() {
        let count = this.obForm.querySelectorAll('li.active').length * 20;
        let ob = this.obForm.querySelector('.progressbar');

        console.log('this');
        ob.removeAttribute('class');

        ob.classList.add('progressbar');
        ob.classList.add('size-' + count);
    }
}

new Form();