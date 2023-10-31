void function(){
    let mainImage = document.querySelector('#main-image')
    document.querySelectorAll('.mo-photos').forEach((elem)=>{
        elem.addEventListener('click',(e)=>{
            mainImage.src = e.target.src
            // console.log()
        })
    });
}()

// void ( ()=> {
//     console.log("")
// })();

// ----------single product-------------------








let product = JSON.parse('{!! json_encode($product->toArray()) !!}');

console.log(product);
let properties = {};
let variation = {};




const setProperty = (id,t) => {

    document.querySelectorAll(`[onclick="setProperty(${id},this)"]`).forEach((item)=>{

        item.classList.remove('border-blue-400')
    })

    t.classList.add('border-blue-400')

    properties[id] = t.dataset.value

    console.log(properties)
}

const decrementQuantity = () => {
    const quantity = document.querySelector("input[name='quantity']");

    if(quantity.value > 1){
        quantity.value--;
    }
}

const incrementQuantity = () => {
    const quantity = document.querySelector("input[name='quantity']");
    quantity.value++;
}


const setVariation = (t) =>{
    document.querySelectorAll(`[onclick="setVariation(this)"]`).forEach((item)=>{
        item.classList.remove('border-blue-400')
    })

    t.classList.add('border-blue-400')

    variation = JSON.parse(t.dataset.value);

    const mainImage = document.querySelector('#main-image');
    const price = document.querySelector('#price');
    const sale_price = document.querySelector('#sale_price');

    if (variation['photo']) {
        mainImage.src = variation['photo']
    }else{
        mainImage.src = product['cover']
    }

    if(variation['price']){
        price.innerText = variation['price_currency']
    }else{
        price.innerText = product['price_currency']
    }

    if(variation['sale_price']){
        sale_price.innerText = variation['sale_price_currency']
    }else{
        sale_price.innerText = product['sale_price_currency']
    }



    console.log(variation)
}
