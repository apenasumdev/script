
var Lite = Lite || {};

Lite.InstallerWizard = class InstallerWizard {

    steps = {
        1: '#welcome',
        2: '#agreement',
        3: '#requirements',
        4: '#install',
        5: '#success'
    }
    current_step = 1;

    constructor(){
        this.stepOne()
    }

    stepOne(){
        let next = $(`${this.steps[1]} button`);
        next.bind('click',()=>{
            this.transite(2,[
                ()=>{
                    $('.logo').classList.add('min')
                },
                this.stepTwo.bind(this)
            ]);
        })
    }
    stepTwo(){
        //Get Elements
        let next = $(`${this.steps[2]} button`);
        let agree = $(`${this.steps[2]} #agree`);
        //Default to true
        next.disabled = true;
        //Add Event
        agree.bind('change',()=>{
            next.disabled = !agree.checked
        })

        next.bind('click',()=>{
            this.transite(3,this.stepThree.bind(this))
        })
    }

    stepThree(){
        let next = $(`${this.steps[3]} button`);
        if(!next.disabled)
            next.bind('click',()=>{
                this.transite(4,this.stepFour.bind(this))
            })
    }

    stepFour(){
        let finish = $(`${this.steps[4]} form`)

        finish.bind('submit',this.install.bind(this))
    }


    install(e){
        e.preventDefault();



        let form = document.installer;

        let license = {
            code: form.license.value
        }

        let db = {
            host: form.db_host.value,
            database: form.db_name.value,
            username: form.db_username.value,
            password: form.db_password.value,
            prefix: form.db_tablePrefix.value
        };

        let user = {
            fName: form.user_fName.value,
            lName: form.user_lName.value,
            email: form.user_email.value,
            password: form.user_password.value
        };

        this.loading(true)
        
        post('/install/api/install',{db: db,user: user, license: license})
        .then((res)=>{
            if(res.status !== 200)
                return this.error(res.data.error)
            else
                this.error('',false)

            this.transite(5,()=>{
                $('.logo').classList.remove('min')
            });

        }).finally(()=>{
            this.loading(false)
        })

    }



    loading(show = true){
        let body = $('body')
        if(!body) return
        if(show)
            body.classList.add('on-progress')
        else body.classList.remove('on-progress')
    }

    error(error, show = true){
        let errorText = $(`${this.steps[4]} .form-errors`)

        if(errorText){
            
            if(!show){
                errorText.classList.remove('show')
                errorText.innerText = ''
                return
            }

            errorText.classList.add('show')
            if (typeof error === 'object') {
                error.innerText = ''
                Object.values(error).forEach(value =>{
                    error.innerText += value + "<br/>"
                })
            } else
                errorText.innerText = error
        }
    }


    /**
     * Transite
     * @param {number} to 
     * @param {*} callback 
     */
    transite(to,callback = null){
        transition(`${this.steps[this.current_step]}`,
        `${this.steps[to]}`,callback); 
        this.current_step = to;
    }
}


document.addEventListener('DOMContentLoaded',function(){
    //Start Installer
    new Lite.InstallerWizard();
})

