
/**
 * 
 * @param {HTMLElement} selector 
 */
function $(selector){
    return document.querySelector(selector);
}
/**
 * 
 * @param {Array} selector 
 */
function $$(selector){
    return document.querySelectorAll(selector);
}

let AddShortcutsTo = [
    HTMLElement,
    HTMLInputElement,
    HTMLLIElement,
    HTMLLabelElement,
    HTMLSelectElement,
    HTMLTextAreaElement,
    HTMLFormElement
];

AddShortcutsTo.forEach((el)=>{
    Object.defineProperty(el.prototype, 'bind', {
        value: function(type, listener){
            this.addEventListener(type,listener)
        }
    });
    
    Object.defineProperty(el.prototype, 'unbind', {
        value: function(type, listener){
            this.removeEventListener(type,listener)
        }
    });
    Object.defineProperty(el.prototype, 'set', {
        value: function(qualifiedName, value){
            this.setAttribute(qualifiedName,value)
        }
    });
    Object.defineProperty(el.prototype, 'get', {
        value: function(qualifiedName, value){
            this.getAttribute(qualifiedName)
        }
    });
})



function transition(from,to, callback = null){
    let el_from = $(from);
    let el_to = $(to);


    if(!el_from || !el_to)
        return console.log('Can`t Transite');

    let transitionEvent =  (e)=>{

        el_from.unbind('animationend',transitionEvent);
        el_from.classList.add('none');
        el_to.classList.remove('none', 'hide');
        el_to.classList.add('show');

        if(callback){
            if(Array.isArray(callback))
                callback.forEach((fn)=>{fn()});
            else callback();
        }
    }

    el_from.bind('animationend',transitionEvent);

    el_from.classList.remove('show');
    el_from.classList.add('hide');

}

/**
 * 
 * @param {string} url 
 * @param {object} data 
 */
async function post(url, data = {}){

    data = Object.keys(data)
    .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(typeof data[key] === 'object' ? JSON.stringify(data[key]) : data[key]))
    .join('&')


    
    const response = await fetch(url, {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        redirect: 'follow',
        referrerPolicy: 'no-referrer', 
        body: data
      });

      return {data: await response.json(), status: response.status};

}