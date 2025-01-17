class Form {
    constructor()   {
        this.obForm = document.getElementById('voteForm');
        this.arListCheck = this.obForm.querySelectorAll('li');
        /*
         tagname
         .className
         #id
        */
       this.arStars = this.obForm.querySelectorAll('.stars input');
       this.arFields = this.obForm.querySelectorAll('textarea');
       this.obFiles = this.obForm.querySelector('.file-upload input');
       this.obNotE = document.getElementById('NOT_EQUALE');
       this.obNotET = document.getElementById('NOT_EQUALE_TEXT');

       this.Init();
    }

    Init() {
        this.obNotE.addEventListener('change', () => {
            this.obNotET.classList.toggle('hidden');
        });

        this.arStars.forEach(item => {
            item.addEventListener('change', () => {
                this.arListCheck.forEach(el => {
                    if(el.dataset.name === 'marks') {
                        el.classList.add('active')
                    }
                });

                this.Count();
            });
        });

        this.arFields.forEach(item => {
            item.addEventListener('keyup', () =>{
                if(item.value != '') {
                    this.arListCheck.forEach(el => {
                        if(el.dataset.name === item.name) {
                            el.classList.add('active')
                        }
                    });
                }
                else {
                    this.arListCheck.forEach(el => {
                        if(el.dataset.name === item.name) {
                            el.classList.remove('active')
                        }
                    });
                }

                this.Count();
            });
        });

        this.obFiles.addEventListener('change', () => {
            if(this.obFiles.value != '') {
                this.arListCheck.forEach(el => {
                    if(el.dataset.name === 'files') {
                        el.classList.add('active')
                    }
                });
            }
            else {
                this.arListCheck.forEach(el => {
                    if(el.dataset.name === 'files') {
                        el.classList.remove('active')
                    }
                });
            }

            
        });
    }

    
}
new Form();